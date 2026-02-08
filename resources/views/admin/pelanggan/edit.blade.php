@extends('layouts.admin')

@section('title', 'Edit Pelanggan')
@section('page-title', 'Edit Pelanggan')

@section('content')
<style>
    .container-custom {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
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
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .card-body {
        padding: 30px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #374151;
        font-size: 14px;
    }
    
    .form-group label span {
        color: #ef4444;
    }
    
    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.3s;
    }
    
    .form-control:focus {
        border-color: #667eea;
    }
    
    .form-control.is-invalid {
        border-color: #ef4444;
    }
    
    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }
    
    .invalid-feedback {
        color: #ef4444;
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }
    
    .form-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
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
    }
    
    .btn-secondary {
        background: #6b7280;
        color: white;
    }
    
    .btn-secondary:hover {
        background: #4b5563;
    }
    
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .alert-danger {
        background: #fee2e2;
        color: #991b1b;
        border-left: 4px solid #ef4444;
    }
    
    .form-hint {
        font-size: 12px;
        color: #6b7280;
        margin-top: 5px;
    }
    
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 20px;
        font-size: 14px;
    }
    
    .back-link:hover {
        color: #5568d3;
    }

    .info-badge {
        display: inline-block;
        background: #e0e7ff;
        color: #3730a3;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        margin-top: 5px;
    }
</style>

<div class="container-custom">
    <!-- Back Link -->
    <a href="{{ route('admin.pelanggan.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i>
        Kembali ke Daftar Pelanggan
    </a>

    <!-- Main Card -->
    <div class="card">
        <div class="card-header">
            <h3>
                <i class="fas fa-user-edit"></i>
                Edit Data Pelanggan
            </h3>
        </div>
        
        <div class="card-body">
            <!-- Error Alert -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <div>
                        <strong>Terjadi kesalahan!</strong>
                        <ul style="margin: 10px 0 0 20px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('admin.pelanggan.update', $pelanggan->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <h4 style="margin: 0 0 20px 0; color: #374151; font-size: 16px; font-weight: 700; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px;">
                    Informasi Akun
                </h4>
                
                <div class="form-group">
                    <label>Nama Lengkap <span>*</span></label>
                    <input type="text" 
                           name="name" 
                           class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $pelanggan->user->name) }}"
                           placeholder="Masukkan nama lengkap"
                           required>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Email <span>*</span></label>
                    <input type="email" 
                           name="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email', $pelanggan->user->email) }}"
                           placeholder="contoh@email.com"
                           required>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <small class="form-hint">Email akan digunakan untuk login</small>
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" 
                           name="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           placeholder="Kosongkan jika tidak ingin mengubah password">
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <small class="form-hint">
                        <i class="fas fa-info-circle"></i> 
                        Kosongkan jika tidak ingin mengubah password. Minimal 6 karakter jika diisi.
                    </small>
                </div>

                <h4 style="margin: 30px 0 20px 0; color: #374151; font-size: 16px; font-weight: 700; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px;">
                    Informasi Kontak
                </h4>
                
                <div class="form-group">
                    <label>Nomor WhatsApp <span>*</span></label>
                    <input type="text" 
                           name="no_wa" 
                           class="form-control @error('no_wa') is-invalid @enderror" 
                           value="{{ old('no_wa', $pelanggan->no_wa) }}"
                           placeholder="08xxxxxxxxxx"
                           required>
                    @error('no_wa')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <small class="form-hint">Format: 08xxxxxxxxxx</small>
                </div>
                
                <div class="form-group">
                    <label>Alamat Lengkap <span>*</span></label>
                    <textarea name="alamat" 
                              class="form-control @error('alamat') is-invalid @enderror" 
                              placeholder="Masukkan alamat lengkap pelanggan"
                              required>{{ old('alamat', $pelanggan->alamat) }}</textarea>
                    @error('alamat')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Link Google Maps</label>
                    <input type="url" 
                           name="link_maps" 
                           class="form-control @error('link_maps') is-invalid @enderror" 
                           value="{{ old('link_maps', $pelanggan->link_maps) }}"
                           placeholder="https://maps.google.com/...">
                    @error('link_maps')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <small class="form-hint">Opsional - untuk memudahkan petugas menemukan lokasi</small>
                    @if($pelanggan->link_maps)
                        <div style="margin-top: 8px;">
                            <a href="{{ $pelanggan->link_maps }}" 
                               target="_blank" 
                               class="info-badge">
                                <i class="fas fa-map-marker-alt"></i> Lihat Lokasi Saat Ini
                            </a>
                        </div>
                    @endif
                </div>

                <h4 style="margin: 30px 0 20px 0; color: #374151; font-size: 16px; font-weight: 700; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px;">
                    Informasi Layanan
                </h4>
                
                <div class="form-group">
                    <label>Paket WiFi <span>*</span></label>
                    <select name="paket_id" 
                            class="form-control @error('paket_id') is-invalid @enderror" 
                            required>
                        <option value="">-- Pilih Paket WiFi --</option>
                        @foreach($pakets ?? [] as $paket)
                            <option value="{{ $paket->id }}" 
                                {{ old('paket_id', $pelanggan->paket_id) == $paket->id ? 'selected' : '' }}>
                                {{ $paket->nama }} - {{ $paket->kecepatan }} - Rp {{ number_format($paket->harga, 0, ',', '.') }}/bulan
                            </option>
                        @endforeach
                    </select>
                    @error('paket_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    @if($pelanggan->paket)
                        <div style="margin-top: 8px;">
                            <span class="info-badge">
                                <i class="fas fa-wifi"></i> 
                                Paket Saat Ini: {{ $pelanggan->paket->nama }} - Rp {{ number_format($pelanggan->paket->harga, 0, ',', '.') }}
                            </span>
                        </div>
                    @endif
                </div>
                
                <div class="form-group">
                    <label>Status Pelanggan <span>*</span></label>
                    <select name="status" 
                            class="form-control @error('status') is-invalid @enderror" 
                            required>
                        <option value="pending" {{ old('status', $pelanggan->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="aktif" {{ old('status', $pelanggan->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status', $pelanggan->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <small class="form-hint">
                        <strong>Pending:</strong> Menunggu instalasi | 
                        <strong>Aktif:</strong> Layanan berjalan | 
                        <strong>Nonaktif:</strong> Layanan dihentikan
                    </small>
                </div>

                <!-- Additional Info -->
                @if($pelanggan->tgl_registrasi)
                    <div style="background: #f9fafb; padding: 15px; border-radius: 8px; margin-top: 20px;">
                        <p style="margin: 0; color: #6b7280; font-size: 13px;">
                            <i class="fas fa-calendar-alt"></i> 
                            <strong>Tanggal Registrasi:</strong> 
                            {{ \Carbon\Carbon::parse($pelanggan->tgl_registrasi)->format('d F Y') }}
                        </p>
                    </div>
                @endif

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('admin.pelanggan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto format nomor WhatsApp
    const noWaInput = document.querySelector('input[name="no_wa"]');
    if (noWaInput) {
        noWaInput.addEventListener('input', function(e) {
            // Hanya izinkan angka
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Auto tambah 0 di depan jika diawali 8
            if (this.value.startsWith('8') && this.value.length > 1) {
                this.value = '0' + this.value;
            }
        });
    }
    
    // Validasi email format
    const emailInput = document.querySelector('input[name="email"]');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value && !emailRegex.test(this.value)) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    }

    // Highlight changes
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        const originalValue = input.value;
        input.addEventListener('change', function() {
            if (this.value !== originalValue) {
                this.style.borderColor = '#f59e0b';
                this.style.borderWidth = '2px';
            } else {
                this.style.borderColor = '#e5e7eb';
            }
        });
    });

    // Confirmation before submit
    form.addEventListener('submit', function(e) {
        const changedFields = Array.from(inputs).filter(input => {
            const original = input.defaultValue || input.getAttribute('data-original');
            return input.value !== original && input.value !== '';
        });

        if (changedFields.length > 0) {
            if (!confirm('Anda yakin ingin menyimpan perubahan data pelanggan ini?')) {
                e.preventDefault();
            }
        }
    });
});
</script>
@endsection