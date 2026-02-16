<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tagihan;
use App\Services\PiwapiService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class KirimNotifikasiNunggak extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'notifikasi:nunggak {--test : Mode test untuk nomor tertentu}';

    /**
     * The console command description.
     */
    protected $description = 'Kirim notifikasi WhatsApp untuk tagihan yang sudah nunggak';

    protected $piwapiService;

    public function __construct(PiwapiService $piwapiService)
    {
        parent::__construct();
        $this->piwapiService = $piwapiService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Memulai pengiriman notifikasi tagihan nunggak...');
        Log::info('Starting notifikasi:nunggak command');

        $isTest = $this->option('test');

        // Ambil tagihan yang nunggak
        $tagihans = Tagihan::with(['pelanggan.user', 'pelanggan.paket'])
            ->where('status', 'nunggak')
            ->whereDate('tanggal_jatuh_tempo', '<', Carbon::now())
            ->get();

        $this->info("   Ditemukan: {$tagihans->count()} tagihan nunggak");

        $totalSent = 0;
        $totalFailed = 0;

        foreach ($tagihans as $tagihan) {
            $pelanggan = $tagihan->pelanggan;
            
            if (!$pelanggan || !$pelanggan->user) {
                $this->warn("   ⚠ Pelanggan tidak ditemukan untuk tagihan ID: {$tagihan->id}");
                continue;
            }

            $nomorHP = $pelanggan->user->no_hp ?? $pelanggan->no_hp ?? null;
            
            if (!$nomorHP) {
                $this->warn("   ⚠ Nomor HP tidak tersedia untuk: {$pelanggan->user->name}");
                continue;
            }

            // Hitung berapa hari sudah lewat jatuh tempo
            $jatuhTempo = Carbon::parse($tagihan->tanggal_jatuh_tempo);
            $daysOverdue = Carbon::now()->diffInDays($jatuhTempo, false) * -1;

            // Hanya kirim notifikasi pada hari ke: 1, 3, 7, 14, 30 setelah jatuh tempo
            // Untuk menghindari spam
            $notificationDays = [1, 3, 7, 14, 30];
            if (!in_array($daysOverdue, $notificationDays)) {
                continue;
            }

            // Mode test: hanya kirim ke nomor tertentu
            if ($isTest) {
                $testPhone = '6281392246785'; // Ganti dengan nomor test Anda
                if ($this->piwapiService->formatPhoneNumber($nomorHP) !== $testPhone) {
                    continue;
                }
            }

            try {
                $this->line("   📤 Mengirim ke: {$pelanggan->user->name} ({$nomorHP}) - Nunggak {$daysOverdue} hari");
                
                $result = $this->piwapiService->sendNotifikasiNunggak(
                    $pelanggan,
                    $tagihan,
                    $daysOverdue
                );

                if ($result['success']) {
                    $this->info("   ✅ Berhasil dikirim!");
                    $totalSent++;
                } else {
                    $this->error("   ❌ Gagal: {$result['message']}");
                    $totalFailed++;
                }

                // Delay untuk menghindari rate limit
                sleep(2);

            } catch (\Exception $e) {
                $this->error("   ❌ Error: {$e->getMessage()}");
                $totalFailed++;
                
                Log::error("Error sending nunggak notification", [
                    'tagihan_id' => $tagihan->id,
                    'pelanggan' => $pelanggan->user->name,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->newLine();
        $this->info("📊 Ringkasan Pengiriman:");
        $this->info("   ✅ Berhasil: {$totalSent}");
        $this->info("   ❌ Gagal: {$totalFailed}");
        $this->info("   📦 Total: " . ($totalSent + $totalFailed));

        Log::info('Notifikasi nunggak command completed', [
            'sent' => $totalSent,
            'failed' => $totalFailed
        ]);

        return Command::SUCCESS;
    }
}