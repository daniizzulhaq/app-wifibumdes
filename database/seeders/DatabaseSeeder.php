<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\PaketWifi;
use App\Models\Pelanggan;
use App\Models\PppoeAccount;
use App\Models\Tagihan;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =====================
        // ADMIN & PETUGAS
        // =====================
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@wifi.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Petugas WiFi',
            'email' => 'petugas@wifi.com',
            'password' => Hash::make('password'),
            'role' => 'petugas',
        ]);

        // =====================
// PAKET WIFI
// =====================
$pakets = [
    ['nama_paket' => 'Paket Basic', 'kecepatan' => '10 Mbps', 'harga' => 150000],
    ['nama_paket' => 'Paket Standard', 'kecepatan' => '20 Mbps', 'harga' => 250000],
    ['nama_paket' => 'Paket Premium', 'kecepatan' => '50 Mbps', 'harga' => 450000],
    ['nama_paket' => 'Paket Ultra', 'kecepatan' => '100 Mbps', 'harga' => 750000],
];

        foreach ($pakets as $paket) {
            PaketWifi::create($paket);
        }

        // =====================
        // PELANGGAN (1 USER SAJA)
        // =====================
        $userPelanggan = User::create([
            'name' => 'Pelanggan 1',
            'email' => 'pelanggan1@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'pelanggan',
        ]);

        $pelanggan = Pelanggan::create([
            'user_id' => $userPelanggan->id,
            'kode_pelanggan' => 'PLG-001',
            'alamat' => 'Jl. Contoh No. 1, Kota Contoh',
            'no_telepon' => '081234567801',
            'no_wa' => '081234567801',
            'link_maps' => 'https://maps.google.com/?q=-6.200000,106.816666',
            'foto_rumah' => null,
            'paket_id' => 1, // Paket Basic
            'status' => 'aktif',
        ]);

        // PPPoE Account
        PppoeAccount::create([
            'pelanggan_id' => $pelanggan->id,
            'username_pppoe' => 'user1',
            'password_pppoe' => 'pass1',
        ]);

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('');
        $this->command->info('=== LOGIN CREDENTIALS ===');
        $this->command->info('Admin    : admin@wifi.com / password');
        $this->command->info('Petugas  : petugas@wifi.com / password');
        $this->command->info('Pelanggan: pelanggan1@gmail.com / password');
        $this->command->info('');
        $this->command->info('📝 Note: Tagihan akan di-generate manual oleh admin');
    }
}