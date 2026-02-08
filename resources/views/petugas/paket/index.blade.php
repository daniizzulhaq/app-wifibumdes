@extends('layouts.petugas')

@section('title', 'Manajemen Paket WiFi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Daftar Paket WiFi</h3>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahPaketModal">
                        <i class="fas fa-plus"></i> Tambah Paket
                    </button>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama Paket</th>
                                    <th>Kecepatan</th>
                                    <th>Harga</th>
                                    <th>Jumlah Pelanggan</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pakets as $index => $paket)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $paket->nama_paket }}</td>
                                        <td>{{ $paket->kecepatan }}</td>
                                        <td>Rp {{ number_format($paket->harga, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $paket->pelanggans_count }} Pelanggan</span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editPaketModal{{ $paket->id }}"
                                                    title="Edit Paket">
                                                <img src="https://img.icons8.com/fluency/20/edit.png" alt="Edit"/>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#hapusPaketModal{{ $paket->id }}"
                                                    title="Hapus Paket">
                                                <img src="https://img.icons8.com/fluency/20/delete-trash.png" alt="Hapus"/>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal Edit Paket -->
                                    <div class="modal fade" id="editPaketModal{{ $paket->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('petugas.paket.update', $paket->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Paket WiFi</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Nama Paket <span class="text-danger">*</span></label>
                                                            <input type="text" name="nama_paket" class="form-control @error('nama_paket') is-invalid @enderror" 
                                                                   value="{{ old('nama_paket', $paket->nama_paket) }}" required>
                                                            @error('nama_paket')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Kecepatan <span class="text-danger">*</span></label>
                                                            <input type="text" name="kecepatan" class="form-control @error('kecepatan') is-invalid @enderror" 
                                                                   value="{{ old('kecepatan', $paket->kecepatan) }}" 
                                                                   placeholder="contoh: 10 Mbps" required>
                                                            @error('kecepatan')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                                                            <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror" 
                                                                   value="{{ old('harga', $paket->harga) }}" min="0" required>
                                                            @error('harga')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Hapus Paket -->
                                    <div class="modal fade" id="hapusPaketModal{{ $paket->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('petugas.paket.destroy', $paket->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menghapus paket <strong>{{ $paket->nama_paket }}</strong>?</p>
                                                        @if($paket->pelanggans_count > 0)
                                                            <div class="alert alert-warning">
                                                                <i class="fas fa-exclamation-triangle"></i>
                                                                Paket ini digunakan oleh {{ $paket->pelanggans_count }} pelanggan!
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada data paket WiFi</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Paket -->
<div class="modal fade" id="tambahPaketModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('petugas.paket.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Paket WiFi Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Paket <span class="text-danger">*</span></label>
                        <input type="text" name="nama_paket" class="form-control @error('nama_paket') is-invalid @enderror" 
                               value="{{ old('nama_paket') }}" placeholder="contoh: Paket Basic" required>
                        @error('nama_paket')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kecepatan <span class="text-danger">*</span></label>
                        <input type="text" name="kecepatan" class="form-control @error('kecepatan') is-invalid @enderror" 
                               value="{{ old('kecepatan') }}" placeholder="contoh: 10 Mbps" required>
                        @error('kecepatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror" 
                               value="{{ old('harga') }}" placeholder="contoh: 150000" min="0" required>
                        @error('harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@endpush