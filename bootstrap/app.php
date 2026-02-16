<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;
use App\Http\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {
        // ✅ DAFTARKAN ROLE MIDDLEWARE DI SINI
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })

    // ============================================================================
    // 📱 SCHEDULED TASKS - Notifikasi WhatsApp Otomatis
    // ============================================================================
    ->withSchedule(function (Schedule $schedule): void {
        
        // 1️⃣ Kirim notifikasi pengingat tagihan setiap hari jam 08:00
        // Akan otomatis cek tagihan yang jatuh tempo 7, 6, 5, 4, 3, 2, 1, 0 hari lagi
        $schedule->command('notifikasi:tagihan')
            ->dailyAt('08:00')
            ->withoutOverlapping()
            ->onOneServer()
            ->onSuccess(function () {
                \Log::info('✓ Notifikasi tagihan berhasil dikirim');
            })
            ->onFailure(function () {
                \Log::error('✗ Notifikasi tagihan gagal dikirim');
            });

        // 2️⃣ Kirim notifikasi untuk tagihan nunggak setiap hari jam 09:00
        // Hanya kirim pada hari ke: 1, 3, 7, 14, 30 setelah jatuh tempo
        $schedule->command('notifikasi:nunggak')
            ->dailyAt('09:00')
            ->withoutOverlapping()
            ->onOneServer()
            ->onSuccess(function () {
                \Log::info('✓ Notifikasi nunggak berhasil dikirim');
            })
            ->onFailure(function () {
                \Log::error('✗ Notifikasi nunggak gagal dikirim');
            });

        // 3️⃣ Update status tagihan yang sudah lewat jatuh tempo menjadi nunggak
        // Jalankan setiap hari jam 00:01
        $schedule->call(function () {
            $updated = \App\Models\Tagihan::where('status', 'belum_bayar')
                ->where('tanggal_jatuh_tempo', '<', now())
                ->update(['status' => 'nunggak']);
            
            \Log::info("⚙️ Auto-update status nunggak: {$updated} tagihan diupdate");
        })
        ->dailyAt('00:01')
        ->name('update-status-nunggak')
        ->onOneServer();

        // ============================================================================
        // 📊 OPTIONAL: Additional Scheduled Tasks
        // ============================================================================

        // 4️⃣ Backup database setiap hari jam 02:00 (opsional)
        // Uncomment jika sudah install spatie/laravel-backup
        // $schedule->command('backup:run')
        //     ->dailyAt('02:00')
        //     ->onOneServer();

        // 5️⃣ Bersihkan log lama setiap minggu (opsional)
        // $schedule->command('log:clear')
        //     ->weekly()
        //     ->onOneServer();

        // 6️⃣ Generate laporan bulanan otomatis setiap tanggal 1 jam 07:00 (opsional)
        // $schedule->command('laporan:generate-bulanan')
        //     ->monthlyOn(1, '07:00')
        //     ->onOneServer();
    })

    ->create();