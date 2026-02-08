<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TagihanController extends Controller
{
    public function index(Request $request)
    {
        $query = Tagihan::with(['pelanggan.user', 'pelanggan.paket', 'konfirmator']); // ⭐ Tambah konfirmator

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan bulan
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }

        // Filter berdasarkan tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Search
        if ($request->filled('search')) {
            $query->whereHas('pelanggan.user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $tagihans = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('petugas.tagihan.index', compact('tagihans'));
    }

    public function nunggak()
    {
        $tagihans = Tagihan::with(['pelanggan.user', 'pelanggan.paket'])
            ->where('status', 'nunggak')
            ->orderBy('bulan', 'desc')
            ->orderBy('tahun', 'desc')
            ->paginate(10);
        
        return view('petugas.tagihan.nunggak', compact('tagihans'));
    }

    public function show(Tagihan $tagihan)
    {
        $tagihan->load(['pelanggan.user', 'pelanggan.paket', 'konfirmator']);
        
        return view('petugas.tagihan.show', compact('tagihan'));
    }

    /**
     * Konfirmasi pembayaran tagihan
     * OTOMATIS CATAT KE BUKU KAS
     */
    public function konfirmasi(Request $request, Tagihan $tagihan)
    {
        $request->validate([
            'metode_bayar' => 'nullable|in:tunai,transfer,qris',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Update tagihan dengan konfirmator
            $tagihan->update([
                'status' => 'lunas',
                'tanggal_bayar' => now(),
                'metode_bayar' => $request->metode_bayar ?? $tagihan->metode_pembayaran ?? 'transfer',
                'keterangan' => $request->keterangan,
                'dikonfirmasi_oleh' => auth()->id(), // ⭐ Simpan ID petugas yang konfirmasi
            ]);

            // Catat ke buku kas
            $namaBulan = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];

            \App\Models\BukuKas::create([
                'tanggal' => now()->format('Y-m-d'),
                'jenis' => 'pemasukan',
                'kategori' => 'tagihan_banwith',
                'nominal' => $tagihan->jumlah,
                'keterangan' => "Pembayaran tagihan - {$tagihan->pelanggan->user->name} | " . 
                               "Paket {$tagihan->pelanggan->paket->nama_paket} | " .
                               $namaBulan[$tagihan->bulan] . " {$tagihan->tahun} | " .
                               "via " . ucfirst($request->metode_bayar ?? $tagihan->metode_pembayaran ?? 'Transfer'),
                'referensi_tagihan_id' => $tagihan->id,
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', '✓ Pembayaran berhasil dikonfirmasi dan tercatat di Buku Kas!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', '✗ Gagal mengkonfirmasi pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Tolak pembayaran (untuk yang menunggu konfirmasi)
     */
    public function tolak(Request $request, Tagihan $tagihan)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            // Hapus bukti pembayaran jika ada
            if ($tagihan->bukti_pembayaran) {
                Storage::disk('public')->delete($tagihan->bukti_pembayaran);
            }

            // Update status kembali ke nunggak
            $tagihan->update([
                'status' => 'nunggak',
                'bukti_pembayaran' => null,
                'metode_pembayaran' => null,
                'catatan_pembayaran' => 'Ditolak: ' . $request->alasan_penolakan,
                'tanggal_bayar' => null
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', '✓ Pembayaran berhasil ditolak dan dikembalikan ke status nunggak.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', '✗ Gagal menolak pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan tagihan menunggu konfirmasi
     */
    public function menungguKonfirmasi()
    {
        $tagihans = Tagihan::with(['pelanggan.user', 'pelanggan.paket'])
            ->where('status', 'menunggu_konfirmasi')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
        
        return view('petugas.tagihan.menunggu-konfirmasi', compact('tagihans'));
    }

    /**
     * Generate tagihan bulanan untuk semua pelanggan aktif
     */
    public function generate(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'tanggal' => 'required|integer|between:1,31'
        ], [
            'bulan.required' => 'Bulan harus dipilih',
            'bulan.between' => 'Bulan tidak valid',
            'tahun.required' => 'Tahun harus dipilih',
            'tahun.min' => 'Tahun minimal 2020',
            'tanggal.required' => 'Tanggal jatuh tempo harus dipilih',
            'tanggal.between' => 'Tanggal tidak valid'
        ]);

        try {
            DB::beginTransaction();

            // Ambil semua pelanggan aktif
            $pelanggans = Pelanggan::with('paket')
                ->where('status', 'aktif')
                ->get();

            if ($pelanggans->count() == 0) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', '✗ Tidak ada pelanggan aktif untuk di-generate tagihan.');
            }

            $generated = 0;
            $skipped = 0;

            foreach ($pelanggans as $pelanggan) {
                // Cek apakah tagihan untuk periode ini sudah ada
                $exists = Tagihan::where('pelanggan_id', $pelanggan->id)
                    ->where('bulan', $request->bulan)
                    ->where('tahun', $request->tahun)
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                // Buat tanggal jatuh tempo
                $tanggalJatuhTempo = \Carbon\Carbon::create(
                    $request->tahun, 
                    $request->bulan, 
                    min($request->tanggal, cal_days_in_month(CAL_GREGORIAN, $request->bulan, $request->tahun))
                );

                // Generate tagihan
                Tagihan::create([
                    'pelanggan_id' => $pelanggan->id,
                    'bulan' => (int)$request->bulan,
                    'tahun' => (int)$request->tahun,
                    'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
                    'jumlah' => $pelanggan->paket->harga ?? 0,
                    'status' => 'nunggak',
                    'tanggal_bayar' => null
                ]);

                $generated++;
            }

            DB::commit();

            $namaBulan = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];

            $message = "✓ Berhasil generate {$generated} tagihan untuk " . 
                       $namaBulan[$request->bulan] . " {$request->tahun} " .
                       "dengan jatuh tempo tanggal {$request->tanggal}.";
            
            if ($skipped > 0) {
                $message .= " {$skipped} tagihan dilewati (sudah ada).";
            }

            return redirect()->route('petugas.tagihan.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', '✗ Gagal generate tagihan: ' . $e->getMessage());
        }
    }
}