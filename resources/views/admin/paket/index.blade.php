@extends('layouts.admin')

@section('title', 'Kelola Paket')
@section('page-title', 'Kelola Paket')

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
    }
    
    .stat-card.purple { border-color: #8b5cf6; }
    .stat-card.blue { border-color: #3b82f6; }
    .stat-card.green { border-color: #10b981; }
    
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
    
    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
    }
    
    /* Paket Grid */
    .paket-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }
    
    .paket-card {
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 24px;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }
    
    .paket-card:hover {
        border-color: #667eea;
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.1);
        transform: translateY(-5px);
    }
    
    .paket-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .paket-header {
        margin-bottom: 16px;
    }
    
    .paket-header h4 {
        font-size: 20px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 8px 0;
    }
    
    .paket-price {
        font-size: 28px;
        font-weight: 800;
        color: #667eea;
        margin: 0 0 4px 0;
    }
    
    .paket-price small {
        font-size: 14px;
        color: #6b7280;
        font-weight: 400;
    }
    
    .paket-body {
        margin: 16px 0;
        padding: 16px 0;
        border-top: 1px solid #e5e7eb;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .paket-info {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        color: #374151;
        font-size: 14px;
    }
    
    .paket-info i {
        color: #667eea;
        width: 20px;
    }
    
    .paket-description {
        color: #6b7280;
        font-size: 13px;
        line-height: 1.6;
        margin-bottom: 16px;
    }
    
    .paket-footer {
        display: flex;
        gap: 10px;
        margin-top: 16px;
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
        font-size: 12px;
        margin-top: 4px;
    }

    .text-muted {
        color: #6b7280;
        font-size: 13px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 64px;
        color: #e5e7eb;
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
        .paket-grid {
            grid-template-columns: 1fr;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
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
            <h2>📦 Kelola Paket</h2>
            <p>Manajemen paket layanan internet</p>
        </div>
        <button type="button" class="btn btn-primary" onclick="openModal('createModal')">
            <i class="fas fa-plus"></i>
            Tambah Paket Baru
        </button>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card purple">
            <h6>Total Paket</h6>
            <h3>{{ $pakets->count() }}</h3>
        </div>
        <div class="stat-card blue">
            <h6>Pelanggan Aktif</h6>
            <h3>{{ \App\Models\Pelanggan::where('status', 'aktif')->count() }}</h3>
        </div>
        <div class="stat-card green">
            <h6>Harga Terendah</h6>
            <h3>Rp {{ number_format($pakets->min('harga') ?? 0, 0, ',', '.') }}</h3>
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

    <!-- Paket Grid -->
    @if($pakets->count() > 0)
        <div class="paket-grid">
            @foreach($pakets as $paket)
                <div class="paket-card">
                    <div class="paket-header">
                        <h4>{{ $paket->nama_paket }}</h4>
                        <div class="paket-price">
                            Rp {{ number_format($paket->harga, 0, ',', '.') }}
                            <small>/bulan</small>
                        </div>
                    </div>
                    
                    <div class="paket-body">
                        <div class="paket-info">
                            <i class="fas fa-tachometer-alt"></i>
                            <span><strong>Kecepatan:</strong> {{ $paket->kecepatan }}</span>
                        </div>
                        <div class="paket-info">
                            <i class="fas fa-users"></i>
                            <span><strong>Pelanggan:</strong> {{ $paket->pelanggans_count ?? 0 }} orang</span>
                        </div>
                    </div>
                    
                    <div class="paket-footer">
                        <button type="button" class="btn btn-warning btn-sm" 
                                onclick="openEditModal({{ $paket->id }}, '{{ addslashes($paket->nama_paket) }}', {{ $paket->harga }}, '{{ addslashes($paket->kecepatan) }}')">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" 
                                onclick="confirmDelete({{ $paket->id }}, '{{ addslashes($paket->nama_paket) }}')">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <h4>Belum Ada Paket</h4>
                    <p>Klik tombol "Tambah Paket Baru" untuk membuat paket layanan pertama Anda</p>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modal Create Paket -->
<div class="modal" id="createModal">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.paket.store') }}">
            @csrf
            <div class="modal-header">
                <h5>Tambah Paket Baru</h5>
                <button type="button" class="close" onclick="closeModal('createModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Paket <span class="text-danger">*</span></label>
                    <input type="text" name="nama_paket" class="form-control" placeholder="Contoh: Paket Silver" required>
                    @error('nama_paket')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Harga (Rp/bulan) <span class="text-danger">*</span></label>
                    <input type="number" name="harga" class="form-control" placeholder="150000" min="0" required>
                    @error('harga')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Kecepatan <span class="text-danger">*</span></label>
                    <input type="text" name="kecepatan" class="form-control" placeholder="Contoh: 20 Mbps" required>
                    @error('kecepatan')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('createModal')">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Paket
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Paket -->
<div class="modal" id="editModal">
    <div class="modal-dialog">
        <form method="POST" id="editForm">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5>Edit Paket</h5>
                <button type="button" class="close" onclick="closeModal('editModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Paket <span class="text-danger">*</span></label>
                    <input type="text" name="nama_paket" id="edit_nama_paket" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Harga (Rp/bulan) <span class="text-danger">*</span></label>
                    <input type="number" name="harga" id="edit_harga" class="form-control" min="0" required>
                </div>
                
                <div class="form-group">
                    <label>Kecepatan <span class="text-danger">*</span></label>
                    <input type="text" name="kecepatan" id="edit_kecepatan" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')">Batal</button>
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save"></i> Update Paket
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Form Delete (Hidden) -->
<form method="POST" id="deleteForm" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
// Modal Functions
function openModal(modalId) {
    document.getElementById(modalId).classList.add('show');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('show');
}

function openEditModal(id, nama_paket, harga, kecepatan) {
    document.getElementById('edit_nama_paket').value = nama_paket;
    document.getElementById('edit_harga').value = harga;
    document.getElementById('edit_kecepatan').value = kecepatan;
    document.getElementById('editForm').action = `/admin/paket/${id}`;
    openModal('editModal');
}

function confirmDelete(id, nama_paket) {
    if (confirm(`Apakah Anda yakin ingin menghapus paket "${nama_paket}"?\n\nPerhatian: Paket yang sudah digunakan oleh pelanggan tidak dapat dihapus.`)) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/paket/${id}`;
        form.submit();
    }
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