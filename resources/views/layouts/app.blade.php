<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Font Awesome Icons (for backward compatibility) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Bootstrap Theme -->
    <link href="{{ asset('css/bootstrap-theme.css') }}" rel="stylesheet">

    @stack('css')
</head>

<body>
    <div class="d-flex min-vh-100">
        <!-- Sidebar Navigation -->
        <nav class="sidebar d-flex flex-column" style="width: 260px; position: fixed; height: 100vh; overflow-y: auto;">
            <!-- Logo Section -->
            <div class="logo text-center py-4 px-3">
                <h5 class="mb-1 text-white fw-bold">{{ config('app.name', 'Inventory') }}</h5>
                <small class="text-muted">Management System</small>
            </div>

            <!-- Navigation Links -->
            <div class="flex-grow-1 px-0 py-3">
                <a href="{{ route('dashboard') }}"
                    class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-graph-up"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('products') }}"
                    class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('products*') ? 'active' : '' }}">
                    <i class="bi bi-box"></i>
                    <span>Produk</span>
                </a>

                <a href="{{ route('stock-warehouse') }}"
                    class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('stock-warehouse*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i>
                    <span>Stok Warehouse</span>
                </a>

                <a href="{{ route('movement-items') }}"
                    class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('movement-items*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-left-right"></i>
                    <span>Pergerakan Barang</span>
                </a>

                <a href="{{ route('backup-data') }}"
                    class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('backup-data*') ? 'active' : '' }}">
                    <i class="bi bi-cloud-download"></i>
                    <span>Backup Data</span>
                </a>

                <a href="{{ route('backup-logs') }}"
                    class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('backup-logs*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Log Backup</span>
                </a>

                <hr class="my-3 border-secondary">

                <a href="#" class="nav-link d-flex align-items-center gap-3">
                    <i class="bi bi-gear"></i>
                    <span>Pengaturan</span>
                </a>
            </div>

            <!-- User Section -->
            <div class="px-3 py-3 border-top border-secondary">
                <small class="text-muted d-block mb-2">Logged in as</small>
                <div class="text-white fw-semibold small mb-3">
                    {{ auth()->user()->username ?? (auth()->user()->email ?? (auth()->user()->name ?? 'User')) }}
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mb-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </nav>

        <!-- Main Content Area -->
        <div class="flex-grow-1 d-flex flex-column" style="margin-left: 260px;">
            <!-- Top Navigation Bar -->
            <header class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3 px-4">
                <div class="container-fluid">
                    <h2 class="h4 mb-0">@yield('page-title')</h2>

                    <div class="ms-auto d-flex align-items-center gap-3">
                        @yield('breadcrumb')

                        <!-- User Dropdown -->
                        <div class="dropdown ms-3">
                            <button class="btn btn-link p-0 text-dark" type="button" data-bs-toggle="dropdown">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 36px; height: 36px;">
                                        <span class="text-white fw-bold">
                                            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <h6 class="dropdown-header">{{ auth()->user()->name ?? auth()->user()->email }}
                                    </h6>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="mb-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-grow-1 overflow-auto p-4" style="background-color: #f8f9fa;">
                <div class="container-fluid">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><i class="bi bi-exclamation-circle"></i> Error!</strong>
                            <ul class="mb-0 ms-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-x-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('js')
</body>

</html>
