<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - Petugas</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <!-- Custom styles -->
    <style>
        :root {
            --sidebar-width: 260px;
            --topbar-height: 60px;
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --danger-color: #e74a3b;
            --warning-color: #f6c23e;
            --info-color: #36b9cc;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f8f9fc;
            overflow-x: hidden;
        }

        /* ===================================== */
        /* SIDEBAR */
        /* ===================================== */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #4e73df 0%, #224abe 100%);
            color: white;
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
            overflow-x: hidden;
        }

        #sidebar.collapsed {
            margin-left: calc(var(--sidebar-width) * -1);
        }

        #sidebar::-webkit-scrollbar {
            width: 6px;
        }

        #sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            height: var(--topbar-height);
            padding: 0 1rem;
            text-decoration: none;
            color: white;
            font-weight: 800;
            font-size: 1.2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }

        .sidebar-brand i {
            font-size: 1.5rem;
            margin-right: 0.5rem;
        }

        .sidebar-divider {
            height: 0;
            margin: 0.5rem 0;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
        }

        .sidebar-heading {
            padding: 0.75rem 1rem;
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            color: rgba(255, 255, 255, 0.4);
        }

        .nav-item {
            position: relative;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.2s;
        }

        .nav-link i {
            font-size: 0.85rem;
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            font-weight: 700;
            border-left: 3px solid white;
        }

        /* ===================================== */
        /* TOPBAR */
        /* ===================================== */
        #topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: white;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            z-index: 999;
            transition: all 0.3s ease;
        }

        #topbar.expanded {
            left: 0;
        }

        .topbar-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
            padding: 0 1.5rem;
        }

        .btn-toggle-sidebar {
            background: none;
            border: none;
            color: var(--secondary-color);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.35rem;
            transition: all 0.2s;
        }

        .btn-toggle-sidebar:hover {
            background: #f8f9fc;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 700;
            color: #5a5c69;
            font-size: 0.9rem;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--secondary-color);
            text-transform: uppercase;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1rem;
        }

        .dropdown-toggle::after {
            display: none;
        }

        /* ===================================== */
        /* MAIN CONTENT */
        /* ===================================== */
        #content {
            margin-left: var(--sidebar-width);
            margin-top: var(--topbar-height);
            padding: 2rem;
            min-height: calc(100vh - var(--topbar-height));
            transition: all 0.3s ease;
        }

        #content.expanded {
            margin-left: 0;
        }

        /* ===================================== */
        /* CARDS */
        /* ===================================== */
        .card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #e3e6f0;
            font-weight: 700;
            color: var(--primary-color);
        }

        /* ===================================== */
        /* RESPONSIVE */
        /* ===================================== */
        @media (max-width: 768px) {
            #sidebar {
                margin-left: calc(var(--sidebar-width) * -1);
            }

            #sidebar.show {
                margin-left: 0;
            }

            #topbar {
                left: 0;
            }

            #content {
                margin-left: 0;
                padding: 1rem;
            }
        }

        /* ===================================== */
        /* UTILITIES */
        /* ===================================== */
        .text-gray-800 {
            color: #5a5c69 !important;
        }

        .text-gray-300 {
            color: #dddfeb !important;
        }

        .border-left-primary {
            border-left: 0.25rem solid var(--primary-color) !important;
        }

        .border-left-success {
            border-left: 0.25rem solid var(--success-color) !important;
        }

        .border-left-info {
            border-left: 0.25rem solid var(--info-color) !important;
        }

        .border-left-warning {
            border-left: 0.25rem solid var(--warning-color) !important;
        }

        .border-left-danger {
            border-left: 0.25rem solid var(--danger-color) !important;
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- ===================================== -->
    <!-- SIDEBAR -->
    <!-- ===================================== -->
    @include('layouts.petugas-navigation')

    <!-- ===================================== -->
    <!-- TOPBAR -->
    <!-- ===================================== -->
    <nav id="topbar">
        <div class="topbar-content">
            <button class="btn-toggle-sidebar" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>

            <div class="topbar-user">
                <div class="dropdown">
                    <a class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle" 
                       href="#" role="button" data-bs-toggle="dropdown">
                        <div class="user-info d-none d-sm-block">
                            <div class="user-name">{{ auth()->user()->name }}</div>
                            <div class="user-role">Petugas</div>
                        </div>
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-person me-2"></i> Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- ===================================== -->
    <!-- MAIN CONTENT -->
    <!-- ===================================== -->
    <main id="content">
        <!-- Alert Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Error!</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Page Content -->
        @yield('content')
    </main>

    <!-- ===================================== -->
    <!-- SCRIPTS -->
    <!-- ===================================== -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // ===================================
        // SIDEBAR TOGGLE
        // ===================================
        $(document).ready(function() {
            const sidebar = $('#sidebar');
            const topbar = $('#topbar');
            const content = $('#content');
            const toggleBtn = $('#sidebarToggle');

            toggleBtn.on('click', function() {
                sidebar.toggleClass('collapsed show');
                topbar.toggleClass('expanded');
                content.toggleClass('expanded');
            });

            // Close sidebar on mobile when clicking outside
            $(document).on('click', function(e) {
                if ($(window).width() <= 768) {
                    if (!$(e.target).closest('#sidebar, #sidebarToggle').length) {
                        sidebar.removeClass('show');
                    }
                }
            });
        });

        // ===================================
        // AUTO DISMISS ALERTS
        // ===================================
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // ===================================
        // DATATABLES DEFAULT CONFIG
        // ===================================
        $.extend(true, $.fn.dataTable.defaults, {
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
            },
            pageLength: 25,
            responsive: true
        });

        // ===================================
        // SELECT2 DEFAULT CONFIG
        // ===================================
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        });
    </script>

    @stack('scripts')
</body>
</html>