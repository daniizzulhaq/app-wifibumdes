@extends('layouts.pelanggan-app')

@section('title', 'Dashboard Pelanggan')

@section('content')
<div class="space-y-6">
    
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-gray-600 mt-1">Selamat datang, {{ Auth::user()->name }}!</p>
        </div>
        <div class="mt-4 md:mt-0">
            <p class="text-sm text-gray-600">
                <i class="fas fa-calendar-alt mr-2"></i>
                {{ now()->isoFormat('dddd, D MMMM YYYY') }}
            </p>
        </div>
    </div>
    
    <!-- Info Pelanggan Card -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-lg font-semibold mb-2">Informasi Pelanggan</h2>
                <div class="space-y-2">
                    <p class="flex items-center gap-2">
                        <i class="fas fa-id-card w-5"></i>
                        <span class="font-mono">{{ $pelanggan->kode_pelanggan ?? 'N/A' }}</span>
                    </p>
                    <p class="flex items-center gap-2">
                        <i class="fas fa-map-marker-alt w-5"></i>
                        <span>{{ $pelanggan->alamat ?? 'Alamat tidak tersedia' }}</span>
                    </p>
                    <p class="flex items-center gap-2">
                        <i class="fas fa-phone w-5"></i>
                        <span>{{ $pelanggan->no_hp ?? 'No HP tidak tersedia' }}</span>
                    </p>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                    {{ $pelanggan->status == 'aktif' ? 'bg-green-400 text-green-900' : 'bg-red-400 text-red-900' }}">
                    <i class="fas fa-circle text-xs mr-2"></i>
                    {{ ucfirst($pelanggan->status ?? 'N/A') }}
                </span>
            </div>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Total Tagihan Bulan Ini -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Tagihan Bulan Ini</p>
                    <h3 class="text-2xl font-bold text-gray-800">
                        Rp {{ number_format($tagihan_bulan_ini ?? 0, 0, ',', '.') }}
                    </h3>
                </div>
                <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-invoice text-blue-600 text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">
                <i class="fas fa-calendar mr-1"></i>
                {{ now()->isoFormat('MMMM YYYY') }}
            </p>
        </div>
        
        <!-- Tagihan Belum Bayar -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Belum Dibayar</p>
                    <h3 class="text-2xl font-bold text-red-600">
                        {{ $tagihan_belum_bayar ?? 0 }}
                    </h3>
                </div>
                <div class="h-12 w-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">
                Total: Rp {{ number_format($total_belum_bayar ?? 0, 0, ',', '.') }}
            </p>
        </div>
        
        <!-- Tagihan Lunas -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Sudah Lunas</p>
                    <h3 class="text-2xl font-bold text-green-600">
                        {{ $tagihan_lunas ?? 0 }}
                    </h3>
                </div>
                <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">
                Tahun ini: {{ $tagihan_lunas_tahun_ini ?? 0 }} tagihan
            </p>
        </div>
        
        <!-- Paket Aktif -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Paket Aktif</p>
                    <h3 class="text-lg font-bold text-gray-800">
                        {{ $paket_aktif->nama_paket ?? 'Tidak ada paket' }}
                    </h3>
                </div>
                <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-box text-purple-600 text-xl"></i>
                </div>
            </div>
            @if(isset($paket_aktif))
            <p class="text-xs text-gray-500 mt-2">
                <i class="fas fa-tachometer-alt mr-1"></i>
                {{ $paket_aktif->kecepatan ?? 'N/A' }} Mbps
            </p>
            @endif
        </div>
        
    </div>
    
    <!-- Tagihan Terbaru & Notifikasi -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Tagihan Terbaru -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-md">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-file-invoice mr-2 text-blue-600"></i>
                        Tagihan Terbaru
                    </h2>
                    <a href="{{ route('pelanggan.tagihan.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            
            <div class="p-6">
             @if(isset($tagihan_terbaru) && $tagihan_terbaru->count() > 0)
                <div class="space-y-4">
                    @foreach($tagihan_terbaru as $index => $tagihan)
@php
    $parts = explode('-', $tagihan->bulan);
    $tahun = $parts[0] ?? date('Y');
    $bulan = $parts[1] ?? date('m');
    $tanggalBulan = \Carbon\Carbon::create($tahun, $bulan, 1);
@endphp
<tr class="hover:bg-gray-50">
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
            <div class="h-10 w-10 flex-shrink-0 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-calendar text-blue-600"></i>
            </div>
            <div class="ml-4">
                <div class="text-sm font-medium text-gray-900">
                    {{ $tanggalBulan->isoFormat('MMMM YYYY') }}
                </div>
                <div class="text-xs text-gray-500">
                    Dibuat: {{ $tagihan->created_at->isoFormat('D MMM Y') }}
                </div>
            </div>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm text-gray-900">
            @if($tagihan->tanggal_jatuh_tempo)
                {{ \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->isoFormat('D MMMM YYYY') }}
            @else
                {{ $tanggalBulan->copy()->addDays(10)->isoFormat('D MMMM YYYY') }}
            @endif
        </div>
        @if($tagihan->tanggal_jatuh_tempo && \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->isPast() && in_array($tagihan->status, ['belum_bayar', 'nunggak']))
        <div class="text-xs text-red-600 font-medium">
            <i class="fas fa-exclamation-triangle mr-1"></i>
            Terlambat
        </div>
        @endif
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm font-bold text-gray-900">
            Rp {{ number_format($tagihan->total ?? $tagihan->jumlah, 0, ',', '.') }}
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        @if($tagihan->status == 'lunas')
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                <i class="fas fa-check-circle mr-1"></i>
                Lunas
            </span>
        @elseif($tagihan->status == 'menunggu_konfirmasi')
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                <i class="fas fa-clock mr-1"></i>
                Menunggu Konfirmasi
            </span>
        @else
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                <i class="fas fa-times-circle mr-1"></i>
                Belum Bayar
            </span>
        @endif
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm">
        <div class="flex items-center gap-2">
            <a href="{{ route('pelanggan.tagihan.show', $tagihan->id) }}" 
               class="text-blue-600 hover:text-blue-900">
                <i class="fas fa-eye"></i> Detail
            </a>
            @if(in_array($tagihan->status, ['belum_bayar', 'nunggak']))
            <a href="{{ route('pelanggan.tagihan.show', $tagihan->id) }}" 
               class="text-green-600 hover:text-green-900">
                <i class="fas fa-credit-card"></i> Bayar
            </a>
            @endif
        </div>
    </td>
</tr>
@endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                    <p class="text-gray-500">Belum ada tagihan</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Notifikasi & Info -->
        <div class="space-y-6">
            
            <!-- Paket WiFi Info -->
            <div class="bg-white rounded-xl shadow-md p-6" id="paket">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-wifi mr-2 text-purple-600"></i>
                    Paket WiFi Saya
                </h3>
                @if(isset($paket_aktif))
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Nama Paket:</span>
                        <span class="text-sm font-semibold">{{ $paket_aktif->nama_paket }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Kecepatan:</span>
                        <span class="text-sm font-semibold">{{ $paket_aktif->kecepatan }} Mbps</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Harga/Bulan:</span>
                        <span class="text-sm font-semibold">Rp {{ number_format($paket_aktif->harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-3 border-t">
                        <p class="text-xs text-gray-500">{{ $paket_aktif->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                    </div>
                </div>
                @else
                <p class="text-sm text-gray-500 text-center py-4">Belum ada paket aktif</p>
                @endif
            </div>
            
            <!-- Notifikasi -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-bell mr-2 text-yellow-600"></i>
                    Notifikasi
                </h3>
                <div class="space-y-3">
                    @if(isset($notifikasi) && count($notifikasi) > 0)
                        @foreach($notifikasi as $notif)
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <p class="text-sm font-medium text-gray-800">{{ $notif['title'] }}</p>
                            <p class="text-xs text-gray-600 mt-1">{{ $notif['message'] }}</p>
                            <p class="text-xs text-gray-400 mt-2">{{ $notif['time'] }}</p>
                        </div>
                        @endforeach
                    @else
                    <div class="text-center py-6">
                        <i class="fas fa-bell-slash text-gray-300 text-3xl mb-2"></i>
                        <p class="text-sm text-gray-500">Tidak ada notifikasi</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="bg-gradient-to-br from-green-400 to-green-600 rounded-xl shadow-md p-6 text-white">
                <h3 class="text-lg font-semibold mb-4">
                    <i class="fas fa-headset mr-2"></i>
                    Butuh Bantuan?
                </h3>
                <p class="text-sm mb-4 text-green-50">
                    Hubungi customer service kami untuk bantuan teknis atau pertanyaan lainnya.
                </p>
                <div class="space-y-2">
                    <a href="https://wa.me/6281234567890" target="_blank" 
                       class="block w-full bg-white text-green-600 text-center py-2 rounded-lg font-semibold hover:bg-green-50 transition-colors">
                        <i class="fab fa-whatsapp mr-2"></i>
                        WhatsApp CS
                    </a>
                    <a href="tel:+6281234567890" 
                       class="block w-full bg-green-700 text-white text-center py-2 rounded-lg font-semibold hover:bg-green-800 transition-colors">
                        <i class="fas fa-phone mr-2"></i>
                        Call Center
                    </a>
                </div>
            </div>
            
        </div>
        
    </div>
    
</div>
@endsection