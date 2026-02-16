<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tagihan;
use App\Services\PiwapiService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class KirimNotifikasiTagihan extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'notifikasi:tagihan {--test : Mode test untuk nomor tertentu}';

    /**
     * The console command description.
     */
    protected $description = 'Kirim notifikasi WhatsApp pengingat tagihan kepada pelanggan';

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
        $this->info('🚀 Memulai pengiriman notifikasi tagihan...');
        Log::info('Starting notifikasi:tagihan command');

        $isTest = $this->option('test');

        // Array untuk menyimpan notifikasi yang akan dikirim
        $days = [7, 6, 5, 4, 3, 2, 1, 0]; // 7 hari sebelum hingga hari H
        
        $totalSent = 0;
        $totalFailed = 0;

        foreach ($days as $dayBefore) {
            $targetDate = Carbon::now()->addDays($dayBefore)->format('Y-m-d');
            
            $this->info("📅 Mencari tagihan dengan jatuh tempo: {$targetDate} ({$dayBefore} hari lagi)");

            // Ambil tagihan yang belum lunas dengan jatuh tempo sesuai target
            $tagihans = Tagihan::with(['pelanggan.user', 'pelanggan.paket'])
                ->whereIn('status', ['belum_bayar', 'nunggak'])
                ->whereDate('tanggal_jatuh_tempo', $targetDate)
                ->get();

            $this->info("   Ditemukan: {$tagihans->count()} tagihan");

            foreach ($tagihans as $tagihan) {
                $pelanggan = $tagihan->pelanggan;
                
                if (!$pelanggan || !$pelanggan->user) {
                    $this->warn("   ⚠ Pelanggan tidak ditemukan untuk tagihan ID: {$tagihan->id}");
                    continue;
                }

                $nomorHP = $pelanggan->user->no_wa ?? $pelanggan->no_wa ?? null;
                
                if (!$nomorHP) {
                    $this->warn("   ⚠ Nomor HP tidak tersedia untuk: {$pelanggan->user->name}");
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
                    $this->line("   📤 Mengirim ke: {$pelanggan->user->name} ({$nomorHP})");
                    
                    $result = $this->piwapiService->sendReminderJatuhTempo(
                        $pelanggan,
                        $tagihan,
                        $dayBefore
                    );

                    if ($result['success']) {
                        $this->info("   ✅ Berhasil dikirim!");
                        $totalSent++;
                        
                        // Catat log pengiriman ke database (opsional)
                        $this->catatLogNotifikasi($tagihan, $dayBefore, 'success');
                    } else {
                        $this->error("   ❌ Gagal: {$result['message']}");
                        $totalFailed++;
                        
                        $this->catatLogNotifikasi($tagihan, $dayBefore, 'failed', $result['message']);
                    }

                    // Delay untuk menghindari rate limit
                    sleep(2);

                } catch (\Exception $e) {
                    $this->error("   ❌ Error: {$e->getMessage()}");
                    $totalFailed++;
                    
                    Log::error("Error sending notification", [
                        'tagihan_id' => $tagihan->id,
                        'pelanggan' => $pelanggan->user->name,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        $this->newLine();
        $this->info("📊 Ringkasan Pengiriman:");
        $this->info("   ✅ Berhasil: {$totalSent}");
        $this->info("   ❌ Gagal: {$totalFailed}");
        $this->info("   📦 Total: " . ($totalSent + $totalFailed));

        Log::info('Notifikasi tagihan command completed', [
            'sent' => $totalSent,
            'failed' => $totalFailed
        ]);

        return Command::SUCCESS;
    }

    /**
     * Catat log notifikasi ke database (opsional)
     */
    protected function catatLogNotifikasi($tagihan, $dayBefore, $status, $errorMessage = null)
    {
        // Anda bisa membuat tabel log_notifikasi untuk tracking
        // Untuk saat ini kita log ke file saja
        Log::info('Notifikasi dikirim', [
            'tagihan_id' => $tagihan->id,
            'pelanggan_id' => $tagihan->pelanggan_id,
            'pelanggan_name' => $tagihan->pelanggan->user->name ?? '-',
            'days_before' => $dayBefore,
            'status' => $status,
            'error_message' => $errorMessage,
            'sent_at' => now()
        ]);
    }
}