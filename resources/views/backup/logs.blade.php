@extends('layouts.app')

@section('title', 'Log Backup')
@section('page-title', 'Log Backup')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Log Backup</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="card shadow-sm border-0">
        <!-- Header -->
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-history"></i> Log Backup
            </h5>
            <p class="text-muted small mt-2 mb-0">Riwayat lengkap semua proses backup</p>
        </div>

        <!-- Filters -->
        <div class="card-body border-bottom">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="date" class="form-control form-control-sm">
                </div>
                <div class="col-md-6">
                    <select class="form-select form-select-sm">
                        <option>Semua Status</option>
                        <option>Sukses</option>
                        <option>Gagal</option>
                        <option>Dalam Proses</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="card-body">
            <!-- Log Entry - Success -->
            <div class="d-flex mb-4">
                <div class="me-3">
                    <span class="badge bg-success rounded-circle p-2">
                        <i class="fas fa-check"></i>
                    </span>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1">Backup Sukses</h6>
                    <small class="text-muted d-block mb-2">29 Apr 2026, 10:30:45</small>
                    <div class="alert alert-success alert-sm py-2 px-3" role="alert">
                        <small>
                            <strong>Database berhasil di-backup.</strong> Ukuran file: 245 MB <br>
                            Durasi: 2 menit 15 detik
                        </small>
                    </div>
                </div>
            </div>

            <!-- Log Entry - Success -->
            <div class="d-flex mb-4">
                <div class="me-3">
                    <span class="badge bg-success rounded-circle p-2">
                        <i class="fas fa-check"></i>
                    </span>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1">Backup Sukses</h6>
                    <small class="text-muted d-block mb-2">28 Apr 2026, 10:30:12</small>
                    <div class="alert alert-success alert-sm py-2 px-3" role="alert">
                        <small>
                            <strong>Database berhasil di-backup.</strong> Ukuran file: 243 MB <br>
                            Durasi: 2 menit 8 detik
                        </small>
                    </div>
                </div>
            </div>

            <!-- Log Entry - Warning -->
            <div class="d-flex mb-4">
                <div class="me-3">
                    <span class="badge bg-warning rounded-circle p-2">
                        <i class="fas fa-exclamation"></i>
                    </span>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1">Backup dengan Peringatan</h6>
                    <small class="text-muted d-block mb-2">27 Apr 2026, 10:28:33</small>
                    <div class="alert alert-warning alert-sm py-2 px-3" role="alert">
                        <small>
                            <strong>Backup selesai namun terdapat peringatan.</strong> Ukuran file lebih besar dari
                            biasanya: 280 MB
                        </small>
                    </div>
                </div>
            </div>

            <!-- Log Entry - Failed -->
            <div class="d-flex mb-4">
                <div class="me-3">
                    <span class="badge bg-danger rounded-circle p-2">
                        <i class="fas fa-times"></i>
                    </span>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1">Backup Gagal</h6>
                    <small class="text-muted d-block mb-2">26 Apr 2026, 10:30:00</small>
                    <div class="alert alert-danger alert-sm py-2 px-3" role="alert">
                        <small>
                            <strong>Proses backup gagal.</strong> Ruang penyimpanan tidak mencukup. Mohon bersihkan
                            penyimpanan dan coba lagi.
                        </small>
                    </div>
                </div>
            </div>

            <!-- Log Entry - Success -->
            <div class="d-flex">
                <div class="me-3">
                    <span class="badge bg-success rounded-circle p-2">
                        <i class="fas fa-check"></i>
                    </span>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1">Backup Manual</h6>
                    <small class="text-muted d-block mb-2">25 Apr 2026, 15:45:22</small>
                    <div class="alert alert-success alert-sm py-2 px-3" role="alert">
                        <small>
                            <strong>Backup manual berhasil dilakukan.</strong> Ukuran file: 240 MB
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
</div>
</div>

<!-- Log Entry -->
<div class="flex gap-4">
    <div class="flex flex-col items-center">
        <div class="w-4 h-4 rounded-full bg-red-500 border-4 border-red-100 dark:border-red-900"></div>
        <div class="w-0.5 h-20 bg-gray-200 dark:bg-gray-700 mt-2"></div>
    </div>
    <div class="pb-6">
        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Backup Gagal</h4>
        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">27 Apr 2026, 02:00:05</p>
        <div
            class="mt-2 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded text-xs text-red-800 dark:text-red-200">
            <p>Backup gagal: Ruang penyimpanan tidak mencukup</p>
            <p class="mt-1">Error Code: STORAGE_FULL</p>
        </div>
    </div>
</div>

<!-- Log Entry -->
<div class="flex gap-4">
    <div class="flex flex-col items-center">
        <div class="w-4 h-4 rounded-full bg-blue-500 border-4 border-blue-100 dark:border-blue-900"></div>
    </div>
    <div>
        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Backup Dimulai</h4>
        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">26 Apr 2026, 02:00:00</p>
        <div
            class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded text-xs text-blue-800 dark:text-blue-200">
            <p>Proses backup dimulai secara otomatis</p>
        </div>
    </div>
</div>
</div>
</div>
