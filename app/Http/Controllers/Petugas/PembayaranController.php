<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PembayaranController extends Controller
{
    /**
     * Display tagihan nunggak for payment processing
     */
    public function index(Request $request)
    {
        $query = Tagihan::with(['pelanggan.user', 'pelanggan.paket'])
            ->whereIn('status', ['nunggak', 'belum_bayar', 'menunggu_konfirmasi']);

        // Filter berdasarkan search
        if ($request->filled('search')) {
            $query->whereHas('pelanggan.user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter berdasarkan bulan
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }

        // Filter berdasarkan tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tagihans = $query->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate(20);

        // Summary statistics
        $totalNunggak = Tagihan::whereIn('status', ['nunggak', 'belum_bayar'])->sum('jumlah');
        $jumlahTagihan = Tagihan::whereIn('status', ['nunggak', 'belum_bayar'])->count();
        $pelangganNunggak = Tagihan::whereIn('status', ['nunggak', 'belum_bayar'])
            ->distinct('pelanggan_id')
            ->count('pelanggan_id');
        $menungguKonfirmasi = Tagihan::where('status', 'menunggu_konfirmasi')->count();

        return view('petugas.pembayaran.index', compact(
            'tagihans',
            'totalNunggak',
            'jumlahTagihan',
            'pelangganNunggak',
            'menungguKonfirmasi'
        ));
    }

    /**
     * Show detail tagihan for payment
     */
    public function show(Tagihan $tagihan)
    {
        $tagihan->load(['pelanggan.user', 'pelanggan.paket']);
        
        // Get payment history for this customer
        $riwayatPembayaran = Tagihan::where('pelanggan_id', $tagihan->pelanggan_id)
            ->where('status', 'lunas')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->take(5)
            ->get();

        return view('petugas.pembayaran.show', compact('tagihan', 'riwayatPembayaran'));
    }

    /**
     * Konfirmasi pembayaran
     */
    public function konfirmasi(Request $request, Tagihan $tagihan)
    {
        // Validasi
        $request->validate([
            'metode_bayar' => 'required|in:tunai,transfer,qris',
            'jumlah_bayar' => 'required|numeric|min:0',
            'tanggal_bayar' => 'nullable|date',
            'keterangan' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Update tagihan
            $tagihan->update([
                'status' => 'lunas',
                'tanggal_bayar' => $request->tanggal_bayar ?? Carbon::now(),
                'metode_bayar' => $request->metode_bayar,
                'jumlah_bayar' => $request->jumlah_bayar,
                'keterangan' => $request->keterangan,
            ]);

            DB::commit();

            return redirect()
                ->route('petugas.pembayaran.index')
                ->with('success', 'Pembayaran berhasil dikonfirmasi!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Gagal mengkonfirmasi pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Tolak pembayaran
     */
    public function tolakPembayaran(Request $request, Tagihan $tagihan)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            // Hapus bukti pembayaran
            if ($tagihan->bukti_pembayaran) {
                Storage::disk('public')->delete($tagihan->bukti_pembayaran);
            }

            $tagihan->update([
                'status' => 'nunggak',
                'bukti_pembayaran' => null,
                'metode_pembayaran' => null,
                'catatan_pembayaran' => 'Ditolak: ' . $request->alasan_penolakan,
            ]);

            DB::commit();

            return redirect()
                ->route('petugas.pembayaran.index')
                ->with('success', 'Pembayaran berhasil ditolak dan pelanggan akan diberitahu.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Gagal menolak pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Proses pembayaran multiple (batch)
     */
    public function batchKonfirmasi(Request $request)
    {
        $request->validate([
            'tagihan_ids' => 'required|array',
            'tagihan_ids.*' => 'exists:tagihans,id',
            'metode_bayar' => 'required|in:tunai,transfer,qris',
            'tanggal_bayar' => 'nullable|date',
        ]);

        try {
            DB::beginTransaction();

            $count = 0;
            foreach ($request->tagihan_ids as $id) {
                $tagihan = Tagihan::find($id);
                if ($tagihan && $tagihan->status != 'lunas') {
                    $tagihan->update([
                        'status' => 'lunas',
                        'tanggal_bayar' => $request->tanggal_bayar ?? Carbon::now(),
                        'metode_bayar' => $request->metode_bayar,
                        'jumlah_bayar' => $tagihan->jumlah,
                    ]);
                    $count++;
                }
            }

            DB::commit();

            return redirect()
                ->route('petugas.pembayaran.index')
                ->with('success', "Berhasil mengkonfirmasi {$count} pembayaran!");

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Cetak kwitansi pembayaran
     */
    public function cetakKwitansi(Tagihan $tagihan)
    {
        if ($tagihan->status != 'lunas') {
            return redirect()
                ->back()
                ->with('error', 'Hanya tagihan yang sudah lunas yang bisa dicetak kwitansi!');
        }

        $tagihan->load(['pelanggan.user', 'pelanggan.paket']);
        
        return view('petugas.pembayaran.kwitansi', compact('tagihan'));
    }
}