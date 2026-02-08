@extends('layouts.petugas')

@section('title', 'Data Pelanggan')

@section('content')
<div class="container-fluid">
    
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-people-fill text-primary"></i>
                Data Pelanggan
            </h1>
            <p class="text-muted mb-0">Kelola data pelanggan WiFi</p>
        </div>
        <a href="{{ route('petugas.pelanggan.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus-fill"></i> Tambah Pelanggan
        </a>
    </div>

    {{-- STATISTIK CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Pelanggan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $totalPelanggan ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Pelanggan Aktif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $pelangganAktif ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pelanggan Nonaktif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $pelangganNonaktif ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-x-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Baru Bulan Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $pelangganBaru ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-person-plus fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER & TABEL --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-table"></i> Daftar Pelanggan
                    </h6>
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-sm btn-success" id="btnExport">
                        <i class="bi bi-file-earmark-excel"></i> Export Excel
                    </button>
                    <button class="btn btn-sm btn-info" id="btnRefresh">
                        <i class="bi bi-arrow-clockwise"></i> Refresh
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            
            {{-- FILTER --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select class="form-select form-select-sm" id="filterStatus">
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Paket WiFi</label>
                    <select class="form-select form-select-sm" id="filterPaket">
                        <option value="">Semua Paket</option>
                        @foreach($paketWifis ?? [] as $paket)
                        <option value="{{ $paket->id }}">{{ $paket->nama_paket }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button class="btn btn-sm btn-primary w-100" id="btnFilter">
                        <i class="bi bi-funnel"></i> Terapkan Filter
                    </button>
                </div>
            </div>

            {{-- TABEL --}}
            <div class="table-responsive">
                <table class="table table-hover" id="pelangganTable">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No. HP</th>
                            <th>Alamat</th>
                            <th>Paket WiFi</th>
                            <th>Status</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pelanggans ?? [] as $key => $pelanggan)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-primary text-white me-2">
                                        {{ strtoupper(substr($pelanggan->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <strong>{{ $pelanggan->user->name }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $pelanggan->user->email }}</td>
                            <td>
                                <a href="https://wa.me/{{ $pelanggan->no_hp }}" 
                                   target="_blank" 
                                   class="text-success">
                                    <i class="bi bi-whatsapp"></i> {{ $pelanggan->no_hp }}
                                </a>
                            </td>
                            <td>
                                <small>{{ Str::limit($pelanggan->alamat, 40) }}</small>
                            </td>
                            <td>
                                @if($pelanggan->paketWifi)
                                <span class="badge bg-info">
                                    {{ $pelanggan->paketWifi->nama_paket }}
                                </span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($pelanggan->status == 'aktif')
                                <span class="badge bg-success">Aktif</span>
                                @else
                                <span class="badge bg-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('petugas.pelanggan.show', $pelanggan->id) }}" 
                                       class="btn btn-info" 
                                       title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('petugas.pelanggan.edit', $pelanggan->id) }}" 
                                       class="btn btn-warning" 
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-danger btn-delete" 
                                            data-id="{{ $pelanggan->id }}"
                                            data-name="{{ $pelanggan->user->name }}"
                                            title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-1"></i>
                                <p>Belum ada data pelanggan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if(isset($pelanggans) && $pelanggans->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Menampilkan {{ $pelanggans->firstItem() }} sampai {{ $pelanggans->lastItem() }} 
                    dari {{ $pelanggans->total() }} data
                </div>
                <div>
                    {{ $pelanggans->links() }}
                </div>
            </div>
            @endif

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    
    // ==========================================
    // INITIALIZE DATATABLE
    // ==========================================
    const table = $('#pelangganTable').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
        },
        columnDefs: [
            { targets: [0, 7], orderable: false }
        ]
    });

    // ==========================================
    // FILTER
    // ==========================================
    $('#btnFilter').on('click', function() {
        const status = $('#filterStatus').val();
        const paket = $('#filterPaket').val();
        
        // Build query string
        let params = [];
        if (status) params.push('status=' + status);
        if (paket) params.push('paket=' + paket);
        
        // Redirect with filters
        window.location.href = '{{ route("petugas.pelanggan.index") }}' + 
                               (params.length > 0 ? '?' + params.join('&') : '');
    });

    // ==========================================
    // REFRESH
    // ==========================================
    $('#btnRefresh').on('click', function() {
        window.location.reload();
    });

    // ==========================================
    // EXPORT EXCEL
    // ==========================================
    $('#btnExport').on('click', function() {
        window.location.href = '{{ route("petugas.pelanggan.export") }}';
    });

    // ==========================================
    // DELETE PELANGGAN
    // ==========================================
    $('.btn-delete').on('click', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        
        Swal.fire({
            title: 'Hapus Pelanggan?',
            text: `Apakah Anda yakin ingin menghapus pelanggan "${name}"? Data ini tidak dapat dikembalikan!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create form and submit
                const form = $('<form>', {
                    'method': 'POST',
                    'action': '{{ route("petugas.pelanggan.destroy", ":id") }}'.replace(':id', id)
                });
                
                form.append('@csrf');
                form.append('@method("DELETE")');
                form.appendTo('body').submit();
            }
        });
    });

});
</script>
@endpush

@push('styles')
<style>
.avatar-circle {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
}

.table > :not(caption) > * > * {
    vertical-align: middle;
}

.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
}

/* Card hover effect */
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}
</style>
@endpush