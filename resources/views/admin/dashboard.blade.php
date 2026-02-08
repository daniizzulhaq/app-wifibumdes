@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Pelanggan -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Total Pelanggan</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $totalPelanggan }}</h3>
                    <p class="text-xs text-green-600 mt-1">
                        <i class="fas fa-arrow-up"></i> {{ $pelangganAktif }} Aktif
                    </p>
                </div>
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-3xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Pendapatan Bulan Ini -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Pendapatan Bulan Ini</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h3>
                    <p class="text-xs text-gray-500 mt-1">
                        Target: Rp {{ number_format($tagihanBulanIni, 0, ',', '.') }}
                    </p>
                </div>
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-3xl text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Tagihan Nunggak -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Total Tunggakan</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</h3>
                    <p class="text-xs text-red-600 mt-1">
                        <i class="fas fa-exclamation-triangle"></i> Perlu Tindak Lanjut
                    </p>
                </div>
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-3xl text-red-600"></i>
                </div>
            </div>
        </div>

        <!-- Pelanggan Pending -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Pelanggan Pending</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $pelangganPending }}</h3>
                    <p class="text-xs text-yellow-600 mt-1">
                        <i class="fas fa-clock"></i> Menunggu Aktivasi
                    </p>
                </div>
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-clock text-3xl text-yellow-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Tagihan Bulan Ini -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-chart-pie mr-2 text-blue-600"></i> Tagihan Bulan Ini
            </h3>
            <div class="h-64">
                <canvas id="tagihanChart"></canvas>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-4">
                <div class="text-center">
                    <p class="text-sm text-gray-600">Lunas</p>
                    <p class="text-xl font-bold text-green-600">Rp {{ number_format($tagihanLunas, 0, ',', '.') }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600">Belum Lunas</p>
                    <p class="text-xl font-bold text-red-600">Rp {{ number_format($tagihanNunggak, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Paket Populer -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-star mr-2 text-yellow-500"></i> Paket Paling Populer
            </h3>
            <div class="space-y-4">
                @foreach($paketPopuler as $paket)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-wifi text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $paket->nama_paket }}</p>
                            <p class="text-sm text-gray-600">{{ $paket->kecepatan }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-blue-600">{{ $paket->pelanggans_count }}</p>
                        <p class="text-xs text-gray-500">Pelanggan</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Tagihan Nunggak Terbaru -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-exclamation-triangle mr-2 text-red-600"></i> Tagihan Nunggak
                </h3>
                <a href="{{ route('admin.tagihan.nunggak') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Pelanggan</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Bulan</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600 uppercase">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($tagihanNunggakList as $tagihan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <p class="text-sm font-medium text-gray-800">{{ $tagihan->pelanggan->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $tagihan->pelanggan->paket->nama_paket }}</p>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                @php
                                    try {
                                        // Try Y-m format first
                                        $date = \Carbon\Carbon::createFromFormat('Y-m', $tagihan->bulan);
                                        echo $date->translatedFormat('F Y');
                                    } catch (\Exception $e) {
                                        try {
                                            // Try parsing as regular Carbon date
                                            $date = \Carbon\Carbon::parse($tagihan->bulan);
                                            echo $date->translatedFormat('F Y');
                                        } catch (\Exception $e2) {
                                            // Fallback to raw value
                                            echo $tagihan->bulan;
                                        }
                                    }
                                @endphp
                            </td>
                            <td class="px-4 py-3 text-sm font-semibold text-right text-red-600">
                                Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-check-circle text-3xl text-green-500 mb-2"></i>
                                <p>Tidak ada tunggakan!</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pelanggan Baru -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-user-plus mr-2 text-green-600"></i> Pelanggan Baru Bulan Ini
                </h3>
                <a href="{{ route('admin.pelanggan.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="space-y-3">
                @forelse($pelangganBaru as $pelanggan)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $pelanggan->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $pelanggan->paket->nama_paket }} - {{ $pelanggan->paket->kecepatan }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                            {{ $pelanggan->status == 'aktif' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $pelanggan->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $pelanggan->status == 'nonaktif' ? 'bg-red-100 text-red-700' : '' }}
                        ">
                            {{ ucfirst($pelanggan->status) }}
                        </span>
                        <p class="text-xs text-gray-500 mt-1">{{ $pelanggan->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-inbox text-3xl mb-2"></i>
                    <p>Belum ada pelanggan baru</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-bolt mr-2 text-yellow-500"></i> Quick Actions
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('petugas.pelanggan.create') }}" class="flex flex-col items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition group">
                <i class="fas fa-user-plus text-3xl text-blue-600 mb-2 group-hover:scale-110 transition"></i>
                <span class="text-sm font-medium text-gray-700">Tambah Pelanggan</span>
            </a>
            <button onclick="showGenerateTagihanModal()" class="flex flex-col items-center justify-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition group">
                <i class="fas fa-file-invoice text-3xl text-green-600 mb-2 group-hover:scale-110 transition"></i>
                <span class="text-sm font-medium text-gray-700">Generate Tagihan</span>
            </button>
            <a href="{{ route('admin.paket.create') }}" class="flex flex-col items-center justify-center p-4 bg-orange-50 hover:bg-orange-100 rounded-lg transition group">
                <i class="fas fa-box text-3xl text-orange-600 mb-2 group-hover:scale-110 transition"></i>
                <span class="text-sm font-medium text-gray-700">Tambah Paket</span>
            </a>
        </div>
    </div>
</div>

<!-- Generate Tagihan Modal -->
<div id="generateTagihanModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Generate Tagihan Bulanan</h3>
        <form action="{{ route('petugas.tagihan.generate') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Pilih Bulan</label>
                <input 
                    type="month" 
                    name="bulan" 
                    value="{{ date('Y-m') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                >
            </div>
            <div class="flex space-x-3">
                <button type="button" onclick="closeGenerateTagihanModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    Batal
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Generate
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Tagihan Chart
    const tagihanCtx = document.getElementById('tagihanChart').getContext('2d');
    new Chart(tagihanCtx, {
        type: 'doughnut',
        data: {
            labels: ['Lunas', 'Belum Lunas'],
            datasets: [{
                data: [{{ $tagihanLunas }}, {{ $tagihanNunggak }}],
                backgroundColor: ['#10b981', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Modal Functions
    function showGenerateTagihanModal() {
        document.getElementById('generateTagihanModal').classList.remove('hidden');
        document.getElementById('generateTagihanModal').classList.add('flex');
    }

    function closeGenerateTagihanModal() {
        document.getElementById('generateTagihanModal').classList.add('hidden');
        document.getElementById('generateTagihanModal').classList.remove('flex');
    }
</script>
@endpush