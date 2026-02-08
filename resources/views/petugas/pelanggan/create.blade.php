@extends('layouts.petugas')

@section('title', 'Tambah Pelanggan')

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
        box-sizing: border-box;
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

    .section-title {
        margin: 0 0 20px 0;
        color: #374151;
        font-size: 16px;
        font-weight: 700;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 10px;
    }

    .section-title:not(:first-child) {
        margin-top: 30px;
    }
</style>

<div class="container-custom">
    <!-- Back Link -->
    <a href="{{ route('petugas.pelanggan.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i>
        Kembali ke Daftar Pelanggan
    </a>

    <!-- Main Card -->
    <div class="card">
        <div class="card-header">
            <h3>
                <i class="fas fa-user-plus"></i>
                Tambah Pelanggan Baru
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

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('petugas.pelanggan.store') }}" method="POST" id="formPelanggan">
                @csrf

                <!-- Informasi Akun -->
                <h4 class="section-title">Informasi Akun</h4>
                
                <div class="form-group">
                    <label>Nama Lengkap <span>*</span></label>
                    <input type="text" 
                           name="name" 
                           class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}"
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
                           value="{{ old('email') }}"
                           placeholder="contoh@email.com"
                           required>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <small class="form-hint">Email akan digunakan untuk login</small>
                </div>
                
                <div class="form-group">
                    <label>Password <span>*</span></label>
                    <div style="display: flex; gap: 8px;">
                        <div style="position: relative; flex: 1;">
                            <input type="password" 
                                   name="password" 
                                   id="passwordInput"
                                   class="form-control @error('password') is-invalid @enderror" 
                                   placeholder="Minimal 8 karakter"
                                   required>
                            <button type="button" id="togglePassword" onclick="togglePasswordVisibility()"
                                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #6b7280; padding: 0;">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                        <button type="button" id="generatePassword" onclick="generatePassword()"
                                style="background: #667eea; color: white; border: none; border-radius: 8px; padding: 10px 16px; cursor: pointer; font-weight: 600; white-space: nowrap;">
                            <i class="fas fa-key"></i> Generate
                        </button>
                    </div>
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <small class="form-hint">Minimal 8 karakter</small>
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password <span>*</span></label>
                    <input type="password" 
                           name="password_confirmation" 
                           id="passwordConfirmation"
                           class="form-control" 
                           placeholder="Ulangi password"
                           required>
                </div>

                <!-- Informasi Kontak -->
                <h4 class="section-title" style="margin-top: 30px;">Informasi Kontak</h4>
                
                <div class="form-group">
                    <label>Nomor WhatsApp <span>*</span></label>
                    <input type="text" 
                           name="no_wa" 
                           id="noWaInput"
                           class="form-control @error('no_wa') is-invalid @enderror" 
                           value="{{ old('no_wa') }}"
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
                              required>{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Link Google Maps</label>
                    <input type="url" 
                           name="link_maps" 
                           class="form-control @error('link_maps') is-invalid @enderror" 
                           value="{{ old('link_maps') }}"
                           placeholder="https://maps.google.com/...">
                    @error('link_maps')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <small class="form-hint">Opsional - untuk memudahkan petugas menemukan lokasi</small>
                </div>

                <!-- Informasi Layanan -->
                <h4 class="section-title" style="margin-top: 30px;">Informasi Layanan</h4>
                
                <div class="form-group">
                    <label>Paket WiFi <span>*</span></label>
                    <select name="paket_id" 
                            id="paketSelect"
                            class="form-control @error('paket_id') is-invalid @enderror" 
                            required>
                        <option value="">-- Pilih Paket WiFi --</option>
                        @foreach($paketWifis as $paket)
                            <option value="{{ $paket->id }}" 
                                    data-harga="{{ $paket->harga }}"
                                    data-kecepatan="{{ $paket->kecepatan }}"
                                    {{ old('paket_id') == $paket->id ? 'selected' : '' }}>
                                {{ $paket->nama_paket }} - {{ $paket->kecepatan }} - Rp {{ number_format($paket->harga, 0, ',', '.') }}/bulan
                            </option>
                        @endforeach
                    </select>
                    @error('paket_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror

                    <!-- Info Paket -->
                    <div id="paketInfo" style="display: none; margin-top: 12px; padding: 12px 16px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px;">
                        <div style="display: flex; gap: 40px;">
                            <div>
                                <strong style="color: #374151;">Kecepatan:</strong>
                                <span id="infoKecepatan" style="color: #1d4ed8; margin-left: 6px;">-</span>
                            </div>
                            <div>
                                <strong style="color: #374151;">Harga:</strong>
                                <span id="infoHarga" style="color: #1d4ed8; margin-left: 6px;">-</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Status Pelanggan <span>*</span></label>
                    <select name="status" 
                            class="form-control @error('status') is-invalid @enderror" 
                            required>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
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

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('petugas.pelanggan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Simpan Pelanggan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // ==========================================
    // TOGGLE PASSWORD VISIBILITY
    // ==========================================
    function togglePasswordVisibility() {
        const input = document.getElementById('passwordInput');
        const icon = document.getElementById('eyeIcon');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    window.togglePasswordVisibility = togglePasswordVisibility;

    // ==========================================
    // GENERATE PASSWORD
    // ==========================================
    function generatePassword() {
        const length = 12;
        const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
        let password = "";

        for (let i = 0; i < length; i++) {
            password += charset.charAt(Math.floor(Math.random() * charset.length));
        }

        document.getElementById('passwordInput').value = password;
        document.getElementById('passwordConfirmation').value = password;

        // Tampilkan password
        document.getElementById('passwordInput').type = 'text';
        document.getElementById('eyeIcon').classList.remove('fa-eye');
        document.getElementById('eyeIcon').classList.add('fa-eye-slash');

        // Show alert
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Password Generated!',
                text: 'Password: ' + password,
                confirmButtonText: 'OK'
            });
        } else {
            alert('Password berhasil digenerate: ' + password);
        }
    }
    window.generatePassword = generatePassword;

    // ==========================================
    // SHOW PAKET INFO ON SELECT
    // ==========================================
    const paketSelect = document.getElementById('paketSelect');
    const paketInfo = document.getElementById('paketInfo');

    if (paketSelect) {
        paketSelect.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];

            if (selected.value) {
                const kecepatan = selected.dataset.kecepatan;
                const harga = selected.dataset.harga;

                document.getElementById('infoKecepatan').textContent = kecepatan;
                document.getElementById('infoHarga').textContent = 'Rp ' + parseInt(harga).toLocaleString('id-ID');
                paketInfo.style.display = 'block';
            } else {
                paketInfo.style.display = 'none';
            }
        });

        // Trigger saat page load kalau ada old value
        if (paketSelect.value) {
            paketSelect.dispatchEvent(new Event('change'));
        }
    }

    // ==========================================
    // AUTO FORMAT NOMOR WHATSAPP
    // ==========================================
    const noWaInput = document.getElementById('noWaInput');
    if (noWaInput) {
        noWaInput.addEventListener('input', function() {
            // Hanya izinkan angka
            this.value = this.value.replace(/[^0-9]/g, '');

            // Auto tambah 0 di depan jika diawali 8
            if (this.value.startsWith('8') && this.value.length > 1) {
                this.value = '0' + this.value;
            }
        });
    }

    // ==========================================
    // FORM VALIDATION
    // ==========================================
    document.getElementById('formPelanggan').addEventListener('submit', function(e) {
        const password = document.getElementById('passwordInput').value;
        const passwordConfirmation = document.getElementById('passwordConfirmation').value;

        if (password.length < 8) {
            e.preventDefault();
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Terlalu Pendek',
                    text: 'Password minimal 8 karakter!',
                    confirmButtonText: 'OK'
                });
            } else {
                alert('Password minimal 8 karakter!');
            }
            return false;
        }

        if (password !== passwordConfirmation) {
            e.preventDefault();
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Tidak Cocok',
                    text: 'Password dan konfirmasi password harus sama!',
                    confirmButtonText: 'OK'
                });
            } else {
                alert('Password dan konfirmasi password harus sama!');
            }
            return false;
        }
    });

});
</script>
@endsection