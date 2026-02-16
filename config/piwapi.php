<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Piwapi API Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk integrasi dengan Piwapi WhatsApp Gateway
    | Dapatkan credentials dari https://panel.piwapi.com
    |
    */

     'api_url'     => env('PIWAPI_URL', 'https://piwapi.com/api'),
    'api_secret' => env('PIWAPI_API_KEY'),
    'account'    => env('PIWAPI_ACCOUNT', null),

    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    */

    // Hari-hari sebelum jatuh tempo untuk kirim notifikasi
    'reminder_days' => [7, 6, 5, 4, 3, 2, 1, 0],

    // Hari-hari setelah nunggak untuk kirim notifikasi
    'overdue_notification_days' => [1, 3, 7, 14, 30],

    // Delay antar pengiriman pesan (dalam detik)
    'sending_delay' => 2,

    // Nomor test untuk mode testing
    'test_phone_number' => env('PIWAPI_TEST_PHONE', '6281392246785'),

];