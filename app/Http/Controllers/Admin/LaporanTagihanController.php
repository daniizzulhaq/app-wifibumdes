<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanTagihanController extends Controller
{
    /**
     * Tampilkan laporan tagihan pelanggan
     * Data diambil langsung dari tabel tagihans
     */
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', date('n'));
        $tahun = $request->get('tahun', date('Y'));
        $status = $request->get('status', '');

        // Query tagihan berdasarkan filter
        $query = Tagihan::with(['pelanggan.user', 'pelanggan.paket'])
            ->where('bulan', $bulan)
            ->where('tahun', $tahun);

        if ($status) {
            $query->where('status', $status);
        }

        $tagihan = $query->orderBy('created_at', 'desc')->get();

        // Hitung statistik
        $totalTagihan = $tagihan->sum('jumlah');
        $totalLunas = $tagihan->where('status', 'lunas')->sum('jumlah');
        $totalBelumLunas = $tagihan->whereIn('status', ['belum_lunas', 'menunggu_konfirmasi'])->sum('jumlah');
        
        $jumlahPelanggan = $tagihan->count();
        $jumlahLunas = $tagihan->where('status', 'lunas')->count();
        $jumlahBelumLunas = $tagihan->whereIn('status', ['belum_lunas', 'menunggu_konfirmasi'])->count();

        return view('admin.laporan.tagihan', compact(
            'tagihan',
            'bulan',
            'tahun',
            'status',
            'totalTagihan',
            'totalLunas',
            'totalBelumLunas',
            'jumlahPelanggan',
            'jumlahLunas',
            'jumlahBelumLunas'
        ));
    }

    /**
     * Cetak laporan tagihan
     */
    public function cetak(Request $request)
    {
        $bulan = $request->get('bulan', date('n'));
        $tahun = $request->get('tahun', date('Y'));
        $status = $request->get('status', '');

        // Query tagihan berdasarkan filter
        $query = Tagihan::with(['pelanggan.user', 'pelanggan.paket'])
            ->where('bulan', $bulan)
            ->where('tahun', $tahun);

        if ($status) {
            $query->where('status', $status);
        }

        $tagihan = $query->orderBy('created_at', 'desc')->get();

        // Hitung statistik
        $totalTagihan = $tagihan->sum('jumlah');
        $totalLunas = $tagihan->where('status', 'lunas')->sum('jumlah');
        $totalBelumLunas = $tagihan->whereIn('status', ['belum_lunas', 'menunggu_konfirmasi'])->sum('jumlah');
        
        $jumlahPelanggan = $tagihan->count();
        $jumlahLunas = $tagihan->where('status', 'lunas')->count();
        $jumlahBelumLunas = $tagihan->whereIn('status', ['belum_lunas', 'menunggu_konfirmasi'])->count();

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return view('admin.laporan.cetak-tagihan', compact(
            'tagihan',
            'bulan',
            'tahun',
            'status',
            'totalTagihan',
            'totalLunas',
            'totalBelumLunas',
            'jumlahPelanggan',
            'jumlahLunas',
            'jumlahBelumLunas',
            'namaBulan'
        ));
    }

    /**
     * Catat pembayaran otomatis saat konfirmasi tagihan
     * Method ini dipanggil dari TagihanController saat konfirmasi pembayaran
     */
    public static function catatPembayaranTagihan($tagihan)
    {
        try {
            // Pastikan tagihan sudah ada tanggal bayar dan metode bayar
            if (!$tagihan->tanggal_bayar || !$tagihan->metode_bayar) {
                return [
                    'success' => false,
                    'message' => 'Data pembayaran tidak lengkap'
                ];
            }

            // Update status menjadi lunas jika belum
            if ($tagihan->status !== 'lunas') {
                $tagihan->update(['status' => 'lunas']);
            }

            return [
                'success' => true,
                'message' => 'Pembayaran berhasil dicatat di Laporan Tagihan'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mencatat pembayaran: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Batalkan pembayaran tagihan
     * Method ini dipanggil saat admin membatalkan konfirmasi pembayaran
     */
    public static function batalkanPembayaran($tagihan)
    {
        try {
            // Reset data pembayaran
            $tagihan->update([
                'status' => 'belum_lunas',
                'tanggal_bayar' => null,
                'metode_bayar' => null,
                'bukti_bayar' => null
            ]);

            return [
                'success' => true,
                'message' => 'Pembayaran berhasil dibatalkan'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal membatalkan pembayaran: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Laporan ringkasan per tahun
     */
    public function laporanTahunan(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // Data per bulan dalam setahun
        $laporanBulanan = [];
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $tagihanBulan = Tagihan::where('tahun', $tahun)
                                   ->where('bulan', $bulan)
                                   ->get();

            $totalTagihan = $tagihanBulan->sum('jumlah');
            $totalLunas = $tagihanBulan->where('status', 'lunas')->sum('jumlah');
            $totalBelum = $tagihanBulan->whereIn('status', ['belum_lunas', 'menunggu_konfirmasi'])->sum('jumlah');

            $laporanBulanan[$bulan] = [
                'nama_bulan' => $namaBulan[$bulan],
                'total_tagihan' => $totalTagihan,
                'total_lunas' => $totalLunas,
                'total_belum_lunas' => $totalBelum,
                'jumlah_pelanggan' => $tagihanBulan->count(),
                'jumlah_lunas' => $tagihanBulan->where('status', 'lunas')->count(),
                'persentase' => $totalTagihan > 0 ? round(($totalLunas / $totalTagihan) * 100, 1) : 0
            ];
        }

        // Total tahunan
        $totalTagihanTahun = Tagihan::where('tahun', $tahun)->sum('jumlah');
        $totalLunasTahun = Tagihan::where('tahun', $tahun)->where('status', 'lunas')->sum('jumlah');
        $totalBelumTahun = Tagihan::where('tahun', $tahun)
                                  ->whereIn('status', ['belum_lunas', 'menunggu_konfirmasi'])
                                  ->sum('jumlah');

        return view('admin.laporan.tahunan', compact(
            'tahun',
            'laporanBulanan',
            'totalTagihanTahun',
            'totalLunasTahun',
            'totalBelumTahun',
            'namaBulan'
        ));
    }

    /**
     * Export laporan ke Excel (opsional)
     */
    public function export(Request $request)
    {
        // Implementasi export jika diperlukan
        // Bisa menggunakan package maatwebsite/excel
        
        return redirect()->back()
                         ->with('info', 'Fitur export sedang dalam pengembangan');
    }
}