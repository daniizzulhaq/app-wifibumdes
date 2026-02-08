@extends('layouts.admin')

@section('title', 'Detail User')
@section('page-title', 'Detail User')

@section('content')
<style>
    .container-custom {
        max-width: 1200px;
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
        margin: 0;
        color: #1f2937;
    }

    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
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

    table td {
        padding: 10px 0;
        font-size: 14px;
    }

    .badge {
        padding: 6px 14px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-block;
    }

    .badge-purple { background:#ede9fe; color:#6b21a8; }
    .badge-blue { background:#dbeafe; color:#1e40af; }
    .badge-green { background:#d1fae5; color:#065f46; }
    .badge-orange { background:#fed7aa; color:#92400e; }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 18px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }

    .btn-warning { background:#f59e0b; color:white; }
    .btn-danger { background:#ef4444; color:white; }
    .btn-secondary { background:#6b7280; color:white; }
</style>

<div class="container-custom">

    <!-- Header -->
    <div class="header-section">
        <div class="header-title">
            <h2>👤 Detail User</h2>
        </div>
        <div>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- KIRI -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3>Informasi User</h3>
                </div>
                <div class="card-body">
                    <table width="100%">
                        <tr>
                            <td width="180"><strong>Nama</strong></td>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email</strong></td>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>Role</strong></td>
                            <td>
                                @if($user->role == 'admin')
                                    <span class="badge badge-purple">Admin</span>
                                @elseif($user->role == 'petugas')
                                    <span class="badge badge-blue">Petugas</span>
                                @else
                                    <span class="badge badge-orange">Pelanggan</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Dibuat</strong></td>
                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Update</strong></td>
                            <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- DATA PELANGGAN -->
            @if($user->role == 'pelanggan' && $user->pelanggan)
            <div class="card">
                <div class="card-header">
                    <h3>Data Pelanggan</h3>
                </div>
                <div class="card-body">
                    <table width="100%">
                        <tr>
                            <td width="180"><strong>No WhatsApp</strong></td>
                            <td>{{ $user->pelanggan->no_wa }}</td>
                        </tr>
                        <tr>
                            <td><strong>Alamat</strong></td>
                            <td>{{ $user->pelanggan->alamat }}</td>
                        </tr>
                        <tr>
                            <td><strong>Paket</strong></td>
                            <td>{{ $user->pelanggan->paket->nama_paket ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            @endif
        </div>

        <!-- KANAN -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3>Aksi</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning" style="width:100%; margin-bottom:10px;">
                        <i class="fas fa-edit"></i> Edit User
                    </a>

                    <form action="{{ route('admin.users.destroy', $user->id) }}"
                          method="POST"
                          onsubmit="return confirm('Yakin hapus user ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" style="width:100%;">
                            <i class="fas fa-trash"></i> Hapus User
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
