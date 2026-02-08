@extends('layouts.admin')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')

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
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
    
    .btn-warning {
        background: #f59e0b;
        color: white;
    }
    
    .btn-danger {
        background: #ef4444;
        color: white;
    }
    
    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
    }
    
    /* Filters */
    .filters {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 15px;
        margin-bottom: 20px;
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
    
    .badge-purple {
        background: #ede9fe;
        color: #6b21a8;
    }
    
    .badge-blue {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .badge-green {
        background: #d1fae5;
        color: #065f46;
    }
    
    .badge-orange {
        background: #fed7aa;
        color: #92400e;
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
    }
</style>

<div class="container-custom">
    <!-- Header -->
    <div class="header-section">
        <div class="header-title">
            <h2>👥 Manajemen User</h2>
            <p>Kelola akun pengguna sistem</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Tambah User
        </a>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card purple">
            <h6>Total User</h6>
            <h3>{{ $users->total() ?? 0 }}</h3>
        </div>
        <div class="stat-card blue">
            <h6>Admin</h6>
            <h3>{{ $users->where('role', 'admin')->count() ?? 0 }}</h3>
        </div>
        <div class="stat-card green">
            <h6>Petugas</h6>
            <h3>{{ $users->where('role', 'petugas')->count() ?? 0 }}</h3>
        </div>
        <div class="stat-card orange">
            <h6>Pelanggan</h6>
            <h3>{{ $users->where('role', 'pelanggan')->count() ?? 0 }}</h3>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card">
        <div class="card-header">
            <h3>Daftar User</h3>
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
            <div class="filters">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Cari nama atau email...">
                </div>
                <select id="roleFilter" class="filter-select">
                    <option value="">Semua Role</option>
                    <option value="admin">Admin</option>
                    <option value="petugas">Petugas</option>
                    <option value="teknisi">Teknisi</option>
                    <option value="pelanggan">Pelanggan</option>
                </select>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table id="usersTable">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th style="width: 120px;">Role</th>
                            <th style="width: 180px;">Tanggal Daftar</th>
                            <th style="width: 150px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            <tr>
                                <td>{{ $users->firstItem() + $index }}</td>
                                <td><strong>{{ $user->name }}</strong></td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role == 'admin')
                                        <span class="badge badge-purple">
                                            <i class="fas fa-user-shield"></i> Admin
                                        </span>
                                    @elseif($user->role == 'petugas')
                                        <span class="badge badge-blue">
                                            <i class="fas fa-user-tie"></i> Petugas
                                        </span>
                                    @elseif($user->role == 'teknisi')
                                        <span class="badge badge-green">
                                            <i class="fas fa-user-cog"></i> Teknisi
                                        </span>
                                    @else
                                        <span class="badge badge-orange">
                                            <i class="fas fa-user"></i> Pelanggan
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.users.show', $user->id) }}" 
                                       class="btn btn-sm btn-info"
                                       title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user->id) }}" 
                                       class="btn btn-sm btn-warning"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px;">
                                    <i class="fas fa-users" style="font-size: 48px; color: #e5e7eb;"></i>
                                    <p style="margin: 10px 0 0 0; color: #6b7280;">Belum ada data user</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->total() > 0)
                <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center;">
                    <p style="color: #6b7280; margin: 0;">
                        Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }}
                    </p>
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Search and Filter
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    
    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        const roleValue = roleFilter.value.toLowerCase();
        const rows = document.querySelectorAll('#usersTable tbody tr');
        
        rows.forEach(row => {
            if (row.cells.length === 1) return; // Skip empty state row
            
            const text = row.textContent.toLowerCase();
            const badge = row.querySelector('.badge');
            
            const matchSearch = text.includes(searchValue);
            const matchRole = !roleValue || (badge && badge.textContent.toLowerCase().includes(roleValue));
            
            row.style.display = matchSearch && matchRole ? '' : 'none';
        });
    }
    
    searchInput.addEventListener('keyup', filterTable);
    roleFilter.addEventListener('change', filterTable);
    
    // Auto hide alerts
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.style.display = 'none';
        });
    }, 5000);
});
</script>
@endsection