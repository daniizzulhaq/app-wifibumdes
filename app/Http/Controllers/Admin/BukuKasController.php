<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BukuKas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BukuKasController extends Controller
{
    /**
     * Tampilan utama Buku Kas dengan filter
     */
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', date('n'));
        $tahun = $request->get('tahun', date('Y'));
        $jenis = $request->get('jenis');
        $kategori = $request->get('kategori');

        // Query dengan filter
        $query = BukuKas::whereYear('tanggal', $tahun)
                        ->whereMonth('tanggal', $bulan);

        if ($jenis) {
            $query->where('jenis', $jenis);
        }

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $bukuKas = $query->orderBy('tanggal', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->get();

        // Hitung total per bulan yang difilter dengan mempertimbangkan kategori
        $totalQuery = BukuKas::whereYear('tanggal', $tahun)
                              ->whereMonth('tanggal', $bulan);
        
        if ($kategori) {
            $totalQuery->where('kategori', $kategori);
        }

        $totalPemasukan = (clone $totalQuery)->where('jenis', 'pemasukan')->sum('nominal');
        $totalPengeluaran = (clone $totalQuery)->where('jenis', 'pengeluaran')->sum('nominal');

        $saldo = $totalPemasukan - $totalPengeluaran;

        // Daftar kategori untuk dropdown
        $kategoriList = [
            'operasional' => 'Operasional',
            'perbaikan' => 'Perbaikan',
            'perawatan' => 'Perawatan',
            'pelatihan' => 'Pelatihan',
            'stock_barang' => 'Stock Barang',
            'tagihan_banwith' => 'Tagihan Bandwidth',
            'honor_karyawan' => 'Honor Karyawan',
            'sosial' => 'Sosial',
            'donatur' => 'Donatur',
            'listrik' => 'Listrik',
            'bpjs' => 'BPJS',
            'pajak' => 'Pajak',
            'administrasi' => 'Administrasi',
            'thr' => 'THR',
            'lain_lain' => 'Lain-lain'
        ];

        return view('admin.buku_kas.index', compact(
            'bukuKas',
            'totalPemasukan',
            'totalPengeluaran',
            'saldo',
            'kategoriList'
        ));
    }

    /**
     * Form tambah baru
     */
    public function create()
    {
        return view('admin.buku_kas.create');
    }

    /**
     * Simpan data baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal'   => 'required|date',
            'jenis'     => 'required|in:pemasukan,pengeluaran',
            'nominal'   => 'required|numeric|min:0',
            'kategori'  => 'required|in:operasional,perbaikan,perawatan,pelatihan,stock_barang,tagihan_banwith,honor_karyawan,sosial,donatur,listrik,bpjs,pajak,administrasi,thr,lain_lain',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'tanggal.required' => 'Tanggal harus diisi',
            'tanggal.date' => 'Format tanggal tidak valid',
            'jenis.required' => 'Jenis transaksi harus dipilih',
            'jenis.in' => 'Jenis transaksi tidak valid',
            'nominal.required' => 'Nominal harus diisi',
            'nominal.numeric' => 'Nominal harus berupa angka',
            'nominal.min' => 'Nominal tidak boleh negatif',
            'kategori.required' => 'Kategori harus dipilih',
            'kategori.in' => 'Kategori tidak valid',
        ]);

        try {
            BukuKas::create([
                'tanggal'    => $request->tanggal,
                'jenis'      => $request->jenis,
                'nominal'    => $request->nominal,
                'kategori'   => $request->kategori,
                'keterangan' => $request->keterangan,
            ]);

            return redirect()->route('admin.buku_kas.index')
                             ->with('success', '✓ Data berhasil ditambahkan ke Buku Kas!');
        } catch (\Exception $e) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', '✗ Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    /**
     * Form edit
     */
    public function edit(BukuKas $bukuKas)
    {
        return view('admin.buku_kas.edit', compact('bukuKas'));
    }

    /**
     * Update data
     */
    public function update(Request $request, BukuKas $bukuKas)
    {
        $request->validate([
            'tanggal'   => 'required|date',
            'jenis'     => 'required|in:pemasukan,pengeluaran',
            'nominal'   => 'required|numeric|min:0',
            'kategori'  => 'required|in:operasional,perbaikan,perawatan,pelatihan,stock_barang,tagihan_banwith,honor_karyawan,sosial,donatur,listrik,bpjs,pajak,administrasi,thr,lain_lain',
            'keterangan' => 'nullable|string|max:500',
        ]);

        try {
            $bukuKas->update([
                'tanggal'    => $request->tanggal,
                'jenis'      => $request->jenis,
                'nominal'    => $request->nominal,
                'kategori'   => $request->kategori,
                'keterangan' => $request->keterangan,
            ]);

            return redirect()->route('admin.buku_kas.index')
                             ->with('success', '✓ Data berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', '✗ Gagal mengupdate data: ' . $e->getMessage());
        }
    }

    /**
     * Delete
     */
    public function destroy(BukuKas $bukuKas)
    {
        try {
            $bukuKas->delete();
            return redirect()->route('admin.buku_kas.index')
                             ->with('success', '✓ Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', '✗ Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Cetak Laporan Per Bulan dengan Filter
     */
    public function cetak(Request $request)
    {
        $bulan = $request->get('bulan', date('n'));
        $tahun = $request->get('tahun', date('Y'));
        $jenis = $request->get('jenis');
        $kategori = $request->get('kategori');

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $kategoriList = [
            'operasional' => 'Operasional',
            'perbaikan' => 'Perbaikan',
            'perawatan' => 'Perawatan',
            'pelatihan' => 'Pelatihan',
            'stock_barang' => 'Stock Barang',
            'tagihan_banwith' => 'Tagihan Bandwidth',
            'honor_karyawan' => 'Honor Karyawan',
            'sosial' => 'Sosial',
            'donatur' => 'Donatur',
            'listrik' => 'Listrik',
            'bpjs' => 'BPJS',
            'pajak' => 'Pajak',
            'administrasi' => 'Administrasi',
            'thr' => 'THR',
            'lain_lain' => 'Lain-lain'
        ];

        $periodeTampil = $namaBulan[(int)$bulan] . ' ' . $tahun;

        // Tambahkan info filter di periode tampil
        $filterInfo = [];
        if ($jenis) {
            $filterInfo[] = ucfirst($jenis);
        }
        if ($kategori) {
            $filterInfo[] = $kategoriList[$kategori];
        }
        
        if (!empty($filterInfo)) {
            $periodeTampil .= ' (' . implode(', ', $filterInfo) . ')';
        }

        // Data transaksi per bulan dengan filter
        $query = BukuKas::whereYear('tanggal', $tahun)
                        ->whereMonth('tanggal', $bulan);

        if ($jenis) {
            $query->where('jenis', $jenis);
        }

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $bukuKas = $query->orderBy('tanggal', 'asc')
                        ->orderBy('created_at', 'asc')
                        ->get();

        // Total per bulan dengan filter
        $totalPemasukan = $bukuKas->where('jenis', 'pemasukan')->sum('nominal');
        $totalPengeluaran = $bukuKas->where('jenis', 'pengeluaran')->sum('nominal');
        $saldo = $totalPemasukan - $totalPengeluaran;

        // Detail per kategori
        $pemasukanPerKategori = $bukuKas->where('jenis', 'pemasukan')
                                        ->groupBy('kategori')
                                        ->map(function($items) {
                                            return [
                                                'total' => $items->sum('nominal'),
                                                'count' => $items->count()
                                            ];
                                        });

        $pengeluaranPerKategori = $bukuKas->where('jenis', 'pengeluaran')
                                          ->groupBy('kategori')
                                          ->map(function($items) {
                                              return [
                                                  'total' => $items->sum('nominal'),
                                                  'count' => $items->count()
                                              ];
                                          });

        return view('admin.buku_kas.cetak', compact(
            'bukuKas',
            'periodeTampil',
            'totalPemasukan',
            'totalPengeluaran',
            'saldo',
            'pemasukanPerKategori',
            'pengeluaranPerKategori',
            'jenis',
            'kategori'
        ));
    }

    /**
     * Tampilkan laporan keuangan lengkap
     */
    public function laporan(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        // Data per bulan dalam setahun
        $laporanBulanan = [];
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $pemasukan = BukuKas::whereYear('tanggal', $tahun)
                                ->whereMonth('tanggal', $bulan)
                                ->where('jenis', 'pemasukan')
                                ->sum('nominal');

            $pengeluaran = BukuKas::whereYear('tanggal', $tahun)
                                  ->whereMonth('tanggal', $bulan)
                                  ->where('jenis', 'pengeluaran')
                                  ->sum('nominal');

            $laporanBulanan[$bulan] = [
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran,
                'saldo' => $pemasukan - $pengeluaran
            ];
        }

        // Total tahunan
        $totalPemasukanTahun = BukuKas::whereYear('tanggal', $tahun)
                                      ->where('jenis', 'pemasukan')
                                      ->sum('nominal');

        $totalPengeluaranTahun = BukuKas::whereYear('tanggal', $tahun)
                                        ->where('jenis', 'pengeluaran')
                                        ->sum('nominal');

        $saldoTahun = $totalPemasukanTahun - $totalPengeluaranTahun;

        // Laporan per kategori
        $laporanKategori = BukuKas::whereYear('tanggal', $tahun)
                                  ->select('kategori', 'jenis', DB::raw('SUM(nominal) as total'))
                                  ->groupBy('kategori', 'jenis')
                                  ->get()
                                  ->groupBy('kategori');

        return view('admin.buku_kas.laporan', compact(
            'tahun',
            'laporanBulanan',
            'totalPemasukanTahun',
            'totalPengeluaranTahun',
            'saldoTahun',
            'laporanKategori'
        ));
    }

    /**
     * Dashboard Ringkasan Keuangan
     */
    public function dashboard()
    {
        // Saldo bulan ini
        $bulanIni = date('n');
        $tahunIni = date('Y');

        $pemasukanBulanIni = BukuKas::whereYear('tanggal', $tahunIni)
                                    ->whereMonth('tanggal', $bulanIni)
                                    ->where('jenis', 'pemasukan')
                                    ->sum('nominal');

        $pengeluaranBulanIni = BukuKas::whereYear('tanggal', $tahunIni)
                                      ->whereMonth('tanggal', $bulanIni)
                                      ->where('jenis', 'pengeluaran')
                                      ->sum('nominal');

        $saldoBulanIni = $pemasukanBulanIni - $pengeluaranBulanIni;

        // Total keseluruhan
        $totalPemasukan = BukuKas::where('jenis', 'pemasukan')->sum('nominal');
        $totalPengeluaran = BukuKas::where('jenis', 'pengeluaran')->sum('nominal');
        $saldoKeseluruhan = $totalPemasukan - $totalPengeluaran;

        // Transaksi terakhir
        $transaksiTerakhir = BukuKas::orderBy('created_at', 'desc')
                                    ->limit(5)
                                    ->get();

        return view('admin.buku_kas.dashboard', compact(
            'pemasukanBulanIni',
            'pengeluaranBulanIni',
            'saldoBulanIni',
            'totalPemasukan',
            'totalPengeluaran',
            'saldoKeseluruhan',
            'transaksiTerakhir'
        ));
    }
}