@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
<style>
    /* === COPY PERSIS DARI INDEX (TIDAK DIUBAH) === */
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

    .form-control {
        width: 100%;
        padding: 12px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 6px;
        display: block;
        color: #374151;
    }
</style>

<div class="container-custom">

    <!-- Header -->
    <div class="header-section">
        <div class="header-title">
            <h2>✏️ Edit User</h2>
            <p>Perbarui data akun pengguna sistem</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Card -->
    <div class="card">
        <div class="card-header">
            <h3>Form Edit User</h3>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Nama Lengkap *</label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ old('name', $user->name) }}"
                               required>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Email *</label>
                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email', $user->email) }}"
                               required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control">
                        <small style="color:#6b7280">Kosongkan jika tidak ingin mengubah password</small>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Role *</label>
                        <select name="role" class="form-control" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="petugas" {{ $user->role == 'petugas' ? 'selected' : '' }}>Petugas</option>
                            <option value="pelanggan" {{ $user->role == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                        </select>
                    </div>
                </div>

                <hr>

                <div style="display:flex; gap:10px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
