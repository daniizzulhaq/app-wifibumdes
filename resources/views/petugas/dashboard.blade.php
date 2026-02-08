@extends('layouts.petugas')

@section('title', 'Dashboard Petugas')

@section('content')
<div class="container-fluid">
    
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Dashboard Petugas</h1>
            <p class="text-muted mb-0">Selamat datang, <strong>{{ auth()->user()->name }}</strong></p>
        </div>
        <div class="text-muted">
            <i class="bi bi-calendar3"></i> {{ Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- STATISTIK CARDS --}}
    {{-- ========================================== --}}
    <div class="row g-3 mb-4">
        
        {{-- CARD: Total Pelanggan --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Pelanggan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($totalPelanggan) }}
                            </div>
                            <div class="mt-2 text-xs">
                                <span class="text-success">
                                    <i class="bi bi-arrow-up"></i> {{ $pelangganBaru }}
                                </span>
                                <span class="text-muted">pelanggan baru bulan ini</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD: Pelanggan Aktif --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Pelanggan Aktif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($pelangganAktif) }}
                            </div>
                            <div class="mt-2">
                                <div class="progress" style="height: 4px;">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: {{ $totalPelanggan > 0 ? ($pelangganAktif / $totalPelanggan * 100) : 0 }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD: Tagihan Bulan Ini --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Tagihan Bulan Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($totalTagihanBulanIni) }}
                            </div>
                            <div class="mt-2 text-xs">
                                <span class="text-success">{{ $tagihanLunas }} Lunas</span> | 
                                <span class="text-danger">{{ $tagihanNunggak }} Nunggak</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-file-earmark-text-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD: Pembayaran Hari Ini --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pembayaran Hari Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($pembayaranHariIni) }}
                            </div>
                            <div class="mt-2 text-xs text-muted">
                                Rp {{ number_format($nominalHariIni, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cash-coin fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ========================================== --}}
    {{-- ROW 2: GRAFIK & RINGKASAN --}}
    {{-- ========================================== --}}
    <div class="row g-3 mb-4">
        
        {{-- GRAFIK PEMBAYARAN 7 HARI --}}
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-graph-up"></i> Grafik Pembayaran 7 Hari Terakhir
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="grafikPembayaran" height="80"></canvas>
                </div>
            </div>
        </div>

        {{-- RINGKASAN TAGIHAN --}}
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-pie-chart-fill"></i> Ringkasan Tagihan Bulan Ini
                    </h6>
                </div>
                <div class="card-body">
                    
                    {{-- Total Tagihan --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-xs font-weight-bold">Total Tagihan</span>
                            <span class="text-xs font-weight-bold">
                                Rp {{ number_format($nominalTagihanBulanIni, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-info" style="width: 100%"></div>
                        </div>
                    </div>

                    {{-- Terbayar --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-xs font-weight-bold text-success">Terbayar ({{ $persentaseLunas }}%)</span>
                            <span class="text-xs font-weight-bold text-success">
                                Rp {{ number_format($nominalTerbayar, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: {{ $persentaseLunas }}%"></div>
                        </div>
                    </div>

                    {{-- Nunggak --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-xs font-weight-bold text-danger">Nunggak ({{ $persentaseNunggak }}%)</span>
                            <span class="text-xs font-weight-bold text-danger">
                                Rp {{ number_format($nominalNunggak, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-danger" style="width: {{ $persentaseNunggak }}%"></div>
                        </div>
                    </div>

                    <hr>

                    {{-- Quick Stats --}}
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <div class="h4 font-weight-bold text-success">{{ $tagihanLunas }}</div>
                                <div class="text-xs text-muted">Lunas</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="h4 font-weight-bold text-danger">{{ $tagihanNunggak }}</div>
                            <div class="text-xs text-muted">Nunggak</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- ========================================== --}}
    {{-- ROW 3: TABEL DATA --}}
    {{-- ========================================== --}}
    <div class="row g-3">
        
        {{-- PELANGGAN NUNGGAK --}}
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="bi bi-exclamation-triangle-fill"></i> Top 10 Pelanggan Nunggak
                    </h6>
                    <a href="{{ route('petugas.tagihan.nunggak') }}" class="btn btn-sm btn-danger">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 400px;">
                        <table class="table table-hover mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>Pelanggan</th>
                                    <th>Kontak</th>
                                    <th class="text-center">Jml Nunggak</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pelangganNunggakList as $pelanggan)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold">{{ $pelanggan->user->name }}</div>
                                        <small class="text-muted">{{ Str::limit($pelanggan->alamat, 30) }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $pelanggan->no_hp }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger">{{ $pelanggan->total_nunggak }}</span>
                                    </td>
                                    <td class="text-end">
                                        <strong>Rp {{ number_format($pelanggan->tagihans->sum('total'), 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="bi bi-check-circle"></i> Tidak ada pelanggan nunggak
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAGIHAN TERBARU --}}
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-clock-history"></i> Tagihan Terbaru
                    </h6>
                    <a href="{{ route('petugas.tagihan.index') }}" class="btn btn-sm btn-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 400px;">
                        <table class="table table-hover mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>Pelanggan</th>
                                    <th>Jenis Kegiatan</th>
                                    <th>Bulan</th>
                                    <th>Status</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tagihanTerbaru as $tagihan)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold">{{ $tagihan->pelanggan->user->name }}</div>
                                    </td>
                                    <td>
                                        <small>{{ Carbon\Carbon::parse($tagihan->bulan)->isoFormat('MMM Y') }}</small>
                                    </td>
                                    <td>
                                        @if($tagihan->status == 'lunas')
                                        <span class="badge bg-success">Lunas</span>
                                        @else
                                        <span class="badge bg-danger">Nunggak</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <strong>Rp {{ number_format($tagihan->total, 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        Belum ada tagihan
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ========================================== --}}
    {{-- QUICK ACTIONS --}}
    {{-- ========================================== --}}
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-lightning-fill"></i> Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('petugas.pelanggan.create') }}" class="btn btn-primary btn-block w-100">
                                <i class="bi bi-person-plus"></i> Registrasi Pelanggan Baru
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('petugas.tagihan.index') }}" class="btn btn-info btn-block w-100">
                                <i class="bi bi-file-earmark-text"></i> Lihat Tagihan Pelanggan
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('petugas.tagihan.nunggak') }}" class="btn btn-danger btn-block w-100">
                                <i class="bi bi-exclamation-triangle"></i> Tagihan Nunggak
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('petugas.pelanggan.index') }}" class="btn btn-success btn-block w-100">
                                <i class="bi bi-people"></i> Kelola Pelanggan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// ==========================================
// CHART: GRAFIK PEMBAYARAN 7 HARI
// ==========================================
const ctx = document.getElementById('grafikPembayaran').getContext('2d');
const grafikData = @json($grafikData);

const labels = grafikData.map(item => item.tanggal);
const jumlahData = grafikData.map(item => item.jumlah);
const totalData = grafikData.map(item => item.total);

new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Jumlah Transaksi',
                data: jumlahData,
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                tension: 0.4,
                yAxisID: 'y'
            },
            {
                label: 'Total Nominal (Rp)',
                data: totalData,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.4,
                yAxisID: 'y1'
            }
        ]
    },
    options: {
        responsive: true,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        if (context.datasetIndex === 1) {
                            label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        } else {
                            label += context.parsed.y;
                        }
                        return label;
                    }
                }
            }
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: {
                    display: true,
                    text: 'Jumlah Transaksi'
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: {
                    display: true,
                    text: 'Total (Rp)'
                },
                grid: {
                    drawOnChartArea: false,
                },
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});
</script>
@endpush

@push('styles')
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.sticky-top {
    position: sticky;
    top: 0;
    z-index: 10;
}
</style>
@endpush