@extends('layouts.app')

@section('title', 'Backup Data')
@section('page-title', 'Backup Data')

@section('content')
    <!-- Info Alert -->
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="bi bi-info-circle me-2"></i>
        <strong>Info:</strong> Backup otomatis berjalan setiap hari pada pukul 02:00 malam.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <!-- Statistics -->
    <div class="row mb-4 gy-3">
        <div class="col-md-6 col-lg-3">
            @include('components.stat-card', [
                'title' => 'Total Backup',
                'value' => '156',
                'color' => 'blue',
                'icon' => '<i class="bi bi-archive"></i>',
            ])
        </div>
        <div class="col-md-6 col-lg-3">
            @include('components.stat-card', [
                'title' => 'Backup Hari Ini',
                'value' => '3',
                'color' => 'green',
                'icon' => '<i class="bi bi-cloud-check"></i>',
            ])
        </div>
        <div class="col-md-6 col-lg-3">
            @include('components.stat-card', [
                'title' => 'Total Ukuran',
                'value' => '185 GB',
                'color' => 'orange',
                'icon' => '<i class="bi bi-hdd"></i>',
            ])
        </div>
        <div class="col-md-6 col-lg-3">
            @include('components.stat-card', [
                'title' => 'Status Terakhir',
                'value' => 'Sukses',
                'color' => 'red',
                'icon' => '<i class="bi bi-check-circle"></i>',
            ])
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex gap-2">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#manualBackupModal">
                    <i class="bi bi-cloud-arrow-down"></i> Backup Manual
                </button>
                <button class="btn btn-secondary">
                    <i class="bi bi-arrow-counterclockwise"></i> Restore Data
                </button>
                <button class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i> Hapus Backup Lama
                </button>
            </div>
        </div>
    </div>

    <!-- Backup Schedule -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-event"></i> Jadwal Backup
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Waktu Backup Otomatis</label>
                        <input type="time" class="form-control" value="02:00" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Frekuensi</label>
                        <select class="form-select" disabled>
                            <option selected>Setiap Hari</option>
                            <option>Setiap Minggu</option>
                            <option>Setiap Bulan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sistem Backup</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="backupProducts" checked disabled>
                            <label class="form-check-label" for="backupProducts">
                                Database Produk
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="backupLogs" checked disabled>
                            <label class="form-check-label" for="backupLogs">
                                Log Aktivitas
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="backupFiles" checked disabled>
                            <label class="form-check-label" for="backupFiles">
                                File & Dokumen
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-shield-check"></i> Status Backup
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <label>Status Keseluruhan</label>
                            @include('components.badge', [
                                'variant' => 'success',
                                'icon' => 'check-circle',
                                'text' => 'Sehat',
                            ])
                        </div>
                        <div class="progress" style="height: 24px;">
                            <div class="progress-bar bg-success" style="width: 100%;">100%</div>
                        </div>
                    </div>
                    <div class="alert alert-success small mb-2">
                        <i class="bi bi-check-circle"></i> Semua backup tersimpan dengan aman
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tr>
                                <td>Backup Terakhir:</td>
                                <td class="text-end">2024-04-28 02:00</td>
                            </tr>
                            <tr>
                                <td>Ukuran Backup:</td>
                                <td class="text-end">12.5 GB</td>
                            </tr>
                            <tr>
                                <td>Lokasi:</td>
                                <td class="text-end">SQL Server 2019</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Backup History -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">
                <i class="bi bi-clock-history"></i> Riwayat Backup
            </h5>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal Backup</th>
                        <th>Waktu</th>
                        <th>Ukuran</th>
                        <th>Jenis</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2024-04-28</td>
                        <td>02:00 AM</td>
                        <td><strong>12.5 GB</strong></td>
                        <td><span class="badge bg-secondary">Full</span></td>
                        <td class="text-center">
                            @include('components.badge', [
                                'variant' => 'success',
                                'icon' => 'check-circle',
                                'text' => 'Sukses',
                            ])
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary" title="Download">
                                <i class="bi bi-download"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>2024-04-27</td>
                        <td>02:00 AM</td>
                        <td><strong>11.8 GB</strong></td>
                        <td><span class="badge bg-secondary">Full</span></td>
                        <td class="text-center">
                            @include('components.badge', [
                                'variant' => 'success',
                                'icon' => 'check-circle',
                                'text' => 'Sukses',
                            ])
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary" title="Download">
                                <i class="bi bi-download"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>2024-04-26</td>
                        <td>02:00 AM</td>
                        <td><strong>11.3 GB</strong></td>
                        <td><span class="badge bg-info">Incremental</span></td>
                        <td class="text-center">
                            @include('components.badge', [
                                'variant' => 'success',
                                'icon' => 'check-circle',
                                'text' => 'Sukses',
                            ])
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary" title="Download">
                                <i class="bi bi-download"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Manual Backup Modal -->
    <div class="modal fade" id="manualBackupModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Backup Manual</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Jenis Backup</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="backupType" id="backupFull"
                                    checked>
                                <label class="form-check-label" for="backupFull">
                                    Full Backup (Semua data)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="backupType" id="backupIncremental">
                                <label class="form-check-label" for="backupIncremental">
                                    Incremental Backup (Hanya perubahan)
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Data yang akan di-backup</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="backupProd2" checked>
                                <label class="form-check-label" for="backupProd2">
                                    Database Produk & Stok
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="backupLogs2" checked>
                                <label class="form-check-label" for="backupLogs2">
                                    Log Aktivitas
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary">Mulai Backup</button>
                </div>
            </div>
        </div>
    </div>
@endsection
