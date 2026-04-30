@extends('layouts.app')

@section('title', 'Log Backup')
@section('page-title', 'Log Backup')

@section('content')
    <!-- Filter & Action Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div class="input-group" style="max-width: 300px;">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control border-start-0" id="logSearch" placeholder="Cari log...">
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary" data-bs-toggle="offcanvas" data-bs-target="#filterLogs">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <button class="btn btn-outline-primary">
                        <i class="bi bi-download"></i> Export Logs
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4 gy-3">
        <div class="col-md-6 col-lg-3">
            @include('components.stat-card', [
                'title' => 'Total Logs',
                'value' => '1,245',
                'color' => 'blue',
                'icon' => '<i class="bi bi-file-text"></i>',
            ])
        </div>
        <div class="col-md-6 col-lg-3">
            @include('components.stat-card', [
                'title' => 'Sukses',
                'value' => '1,198',
                'subtitle' => '96.2%',
                'subtitleColor' => 'success',
                'color' => 'green',
                'icon' => '<i class="bi bi-check-circle"></i>',
            ])
        </div>
        <div class="col-md-6 col-lg-3">
            @include('components.stat-card', [
                'title' => 'Peringatan',
                'value' => '35',
                'subtitle' => '2.8%',
                'subtitleColor' => 'warning',
                'color' => 'orange',
                'icon' => '<i class="bi bi-exclamation-triangle"></i>',
            ])
        </div>
        <div class="col-md-6 col-lg-3">
            @include('components.stat-card', [
                'title' => 'Gagal',
                'value' => '12',
                'subtitle' => '1.0%',
                'subtitleColor' => 'danger',
                'color' => 'red',
                'icon' => '<i class="bi bi-x-circle"></i>',
            ])
        </div>
    </div>

    <!-- Log Status Overview -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-bar-chart"></i> Statistik Log 7 Hari Terakhir
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center rounded bg-light" style="height: 250px;">
                        <div class="text-center text-muted">
                            <i class="bi bi-graph-up" style="font-size: 3rem;"></i>
                            <p class="mt-2">Grafik akan ditampilkan di sini</p>
                            <small>Integrasikan dengan Chart.js atau library lainnya</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-speedometer2"></i> Performa Backup
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <label class="small">Rata-rata Waktu Backup</label>
                            <span class="badge bg-primary">45 menit</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <label class="small">Rata-rata Ukuran Backup</label>
                            <span class="badge bg-info">12.1 GB</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <label class="small">Backup Hari Ini</label>
                            <span class="badge bg-success">Selesai</span>
                        </div>
                        <div class="progress" style="height: 24px;">
                            <div class="progress-bar bg-success" style="width: 100%;">100%</div>
                        </div>
                    </div>
                    <div class="alert alert-info small">
                        <i class="bi bi-info-circle"></i> Sistem backup berjalan optimal tanpa masalah yang berarti.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">
                <i class="bi bi-file-earmark-text"></i> Detail Log Backup
            </h5>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal & Waktu</th>
                        <th>Tipe Backup</th>
                        <th>Database</th>
                        <th class="text-end">Durasi</th>
                        <th class="text-end">Ukuran</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <small class="text-muted">2024-04-28 02:00</small>
                        </td>
                        <td><span class="badge bg-secondary">Full</span></td>
                        <td>Produk & Stok</td>
                        <td class="text-end"><code>45m 32s</code></td>
                        <td class="text-end"><strong>12.5 GB</strong></td>
                        <td class="text-center">
                            @include('components.badge', [
                                'variant' => 'success',
                                'icon' => 'check-circle',
                                'text' => 'Sukses',
                            ])
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary" title="View Details">
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <small class="text-muted">2024-04-27 02:00</small>
                        </td>
                        <td><span class="badge bg-secondary">Full</span></td>
                        <td>Produk & Stok</td>
                        <td class="text-end"><code>44m 18s</code></td>
                        <td class="text-end"><strong>11.8 GB</strong></td>
                        <td class="text-center">
                            @include('components.badge', [
                                'variant' => 'success',
                                'icon' => 'check-circle',
                                'text' => 'Sukses',
                            ])
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary" title="View Details">
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <small class="text-muted">2024-04-26 02:00</small>
                        </td>
                        <td><span class="badge bg-info">Incremental</span></td>
                        <td>Log Aktivitas</td>
                        <td class="text-end"><code>12m 45s</code></td>
                        <td class="text-end"><strong>1.2 GB</strong></td>
                        <td class="text-center">
                            @include('components.badge', [
                                'variant' => 'warning',
                                'icon' => 'exclamation-circle',
                                'text' => 'Peringatan',
                            ])
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary" title="View Details">
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <small class="text-muted">2024-04-25 02:00</small>
                        </td>
                        <td><span class="badge bg-secondary">Full</span></td>
                        <td>Produk & Stok</td>
                        <td class="text-end"><code>46m 12s</code></td>
                        <td class="text-end"><strong>11.9 GB</strong></td>
                        <td class="text-center">
                            @include('components.badge', [
                                'variant' => 'success',
                                'icon' => 'check-circle',
                                'text' => 'Sukses',
                            ])
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary" title="View Details">
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <small class="text-muted">2024-04-24 02:00</small>
                        </td>
                        <td><span class="badge bg-secondary">Full</span></td>
                        <td>Produk & Stok</td>
                        <td class="text-end"><code>50m 05s</code></td>
                        <td class="text-end"><strong>12.3 GB</strong></td>
                        <td class="text-center">
                            @include('components.badge', [
                                'variant' => 'danger',
                                'icon' => 'x-circle',
                                'text' => 'Gagal',
                            ])
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary" title="View Details">
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-light py-3 d-flex justify-content-between align-items-center">
            <small class="text-muted">Showing 5 of 1,245 logs</small>
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Filter Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="filterLogs">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Filter Log</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <form id="filterLogForm">
                <div class="mb-3">
                    <label for="filterDate" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="filterDate">
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="statusSuccess">
                        <label class="form-check-label" for="statusSuccess">
                            Sukses
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="statusWarning">
                        <label class="form-check-label" for="statusWarning">
                            Peringatan
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="statusFailed">
                        <label class="form-check-label" for="statusFailed">
                            Gagal
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="filterType" class="form-label">Tipe Backup</label>
                    <select class="form-select" id="filterType">
                        <option value="">Semua</option>
                        <option value="full">Full</option>
                        <option value="incremental">Incremental</option>
                    </select>
                </div>

                <button type="reset" class="btn btn-sm btn-outline-secondary w-100">Reset Filter</button>
            </form>
        </div>
    </div>
@endsection
