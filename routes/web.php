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
use App\Http\Controllers\Admin\ImportPelangganController;
use App\Http\Controllers\QrController; // ← BARU

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
| QR Code Routes
|--------------------------------------------------------------------------
| Route scan bersifat PUBLIK (tanpa middleware auth) agar QR bisa dibuka
| langsung dari kamera HP sebelum login. Controller akan handle redirect
| ke login jika belum authenticated.
*/

// Scan QR — PUBLIK (tidak butuh login dulu)
Route::get('/qr/scan/{token}', [QrController::class, 'scan'])->name('qr.scan');

// Lihat & kelola QR — butuh login
Route::middleware('auth')->group(function () {
    Route::get('/qr/pelanggan/{pelanggan}',             [QrController::class, 'show'])->name('qr.show');
    Route::post('/qr/pelanggan/{pelanggan}/regenerate', [QrController::class, 'regenerate'])->name('qr.regenerate');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Laporan Tagihan
    Route::get('/laporan/tagihan',        [LaporanTagihanController::class, 'index'])->name('laporan.tagihan');
    Route::get('/laporan/tagihan/cetak',  [LaporanTagihanController::class, 'cetak'])->name('laporan.tagihan.cetak');
    
    // Paket WiFi Management
    Route::resource('paket', PaketWifiController::class);

    // Users
    Route::resource('users', UserController::class);

    // ---------------------------------------------------------------
    // Import Pelanggan — WAJIB sebelum Route::resource pelanggan
    // ---------------------------------------------------------------
    Route::get('/pelanggan/import',           [ImportPelangganController::class, 'index'])->name('pelanggan.import');
    Route::get('/pelanggan/import/template',  [ImportPelangganController::class, 'downloadTemplate'])->name('pelanggan.import.template');
    Route::post('/pelanggan/import/preview',  [ImportPelangganController::class, 'preview'])->name('pelanggan.import.preview');
    Route::post('/pelanggan/import/process',  [ImportPelangganController::class, 'import'])->name('pelanggan.import.process');

    // Pelanggan Management — resource SETELAH import
    Route::resource('pelanggan', AdminPelangganController::class);
    Route::post('/pelanggan/{pelanggan}/status',  [AdminPelangganController::class, 'updateStatus'])->name('pelanggan.update-status');
    Route::post('/pelanggan/bulk-action',          [AdminPelangganController::class, 'bulkAction'])->name('pelanggan.bulk-action');
    Route::get('/pelanggan-export',                [AdminPelangganController::class, 'export'])->name('pelanggan.export');

    // Tagihan Management
    Route::get('/tagihan',                            [AdminTagihanController::class, 'index'])->name('tagihan.index');
    Route::get('/tagihan/nunggak',                    [AdminTagihanController::class, 'nunggak'])->name('tagihan.nunggak');
    // ↓ BARU — halaman tagihan per pelanggan (target setelah admin scan QR)
    Route::get('/tagihan/pelanggan/{pelanggan}',      [AdminTagihanController::class, 'indexByPelanggan'])->name('tagihan.pelanggan');
    Route::get('/tagihan/{tagihan}',                  [AdminTagihanController::class, 'show'])->name('tagihan.show');
    Route::post('/tagihan/{tagihan}/konfirmasi',      [AdminTagihanController::class, 'konfirmasi'])->name('tagihan.konfirmasi');
    Route::post('/tagihan/{tagihan}/tolak',           [AdminTagihanController::class, 'tolakPembayaran'])->name('tagihan.tolak');
    Route::post('/tagihan/generate',                  [AdminTagihanController::class, 'generate'])->name('tagihan.generate');
    
    // Buku Kas
    Route::get('/buku-kas',                   [BukuKasController::class, 'index'])->name('buku_kas.index');
    Route::get('/buku-kas/cetak',             [BukuKasController::class, 'cetak'])->name('buku_kas.cetak');
    Route::get('/buku-kas/create',            [BukuKasController::class, 'create'])->name('buku_kas.create');
    Route::post('/buku-kas',                  [BukuKasController::class, 'store'])->name('buku_kas.store');
    Route::get('/buku-kas/{bukuKas}/edit',    [BukuKasController::class, 'edit'])->name('buku_kas.edit');
    Route::put('/buku-kas/{bukuKas}',         [BukuKasController::class, 'update'])->name('buku_kas.update');
    Route::delete('/buku-kas/{bukuKas}',      [BukuKasController::class, 'destroy'])->name('buku_kas.destroy');
    
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
    Route::post('/pelanggan/{pelanggan}/status',  [PetugasPelangganController::class, 'updateStatus'])->name('pelanggan.update-status');
    Route::post('/pelanggan/{pelanggan}/paket',   [PetugasPelangganController::class, 'updatePaket'])->name('pelanggan.update-paket');
    Route::post('/pelanggan/{pelanggan}/pppoe',   [PetugasPelangganController::class, 'updatePppoe'])->name('pelanggan.update-pppoe');
    Route::post('/pelanggan-bulk-action',          [PetugasPelangganController::class, 'bulkAction'])->name('pelanggan.bulk-action');
    Route::get('/pelanggan-export',                [PetugasPelangganController::class, 'export'])->name('pelanggan.export');
    Route::get('/generate-password',               [PetugasPelangganController::class, 'generatePassword'])->name('generate-password');
    
    // Tagihan Management
    Route::prefix('tagihan')->name('tagihan.')->group(function () {
        Route::get('/',                           [PetugasTagihanController::class, 'index'])->name('index');
        Route::get('/menunggu-konfirmasi',        [PetugasTagihanController::class, 'menungguKonfirmasi'])->name('menunggu-konfirmasi');
        Route::get('/nunggak',                    [PetugasTagihanController::class, 'nunggak'])->name('nunggak');
        Route::post('/generate',                  [PetugasTagihanController::class, 'generate'])->name('generate');
        Route::post('/{tagihan}/konfirmasi',      [PetugasTagihanController::class, 'konfirmasi'])->name('konfirmasi');
        Route::post('/{tagihan}/tolak',           [PetugasTagihanController::class, 'tolakPembayaran'])->name('tolak');
        Route::get('/{tagihan}',                  [PetugasTagihanController::class, 'show'])->name('show');
    });

    // Pembayaran
    Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
        Route::get('/',                               [PembayaranController::class, 'index'])->name('index');
        Route::get('/laporan/harian',                 [PembayaranController::class, 'laporanHarian'])->name('laporan-harian');
        // ↓ BARU — halaman tagihan per pelanggan (target setelah petugas scan QR)
        // PENTING: route statis '/pelanggan/{id}' WAJIB di atas '/{tagihan}' agar tidak bentrok
        Route::get('/pelanggan/{pelanggan}',          [PembayaranController::class, 'indexByPelanggan'])->name('pelanggan');
        Route::post('/batch-konfirmasi',              [PembayaranController::class, 'batchKonfirmasi'])->name('batch-konfirmasi');
        Route::get('/{tagihan}',                      [PembayaranController::class, 'show'])->name('show');
        Route::post('/{tagihan}/konfirmasi',          [PembayaranController::class, 'konfirmasi'])->name('konfirmasi');
        Route::get('/{tagihan}/kwitansi',             [PembayaranController::class, 'cetakKwitansi'])->name('kwitansi');
        Route::post('/{tagihan}/tolak',               [PembayaranController::class, 'tolak'])->name('tolak');
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
    Route::get('/tagihan',                              [PelangganDashboardController::class, 'tagihan'])->name('tagihan.index');
    Route::get('/tagihan/{tagihan}',                    [PelangganDashboardController::class, 'tagihanShow'])->name('tagihan.show');
    Route::post('/tagihan/{tagihan}/upload-bukti',      [PelangganDashboardController::class, 'uploadBukti'])->name('tagihan.upload-bukti');
    Route::get('/tagihan/{tagihan}/cetak-invoice',      [PelangganDashboardController::class, 'cetakInvoice'])->name('tagihan.cetak-invoice');
    
    // Profile
    Route::get('/profile',                 [PelangganDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile',                 [PelangganDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/change-password', [PelangganDashboardController::class, 'changePassword'])->name('profile.change-password');
    
    // Paket WiFi
    Route::get('/paket', [PelangganDashboardController::class, 'paket'])->name('paket.index');
});