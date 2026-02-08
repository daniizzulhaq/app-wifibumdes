@extends('layouts.admin')

@section('title', 'Tambah User')
@section('page-title', 'Tambah User Baru')

@section('content')
<style>
    .container-custom {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
    }

    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
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
        padding: 25px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    label {
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 6px;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
        font-size: 14px;
        outline: none;
    }

    .form-control:focus {
        border-color: #667eea;
    }

    .is-invalid {
        border-color: #ef4444;
    }

    .invalid-feedback {
        color: #b91c1c;
        font-size: 12px;
        margin-top: 5px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 22px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
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

    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .header-section h2 {
        font-size: 22px;
        font-weight: 700;
        margin: 0;
        color: #1f2937;
    }
</style>

<div class="container-custom">

    <!-- Header -->
    <div class="header-section">
        <h2>➕ Tambah User Baru</h2>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Card -->
    <div class="card">
        <div class="card-header">
            <h3>Form Tambah User</h3>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Lengkap *</label>
                            <input type="text" 
                                   name="name"
                                   value="{{ old('name') }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   class="form-control @error('email') is-invalid @enderror"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Password *</label>
                            <input type="password"
                                   name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Konfirmasi Password *</label>
                            <input type="password"
                                   name="password_confirmation"
                                   class="form-control"
                                   required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Role *</label>
                    <select name="role"
                            class="form-control @error('role') is-invalid @enderror"
                            required>
                        <option value="">-- Pilih Role --</option>
                        <option value="admin" {{ old('role')=='admin'?'selected':'' }}>Admin</option>
                        <option value="petugas" {{ old('role')=='petugas'?'selected':'' }}>Petugas</option>
                        <option value="pelanggan" {{ old('role')=='pelanggan'?'selected':'' }}>Pelanggan</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr>

                <div style="display:flex; gap:10px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
