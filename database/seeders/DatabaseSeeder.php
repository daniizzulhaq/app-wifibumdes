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
        // PELANGGAN + TAGIHAN
        // =====================
        for ($i = 1; $i <= 10; $i++) {

            $userPelanggan = User::create([
                'name' => "Pelanggan $i",
                'email' => "pelanggan$i@gmail.com",
                'password' => Hash::make('password'),
                'role' => 'pelanggan',
            ]);

            $status = $i <= 8 ? 'aktif' : ($i == 9 ? 'pending' : 'nonaktif');
            $paketId = rand(1, 4);

            $pelanggan = Pelanggan::create([
                'user_id' => $userPelanggan->id,
                'alamat' => "Jl. Contoh No. $i, Kota Contoh",
                'no_wa' => '0812345678' . sprintf('%02d', $i),
                'link_maps' => 'https://maps.google.com/?q=-6.200000,106.816666',
                'foto_rumah' => null,
                'paket_id' => $paketId,
                'status' => $status,
            ]);

            // PPPoE
            PppoeAccount::create([
                'pelanggan_id' => $pelanggan->id,
                'username_pppoe' => "user$i",
                'password_pppoe' => "pass$i",
            ]);

            // =====================
            // TAGIHAN (HANYA AKTIF)
            // =====================
            if ($status === 'aktif') {

                $paket = PaketWifi::find($paketId);

                // Bulan ini
                $now = Carbon::now();
                Tagihan::create([
                    'pelanggan_id' => $pelanggan->id,
                    'bulan' => $now->month,
                    'tahun' => $now->year,
                    'jumlah' => $paket->harga,
                    'status' => $i % 3 == 0 ? 'nunggak' : 'lunas',
                    'tanggal_bayar' => $i % 3 == 0 ? null : Carbon::now()->subDays(rand(1, 10)),
                ]);

                // Bulan lalu
                $lastMonth = Carbon::now()->subMonth();
                Tagihan::create([
                    'pelanggan_id' => $pelanggan->id,
                    'bulan' => $lastMonth->month,
                    'tahun' => $lastMonth->year,
                    'jumlah' => $paket->harga,
                    'status' => 'lunas',
                    'tanggal_bayar' => $lastMonth->copy()->addDays(5),
                ]);

                // Tunggakan 2 bulan lalu
                if ($i % 4 == 0) {
                    $twoMonthsAgo = Carbon::now()->subMonths(2);
                    Tagihan::create([
                        'pelanggan_id' => $pelanggan->id,
                        'bulan' => $twoMonthsAgo->month,
                        'tahun' => $twoMonthsAgo->year,
                        'jumlah' => $paket->harga,
                        'status' => 'nunggak',
                    ]);
                }
            }
        }

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('Admin    : admin@wifi.com / password');
        $this->command->info('Petugas  : petugas@wifi.com / password');
        $this->command->info('Pelanggan: pelanggan1@gmail.com / password');
    }
}
