<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PaketWifiController;
use App\Http\Controllers\Admin\PelangganController as AdminPelangganController;
use App\Http\Controllers\Admin\TagihanController as AdminTagihanController;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboardController;
use App\Http\Controllers\Petugas\PelangganController as PetugasPelangganController;
use App\Http\Controllers\Petugas\TagihanController as PetugasTagihanController;
use App\Http\Controllers\Pelanggan\DashboardController as PelangganDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BukuKasController;
use App\Http\Controllers\Admin\LaporanTagihanController;
use App\Http\Controllers\Petugas\PembayaranController;
use App\Http\Controllers\Petugas\PaketWifiController as PetugasPaketWifiController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
    
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->isPetugas()) {
            return redirect()->route('petugas.dashboard');
        } else {
            return redirect()->route('pelanggan.dashboard');
        }
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    
    // Laporan Tagihan (BARU)
    Route::get('/laporan/tagihan', [LaporanTagihanController::class, 'index'])->name('laporan.tagihan');
    Route::get('/laporan/tagihan/cetak', [LaporanTagihanController::class, 'cetak'])->name('laporan.tagihan.cetak');
    
    // Paket WiFi Management
    Route::resource('paket', PaketWifiController::class);

    // Users
    Route::resource('users', UserController::class);
    
    // Pelanggan Management
    Route::resource('pelanggan', AdminPelangganController::class);
    Route::post('/pelanggan/{pelanggan}/status', [AdminPelangganController::class, 'updateStatus'])->name('pelanggan.update-status');
    Route::post('/pelanggan/bulk-action', [AdminPelangganController::class, 'bulkAction'])->name('pelanggan.bulk-action');
    Route::get('/pelanggan-export', [AdminPelangganController::class, 'export'])->name('pelanggan.export');
    
    // Tagihan Management
    Route::get('/tagihan', [AdminTagihanController::class, 'index'])->name('tagihan.index');
    Route::get('/tagihan/nunggak', [AdminTagihanController::class, 'nunggak'])->name('tagihan.nunggak');
    Route::get('/tagihan/{tagihan}', [AdminTagihanController::class, 'show'])->name('tagihan.show');
    Route::post('/tagihan/{tagihan}/konfirmasi', [AdminTagihanController::class, 'konfirmasi'])->name('tagihan.konfirmasi');
    Route::post('/tagihan/{tagihan}/tolak', [AdminTagihanController::class, 'tolakPembayaran'])->name('tagihan.tolak');
    Route::post('/tagihan/generate', [AdminTagihanController::class, 'generate'])->name('tagihan.generate');
    
    // Buku Kas
    Route::get('/buku-kas', [BukuKasController::class, 'index'])->name('buku_kas.index');
    Route::get('/buku-kas/cetak', [BukuKasController::class, 'cetak'])->name('buku_kas.cetak');
    Route::get('/buku-kas/create', [BukuKasController::class, 'create'])->name('buku_kas.create');
    Route::post('/buku-kas', [BukuKasController::class, 'store'])->name('buku_kas.store');
    Route::get('/buku-kas/{bukuKas}/edit', [BukuKasController::class, 'edit'])->name('buku_kas.edit');
    Route::put('/buku-kas/{bukuKas}', [BukuKasController::class, 'update'])->name('buku_kas.update');
    Route::delete('/buku-kas/{bukuKas}', [BukuKasController::class, 'destroy'])->name('buku_kas.destroy');
    
    // Settings
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('settings');
    
    // Profile
    Route::get('/profile', function () {
        return view('admin.profile');
    })->name('profile');
});

/*
|--------------------------------------------------------------------------
| Petugas Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');
    
    // Pelanggan Management
    Route::resource('pelanggan', PetugasPelangganController::class);
    Route::post('/pelanggan/{pelanggan}/status', [PetugasPelangganController::class, 'updateStatus'])->name('pelanggan.update-status');
    Route::post('/pelanggan/{pelanggan}/paket', [PetugasPelangganController::class, 'updatePaket'])->name('pelanggan.update-paket');
    Route::post('/pelanggan/{pelanggan}/pppoe', [PetugasPelangganController::class, 'updatePppoe'])->name('pelanggan.update-pppoe');
    Route::post('/pelanggan-bulk-action', [PetugasPelangganController::class, 'bulkAction'])->name('pelanggan.bulk-action');
    Route::get('/pelanggan-export', [PetugasPelangganController::class, 'export'])->name('pelanggan.export');
    Route::get('/generate-password', [PetugasPelangganController::class, 'generatePassword'])->name('generate-password');
    
    // Tagihan Management
    Route::prefix('tagihan')->name('tagihan.')->group(function () {
        // Index - Daftar semua tagihan
        Route::get('/', [PetugasTagihanController::class, 'index'])->name('index');

        // Menunggu Konfirmasi (WAJIB sebelum /{tagihan})
        Route::get('/menunggu-konfirmasi', [PetugasTagihanController::class, 'menungguKonfirmasi'])->name('menunggu-konfirmasi');

        // Nunggak - Daftar tagihan nunggak
        Route::get('/nunggak', [PetugasTagihanController::class, 'nunggak'])->name('nunggak');

        // Generate tagihan bulanan
        Route::post('/generate', [PetugasTagihanController::class, 'generate'])->name('generate');

        // Konfirmasi pembayaran
        Route::post('/{tagihan}/konfirmasi', [PetugasTagihanController::class, 'konfirmasi'])->name('konfirmasi');

        // Tolak pembayaran
        Route::post('/{tagihan}/tolak', [PetugasTagihanController::class, 'tolakPembayaran'])->name('tolak');

        // Detail tagihan (HARUS PALING BAWAH)
        Route::get('/{tagihan}', [PetugasTagihanController::class, 'show'])->name('show');
    });

    // Pembayaran
    Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
        Route::get('/', [PembayaranController::class, 'index'])->name('index');
        Route::get('/{tagihan}', [PembayaranController::class, 'show'])->name('show');
        Route::post('/{tagihan}/konfirmasi', [PembayaranController::class, 'konfirmasi'])->name('konfirmasi');
        Route::post('/batch-konfirmasi', [PembayaranController::class, 'batchKonfirmasi'])->name('batch-konfirmasi');
        Route::get('/{tagihan}/kwitansi', [PembayaranController::class, 'cetakKwitansi'])->name('kwitansi');
        Route::post('/{tagihan}/tolak', [PembayaranController::class, 'tolak'])->name('tolak');

         Route::get('/laporan/harian', [PembayaranController::class, 'laporanHarian'])->name('laporan-harian');
    });


    Route::resource('paket', PetugasPaketWifiController::class);
  
});

/*
|--------------------------------------------------------------------------
| Pelanggan Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pelanggan'])->prefix('pelanggan')->name('pelanggan.')->group(function () {
    Route::get('/dashboard', [PelangganDashboardController::class, 'index'])->name('dashboard');
    
    // Tagihan
    Route::get('/tagihan', [PelangganDashboardController::class, 'tagihan'])->name('tagihan.index');
    Route::get('/tagihan/{tagihan}', [PelangganDashboardController::class, 'tagihanShow'])->name('tagihan.show');
    Route::post('/tagihan/{tagihan}/upload-bukti', [PelangganDashboardController::class, 'uploadBukti'])->name('tagihan.upload-bukti');
    Route::get('/tagihan/{tagihan}/cetak-invoice', [PelangganDashboardController::class, 'cetakInvoice'])->name('tagihan.cetak-invoice'); // ✅ ROUTE BARU
    
    // Profile
    Route::get('/profile', [PelangganDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [PelangganDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/change-password', [PelangganDashboardController::class, 'changePassword'])->name('profile.change-password');
    
    // Paket WiFi
    Route::get('/paket', [PelangganDashboardController::class, 'paket'])->name('paket.index');
});