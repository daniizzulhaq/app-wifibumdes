<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Tagihan;
use App\Models\PaketWifi;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik umum
        $totalPelanggan = Pelanggan::count();
        $pelangganAktif = Pelanggan::where('status', 'aktif')->count();
        $pelangganPending = Pelanggan::where('status', 'pending')->count();
        
        // Bulan ini dalam format Y-m (misal: 2025-02)
        $bulanIni = Carbon::now()->format('Y-m');
        
        // DEBUG: Cek sample tagihan untuk melihat format bulan
        $sampleTagihan = Tagihan::first();
        \Log::info('=== DASHBOARD DEBUG ===');
        \Log::info('Bulan Ini Format: ' . $bulanIni);
        \Log::info('Sample Tagihan: ' . ($sampleTagihan ? json_encode($sampleTagihan->toArray()) : 'No tagihan found'));
        
        // Cek total tagihan di database
        $totalTagihanCount = Tagihan::count();
        \Log::info('Total Tagihan Count: ' . $totalTagihanCount);
        
        // Cek distinct bulan values
        $distinctBulan = Tagihan::select('bulan')->distinct()->pluck('bulan');
        \Log::info('Distinct Bulan Values: ' . $distinctBulan->toJson());
        
        // Query tagihan bulan ini dengan berbagai metode
        $tagihanBulanIniQuery = Tagihan::where(function($query) use ($bulanIni) {
            $query->where('bulan', $bulanIni)
                  ->orWhere('bulan', 'LIKE', $bulanIni . '%');
        });
        
        $tagihanBulanIniCount = $tagihanBulanIniQuery->count();
        \Log::info('Tagihan Bulan Ini Count: ' . $tagihanBulanIniCount);
        
        // Statistik tagihan bulan ini
        $tagihanBulanIni = Tagihan::where(function($query) use ($bulanIni) {
            $query->where('bulan', $bulanIni)
                  ->orWhere('bulan', 'LIKE', $bulanIni . '%');
        })->sum('jumlah');
        
        $tagihanLunas = Tagihan::where(function($query) use ($bulanIni) {
            $query->where('bulan', $bulanIni)
                  ->orWhere('bulan', 'LIKE', $bulanIni . '%');
        })
        ->where('status', 'lunas')
        ->sum('jumlah');
        
        $tagihanNunggak = Tagihan::where(function($query) use ($bulanIni) {
            $query->where('bulan', $bulanIni)
                  ->orWhere('bulan', 'LIKE', $bulanIni . '%');
        })
        ->where('status', 'nunggak')
        ->sum('jumlah');
        
        \Log::info('Tagihan Bulan Ini Total: ' . $tagihanBulanIni);
        \Log::info('Tagihan Lunas: ' . $tagihanLunas);
        \Log::info('Tagihan Nunggak: ' . $tagihanNunggak);
        
        // Total tunggakan keseluruhan (dari semua bulan)
        $totalTunggakan = Tagihan::where('status', 'nunggak')->sum('jumlah');
        \Log::info('Total Tunggakan All Time: ' . $totalTunggakan);
        
        // Tagihan nunggak terbaru (dari semua bulan)
        $tagihanNunggakList = Tagihan::with(['pelanggan.user', 'pelanggan.paket'])
            ->where('status', 'nunggak')
            ->orderBy('bulan', 'desc')
            ->limit(10)
            ->get();
        
        // Pelanggan baru bulan ini
        $pelangganBaru = Pelanggan::with(['user', 'paket'])
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->latest()
            ->limit(5)
            ->get();
        
        // Statistik paket
        $paketPopuler = PaketWifi::withCount('pelanggans')
            ->orderBy('pelanggans_count', 'desc')
            ->limit(5)
            ->get();
        
        // Total pendapatan bulan ini
        $pendapatanBulanIni = $tagihanLunas;

        return view('admin.dashboard', compact(
            'totalPelanggan',
            'pelangganAktif',
            'pelangganPending',
            'tagihanBulanIni',
            'tagihanLunas',
            'tagihanNunggak',
            'totalTunggakan',
            'tagihanNunggakList',
            'pelangganBaru',
            'paketPopuler',
            'pendapatanBulanIni'
        ));
    }

    public function laporan(Request $request)
    {
        $tahun = $request->input('tahun', Carbon::now()->year);
        
        // Pendapatan per bulan
        $pendapatanPerBulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $bulan = $tahun . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $pendapatanPerBulan[$i] = Tagihan::where(function($query) use ($bulan) {
                $query->where('bulan', $bulan)
                      ->orWhere('bulan', 'LIKE', $bulan . '%');
            })
            ->where('status', 'lunas')
            ->sum('jumlah');
        }
        
        // Total pendapatan tahun ini
        $totalPendapatan = array_sum($pendapatanPerBulan);
        
        // Tagihan nunggak per bulan
        $nunggakPerBulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $bulan = $tahun . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $nunggakPerBulan[$i] = Tagihan::where(function($query) use ($bulan) {
                $query->where('bulan', $bulan)
                      ->orWhere('bulan', 'LIKE', $bulan . '%');
            })
            ->where('status', 'nunggak')
            ->sum('jumlah');
        }

        return view('admin.laporan', compact(
            'tahun',
            'pendapatanPerBulan',
            'totalPendapatan',
            'nunggakPerBulan'
        ));
    }
}