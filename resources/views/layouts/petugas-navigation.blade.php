<aside id="sidebar">
    
    <!-- BRAND -->
    <a class="sidebar-brand" href="{{ route('petugas.dashboard') }}">
        <i class="bi bi-house-check-fill"></i>
        <span>Petugas Panel</span>
    </a>

    <!-- DIVIDER -->
    <hr class="sidebar-divider my-0">

    <!-- NAV: DASHBOARD -->
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}" 
               href="{{ route('petugas.dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>
    </ul>

    <!-- DIVIDER -->
    <hr class="sidebar-divider">

    <!-- HEADING: PELANGGAN -->
    <div class="sidebar-heading">
        Manajemen Pelanggan
    </div>

    <!-- NAV: PELANGGAN -->
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('petugas.pelanggan.*') ? 'active' : '' }}" 
               href="{{ route('petugas.pelanggan.index') }}">
                <i class="bi bi-people-fill"></i>
                <span>Data Pelanggan</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('petugas.pelanggan.create') ? 'active' : '' }}" 
               href="{{ route('petugas.pelanggan.create') }}">
                <i class="bi bi-person-plus-fill"></i>
                <span>Registrasi Pelanggan</span>
            </a>
        </li>
    </ul>

    <!-- DIVIDER -->
    <hr class="sidebar-divider">

    <!-- HEADING: TAGIHAN -->
    <div class="sidebar-heading">
        Manajemen Tagihan
    </div>

    <!-- NAV: TAGIHAN -->
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('petugas.tagihan.index') ? 'active' : '' }}" 
               href="{{ route('petugas.tagihan.index') }}">
                <i class="bi bi-file-earmark-text-fill"></i>
                <span>Tagihan Pelanggan</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('petugas.tagihan.nunggak') ? 'active' : '' }}" 
               href="{{ route('petugas.tagihan.nunggak') }}">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>Tagihan Nunggak</span>
                @php
                    $nunggakCount = \App\Models\Tagihan::where('status', 'nunggak')->count();
                @endphp
                @if($nunggakCount > 0)
                <span class="badge bg-danger rounded-pill ms-auto">{{ $nunggakCount }}</span>
                @endif
            </a>
        </li>
    </ul>

    <!-- DIVIDER -->
    <hr class="sidebar-divider">

    <!-- HEADING: PEMBAYARAN -->
    <div class="sidebar-heading">
        Pembayaran
    </div>

    <!-- NAV: BAYAR -->
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('petugas.pembayaran.*') ? 'active' : '' }}" 
               href="{{ route('petugas.pembayaran.index') }}?status=nunggak">
                <i class="bi bi-cash-coin"></i>
                <span>Proses Pembayaran</span>
            </a>
        </li>
    </ul>

    <!-- DIVIDER -->
    <hr class="sidebar-divider">

    <!-- HEADING: REFERENSI -->
    <div class="sidebar-heading">
        Referensi
    </div>

    <!-- NAV: PAKET WIFI -->
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('petugas.paket.*') ? 'active' : '' }}" 
               href="{{ route('petugas.paket.index') }}">
                <i class="bi bi-wifi"></i>
                <span>Paket WiFi</span>
            </a>
        </li>
    </ul>

    <!-- DIVIDER -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- SIDEBAR INFO -->
    <div class="text-center d-none d-md-block px-3 py-4">
        <div class="small text-white-50 mb-2">
            <i class="bi bi-info-circle"></i> Sistem Tagihan
        </div>
        <div class="small text-white-50">
            v1.0.0
        </div>
    </div>

</aside>

<style>
/* Custom badge positioning */
.nav-link {
    position: relative;
}

.nav-link .badge {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
}

/* Active link animation */
.nav-link.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: white;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        height: 0;
        top: 50%;
    }
    to {
        height: 100%;
        top: 0;
    }
}

/* Hover effect */
.nav-link:hover {
    padding-left: 1.25rem;
    transition: all 0.2s ease;
}

/* Badge pulse animation for nunggak */
.badge.bg-danger {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}
</style>