<?php

namespace App\Observers;

use App\Models\Pelanggan;
use Illuminate\Support\Str;

class PelangganObserver
{
    /**
     * Otomatis generate QR token setiap kali pelanggan baru dibuat.
     */
    public function created(Pelanggan $pelanggan): void
    {
        // Generate token unik berbasis id + random
        $token = hash('sha256', $pelanggan->id . Str::random(40) . now()->timestamp);

        $pelanggan->updateQuietly([
            'qr_token' => $token,
            'qr_token_generated_at' => now(),
        ]);
    }
}