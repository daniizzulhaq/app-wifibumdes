@extends('layouts.admin')

@section('title', 'Kelola Tagihan')
@section('page-title', 'Kelola Tagihan')

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

    .header-actions {
        display: flex;
        gap: 10px;
    }
    
    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-left: 4px solid;
    }
    
    .stat-card.purple { border-color: #8b5cf6; }
    .stat-card.blue { border-color: #3b82f6; }
    .stat-card.green { border-color: #10b981; }
    .stat-card.orange { border-color: #f59e0b; }
    .stat-card.red { border-color: #ef4444; }
    
    .stat-card h6 {
        color: #6b7280;
        font-size: 12px;
        font-weight: 600;
        margin: 0 0 8px 0;
        text-transform: uppercase;
    }
    
    .stat-card h3 {
        font-size: 28px;
        font-weight: 800;
        margin: 0;
        color: #111827;
    }

    .stat-card p {
        margin: 5px 0 0 0;
        font-size: 13px;
        color: #6b7280;
    }

    /* Finance Stats - Lebih menonjol */
    .finance-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .finance-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }

    .finance-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .finance-card.income::before {
        background: linear-gradient(90deg, #10b981, #059669);
    }

    .finance-card.pending::before {
        background: linear-gradient(90deg, #f59e0b, #d97706);
    }

    .finance-card.overdue::before {
        background: linear-gradient(90deg, #ef4444, #dc2626);
    }

    .finance-card .icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        font-size: 24px;
    }

    .finance-card.income .icon {
        background: #d1fae5;
        color: #059669;
    }

    .finance-card.pending .icon {
        background: #fed7aa;
        color: #d97706;
    }

    .finance-card.overdue .icon {
        background: #fee2e2;
        color: #dc2626;
    }

    .finance-card h6 {
        color: #6b7280;
        font-size: 13px;
        font-weight: 600;
        margin: 0 0 8px 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .finance-card h2 {
        font-size: 32px;
        font-weight: 800;
        margin: 0 0 5px 0;
        color: #111827;
    }

    .finance-card p {
        margin: 0;
        font-size: 13px;
        color: #6b7280;
    }

    .finance-card .detail {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        font-size: 13px;
    }

    .finance-card .detail span {
        color: #374151;
        font-weight: 600;
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
    
    .btn-info {
        background: #3b82f6;
        color: white;
    }

    .btn-info:hover {
        background: #2563eb;
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
        grid-template-columns: 1fr 1fr 1fr 1fr 0.5fr;
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
    }

    .search-box {
        display: flex;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        background: white;
    }
    
    .search-box:focus-within {
        border-color: #667eea;
    }
    
    .search-box i {
        padding: 12px;
        color: #9ca3af;
    }
    
    .search-box input {
        flex: 1;
        border: none;
        outline: none;
        padding: 12px 12px 12px 0;
        font-size: 14px;
    }
    
    .filter-select {
        width: 100%;
        padding: 12px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
    }
    
    .filter-select:focus {
        border-color: #667eea;
    }
    
    /* Table */
    .table-responsive {
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    thead {
        background: #f9fafb;
    }
    
    th {
        padding: 12px;
        text-align: left;
        font-weight: 700;
        font-size: 12px;
        color: #374151;
        text-transform: uppercase;
        border-bottom: 2px solid #e5e7eb;
    }
    
    td {
        padding: 12px;
        border-bottom: 1px solid #f3f4f6;
        font-size: 14px;
    }
    
    tbody tr:hover {
        background-color: #f9fafb;
    }

    .text-center {
        text-align: center;
    }
    
    /* Badge */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
    
    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }
    
    .badge-warning {
        background: #fed7aa;
        color: #92400e;
    }

    .badge-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-info {
        background: #dbeafe;
        color: #1e40af;
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

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }

    .modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-dialog {
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
    }

    .modal-header {
        padding: 20px;
        border-bottom: 2px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h5 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #111827;
    }

    .modal-header .close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #6b7280;
        padding: 0;
        width: 30px;
        height: 30px;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        padding: 20px;
        border-top: 2px solid #e5e7eb;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 5px;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
    }

    .form-control:focus {
        border-color: #667eea;
    }

    .text-danger {
        color: #ef4444;
    }

    .text-muted {
        color: #6b7280;
        font-size: 13px;
    }

    .info-box {
        background: #dbeafe;
        border-left: 4px solid #3b82f6;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .info-box p {
        margin: 0;
        color: #1e40af;
        font-size: 13px;
    }
    
    @media (max-width: 768px) {
        .filters {
            grid-template-columns: 1fr;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .finance-stats {
            grid-template-columns: 1fr;
        }
        
        .header-section {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .header-actions {
            width: 100%;
            flex-direction: column;
        }

        .header-actions .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

@php
$namaBulan = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];

// Hitung statistik keuangan per bulan
$bulanFilter = request('bulan') ?: date('n');
$tahunFilter = request('tahun') ?: date('Y');

// Total uang yang sudah masuk (lunas) per bulan
$uangMasuk = \App\Models\Tagihan::where('status', 'lunas')
    ->where('bulan', $bulanFilter)
    ->where('tahun', $tahunFilter)
    ->sum('jumlah');

$jumlahLunas = \App\Models\Tagihan::where('status', 'lunas')
    ->where('bulan', $bulanFilter)
    ->where('tahun', $tahunFilter)
    ->count();

// Total uang belum bayar per bulan
$uangBelumBayar = \App\Models\Tagihan::where('status', 'belum_bayar')
    ->where('bulan', $bulanFilter)
    ->where('tahun', $tahunFilter)
    ->sum('jumlah');

$jumlahBelumBayar = \App\Models\Tagihan::where('status', 'belum_bayar')
    ->where('bulan', $bulanFilter)
    ->where('tahun', $tahunFilter)
    ->count();

// Total uang nunggak per bulan
$uangNunggak = \App\Models\Tagihan::where('status', 'nunggak')
    ->where('bulan', $bulanFilter)
    ->where('tahun', $tahunFilter)
    ->sum('jumlah');

$jumlahNunggak = \App\Models\Tagihan::where('status', 'nunggak')
    ->where('bulan', $bulanFilter)
    ->where('tahun', $tahunFilter)
    ->count();

// Periode yang ditampilkan
$periodeTampil = $namaBulan[(int)$bulanFilter] . ' ' . $tahunFilter;
@endphp

<div class="container-custom">
    <!-- Header -->
    <div class="header-section">
        <div class="header-title">
            <h2>💰 Kelola Tagihan</h2>
            <p>Manajemen tagihan pelanggan</p>
        </div>
        <div class="header-actions">
            <button type="button" class="btn btn-primary" onclick="openModal('generateModal')">
                <i class="fas fa-plus"></i>
                Generate Tagihan
            </button>
            <a href="{{ route('admin.tagihan.nunggak') }}" class="btn btn-warning">
                <i class="fas fa-exclamation-triangle"></i>
                Tagihan Nunggak
            </a>
        </div>
    </div>

    <!-- Finance Stats - Statistik Keuangan Per Bulan -->
    <div class="finance-stats">
        <div class="finance-card income">
            <div class="icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <h6>💵 Uang Masuk ({{ $periodeTampil }})</h6>
            <h2>Rp {{ number_format($uangMasuk, 0, ',', '.') }}</h2>
            <p>Dari {{ $jumlahLunas }} tagihan yang sudah lunas</p>
            <div class="detail">
                <span>Status:</span>
                <span style="color: #059669;">✓ Sudah Terbayar</span>
            </div>
        </div>

        <div class="finance-card pending">
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
            <h6>⏳ Belum Bayar ({{ $periodeTampil }})</h6>
            <h2>Rp {{ number_format($uangBelumBayar, 0, ',', '.') }}</h2>
            <p>Dari {{ $jumlahBelumBayar }} tagihan yang belum dibayar</p>
            <div class="detail">
                <span>Status:</span>
                <span style="color: #d97706;">⚠ Menunggu Pembayaran</span>
            </div>
        </div>

        <div class="finance-card overdue">
            <div class="icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <h6>⛔ Uang Nunggak ({{ $periodeTampil }})</h6>
            <h2>Rp {{ number_format($uangNunggak, 0, ',', '.') }}</h2>
            <p>Dari {{ $jumlahNunggak }} tagihan yang nunggak</p>
            <div class="detail">
                <span>Status:</span>
                <span style="color: #dc2626;">✗ Sudah Jatuh Tempo</span>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card purple">
            <h6>Total Tagihan</h6>
            <h3>{{ $tagihans->total() ?? 0 }}</h3>
        </div>
        <div class="stat-card green">
            <h6>Lunas</h6>
            <h3>{{ \App\Models\Tagihan::where('status', 'lunas')->count() }}</h3>
        </div>
        <div class="stat-card orange">
            <h6>Belum Bayar</h6>
            <h3>{{ \App\Models\Tagihan::where('status', 'belum_bayar')->count() }}</h3>
        </div>
        <div class="stat-card red">
            <h6>Nunggak</h6>
            <h3>{{ \App\Models\Tagihan::where('status', 'nunggak')->count() }}</h3>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card">
        <div class="card-header">
            <h3>Filter & Pencarian</h3>
        </div>
        
        <div class="card-body">
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

            <!-- Filters -->
            <form method="GET" action="{{ route('admin.tagihan.index') }}">
                <div class="filters">
                    <div class="filter-group">
                        <label>Status</label>
                        <select name="status" class="filter-select">
                            <option value="">Semua Status</option>
                            <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                            <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="nunggak" {{ request('status') == 'nunggak' ? 'selected' : '' }}>Nunggak</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Bulan</label>
                        <select name="bulan" class="filter-select">
                            <option value="">Semua Bulan</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                    {{ $namaBulan[$i] }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Tahun</label>
                        <select name="tahun" class="filter-select">
                            <option value="">Semua Tahun</option>
                            @for($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Cari Pelanggan</label>
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" placeholder="Nama pelanggan..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="filter-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </div>
                @if(request()->hasAny(['status', 'bulan', 'tahun', 'search']))
                    <a href="{{ route('admin.tagihan.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-redo"></i> Reset Filter
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card">
        <div class="card-header">
            <h3>Daftar Tagihan</h3>
        </div>
        
        <div class="card-body">
            <!-- Table -->
            <div class="table-responsive">
                <table>
                 <!-- Di bagian thead, tambahkan kolom baru -->
<thead>
    <tr>
        <th style="width: 50px;">#</th>
        <th>Pelanggan</th>
        <th>Paket</th>
        <th>Periode</th>
        <th>Jumlah</th>
        <th style="width: 150px;">Status</th>
        <th>Jatuh Tempo</th>
        <th>Tgl Bayar</th>
        <th>Dikonfirmasi Oleh</th> <!-- Kolom baru -->
        <th style="width: 100px;" class="text-center">Aksi</th>
    </tr>
</thead>

<!-- Di bagian tbody, tambahkan data -->
<tbody>
    @forelse($tagihans as $index => $tagihan)
        @php
            $bulanInt = (int)$tagihan->bulan;
            $tahunInt = (int)$tagihan->tahun;
            $namaBulanText = isset($namaBulan[$bulanInt]) ? $namaBulan[$bulanInt] : 'N/A';
        @endphp
        <tr>
            <td>{{ $tagihans->firstItem() + $index }}</td>
            <td>
                <strong>{{ $tagihan->pelanggan->user->name ?? 'N/A' }}</strong><br>
                <small class="text-muted">{{ Str::limit($tagihan->pelanggan->alamat ?? '-', 30) }}</small>
            </td>
            <td>
                @if($tagihan->pelanggan && $tagihan->pelanggan->paket)
                    {{ $tagihan->pelanggan->paket->nama }}
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
            <td>{{ $namaBulanText }} {{ $tahunInt }}</td>
            <td><strong>Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</strong></td>
            <td>
                @if($tagihan->status == 'lunas')
                    <span class="badge badge-success"><i class="fas fa-check-circle"></i> Lunas</span>
                @elseif($tagihan->status == 'menunggu_konfirmasi')
                    <span class="badge badge-info"><i class="fas fa-clock"></i> Menunggu Konfirmasi</span>
                @elseif($tagihan->status == 'nunggak')
                    <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Nunggak</span>
                @else
                    <span class="badge badge-warning"><i class="fas fa-clock"></i> Belum Bayar</span>
                @endif
            </td>
            <td>
                @if($tagihan->tanggal_jatuh_tempo)
                    {{ \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->format('d/m/Y') }}
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
            <td>
                @if($tagihan->tanggal_bayar)
                    {{ \Carbon\Carbon::parse($tagihan->tanggal_bayar)->format('d/m/Y') }}
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
            <!-- Kolom Dikonfirmasi Oleh -->
            <td>
                @if($tagihan->konfirmator)
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="width: 30px; height: 30px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 12px;">
                            {{ strtoupper(substr($tagihan->konfirmator->name, 0, 1)) }}
                        </div>
                        <div>
                            <strong style="font-size: 13px;">{{ $tagihan->konfirmator->name }}</strong><br>
                            <small class="text-muted" style="font-size: 11px;">
                                <i class="fas fa-user-shield"></i> 
                                {{ ucfirst($tagihan->konfirmator->role) }}
                            </small>
                        </div>
                    </div>
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
            <td class="text-center">
                @if($tagihan->status == 'menunggu_konfirmasi')
                    <a href="{{ route('admin.tagihan.show', $tagihan->id) }}" class="btn btn-sm btn-warning" title="Lihat Bukti Transfer">
                        <i class="fas fa-image"></i> Bukti
                    </a>
                @else
                    <a href="{{ route('admin.tagihan.show', $tagihan->id) }}" class="btn btn-sm btn-info" title="Detail">
                        <i class="fas fa-eye"></i> Detail
                    </a>
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="10" style="text-align: center; padding: 40px;">
                <i class="fas fa-receipt" style="font-size: 48px; color: #e5e7eb;"></i>
                <p style="margin: 10px 0 0 0; color: #6b7280;">Belum ada data tagihan</p>
            </td>
        </tr>
    @endforelse
</tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($tagihans->total() > 0)
                <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center;">
                    <p style="color: #6b7280; margin: 0;">
                        Menampilkan {{ $tagihans->firstItem() }} - {{ $tagihans->lastItem() }} dari {{ $tagihans->total() }}
                    </p>
                    {{ $tagihans->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Generate Tagihan Bulanan -->
<div class="modal" id="generateModal">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.tagihan.generate') }}">
            @csrf
            <div class="modal-header">
                <h5>Generate Tagihan Bulanan</h5>
                <button type="button" class="close" onclick="closeModal('generateModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="info-box">
                    <p><i class="fas fa-info-circle"></i> Sistem akan membuat tagihan untuk semua pelanggan aktif pada periode yang dipilih.</p>
                </div>
                
                <div class="form-group">
                    <label>Bulan <span class="text-danger">*</span></label>
                    <select name="bulan" class="form-control" id="bulanSelect" required>
                        <option value="">Pilih Bulan</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ (int)date('n') == $i ? 'selected' : '' }}>
                                {{ $namaBulan[$i] }}
                            </option>
                        @endfor
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Tahun <span class="text-danger">*</span></label>
                    <select name="tahun" class="form-control" id="tahunSelect" required>
                        @for($y = date('Y'); $y <= date('Y') + 1; $y++)
                            <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Jatuh Tempo <span class="text-danger">*</span></label>
                    <select name="tanggal" class="form-control" required>
                        <option value="">Pilih Tanggal</option>
                        @for($d = 1; $d <= 31; $d++)
                            <option value="{{ $d }}" {{ $d == 10 ? 'selected' : '' }}>
                                Tanggal {{ $d }}
                            </option>
                        @endfor
                    </select>
                    <small class="text-muted">Tanggal berapa tagihan ini harus dibayar setiap bulannya</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('generateModal')">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-cog"></i> Generate Tagihan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Pembayaran -->
<div class="modal" id="konfirmasiModal">
    <div class="modal-dialog">
        <form method="POST" id="konfirmasiForm">
            @csrf
            <div class="modal-header">
                <h5>Konfirmasi Pembayaran</h5>
                <button type="button" class="close" onclick="closeModal('konfirmasiModal')">&times;</button>
            </div>
            <div class="modal-body">
                <p><strong>Pelanggan:</strong> <span id="konfNama"></span></p>
                <p><strong>Periode:</strong> <span id="konfPeriode"></span></p>
                <p><strong>Jumlah:</strong> Rp <span id="konfJumlah"></span></p>
                
                <hr style="margin: 15px 0; border: 0; border-top: 1px solid #e5e7eb;">
                
                <div class="form-group">
                    <label>Metode Pembayaran <span class="text-danger">*</span></label>
                    <select name="metode_bayar" class="form-control" required>
                        <option value="">Pilih Metode</option>
                        <option value="tunai">Tunai</option>
                        <option value="transfer">Transfer Bank</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan tambahan (opsional)"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('konfirmasiModal')">Batal</button>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i> Konfirmasi Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Modal Functions
function openModal(modalId) {
    document.getElementById(modalId).classList.add('show');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('show');
}

function openKonfirmasiModal(id, nama, periode, jumlah) {
    document.getElementById('konfNama').textContent = nama;
    document.getElementById('konfPeriode').textContent = periode;
    document.getElementById('konfJumlah').textContent = new Intl.NumberFormat('id-ID').format(jumlah);
    document.getElementById('konfirmasiForm').action = `/admin/tagihan/${id}/konfirmasi`;
    openModal('konfirmasiModal');
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.classList.remove('show');
    }
}

// Auto hide alerts
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.style.display = 'none';
        });
    }, 5000);
});
</script>
@endsection