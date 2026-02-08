@extends('layouts.admin')

@section('title', 'Laporan Buku Kas')
@section('page-title', 'Laporan Buku Kas')

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

    /* Card */
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
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
    }

    .card-body {
        padding: 24px;
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

    .btn-secondary {
        background: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background: #4b5563;
        color: white;
    }

    .btn-success {
        background: #10b981;
        color: white;
    }

    .btn-success:hover {
        background: #059669;
        color: white;
    }

    .btn-info {
        background: #3b82f6;
        color: white;
    }

    .btn-info:hover {
        background: #2563eb;
        color: white;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
    }

    /* Filter Bar */
    .filter-bar {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        background: #f9fafb;
        padding: 16px 20px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        margin-bottom: 24px;
    }

    .filter-bar label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-bar select {
        padding: 9px 36px 9px 14px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        color: #111827;
        background: white;
        outline: none;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        cursor: pointer;
        transition: border-color 0.2s;
    }

    .filter-bar select:focus {
        border-color: #667eea;
    }

    .filter-bar .btn {
        margin-left: auto;
    }

    .filter-actions {
        display: flex;
        gap: 8px;
        margin-left: auto;
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

    tbody tr:last-child {
        border-bottom: none;
    }

    tbody td {
        padding: 14px 16px;
        color: #374151;
    }

    /* Tfoot Totals Row */
    tfoot tr {
        background: #f3f4f6;
    }

    tfoot td {
        padding: 14px 16px;
        font-weight: 700;
        color: #111827;
        border-top: 2px solid #e5e7eb;
        font-size: 14px;
    }

    .td-right {
        text-align: right;
        font-weight: 600;
    }

    /* Kategori Tag */
    .kategori-tag {
        display: inline-block;
        background: #ede9fe;
        color: #6d28d9;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    /* Progress Bar */
    .progress-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .progress-bar-bg {
        flex: 1;
        height: 8px;
        background: #f3f4f6;
        border-radius: 4px;
        overflow: hidden;
        min-width: 60px;
    }

    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #667eea, #764ba2);
        border-radius: 4px;
        transition: width 0.5s ease;
    }

    .progress-percent {
        font-size: 12px;
        color: #6b7280;
        font-weight: 600;
        min-width: 38px;
        text-align: right;
    }

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

    /* Print styles */
    @media print {
        .no-print { display: none !important; }
        .card {
            box-shadow: none;
            border: 1px solid #e5e7eb;
        }
        .card-header {
            background: #333 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }

    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: 1fr; }
        .header-section {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        .filter-bar { flex-direction: column; align-items: flex-start; }
        .filter-actions { margin-left: 0; }
        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
    }
</style>

<div class="container-custom">

    <!-- Header -->
    <div class="header-section">
        <div class="header-title">
            <h2>📊 Laporan Buku Kas</h2>
            <p>Rincian pengeluaran per kategori berdasarkan bulan</p>
        </div>
        <a href="{{ route('admin.buku_kas.index') }}" class="btn btn-secondary btn-sm no-print">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Stats -->
    <div class="stats-grid no-print">
        <div class="stat-card green">
            <div class="stat-icon"><i class="fas fa-arrow-down"></i></div>
            <div class="stat-info">
                <h6>Total Pemasukan</h6>
                <h3>Rp {{ number_format($totalMasuk) }}</h3>
            </div>
        </div>
        <div class="stat-card red">
            <div class="stat-icon"><i class="fas fa-arrow-up"></i></div>
            <div class="stat-info">
                <h6>Total Pengeluaran</h6>
                <h3>Rp {{ number_format($totalKeluar) }}</h3>
            </div>
        </div>
        <div class="stat-card blue">
            <div class="stat-icon"><i class="fas fa-wallet"></i></div>
            <div class="stat-info">
                <h6>Saldo</h6>
                <h3>Rp {{ number_format($totalMasuk - $totalKeluar) }}</h3>
            </div>
        </div>
    </div>

    <!-- Filter + Aksi -->
    <div class="card no-print">
        <div class="card-body" style="padding: 0;">
            <div class="filter-bar">
                <label><i class="fas fa-calendar-alt"></i> Bulan</label>
                <form method="GET" action="{{ route('admin.buku_kas.laporan') }}" style="display:flex; gap:10px; align-items:center; flex:1; flex-wrap:wrap;">
                    <select name="bulan">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                    {{ $bulan == $i ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                            </option>
                        @endfor
                    </select>

                    <select name="tahun">
                        @for ($y = date('Y'); $y >= date('Y') - 3; $y--)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>

                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-search"></i> Tampilkan
                    </button>

                    <div class="filter-actions">
                        <button type="button" class="btn btn-success btn-sm" onclick="window.print()">
                            <i class="fas fa-print"></i> Cetak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Laporan Table -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-chart-bar"></i> &nbsp;Rincian Pengeluaran — {{ date('F', mktime(0,0,0,$bulan,1)) }} {{ $tahun }}</h3>
        </div>

        <div class="card-body">
            @if ($rincian->count() > 0)
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kategori</th>
                                <th style="text-align:right;">Nominal</th>
                                <th>Porsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rincian as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <span class="kategori-tag">{{ str_replace('_', ' ', ucfirst($item->kategori)) }}</span>
                                </td>
                                <td class="td-right">Rp {{ number_format($item->total) }}</td>
                                <td>
                                    @php
                                        $persen = $totalKeluar > 0
                                            ? ($item->total / $totalKeluar) * 100
                                            : 0;
                                    @endphp
                                    <div class="progress-wrap">
                                        <div class="progress-bar-bg">
                                            <div class="progress-bar-fill" style="width: {{ $persen }}%"></div>
                                        </div>
                                        <span class="progress-percent">{{ round($persen, 1) }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" style="text-align:right;">Total Pengeluaran</td>
                                <td class="td-right" style="color:#dc2626;">Rp {{ number_format($totalKeluar) }}</td>
                                <td>100%</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-chart-bar"></i>
                    <h4>Tidak Ada Data</h4>
                    <p>Tidak ada pengeluaran pada bulan {{ date('F', mktime(0,0,0,$bulan,1)) }} {{ $tahun }}</p>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection