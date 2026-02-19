<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Jalankan seeder ini SEKALI untuk men-generate QR token
 * bagi pelanggan lama yang belum punya token.
 *
 * php artisan db:seed --class=GenerateQrTokenSeeder
 */
class GenerateQrTokenSeeder extends Seeder
{
    public function run(): void
    {
        $pelanggans = Pelanggan::whereNull('qr_token')->get();

        $count = 0;
        foreach ($pelanggans as $pelanggan) {
            $token = hash('sha256', $pelanggan->id . Str::random(40) . now()->timestamp . $count);

            $pelanggan->updateQuietly([
                'qr_token' => $token,
                'qr_token_generated_at' => now(),
            ]);
            $count++;
        }

        $this->command->info("✓ Berhasil generate QR token untuk {$count} pelanggan.");
    }
}