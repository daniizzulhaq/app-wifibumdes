@extends('layouts.admin')

@section('title', 'Buku Kas')
@section('page-title', 'Buku Kas')

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

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
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

    /* Alert */
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border-left: 4px solid #10b981;
    }

    .alert-danger {
        background: #fee2e2;
        color: #991b1b;
        border-left: 4px solid #ef4444;
    }

    .alert-info {
        background: #dbeafe;
        color: #1e40af;
        border-left: 4px solid #3b82f6;
    }

    .alert button {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: inherit;
        opacity: 0.7;
    }

    .alert button:hover {
        opacity: 1;
    }

    /* Filters */
    .filters {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr 0.5fr 0.5fr;
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

    thead th:last-child {
        text-align: center;
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

    .td-center {
        text-align: center;
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
    .badge-danger  .dot { background: #ef4444; }

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

    /* Nominal color */
    .nominal-masuk  { color: #059669; font-weight: 700; }
    .nominal-keluar { color: #dc2626; font-weight: 700; }

    /* Action buttons */
    .action-buttons {
        display: flex;
        gap: 5px;
        justify-content: center;
    }

    /* Disabled button style */
    .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
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

$bulanFilter = request('bulan') ?: date('n');
$tahunFilter = request('tahun') ?: date('Y');
$periodeTampil = $namaBulan[(int)$bulanFilter] . ' ' . $tahunFilter;
@endphp

<div class="container-custom">

    <!-- Header -->
    <div class="header-section">
        <div class="header-title">
            <h2>💰 Buku Kas</h2>
            <p>Kelola pemasukan dan pengeluaran keuangan</p>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success">
            <span><i class="fas fa-check-circle"></i> {{ session('success') }}</span>
            <button onclick="this.parentElement.style.display='none'">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <span><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</span>
            <button onclick="this.parentElement.style.display='none'">&times;</button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info">
            <span><i class="fas fa-info-circle"></i> {{ session('info') }}</span>
            <button onclick="this.parentElement.style.display='none'">&times;</button>
        </div>
    @endif

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card green">
            <div class="stat-icon"><i class="fas fa-arrow-down"></i></div>
            <div class="stat-info">
                <h6>Total Pemasukan</h6>
                <h3>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                <p>{{ $periodeTampil }}</p>
            </div>
        </div>
        <div class="stat-card red">
            <div class="stat-icon"><i class="fas fa-arrow-up"></i></div>
            <div class="stat-info">
                <h6>Total Pengeluaran</h6>
                <h3>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                <p>{{ $periodeTampil }}</p>
            </div>
        </div>
        <div class="stat-card blue">
            <div class="stat-icon"><i class="fas fa-wallet"></i></div>
            <div class="stat-info">
                <h6>Saldo</h6>
                <h3 style="color: {{ $saldo >= 0 ? '#059669' : '#dc2626' }}">
                    Rp {{ number_format($saldo, 0, ',', '.') }}
                </h3>
                <p>{{ $periodeTampil }}</p>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-filter"></i> Filter & Pencarian</h3>
        </div>
        
        <div class="card-body">
            <form method="GET" action="{{ route('admin.buku_kas.index') }}">
                <div class="filters">
                    <div class="filter-group">
                        <label>Bulan</label>
                        <select name="bulan" class="filter-select">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('bulan', date('n')) == $i ? 'selected' : '' }}>
                                    {{ $namaBulan[$i] }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Tahun</label>
                        <select name="tahun" class="filter-select">
                            @for($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ request('tahun', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Jenis</label>
                        <select name="jenis" class="filter-select">
                            <option value="">Semua Jenis</option>
                            <option value="pemasukan" {{ request('jenis') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                            <option value="pengeluaran" {{ request('jenis') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Kategori</label>
                        <select name="kategori" class="filter-select">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoriList as $key => $label)
                                <option value="{{ $key }}" {{ request('kategori') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
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
                        <a href="{{ route('admin.buku_kas.index') }}" class="btn btn-secondary">
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
            <h3><i class="fas fa-book"></i> &nbsp;Data Transaksi - {{ $periodeTampil }}</h3>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('admin.buku_kas.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Baru
                </a>
                <a href="{{ route('admin.buku_kas.cetak', array_filter(['bulan' => $bulanFilter, 'tahun' => $tahunFilter, 'jenis' => request('jenis'), 'kategori' => request('kategori')])) }}" 
                   class="btn btn-warning btn-sm" target="_blank">
                    <i class="fas fa-print"></i> Cetak Laporan
                </a>
            </div>
        </div>

        <div class="card-body">
            @if ($bukuKas->count() > 0)
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Kategori</th>
                                <th style="text-align:right;">Nominal</th>
                                <th>Keterangan</th>
                                <th style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bukuKas as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                <td>
                                    @if ($item->jenis == 'pemasukan')
                                        <span class="badge badge-success"><span class="dot"></span> Pemasukan</span>
                                    @else
                                        <span class="badge badge-danger"><span class="dot"></span> Pengeluaran</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="kategori-tag">{{ str_replace('_', ' ', ucfirst($item->kategori)) }}</span>
                                </td>
                                <td class="td-right">
                                    <span class="nominal-{{ $item->jenis == 'pemasukan' ? 'masuk' : 'keluar' }}">
                                        @if ($item->jenis == 'pengeluaran') - @endif
                                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>{{ Str::limit($item->keterangan ?? '-', 50) }}</td>
                                <td class="td-center">
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.buku_kas.edit', $item->id) }}" 
                                           class="btn btn-info btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.buku_kas.destroy', $item->id) }}"
                                              onsubmit="return confirm('Yakin ingin hapus transaksi ini?')"
                                              style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Summary Footer --}}
                <div style="margin-top: 20px; padding: 15px; background: #f9fafb; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
                    <div style="font-size: 13px; color: #6b7280;">
                        <i class="fas fa-info-circle"></i> Total {{ $bukuKas->count() }} transaksi di periode ini
                    </div>
                    <div style="display: flex; gap: 20px; font-size: 14px; font-weight: 600;">
                        <span style="color: #059669;">
                            <i class="fas fa-arrow-down"></i> Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                        </span>
                        <span style="color: #dc2626;">
                            <i class="fas fa-arrow-up"></i> Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                        </span>
                        <span style="color: {{ $saldo >= 0 ? '#3b82f6' : '#ef4444' }}">
                            <i class="fas fa-wallet"></i> Rp {{ number_format($saldo, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-book-open"></i>
                    <h4>Belum Ada Transaksi</h4>
                    <p>Belum ada transaksi untuk periode {{ $periodeTampil }}</p>
                    <a href="{{ route('admin.buku_kas.create') }}" class="btn btn-primary" style="margin-top: 15px;">
                        <i class="fas fa-plus"></i> Tambah Transaksi Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>

</div>

<!-- Auto hide alerts -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.display = 'none';
            });
        }, 5000);
    });
</script>
@endsection