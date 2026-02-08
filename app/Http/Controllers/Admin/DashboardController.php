<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Tagihan;
use App\Models\PaketWifi;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        // Statistik umum
        $totalPelanggan = Pelanggan::count();
        $pelangganAktif = Pelanggan::where('status', 'aktif')->count();
        $pelangganPending = Pelanggan::where('status', 'pending')->count();
        
        // Statistik tagihan
        $bulanIni = Carbon::now()->format('Y-m');
        $tagihanBulanIni = Tagihan::where('bulan', $bulanIni)->sum('jumlah');
        $tagihanLunas = Tagihan::where('bulan', $bulanIni)
            ->where('status', 'lunas')
            ->sum('jumlah');
        $tagihanNunggak = Tagihan::where('bulan', $bulanIni)
            ->where('status', 'nunggak')
            ->sum('jumlah');
        
        // Total tunggakan keseluruhan
        $totalTunggakan = Tagihan::where('status', 'nunggak')->sum('jumlah');
        
        // Tagihan terbaru yang nunggak
        $tagihanNunggakList = Tagihan::with(['pelanggan.user', 'pelanggan.paket'])
            ->where('status', 'nunggak')
            ->orderBy('bulan', 'asc')
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
        $pendapatanBulanIni = Tagihan::where('bulan', $bulanIni)
            ->where('status', 'lunas')
            ->sum('jumlah');

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

    /**
     * Display laporan pendapatan
     */
    public function laporan(Request $request)
    {
        $tahun = $request->input('tahun', Carbon::now()->year);
        
        // Pendapatan per bulan
        $pendapatanPerBulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $bulan = $tahun . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $pendapatanPerBulan[$i] = Tagihan::where('bulan', $bulan)
                ->where('status', 'lunas')
                ->sum('jumlah');
        }
        
        // Total pendapatan tahun ini
        $totalPendapatan = array_sum($pendapatanPerBulan);
        
        // Tagihan nunggak per bulan
        $nunggakPerBulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $bulan = $tahun . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $nunggakPerBulan[$i] = Tagihan::where('bulan', $bulan)
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