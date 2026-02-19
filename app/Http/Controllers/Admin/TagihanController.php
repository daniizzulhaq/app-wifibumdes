<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Admin\LaporanTagihanController;
use App\Services\PiwapiService;

class TagihanController extends Controller
{
    protected $piwapiService;

    public function __construct(PiwapiService $piwapiService)
    {
        $this->piwapiService = $piwapiService;
    }

    /**
     * Tampilkan semua tagihan dengan filter
     */
    public function index(Request $request)
    {
        $query = Tagihan::with(['pelanggan.user', 'pelanggan.paket']);

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
        
        return view('admin.tagihan.index', compact('tagihans'));
    }

    /**
     * Tampilkan tagihan yang nunggak
     */
    public function nunggak()
    {
        $tagihans = Tagihan::with(['pelanggan.user', 'pelanggan.paket'])
            ->where('status', 'nunggak')
            ->orderBy('bulan', 'desc')
            ->orderBy('tahun', 'desc')
            ->paginate(10);
        
        return view('admin.tagihan.nunggak', compact('tagihans'));
    }

    /**
     * Detail tagihan
     */
    public function show(Tagihan $tagihan)
    {
        $tagihan->load(['pelanggan.user', 'pelanggan.paket', 'konfirmator']);
        
        return view('admin.tagihan.show', compact('tagihan'));
    }

    /**
     * Konfirmasi pembayaran tagihan
     * OTOMATIS CATAT KE LAPORAN TAGIHAN (bukan Buku Kas lagi)
     * DAN KIRIM NOTIFIKASI WHATSAPP
     */
    public function konfirmasi(Request $request, $id)
    {
        $request->validate([
            'metode_pembayaran' => 'nullable|in:tunai,transfer,qris',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $tagihan = Tagihan::with(['pelanggan.user', 'pelanggan.paket'])->findOrFail($id);
            
            // Update tagihan dengan konfirmator
            $tagihan->update([
                'status' => 'lunas',
                'tanggal_bayar' => now(),
                'metode_pembayaran' => $request->metode_bayar ?? $tagihan->metode_pembayaran ?? 'transfer',
                'keterangan' => $request->keterangan,
                'dikonfirmasi_oleh' => auth()->id(),
            ]);

            // Catat ke Laporan Tagihan (otomatis karena status sudah lunas)
            $result = LaporanTagihanController::catatPembayaranTagihan($tagihan);

            DB::commit();

            // Kirim notifikasi WhatsApp ke pelanggan
            try {
                $notifResult = $this->piwapiService->sendNotifikasiPembayaranBerhasil(
                    $tagihan->pelanggan,
                    $tagihan
                );
                
                if ($notifResult['success']) {
                    \Log::info('Notifikasi pembayaran berhasil dikirim', [
                        'tagihan_id' => $tagihan->id,
                        'pelanggan' => $tagihan->pelanggan->user->name
                    ]);
                }
            } catch (\Exception $e) {
                // Log error tapi jangan gagalkan proses konfirmasi
                \Log::error('Gagal kirim notifikasi pembayaran', [
                    'tagihan_id' => $tagihan->id,
                    'error' => $e->getMessage()
                ]);
            }

            if ($result['success']) {
                return redirect()->route('admin.tagihan.index')
                    ->with('success', '✓ Pembayaran berhasil dikonfirmasi! ' . $result['message']);
            } else {
                return redirect()->route('admin.tagihan.index')
                    ->with('warning', '⚠ Pembayaran dikonfirmasi tapi: ' . $result['message']);
            }

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
                'tanggal_bayar' => null,
                'keterangan' => null,
                'dikonfirmasi_oleh' => null,
            ]);

            DB::commit();

            return redirect()->route('admin.tagihan.index')
                ->with('success', '✓ Pembayaran berhasil ditolak dan dikembalikan ke status nunggak.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', '✗ Gagal menolak pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Batalkan pembayaran yang sudah lunas
     * BATALKAN DI LAPORAN TAGIHAN (bukan Buku Kas lagi)
     */
    public function batalkan($id)
    {
        try {
            DB::beginTransaction();

            $tagihan = Tagihan::findOrFail($id);
            
            // Cek apakah tagihan lunas
            if ($tagihan->status != 'lunas') {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', '✗ Hanya tagihan dengan status lunas yang bisa dibatalkan.');
            }

            // Batalkan pembayaran di Laporan Tagihan
            $result = LaporanTagihanController::batalkanPembayaran($tagihan);

            DB::commit();

            if ($result['success']) {
                return redirect()->route('admin.tagihan.index')
                    ->with('success', '✓ Pembayaran berhasil dibatalkan! ' . $result['message']);
            } else {
                return redirect()->route('admin.tagihan.index')
                    ->with('error', '✗ Gagal: ' . $result['message']);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', '✗ Gagal membatalkan pembayaran: ' . $e->getMessage());
        }
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
                    'tanggal_bayar' => null,
                    'metode_pembayaran' => null,
                    'keterangan' => null,
                    'dikonfirmasi_oleh' => null,
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

            return redirect()->route('admin.tagihan.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', '✗ Gagal generate tagihan: ' . $e->getMessage());
        }
    }

    /**
     * Update status tagihan yang sudah lewat jatuh tempo menjadi nunggak
     * Bisa dijadwalkan via Cron Job
     */
    public function updateStatusNunggak()
    {
        try {
            $updated = Tagihan::where('status', 'belum_bayar')
                ->where('tanggal_jatuh_tempo', '<', now())
                ->update(['status' => 'nunggak']);

            return redirect()->back()
                ->with('success', "✓ Berhasil update {$updated} tagihan menjadi status nunggak.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', '✗ Gagal update status: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan tagihan yang menunggu konfirmasi
     */
    public function menungguKonfirmasi()
    {
        $tagihans = Tagihan::with(['pelanggan.user', 'pelanggan.paket'])
            ->where('status', 'menunggu_konfirmasi')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.tagihan.menunggu_konfirmasi', compact('tagihans'));
    }


    public function indexByPelanggan(\App\Models\Pelanggan $pelanggan)
{
    $pelanggan->load(['user', 'paket']);

    // Ambil semua tagihan pelanggan ini
    $tagihans = \App\Models\Tagihan::with(['pelanggan.user', 'pelanggan.paket'])
        ->where('pelanggan_id', $pelanggan->id)
        ->orderBy('tahun', 'desc')
        ->orderBy('bulan', 'desc')
        ->paginate(20);

    // Statistik
    $stats = [
        'total_nunggak'  => \App\Models\Tagihan::where('pelanggan_id', $pelanggan->id)
                               ->whereIn('status', ['nunggak', 'belum_bayar'])->sum('jumlah'),
        'jml_nunggak'    => \App\Models\Tagihan::where('pelanggan_id', $pelanggan->id)
                               ->whereIn('status', ['nunggak', 'belum_bayar'])->count(),
        'total_lunas'    => \App\Models\Tagihan::where('pelanggan_id', $pelanggan->id)
                               ->where('status', 'lunas')->sum('jumlah'),
        'jml_lunas'      => \App\Models\Tagihan::where('pelanggan_id', $pelanggan->id)
                               ->where('status', 'lunas')->count(),
        'menunggu'       => \App\Models\Tagihan::where('pelanggan_id', $pelanggan->id)
                               ->where('status', 'menunggu_konfirmasi')->count(),
    ];

    // Tagihan nunggak untuk ditampilkan paling atas (aksi cepat)
    $tagihanNunggak = \App\Models\Tagihan::where('pelanggan_id', $pelanggan->id)
        ->whereIn('status', ['nunggak', 'belum_bayar', 'menunggu_konfirmasi'])
        ->orderBy('tahun', 'asc')
        ->orderBy('bulan', 'asc')
        ->get();

    return view('admin.tagihan.pelanggan', compact('pelanggan', 'tagihans', 'stats', 'tagihanNunggak'));
}

    /**
     * Hapus tagihan (opsional, hati-hati jika digunakan)
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $tagihan = Tagihan::findOrFail($id);
            
            // Cek apakah tagihan sudah lunas
            if ($tagihan->status == 'lunas') {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', '✗ Tidak bisa menghapus tagihan yang sudah lunas. Silakan batalkan pembayaran terlebih dahulu.');
            }

            // Hapus bukti pembayaran jika ada
            if ($tagihan->bukti_pembayaran) {
                Storage::disk('public')->delete($tagihan->bukti_pembayaran);
            }

            $tagihan->delete();

            DB::commit();

            return redirect()->route('admin.tagihan.index')
                ->with('success', '✓ Tagihan berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', '✗ Gagal menghapus tagihan: ' . $e->getMessage());
        }
    }
}