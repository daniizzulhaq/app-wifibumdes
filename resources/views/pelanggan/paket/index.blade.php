@extends('layouts.pelanggan-app')

@section('title', 'Paket WiFi Saya')

@section('content')
<div class="space-y-6">
    
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Paket WiFi Saya</h1>
            <p class="text-gray-600 mt-1">Informasi lengkap paket internet Anda</p>
        </div>
    </div>

    <!-- Informasi Paket -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Card Paket Aktif - Span 2 columns on large screens -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-white">
                        <i class="fas fa-wifi mr-2"></i>
                        Informasi Paket
                    </h2>
                    <span class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full">
                        <i class="fas fa-check-circle mr-1"></i>
                        Aktif
                    </span>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Paket -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="text-sm text-gray-500 mb-1 block">Nama Paket</label>
                            <h3 class="text-xl font-bold text-gray-800">
                                {{ $pelanggan->paket->nama_paket ?? '-' }}
                            </h3>
                        </div>
                        
                        <!-- Kecepatan -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="text-sm text-gray-500 mb-1 block">Kecepatan Internet</label>
                            <h3 class="text-xl font-bold text-blue-600 flex items-center">
                                <i class="fas fa-tachometer-alt mr-2"></i>
                                {{ $pelanggan->paket->kecepatan ?? '-' }}
                            </h3>
                        </div>
                        
                        <!-- Biaya Bulanan -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                            <label class="text-sm text-green-700 mb-1 block">Biaya Bulanan</label>
                            <h3 class="text-2xl font-bold text-green-600">
                                {{ $pelanggan->paket->harga_format ?? '-' }}
                            </h3>
                        </div>
                        
                        <!-- ID Pelanggan -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="text-sm text-gray-500 mb-1 block">ID Pelanggan</label>
                            <h3 class="text-xl font-bold text-gray-800 font-mono">
                                {{ $pelanggan->id_pelanggan }}
                            </h3>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Alamat -->
                            <div>
                                <label class="text-sm text-gray-500 mb-2 block flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                                    Alamat Pemasangan
                                </label>
                                <p class="text-gray-800">{{ $pelanggan->alamat ?? '-' }}</p>
                            </div>
                            
                            <!-- No Telepon -->
                            <div>
                                <label class="text-sm text-gray-500 mb-2 block flex items-center">
                                    <i class="fas fa-phone mr-2 text-gray-400"></i>
                                    No. Telepon
                                </label>
                                <p class="text-gray-800">{{ $pelanggan->no_telepon ?? '-' }}</p>
                            </div>
                            
                            <!-- Email -->
                            <div class="md:col-span-2">
                                <label class="text-sm text-gray-500 mb-2 block flex items-center">
                                    <i class="fas fa-envelope mr-2 text-gray-400"></i>
                                    Email
                                </label>
                                <p class="text-gray-800">{{ $pelanggan->user->email ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar - Statistics & Info -->
        <div class="space-y-6">
            
            <!-- Statistik Pembayaran -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-chart-pie mr-2 text-blue-600"></i>
                        Statistik Pembayaran
                    </h3>
                </div>
                
                <div class="p-6 space-y-4">
                    <!-- Bulan Lunas -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Bulan Lunas</span>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full">
                                {{ $totalLunas }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-green-500 h-2.5 rounded-full transition-all duration-300" 
                                 style="width: {{ $totalLunas > 0 ? ($totalLunas / max(1, $totalLunas + $totalNunggak) * 100) : 0 }}%">
                            </div>
                        </div>
                    </div>

                    <!-- Bulan Nunggak -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Bulan Nunggak</span>
                            <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-semibold rounded-full">
                                {{ $totalNunggak }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-red-500 h-2.5 rounded-full transition-all duration-300" 
                                 style="width: {{ $totalNunggak > 0 ? ($totalNunggak / max(1, $totalLunas + $totalNunggak) * 100) : 0 }}%">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <a href="{{ route('pelanggan.tagihan.index') }}" 
                           class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center px-4 py-3 rounded-lg font-semibold transition-colors">
                            <i class="fas fa-file-invoice mr-2"></i>
                            Lihat Semua Tagihan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Info Card -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-md p-6 border-l-4 border-blue-600">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 bg-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-info-circle text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-semibold text-blue-900 mb-2">Informasi Penting</h4>
                        <p class="text-sm text-blue-800">
                            Hubungi admin jika Anda ingin mengubah atau upgrade paket WiFi Anda.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Riwayat Tagihan 12 Bulan Terakhir -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-history mr-2 text-blue-600"></i>
                Riwayat Tagihan (12 Bulan Terakhir)
            </h2>
        </div>
        
        @if($riwayatTagihan->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Periode
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jumlah
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal Bayar
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Metode
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($riwayatTagihan as $tagihan)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::create()->month($tagihan->bulan)->translatedFormat('F') }} 
                                        {{ $tagihan->tahun }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">
                                Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($tagihan->status == 'lunas')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Lunas
                                </span>
                            @elseif($tagihan->status == 'nunggak')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    Nunggak
                                </span>
                            @elseif($tagihan->status == 'menunggu_konfirmasi')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    Menunggu Konfirmasi
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $tagihan->tanggal_bayar ? \Carbon\Carbon::parse($tagihan->tanggal_bayar)->format('d M Y') : '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($tagihan->metode_pembayaran == 'transfer_bank')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-university mr-1"></i>
                                    Transfer Bank
                                </span>
                            @elseif($tagihan->metode_pembayaran == 'e_wallet')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-wallet mr-1"></i>
                                    E-Wallet
                                </span>
                            @elseif($tagihan->metode_pembayaran == 'tunai')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-money-bill mr-1"></i>
                                    Tunai
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-12 text-center">
            <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Belum Ada Riwayat Tagihan</h3>
            <p class="text-gray-600">Riwayat tagihan Anda akan muncul di sini</p>
        </div>
        @endif
    </div>
    
</div>
@endsection