<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the petugas dashboard.
     */
    public function index()
    {
        // ==========================================
        // 1. STATISTIK PELANGGAN
        // ==========================================
        $totalPelanggan = Pelanggan::count();
        $pelangganAktif = Pelanggan::where('status', 'aktif')->count();
        $pelangganNonaktif = Pelanggan::where('status', 'nonaktif')->count();
        $pelangganBaru = Pelanggan::whereMonth('created_at', Carbon::now()->month)
                                  ->whereYear('created_at', Carbon::now()->year)
                                  ->count();

        // ==========================================
        // 2. STATISTIK TAGIHAN
        // ==========================================
        $bulanIni = Carbon::now()->format('Y-m');
        
        $totalTagihanBulanIni = Tagihan::where('bulan', $bulanIni)->count();
        $tagihanLunas = Tagihan::where('bulan', $bulanIni)
                              ->where('status', 'lunas')
                              ->count();
        $tagihanNunggak = Tagihan::where('bulan', $bulanIni)
                                ->where('status', 'nunggak')
                                ->count();
        
        // Total nominal tagihan bulan ini
       $nominalTagihanBulanIni = Tagihan::where('bulan', $bulanIni)->sum('jumlah');

$nominalTerbayar = Tagihan::where('bulan', $bulanIni)
                          ->where('status', 'lunas')
                          ->sum('jumlah');

$nominalNunggak = Tagihan::where('bulan', $bulanIni)
                        ->where('status', 'nunggak')
                        ->sum('jumlah');

        // ==========================================
        // 3. STATISTIK PEMBAYARAN HARI INI
        // ==========================================
        $pembayaranHariIni = Pembayaran::whereDate('tanggal_bayar', Carbon::today())
                                      ->count();
        $nominalHariIni = Pembayaran::whereDate('tanggal_bayar', Carbon::today())
                                   ->sum('jumlah');

        // ==========================================
        // 4. PELANGGAN NUNGGAK (TOP 10)
        // ==========================================
        $pelangganNunggakList = Pelanggan::whereHas('tagihans', function($q) {
            $q->where('status', 'nunggak');
        })
        ->withCount(['tagihans as total_nunggak' => function($q) {
            $q->where('status', 'nunggak');
        }])
        ->with(['tagihans' => function($q) {
            $q->where('status', 'nunggak')
            ->orderBy('bulan', 'asc');
        }])
        ->orderByDesc('total_nunggak')
        ->limit(10)
        ->get();

        // ==========================================
        // 5. TAGIHAN TERBARU (10 TERAKHIR)
        // ==========================================
        $tagihanTerbaru = Tagihan::with(['pelanggan.user'])
                                ->orderBy('created_at', 'desc')
                                ->limit(10)
                                ->get();

        // ==========================================
        // 6. GRAFIK PEMBAYARAN 7 HARI TERAKHIR
        // ==========================================
        $grafikPembayaran = Pembayaran::select(
                DB::raw('DATE(tanggal_bayar) as tanggal'),
                DB::raw('COUNT(*) as jumlah'),
                DB::raw('SUM(jumlah) as total')
            )
            ->where('tanggal_bayar', '>=', Carbon::now()->subDays(6))
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Fill missing dates
        $grafikData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $found = $grafikPembayaran->firstWhere('tanggal', $date);
            
            $grafikData[] = [
                'tanggal' => Carbon::parse($date)->format('d M'),
                'jumlah' => $found ? $found->jumlah : 0,
                'total' => $found ? $found->total : 0
            ];
        }

        // ==========================================
        // 7. PERSENTASE STATUS TAGIHAN
        // ==========================================
        $persentaseLunas = $totalTagihanBulanIni > 0 
            ? round(($tagihanLunas / $totalTagihanBulanIni) * 100, 1) 
            : 0;
        
        $persentaseNunggak = $totalTagihanBulanIni > 0 
            ? round(($tagihanNunggak / $totalTagihanBulanIni) * 100, 1) 
            : 0;

        // ==========================================
        // RETURN VIEW
        // ==========================================
        return view('petugas.dashboard', compact(
            // Pelanggan
            'totalPelanggan',
            'pelangganAktif',
            'pelangganNonaktif',
            'pelangganBaru',
            
            // Tagihan
            'totalTagihanBulanIni',
            'tagihanLunas',
            'tagihanNunggak',
            'nominalTagihanBulanIni',
            'nominalTerbayar',
            'nominalNunggak',
            'persentaseLunas',
            'persentaseNunggak',
            
            // Pembayaran
            'pembayaranHariIni',
            'nominalHariIni',
            
            // List
            'pelangganNunggakList',
            'tagihanTerbaru',
            
            // Grafik
            'grafikData'
        ));
    }
}