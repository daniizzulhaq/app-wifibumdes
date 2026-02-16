<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PiwapiService
{
    protected $apiUrl;
    protected $apiSecret;
    protected $account;

    public function __construct()
    {
        $this->apiUrl = config('piwapi.api_url');
        $this->apiSecret = config('piwapi.api_secret');
        $this->account = config('piwapi.account');
    }

    /**
     * Kirim notifikasi pengingat jatuh tempo
     */
    public function sendReminderJatuhTempo($pelanggan, $tagihan, $daysBefore = 0)
    {
        // Cek field no_wa di berbagai lokasi yang mungkin
        $phoneNumber = $this->formatPhoneNumber(
            $pelanggan->no_wa ?? 
            $pelanggan->user->no_wa ?? 
            $pelanggan->no_hp ?? 
            $pelanggan->user->no_hp ?? 
            null
        );
        
        if (!$phoneNumber) {
            Log::warning("No phone number found for pelanggan", [
                'pelanggan_id' => $pelanggan->id,
                'pelanggan_name' => $pelanggan->user->name ?? 'Unknown'
            ]);
            return [
                'success' => false,
                'message' => 'Nomor WhatsApp tidak tersedia'
            ];
        }
        
        $message = $this->createReminderMessage($pelanggan, $tagihan, $daysBefore);
        
        return $this->sendMessage($phoneNumber, $message);
    }

    /**
     * Kirim notifikasi tagihan nunggak
     */
    public function sendNotifikasiNunggak($pelanggan, $tagihan, $daysOverdue = 0)
    {
        $phoneNumber = $this->formatPhoneNumber(
            $pelanggan->no_wa ?? 
            $pelanggan->user->no_wa ?? 
            $pelanggan->no_hp ?? 
            $pelanggan->user->no_hp ?? 
            null
        );
        
        if (!$phoneNumber) {
            Log::warning("No phone number found for pelanggan", [
                'pelanggan_id' => $pelanggan->id,
                'pelanggan_name' => $pelanggan->user->name ?? 'Unknown'
            ]);
            return [
                'success' => false,
                'message' => 'Nomor WhatsApp tidak tersedia'
            ];
        }
        
        $message = $this->createNotifikasiNunggak($pelanggan, $tagihan, $daysOverdue);
        
        return $this->sendMessage($phoneNumber, $message);
    }

    /**
     * Kirim notifikasi pembayaran berhasil
     */
    public function sendNotifikasiPembayaranBerhasil($pelanggan, $tagihan)
    {
        $phoneNumber = $this->formatPhoneNumber(
            $pelanggan->no_wa ?? 
            $pelanggan->user->no_wa ?? 
            $pelanggan->no_hp ?? 
            $pelanggan->user->no_hp ?? 
            null
        );
        
        if (!$phoneNumber) {
            Log::warning("No phone number found for pelanggan", [
                'pelanggan_id' => $pelanggan->id,
                'pelanggan_name' => $pelanggan->user->name ?? 'Unknown'
            ]);
            return [
                'success' => false,
                'message' => 'Nomor WhatsApp tidak tersedia'
            ];
        }
        
        $message = $this->createNotifikasiPembayaranBerhasil($pelanggan, $tagihan);
        
        return $this->sendMessage($phoneNumber, $message);
    }

    /**
     * Format nomor telepon ke format internasional (62xxx)
     */
    public function formatPhoneNumber($phone)
    {
        // Return null jika phone kosong
        if (empty($phone)) {
            return null;
        }

        // Hapus karakter non-digit
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Return null jika setelah dibersihkan masih kosong
        if (empty($phone)) {
            return null;
        }
        
        // Jika diawali 0, ganti dengan 62
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        
        // Jika belum ada 62, tambahkan
        if (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }
        
        return $phone;
    }

    /**
     * Buat pesan notifikasi jatuh tempo
     */
    protected function createReminderMessage($pelanggan, $tagihan, $daysBefore)
    {
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $bulanText = $namaBulan[(int)$tagihan->bulan] ?? '-';
        $tahun = $tagihan->tahun;
        $jatuhTempo = Carbon::parse($tagihan->tanggal_jatuh_tempo)->format('d F Y');
        $totalTagihan = number_format($tagihan->jumlah, 0, ',', '.');
        
        // Ambil email user sebagai username
        $email = $pelanggan->user->email ?? '-';
        $namaPelanggan = $pelanggan->user->name ?? 'Pelanggan';
        $paketWifi = $pelanggan->paket->nama ?? '-';
        
        // Tentukan urgency berdasarkan sisa hari
        if ($daysBefore == 0) {
            $urgency = '🚨 *HARI INI JATUH TEMPO!*';
            $pesan = 'Tagihan WiFi Anda jatuh tempo *HARI INI*. Mohon segera lakukan pembayaran untuk menghindari denda keterlambatan.';
        } elseif ($daysBefore == 1) {
            $urgency = '⏰ *BESOK JATUH TEMPO!*';
            $pesan = 'Tagihan WiFi Anda akan jatuh tempo *BESOK*. Segera lakukan pembayaran sebelum terkena denda.';
        } else {
            $urgency = "📅 *{$daysBefore} HARI LAGI*";
            $pesan = "Tagihan WiFi Anda akan jatuh tempo dalam *{$daysBefore} hari*. Harap segera melakukan pembayaran.";
        }

        return <<<MESSAGE
🔔 *PENGINGAT PEMBAYARAN TAGIHAN*

Halo *{$namaPelanggan}*,

{$pesan}

📋 *Detail Tagihan:*
- Periode: {$bulanText} {$tahun}
- Paket: {$paketWifi}
- Nominal: Rp {$totalTagihan}
- Jatuh Tempo: {$jatuhTempo}

{$urgency}

👤 *Informasi Login Dashboard:*
Link login: https://wifi-bumdes.my.id/login
- Username: {$email}
- Password: 123456

💳 *Cara Pembayaran:*
Login ke dashboard pelanggan Anda atau hubungi admin untuk informasi pembayaran atau transfer:
- Transfer Bank BRI: An. Zikri Rizkian
  No. Rekening: 3768 01022083532
- Transfer Bank BCA: An. Zikri Rizkian
  No. Rekening: 8930536084
- E-wallet DANA: An. Zikri Rizkian
  No. Rekening: 082242350529

Terima kasih atas perhatian Anda! 🙏

_Pesan otomatis dari sistem_
MESSAGE;
    }

    /**
     * Buat pesan notifikasi nunggak
     */
    protected function createNotifikasiNunggak($pelanggan, $tagihan, $daysOverdue)
    {
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $bulanText = $namaBulan[(int)$tagihan->bulan] ?? '-';
        $tahun = $tagihan->tahun;
        $jatuhTempo = Carbon::parse($tagihan->tanggal_jatuh_tempo)->format('d F Y');
        $totalTagihan = number_format($tagihan->jumlah, 0, ',', '.');
        
        $email = $pelanggan->user->email ?? '-';
        $namaPelanggan = $pelanggan->user->name ?? 'Pelanggan';
        $paketWifi = $pelanggan->paket->nama ?? '-';

        return <<<MESSAGE
⚠️ *PERINGATAN TAGIHAN NUNGGAK*

Halo *{$namaPelanggan}*,

Tagihan WiFi Anda sudah melewati jatuh tempo selama *{$daysOverdue} hari*.

📋 *Detail Tagihan:*
- Periode: {$bulanText} {$tahun}
- Paket: {$paketWifi}
- Nominal: Rp {$totalTagihan}
- Jatuh Tempo: {$jatuhTempo}

🚨 *SEGERA LAKUKAN PEMBAYARAN!*

Jika tidak segera dibayar, layanan internet Anda akan dinonaktifkan.

👤 *Informasi Login Dashboard:*
Link login: https://wifi-bumdes.my.id/login
- Username: {$email}
- Password: 123456

💳 *Cara Pembayaran:*
- Transfer Bank BRI: An. Zikri Rizkian
  No. Rekening: 3768 01022083532
- Transfer Bank BCA: An. Zikri Rizkian
  No. Rekening: 8930536084
- E-wallet DANA: An. Zikri Rizkian
  No. Rekening: 082242350529

Hubungi admin jika ada pertanyaan.

_Pesan otomatis dari sistem_
MESSAGE;
    }

    /**
     * Buat pesan notifikasi pembayaran berhasil
     */
    protected function createNotifikasiPembayaranBerhasil($pelanggan, $tagihan)
    {
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $bulanText = $namaBulan[(int)$tagihan->bulan] ?? '-';
        $tahun = $tagihan->tahun;
        $totalTagihan = number_format($tagihan->jumlah, 0, ',', '.');
        $tanggalBayar = Carbon::parse($tagihan->tanggal_bayar)->format('d F Y H:i');
        
        $namaPelanggan = $pelanggan->user->name ?? 'Pelanggan';
        $paketWifi = $pelanggan->paket->nama ?? '-';

        return <<<MESSAGE
✅ *PEMBAYARAN BERHASIL DIKONFIRMASI*

Halo *{$namaPelanggan}*,

Pembayaran tagihan WiFi Anda telah berhasil dikonfirmasi!

📋 *Detail Pembayaran:*
- Periode: {$bulanText} {$tahun}
- Paket: {$paketWifi}
- Nominal: Rp {$totalTagihan}
- Tanggal Bayar: {$tanggalBayar}
- Status: LUNAS ✓

Terima kasih atas pembayaran Anda. Layanan internet Anda tetap aktif.

Untuk informasi lebih lanjut, login ke dashboard pelanggan:
https://wifi-bumdes.my.id/login

_Pesan otomatis dari sistem_
MESSAGE;
    }

    /**
     * Kirim pesan via Piwapi API (WhatsApp)
     */
    protected function sendMessage($phoneNumber, $message)
    {
        // Skip jika nomor telepon kosong
        if (empty($phoneNumber)) {
            Log::warning("Cannot send WhatsApp: phone number is empty");
            return [
                'success' => false,
                'message' => 'Nomor telepon tidak tersedia'
            ];
        }

        try {
            $url = $this->apiUrl . '/send/whatsapp';
            
            // Build payload
            $payload = [
                'secret' => $this->apiSecret,
                'recipient' => $phoneNumber,
                'type' => 'text',
                'message' => $message
            ];
            
            // Tambahkan account HANYA jika ada dan tidak kosong
            if (!empty($this->account)) {
                $payload['account'] = $this->account;
            }

            Log::info("Sending WhatsApp message via Piwapi", [
                'url' => $url,
                'phone' => $phoneNumber,
                'message_preview' => substr($message, 0, 100) . '...'
            ]);

            // Kirim request
            $response = Http::asForm()
                ->timeout(30)
                ->post($url, $payload);

            $statusCode = $response->status();
            $responseData = $response->json();
            
            Log::info("Piwapi API Response", [
                'status_code' => $statusCode,
                'response' => $responseData
            ]);

            // Cek status code dan response
            if ($statusCode === 200 && isset($responseData['status']) && $responseData['status'] === 200) {
                Log::info("WhatsApp notification sent successfully", [
                    'phone' => $phoneNumber,
                    'response' => $responseData
                ]);
                
                return [
                    'success' => true,
                    'message' => 'Notifikasi berhasil dikirim',
                    'data' => $responseData
                ];
            }

            // Handle error responses
            $errorMessage = $responseData['message'] ?? 'Unknown error';
            
            Log::error("Failed to send WhatsApp notification", [
                'phone' => $phoneNumber,
                'status_code' => $statusCode,
                'error_message' => $errorMessage,
                'full_response' => $responseData
            ]);
            
            return [
                'success' => false,
                'message' => 'Gagal mengirim notifikasi: ' . $errorMessage,
                'data' => $responseData
            ];

        } catch (\Exception $e) {
            Log::error("Exception sending WhatsApp notification", [
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Test koneksi ke Piwapi API
     */
    public function testConnection()
    {
        try {
            $testPhone = config('piwapi.test_phone_number', '6281392246785');
            $testMessage = '✅ Test connection dari WiFi Billing System - ' . now()->format('d/m/Y H:i:s');
            
            $result = $this->sendMessage($testPhone, $testMessage);
            
            return $result;
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
}