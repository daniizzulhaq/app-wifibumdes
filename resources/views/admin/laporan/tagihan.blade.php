@extends('layouts.admin')

@section('title', 'Laporan Tagihan')
@section('page-title', 'Laporan Tagihan')

@section('content')
<style>
    .container-custom {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
    }

    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .header-title h2 {
        font-size: 24px;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 5px 0;
    }

    .header-title p {
        color: #6b7280;
        font-size: 14px;
        margin: 0;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-left: 4px solid;
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .stat-card.green  { border-color: #10b981; }
    .stat-card.red    { border-color: #ef4444; }
    .stat-card.blue   { border-color: #3b82f6; }
    .stat-card.yellow { border-color: #f59e0b; }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .stat-card.green  .stat-icon { background: #d1fae5; color: #10b981; }
    .stat-card.red    .stat-icon { background: #fee2e2; color: #ef4444; }
    .stat-card.blue   .stat-icon { background: #dbeafe; color: #3b82f6; }
    .stat-card.yellow .stat-icon { background: #fef3c7; color: #f59e0b; }

    .stat-info h6 {
        color: #6b7280;
        font-size: 12px;
        font-weight: 600;
        margin: 0 0 4px 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-info h3 {
        font-size: 24px;
        font-weight: 800;
        margin: 0;
        color: #111827;
    }

    .stat-info p {
        margin: 4px 0 0 0;
        font-size: 12px;
        color: #6b7280;
    }

    /* Main Card */
    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
    }

    .card-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
    }

    .card-body {
        padding: 20px;
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-primary {
        background: #667eea;
        color: white;
    }

    .btn-primary:hover {
        background: #5568d3;
        color: white;
    }

    .btn-warning {
        background: #f59e0b;
        color: white;
    }

    .btn-warning:hover {
        background: #d97706;
        color: white;
    }

    .btn-secondary {
        background: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background: #4b5563;
        color: white;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
    }

    /* Filters */
    .filters {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 0.5fr 0.5fr;
        gap: 15px;
        margin-bottom: 20px;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        font-size: 12px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-select {
        width: 100%;
        padding: 12px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        background: white;
    }

    .filter-select:focus {
        border-color: #667eea;
    }

    /* Table */
    .table-responsive {
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    thead {
        background: #f3f4f6;
    }

    thead th {
        padding: 14px 16px;
        text-align: left;
        font-weight: 600;
        color: #374151;
        border-bottom: 2px solid #e5e7eb;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: background 0.2s;
    }

    tbody tr:hover {
        background: #fafafa;
    }

    tbody td {
        padding: 14px 16px;
        color: #374151;
    }

    .td-right {
        text-align: right;
        font-weight: 600;
    }

    /* Badge */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-warning {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge .dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
    }

    .badge-success .dot { background: #10b981; }
    .badge-warning .dot { background: #f59e0b; }
    .badge-danger  .dot { background: #ef4444; }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 50px;
        color: #d1d5db;
        margin-bottom: 16px;
    }

    .empty-state h4 {
        color: #6b7280;
        font-size: 18px;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: #9ca3af;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .filters {
            grid-template-columns: 1fr;
        }

        .header-section {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .table-responsive {
            overflow-x: auto;
        }
    }
</style>

@php
$namaBulan = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];

$periodeTampil = $namaBulan[(int)$bulan] . ' ' . $tahun;
@endphp

<div class="container-custom">

    <!-- Header -->
    <div class="header-section">
        <div class="header-title">
            <h2>📊 Laporan Tagihan Pelanggan</h2>
            <p>Monitoring pembayaran dan pemasukan dari tagihan WiFi</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="stat-icon"><i class="fas fa-file-invoice"></i></div>
            <div class="stat-info">
                <h6>Total Tagihan</h6>
                <h3>Rp {{ number_format($totalTagihan, 0, ',', '.') }}</h3>
                <p>{{ $jumlahPelanggan }} Pelanggan</p>
            </div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info">
                <h6>Sudah Lunas</h6>
                <h3>Rp {{ number_format($totalLunas, 0, ',', '.') }}</h3>
                <p>{{ $jumlahLunas }} Pembayaran</p>
            </div>
        </div>
        <div class="stat-card red">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div class="stat-info">
                <h6>Belum Lunas</h6>
                <h3>Rp {{ number_format($totalBelumLunas, 0, ',', '.') }}</h3>
                <p>{{ $jumlahBelumLunas }} Tagihan</p>
            </div>
        </div>
        <div class="stat-card yellow">
            <div class="stat-icon"><i class="fas fa-percentage"></i></div>
            <div class="stat-info">
                <h6>Persentase Lunas</h6>
                <h3>{{ $totalTagihan > 0 ? number_format(($totalLunas / $totalTagihan) * 100, 1) : 0 }}%</h3>
                <p>{{ $periodeTampil }}</p>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-filter"></i> Filter Laporan</h3>
        </div>
        
        <div class="card-body">
            <form method="GET" action="{{ route('admin.laporan.tagihan') }}">
                <div class="filters">
                    <div class="filter-group">
                        <label>Bulan</label>
                        <select name="bulan" class="filter-select">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                    {{ $namaBulan[$i] }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Tahun</label>
                        <select name="tahun" class="filter-select">
                            @for($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                   <div class="filter-group">
                        <label>Status</label>
                        <select name="status" class="filter-select">
                            <option value="">Semua Status</option>
                            <option value="lunas" {{ $status == 'lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="nunggak" {{ $status == 'nunggak' ? 'selected' : '' }}>Nunggak</option>
                            <option value="menunggu_konfirmasi" {{ $status == 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                    <div class="filter-group">
                        <label>&nbsp;</label>
                        <a href="{{ route('admin.laporan.tagihan') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3><i class="fas fa-table"></i> Data Tagihan - {{ $periodeTampil }}</h3>
            <a href="{{ route('admin.laporan.tagihan.cetak', ['bulan' => $bulan, 'tahun' => $tahun, 'status' => $status]) }}" 
               class="btn btn-warning btn-sm" target="_blank">
                <i class="fas fa-print"></i> Cetak Laporan
            </a>
        </div>

        <div class="card-body">
            @if ($tagihan->count() > 0)
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Pelanggan</th>
                                <th>Paket</th>
                                <th>Periode</th>
                                <th>Status</th>
                                <th style="text-align:right;">Jumlah</th>
                                <th>Tgl Bayar</th>
                                <th>Metode</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tagihan as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <strong>{{ $item->pelanggan->user->name ?? '-' }}</strong><br>
                                    <small style="color: #6b7280;">{{ $item->pelanggan->kode_pelanggan ?? '-' }}</small>
                                </td>
                                <td>{{ $item->pelanggan->paket->nama_paket ?? '-' }}</td>
                                <td>{{ $namaBulan[(int)$item->bulan] ?? '-' }} {{ $item->tahun }}</td>
                                <td>
                                    @if($item->status == 'lunas')
                                        <span class="badge badge-success">
                                            <span class="dot"></span> Lunas
                                        </span>
                                    @elseif($item->status == 'menunggu_konfirmasi')
                                        <span class="badge badge-warning">
                                            <span class="dot"></span> Menunggu
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            <span class="dot"></span> Belum Lunas
                                        </span>
                                    @endif
                                </td>
                                <td class="td-right" style="color: {{ $item->status == 'lunas' ? '#059669' : '#dc2626' }}">
                                    Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if($item->tanggal_bayar)
                                        {{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d M Y') }}
                                    @else
                                        <span style="color: #9ca3af;">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->metode_pembayaran)
                                        <span style="font-size: 12px; text-transform: capitalize;">
                                            {{ str_replace('_', ' ', $item->metode_pembayaran) }}
                                        </span>
                                    @else
                                        <span style="color: #9ca3af;">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Summary Footer -->
                <div style="margin-top: 20px; padding: 15px; background: #f9fafb; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
                    <div style="font-size: 13px; color: #6b7280;">
                        <i class="fas fa-info-circle"></i> Total {{ $tagihan->count() }} tagihan di periode ini
                    </div>
                    <div style="display: flex; gap: 20px; font-size: 14px; font-weight: 600;">
                        <span style="color: #3b82f6;">
                            <i class="fas fa-file-invoice"></i> Total: Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                        </span>
                        <span style="color: #059669;">
                            <i class="fas fa-check-circle"></i> Lunas: Rp {{ number_format($totalLunas, 0, ',', '.') }}
                        </span>
                        <span style="color: #dc2626;">
                            <i class="fas fa-clock"></i> Belum: Rp {{ number_format($totalBelumLunas, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-file-invoice"></i>
                    <h4>Belum Ada Data Tagihan</h4>
                    <p>Belum ada tagihan untuk periode {{ $periodeTampil }}</p>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection