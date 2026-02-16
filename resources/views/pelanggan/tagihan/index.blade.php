@extends('layouts.pelanggan-app')

@section('title', 'Daftar Tagihan')

@section('content')
<div class="space-y-6">
    
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Tagihan</h1>
            <p class="text-gray-600 mt-1">Kelola dan bayar tagihan WiFi Anda</p>
        </div>
    </div>
    
    <!-- Filter & Search -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <form method="GET" action="{{ route('pelanggan.tagihan.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            
            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="nunggak" {{ request('status') == 'nunggak' ? 'selected' : '' }}>Nunggak</option>
                    <option value="menunggu_konfirmasi" {{ request('status') == 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                    <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>
            
            <!-- Bulan Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                <input type="month" name="bulan" value="{{ request('bulan') }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <!-- Tahun Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                <select name="tahun" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Tahun</option>
                    @for($i = date('Y'); $i >= date('Y') - 3; $i--)
                        <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            
            <!-- Submit Button -->
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                    <i class="fas fa-filter mr-2"></i>
                    Filter
                </button>
            </div>
        </form>
    </div>
    
    <!-- Statistics Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm mb-1">Total Nunggak</p>
                    <h3 class="text-3xl font-bold">{{ $stats['nunggak'] ?? 0 }}</h3>
                </div>
                <div class="h-14 w-14 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-3xl"></i>
                </div>
            </div>
            <p class="text-red-100 text-sm mt-3">
                Rp {{ number_format($stats['total_nunggak'] ?? 0, 0, ',', '.') }}
            </p>
        </div>
        
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm mb-1">Menunggu Konfirmasi</p>
                    <h3 class="text-3xl font-bold">{{ $stats['menunggu_konfirmasi'] ?? 0 }}</h3>
                </div>
                <div class="h-14 w-14 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-3xl"></i>
                </div>
            </div>
            <p class="text-yellow-100 text-sm mt-3">
                Rp {{ number_format($stats['total_menunggu'] ?? 0, 0, ',', '.') }}
            </p>
        </div>
        
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm mb-1">Sudah Lunas</p>
                    <h3 class="text-3xl font-bold">{{ $stats['lunas'] ?? 0 }}</h3>
                </div>
                <div class="h-14 w-14 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-3xl"></i>
                </div>
            </div>
            <p class="text-green-100 text-sm mt-3">
                Rp {{ number_format($stats['total_lunas'] ?? 0, 0, ',', '.') }}
            </p>
        </div>
    </div>
    
    <!-- Tagihan List -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-list mr-2 text-blue-600"></i>
                Riwayat Tagihan
            </h2>
        </div>
        
        @if($tagihans->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Periode
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jatuh Tempo
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total Tagihan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($tagihans as $tagihan)
                    @php
                        $namaBulan = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                        $bulanText = $namaBulan[$tagihan->bulan] ?? 'N/A';
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $bulanText }} {{ $tagihan->tahun }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Dibuat: {{ $tagihan->created_at->isoFormat('D MMM Y') }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->isoFormat('D MMMM YYYY') }}
                            </div>
                            @if(\Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->isPast() && $tagihan->status == 'nunggak')
                            <div class="text-xs text-red-600 font-medium">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Terlambat
                            </div>
                            @endif
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
                            @elseif($tagihan->status == 'menunggu_konfirmasi')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    Menunggu Konfirmasi
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Nunggak
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('pelanggan.tagihan.show', $tagihan->id) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                @if($tagihan->status == 'nunggak')
                                <a href="{{ route('pelanggan.tagihan.show', $tagihan->id) }}" 
                                   class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-credit-card"></i> Bayar
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $tagihans->links() }}
        </div>
        
        @else
        <div class="p-12 text-center">
            <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Belum Ada Tagihan</h3>
            <p class="text-gray-600">Tagihan Anda akan muncul di sini</p>
        </div>
        @endif
    </div>
    
</div>
@endsection