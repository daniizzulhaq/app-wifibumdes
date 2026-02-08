@extends('layouts.petugas')

@section('title', 'Kelola Tagihan')
@section('page-title', 'Kelola Tagihan')

@section('content')

<!-- Load FontAwesome if not already loaded -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- CRITICAL FIX: Inline styles to override everything -->
<style>
    /* Force remove any blocking overlays */
    body::before,
    body::after,
    .sidebar-backdrop,
    .modal-backdrop:not(.show .modal-backdrop),
    .backdrop,
    .overlay,
    [class*="backdrop"]:not(.modal.show *),
    [class*="overlay"]:not(.modal.show *) {
        display: none !important;
        pointer-events: none !important;
        z-index: -9999 !important;
    }

    /* Force all elements in this page to be clickable */
    #tagihan-page,
    #tagihan-page * {
        pointer-events: auto !important;
    }

    /* Force interactive elements */
    #tagihan-page button,
    #tagihan-page a,
    #tagihan-page input,
    #tagihan-page select,
    #tagihan-page textarea,
    #tagihan-page .btn {
        pointer-events: auto !important;
        cursor: pointer !important;
        position: relative;
        z-index: 10;
    }

    #tagihan-page input[type="text"],
    #tagihan-page input[type="search"],
    #tagihan-page textarea {
        cursor: text !important;
    }

    /* CRITICAL FIX: Force modal to be on top of EVERYTHING */
    .modal {
        z-index: 2147483647 !important;
        pointer-events: auto !important;
    }
    
    .modal-dialog {
        z-index: 2147483647 !important;
        pointer-events: auto !important;
    }
    
    .modal-dialog * {
        pointer-events: auto !important;
    }
    
    body.modal-open {
        overflow: hidden;
    }
    
    .modal button,
    .modal select,
    .modal input,
    .modal textarea,
    .modal a {
        pointer-events: auto !important;
        cursor: pointer !important;
        position: relative !important;
        z-index: 1 !important;
    }
    
    .container-custom {
        position: relative;
        z-index: 1;
        pointer-events: auto !important;
    }
    
    .container-custom * {
        pointer-events: auto !important;
    }
    
    button,
    a,
    input,
    select,
    textarea {
        pointer-events: auto !important;
        cursor: pointer !important;
    }
    
    input[type="text"],
    input[type="search"],
    textarea {
        cursor: text !important;
    }
    
    table button,
    table a {
        pointer-events: auto !important;
        cursor: pointer !important;
        position: relative;
        z-index: 10;
    }
    
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
        justify-content: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        border: none;
        cursor: pointer !important;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
        pointer-events: auto !important;
        position: relative;
        z-index: 100 !important;
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
        z-index: 100 !important;
    }
    
    /* Pastikan icon FontAwesome terlihat */
    .btn i,
    .btn .fas,
    .btn .fa {
        display: inline-block !important;
        font-size: 14px;
        margin: 0;
        line-height: 1;
        position: relative;
        z-index: 101 !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
    
    .btn-sm i,
    .btn-sm .fas,
    .btn-sm .fa {
        font-size: 12px !important;
        display: inline-block !important;
        visibility: visible !important;
        opacity: 1 !important;
        z-index: 101 !important;
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
    
    /* Action buttons spacing and z-index */
    td.text-center .btn {
        margin: 0 2px;
        position: relative !important;
        z-index: 100 !important;
    }
    
    /* Force table action buttons and icons to be visible */
    table .btn,
    table .btn-sm {
        z-index: 100 !important;
        position: relative !important;
    }
    
    table .btn i,
    table .btn .fas,
    table .btn .fa {
        z-index: 101 !important;
        display: inline-block !important;
        visibility: visible !important;
        opacity: 1 !important;
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
        position: fixed !important;
        z-index: 2147483647 !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        height: 100% !important;
        background-color: rgba(0,0,0,0.5) !important;
        overflow-y: auto !important;
        pointer-events: auto !important;
    }

    .modal.show {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .modal-dialog {
        background: white !important;
        border-radius: 12px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
        position: relative !important;
        z-index: 2147483647 !important;
        margin: 20px auto;
        pointer-events: auto !important;
    }

    .modal-header {
        padding: 20px;
        border-bottom: 2px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        pointer-events: auto !important;
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
        cursor: pointer !important;
        color: #6b7280;
        padding: 0;
        width: 30px;
        height: 30px;
        pointer-events: auto !important;
        z-index: 1;
    }

    .modal-header .close:hover {
        color: #111827;
    }

    .modal-body {
        padding: 20px;
        pointer-events: auto !important;
    }

    .modal-footer {
        padding: 20px;
        border-top: 2px solid #e5e7eb;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        pointer-events: auto !important;
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
        background-color: white;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        position: relative;
        z-index: 1;
        pointer-events: auto;
    }

    .form-control:focus {
        border-color: #667eea;
        z-index: 2;
    }

    /* Style untuk select dropdown */
    select.form-control {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M10.293 3.293L6 7.586 1.707 3.293A1 1 0 00.293 4.707l5 5a1 1 0 001.414 0l5-5a1 1 0 10-1.414-1.414z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 40px;
        cursor: pointer;
    }

    /* Pastikan select bisa diklik */
    select.form-control::-ms-expand {
        display: none;
    }
    
    select.form-control option {
        padding: 10px;
        background-color: white;
        color: #111827;
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

    /* Bukti Transfer Section di Modal */
    .bukti-transfer-section {
        background: #f3f4f6;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        border: 2px solid #e5e7eb;
    }

    .bukti-transfer-section h6 {
        font-weight: 700;
        color: #111827;
        margin: 0 0 10px 0;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .bukti-image-preview {
        max-width: 100%;
        max-height: 300px;
        border-radius: 8px;
        cursor: pointer;
        border: 2px solid #d1d5db;
        display: block;
        margin: 0 auto;
        transition: all 0.3s;
    }

    .bukti-image-preview:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .image-preview-container {
        text-align: center;
        background: white;
        padding: 10px;
        border-radius: 8px;
    }

    /* Image Modal untuk zoom */
    #imageModal {
        display: none;
        position: fixed;
        z-index: 2147483648 !important;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.9);
        cursor: zoom-out;
    }

    #imageModal.show {
        display: flex !important;
        align-items: center;
        justify-content: center;
    }

    #imageModal img {
        max-width: 90%;
        max-height: 90vh;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.3);
    }
    
    @media (max-width: 768px) {
        .filters {
            grid-template-columns: 1fr;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
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

        .modal-dialog {
            max-width: 95%;
        }
    }
</style>

@php
$namaBulan = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];
@endphp

<!-- WRAPPER for targeted fixing -->
<div id="tagihan-page">
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
            <a href="{{ route('petugas.tagihan.menunggu-konfirmasi') }}" class="btn btn-info">
                <i class="fas fa-clock"></i>
                Menunggu Konfirmasi
            </a>
            <a href="{{ route('petugas.tagihan.nunggak') }}" class="btn btn-warning">
                <i class="fas fa-exclamation-triangle"></i>
                Tagihan Nunggak
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card purple">
            <h6>Total Tagihan</h6>
            <h3>{{ $tagihans->total() ?? 0 }}</h3>
        </div>
        <div class="stat-card blue">
            <h6>Menunggu Konfirmasi</h6>
            <h3>{{ \App\Models\Tagihan::where('status', 'menunggu_konfirmasi')->count() }}</h3>
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
            <form method="GET" action="{{ route('petugas.tagihan.index') }}">
                <div class="filters">
                    <div class="filter-group">
                        <label>Status</label>
                        <select name="status" class="filter-select">
                            <option value="">Semua Status</option>
                            <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                            <option value="menunggu_konfirmasi" {{ request('status') == 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
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
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                </div>
                @if(request()->hasAny(['status', 'bulan', 'tahun', 'search']))
                    <a href="{{ route('petugas.tagihan.index') }}" class="btn btn-secondary btn-sm">
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
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Pelanggan</th>
                            <th>Paket</th>
                            <th>Periode</th>
                            <th>Jumlah</th>
                            <th style="width: 120px;">Status</th>
                            <th>Jatuh Tempo</th>
                            <th>Tgl Bayar</th>
                            <th style="width: 150px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
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
                                        <span class="badge badge-warning"><i class="fas fa-hourglass-half"></i> Belum Bayar</span>
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
                                <td class="text-center">
                                    @if($tagihan->status == 'menunggu_konfirmasi')
                                        <!-- Button Konfirmasi untuk status menunggu konfirmasi - link ke show -->
                                        <a href="{{ route('petugas.tagihan.show', $tagihan->id) }}" 
                                           class="btn btn-sm btn-success" 
                                           title="Konfirmasi Pembayaran">
                                            <i class="fas fa-check"></i> Konfirmasi
                                        </a>
                                    @elseif($tagihan->status == 'belum_bayar' || $tagihan->status == 'nunggak')
                                        <!-- Button Detail -->
                                        <a href="{{ route('petugas.tagihan.show', $tagihan->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        <!-- Button Bayar untuk pembayaran manual -->
                                        <button type="button" 
                                                class="btn btn-sm btn-warning" 
                                                onclick="openKonfirmasiModal({{ $tagihan->id }}, '{{ addslashes($tagihan->pelanggan->user->name ?? 'N/A') }}', '{{ $namaBulanText }} {{ $tahunInt }}', {{ $tagihan->jumlah }})" 
                                                title="Input Pembayaran Manual">
                                            <i class="fas fa-money-bill"></i> Bayar
                                        </button>
                                    @else
                                        <!-- Button Detail untuk status lunas -->
                                        <a href="{{ route('petugas.tagihan.show', $tagihan->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="text-align: center; padding: 40px;">
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
        <form method="POST" action="{{ route('petugas.tagihan.generate') }}" id="formGenerate">
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
                    <select name="bulan" id="selectBulan" class="form-control" required>
                        <option value="">-- Pilih Bulan --</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ (int)date('n') == $i ? 'selected' : '' }}>
                                {{ $namaBulan[$i] }}
                            </option>
                        @endfor
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Tahun <span class="text-danger">*</span></label>
                    <select name="tahun" id="selectTahun" class="form-control" required>
                        <option value="">-- Pilih Tahun --</option>
                        @for($y = date('Y'); $y <= date('Y') + 1; $y++)
                            <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Jatuh Tempo <span class="text-danger">*</span></label>
                    <select name="tanggal" id="selectTanggal" class="form-control" required>
                        <option value="">-- Pilih Tanggal --</option>
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
                <div style="background: #f9fafb; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                    <p style="margin: 0 0 5px 0;"><strong>Pelanggan:</strong> <span id="konfNama"></span></p>
                    <p style="margin: 0 0 5px 0;"><strong>Periode:</strong> <span id="konfPeriode"></span></p>
                    <p style="margin: 0;"><strong>Jumlah:</strong> <span style="color: #667eea; font-weight: 700;">Rp <span id="konfJumlah"></span></span></p>
                </div>
                
                <!-- Bukti Transfer Section (hanya muncul jika ada bukti) -->
                <div id="buktiTransferSection" style="display: none;">
                    <hr style="margin: 15px 0; border: 0; border-top: 2px solid #e5e7eb;">
                    
                    <div class="bukti-transfer-section">
                        <h6><i class="fas fa-receipt"></i> Bukti Transfer dari Pelanggan</h6>
                        
                        <div class="image-preview-container">
                            <img id="buktiTransferImage" 
                                 src="" 
                                 alt="Bukti Transfer" 
                                 class="bukti-image-preview"
                                 onclick="openImageModal(this.src)">
                            <p style="margin: 8px 0 0 0; font-size: 11px; color: #6b7280;">
                                <i class="fas fa-info-circle"></i> Klik gambar untuk memperbesar
                            </p>
                        </div>
                    </div>
                </div>
                
                <hr style="margin: 15px 0; border: 0; border-top: 2px solid #e5e7eb;">
                
                <div class="form-group">
                    <label>Metode Pembayaran <span class="text-danger">*</span></label>
                    <select name="metode_bayar" class="form-control" required>
                        <option value="">-- Pilih Metode --</option>
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

<!-- Image Modal untuk zoom bukti transfer -->
<div id="imageModal" onclick="closeImageModal()">
    <img id="modalImage" src="" alt="Bukti Transfer">
</div>

<script>
// Debug: Check what's blocking clicks
console.log('Modal script loaded');

// Check if FontAwesome is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔍 Checking FontAwesome...');
    
    // Check if FontAwesome CSS is loaded
    const faLoaded = Array.from(document.styleSheets).some(sheet => {
        try {
            return sheet.href && (sheet.href.includes('font-awesome') || sheet.href.includes('fontawesome'));
        } catch(e) {
            return false;
        }
    });
    
    console.log('FontAwesome loaded:', faLoaded);
    
    // Check if icon elements exist
    const icons = document.querySelectorAll('.fas, .fa, i[class*="fa-"]');
    console.log('Icon elements found:', icons.length);
    
    if (icons.length > 0) {
        const firstIcon = icons[0];
        console.log('First icon:', {
            element: firstIcon,
            classes: firstIcon.className,
            computed: window.getComputedStyle(firstIcon).fontFamily,
            display: window.getComputedStyle(firstIcon).display,
            visibility: window.getComputedStyle(firstIcon).visibility
        });
    }
    
    // If FontAwesome not loaded, inject it
    if (!faLoaded) {
        console.warn('⚠️ FontAwesome not detected! Loading from CDN...');
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css';
        link.onload = function() {
            console.log('✅ FontAwesome loaded from CDN');
        };
        link.onerror = function() {
            console.error('❌ Failed to load FontAwesome from CDN');
        };
        document.head.appendChild(link);
    }
});

// Modal Functions
function openModal(modalId) {
    console.log('Opening modal:', modalId);
    const modal = document.getElementById(modalId);
    
    if (!modal) {
        console.error('Modal not found:', modalId);
        return;
    }
    
    modal.classList.add('show');
    modal.style.display = 'flex';
    modal.style.zIndex = '2147483647';
    modal.style.pointerEvents = 'auto';
    
    document.body.classList.add('modal-open');
    document.body.style.overflow = 'hidden';
    
    console.log('Modal opened, z-index:', window.getComputedStyle(modal).zIndex);
    
    // Reset form jika modal generate
    if(modalId === 'generateModal') {
        const form = document.getElementById('formGenerate');
        if (form) {
            form.reset();
            // Set default values
            const bulanSekarang = {{ (int)date('n') }};
            const tahunSekarang = {{ date('Y') }};
            
            const selectBulan = document.getElementById('selectBulan');
            const selectTahun = document.getElementById('selectTahun');
            const selectTanggal = document.getElementById('selectTanggal');
            
            if (selectBulan) selectBulan.value = bulanSekarang;
            if (selectTahun) selectTahun.value = tahunSekarang;
            if (selectTanggal) selectTanggal.value = 10;
            
            console.log('Form reset with defaults');
        }
    }
}

function closeModal(modalId) {
    console.log('Closing modal:', modalId);
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
    }
    document.body.classList.remove('modal-open');
    document.body.style.overflow = 'auto';
}

// Fungsi untuk modal konfirmasi TANPA bukti (untuk pembayaran manual)
function openKonfirmasiModal(id, nama, periode, jumlah) {
    console.log('Opening konfirmasi modal for manual payment:', id);
    document.getElementById('konfNama').textContent = nama;
    document.getElementById('konfPeriode').textContent = periode;
    document.getElementById('konfJumlah').textContent = new Intl.NumberFormat('id-ID').format(jumlah);
    document.getElementById('konfirmasiForm').action = `/petugas/tagihan/${id}/konfirmasi`;
    
    // Sembunyikan section bukti transfer
    document.getElementById('buktiTransferSection').style.display = 'none';
    
    openModal('konfirmasiModal');
}

// Fungsi BARU untuk modal konfirmasi DENGAN bukti transfer
function openKonfirmasiModalWithBukti(id, nama, periode, jumlah, buktiBayar) {
    console.log('Opening konfirmasi modal WITH bukti for tagihan:', id);
    document.getElementById('konfNama').textContent = nama;
    document.getElementById('konfPeriode').textContent = periode;
    document.getElementById('konfJumlah').textContent = new Intl.NumberFormat('id-ID').format(jumlah);
    document.getElementById('konfirmasiForm').action = `/petugas/tagihan/${id}/konfirmasi`;
    
    // Tampilkan dan set bukti transfer
    if (buktiBayar && buktiBayar !== '') {
        document.getElementById('buktiTransferSection').style.display = 'block';
        document.getElementById('buktiTransferImage').src = buktiBayar;
    } else {
        document.getElementById('buktiTransferSection').style.display = 'none';
    }
    
    openModal('konfirmasiModal');
}

// Fungsi untuk membuka image modal (zoom)
function openImageModal(src) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    
    if (modal && modalImg) {
        modal.classList.add('show');
        modal.style.display = 'flex';
        modalImg.src = src;
        document.body.style.overflow = 'hidden';
    }
}

// Fungsi untuk menutup image modal
function closeImageModal() {
    const modal = document.getElementById('imageModal');
    if (modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    // Close konfirmasi modal
    if (event.target.id === 'konfirmasiModal') {
        closeModal('konfirmasiModal');
    }
    
    // Close generate modal
    if (event.target.id === 'generateModal') {
        closeModal('generateModal');
    }
    
    // Close image modal - sudah ditangani oleh onclick di div
}

// Prevent clicks from being blocked
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, setting up modal event listeners');
    
    // AGGRESSIVE FIX: Remove any blocking overlays
    console.log('🔧 Removing blocking elements...');
    const blockingSelectors = [
        '.sidebar-backdrop',
        '.modal-backdrop:not(.show)',
        '.backdrop',
        '.overlay',
        '[class*="backdrop"]:not(.modal.show [class*="backdrop"])',
        '[class*="overlay"]:not(.modal.show [class*="overlay"])'
    ];
    
    blockingSelectors.forEach(selector => {
        document.querySelectorAll(selector).forEach(element => {
            console.log('Found blocking element:', selector);
            element.style.display = 'none';
            element.style.pointerEvents = 'none';
            element.style.zIndex = '-9999';
            element.remove(); // Aggressively remove it
        });
    });
    
    // Force all interactive elements to be clickable
    const makeClickable = (selector) => {
        document.querySelectorAll(selector).forEach(el => {
            el.style.pointerEvents = 'auto';
            el.style.cursor = 'pointer';
            el.style.position = 'relative';
            el.style.zIndex = '10';
        });
    };
    
    makeClickable('button');
    makeClickable('a');
    makeClickable('input');
    makeClickable('select');
    makeClickable('textarea');
    makeClickable('.btn');
    
    console.log('✅ All interactive elements forced clickable');
    
    // Force all buttons in modals to be clickable
    document.querySelectorAll('.modal button, .modal select, .modal input').forEach(function(element) {
        element.style.pointerEvents = 'auto';
        element.style.cursor = 'pointer';
    });
    
    // Auto hide alerts
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.style.display = 'none';
        });
    }, 5000);
});

// Run fix immediately (before DOM ready)
(function() {
    console.log('🚀 Running immediate fix...');
    const style = document.createElement('style');
    style.innerHTML = `
        .sidebar-backdrop,
        .modal-backdrop:not(.show .modal-backdrop),
        .backdrop:not(.modal.show .backdrop),
        .overlay:not(.modal.show .overlay) {
            display: none !important;
            pointer-events: none !important;
            z-index: -9999 !important;
        }
        button, a, input, select, textarea, .btn {
            pointer-events: auto !important;
            cursor: pointer !important;
        }
    `;
    document.head.appendChild(style);
    console.log('✅ Immediate fix applied');
})();

// Debug click events
document.addEventListener('click', function(e) {
    if (e.target.closest('.modal')) {
        console.log('Click inside modal on:', e.target.tagName, e.target.className);
        console.log('Pointer events:', window.getComputedStyle(e.target).pointerEvents);
        console.log('Z-index:', window.getComputedStyle(e.target).zIndex);
    }
}, true);
</script>
</div>
<!-- End #tagihan-page wrapper -->
@endsection