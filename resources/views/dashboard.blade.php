@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')
    <!-- Statistics Row -->
    <div class="row mb-4 gy-3">
        <!-- Total Produk -->
        <div class="col-md-6 col-lg-3">
            @include('components.stat-card', [
                'title' => 'Total Produk',
                'value' => '1,234',
                'subtitle' => '↑ 12% dari bulan lalu',
                'subtitleColor' => 'success',
                'color' => 'blue',
                'icon' => '<i class="bi bi-box"></i>',
            ])
        </div>

        <!-- Total Stok -->
        <div class="col-md-6 col-lg-3">
            @include('components.stat-card', [
                'title' => 'Total Stok',
                'value' => '5,680',
                'subtitle' => '↑ 8% dari bulan lalu',
                'subtitleColor' => 'success',
                'color' => 'green',
                'icon' => '<i class="bi bi-building"></i>',
            ])
        </div>

        <!-- Pergerakan Hari Ini -->
        <div class="col-md-6 col-lg-3">
            @include('components.stat-card', [
                'title' => 'Pergerakan Hari Ini',
                'value' => '328',
                'subtitle' => '↑ 5% dari hari kemarin',
                'subtitleColor' => 'warning',
                'color' => 'orange',
                'icon' => '<i class="bi bi-arrow-left-right"></i>',
            ])
        </div>

        <!-- Backup Status -->
        <div class="col-md-6 col-lg-3">
            @include('components.stat-card', [
                'title' => 'Backup Terakhir',
                'value' => 'Hari Ini',
                'subtitle' => '✓ Sukses',
                'subtitleColor' => 'success',
                'color' => 'red',
                'icon' => '<i class="bi bi-cloud-check"></i>',
            ])
        </div>
    </div>

    <!-- Charts & Activity Section -->
    <div class="row mb-4 gy-3">
        <!-- Stock Movement Chart -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-bar-chart"></i> Pergerakan Stok (7 Hari Terakhir)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center rounded bg-light" style="height: 300px;">
                        <div class="text-center text-muted">
                            <i class="bi bi-graph-up" style="font-size: 3rem;"></i>
                            <p class="mt-2">Grafik akan ditampilkan di sini</p>
                            <small>Integrasikan dengan Chart.js atau library lainnya</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Summary -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-bell"></i> Ringkasan Aktivitas
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action px-3 py-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Produk Baru Ditambahkan</h6>
                                    <small class="text-muted">2 jam yang lalu</small>
                                </div>
                                @include('components.badge', ['variant' => 'primary', 'text' => '5'])
                            </div>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action px-3 py-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Stok Diperbarui</h6>
                                    <small class="text-muted">30 menit yang lalu</small>
                                </div>
                                @include('components.badge', ['variant' => 'success', 'text' => '12'])
                            </div>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action px-3 py-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Pergerakan Barang</h6>
                                    <small class="text-muted">Hari ini</small>
                                </div>
                                @include('components.badge', ['variant' => 'warning', 'text' => '8'])
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history"></i> Aktivitas Terbaru
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Jenis Aktivitas</th>
                                <th>Detail</th>
                                <th>Status</th>
                                <th class="text-end">Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <i class="bi bi-plus-circle text-success"></i>
                                    Produk Ditambahkan
                                </td>
                                <td>3 produk baru dari kategori Electronics</td>
                                <td>
                                    @include('components.badge', [
                                        'variant' => 'success',
                                        'text' => 'Selesai',
                                    ])
                                </td>
                                <td class="text-end text-muted small">2 jam yang lalu</td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="bi bi-pencil-square text-primary"></i>
                                    Stok Diperbarui
                                </td>
                                <td>Update stok untuk 12 item</td>
                                <td>
                                    @include('components.badge', ['variant' => 'info', 'text' => 'Proses'])
                                </td>
                                <td class="text-end text-muted small">30 menit yang lalu</td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="bi bi-arrow-left-right text-warning"></i>
                                    Pergerakan Barang
                                </td>
                                <td>Transfer 8 item ke warehouse B</td>
                                <td>
                                    @include('components.badge', [
                                        'variant' => 'warning',
                                        'text' => 'Pending',
                                    ])
                                </td>
                                <td class="text-end text-muted small">1 jam yang lalu</td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="bi bi-cloud-check text-success"></i>
                                    Backup Selesai
                                </td>
                                <td>Backup database sukses</td>
                                <td>
                                    @include('components.badge', [
                                        'variant' => 'success',
                                        'text' => 'Sukses',
                                    ])
                                </td>
                                <td class="text-end text-muted small">Hari ini</td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="bi bi-exclamation-triangle text-danger"></i>
                                    Stok Rendah
                                </td>
                                <td>5 produk stok di bawah minimum</td>
                                <td>
                                    @include('components.badge', [
                                        'variant' => 'danger',
                                        'text' => 'Alert',
                                    ])
                                </td>
                                <td class="text-end text-muted small">Kemarin</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
