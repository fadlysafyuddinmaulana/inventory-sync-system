@extends('layouts.app')

@section('title', 'Backup Data')
@section('page-title', 'Backup Data')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Backup Data</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-download text-primary"></i> Backup Manual
                    </h5>
                    <p class="card-text text-muted">
                        Jalankan backup data sekarang untuk memastikan data Anda aman.
                    </p>
                    <button class="btn btn-primary w-100">
                        <i class="fas fa-play"></i> Jalankan Backup Sekarang
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-cog text-success"></i> Pengaturan Backup Otomatis
                    </h5>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="auto-backup" checked>
                        <label class="form-check-label" for="auto-backup">
                            Aktifkan backup otomatis
                        </label>
                    </div>
                    <div>
                        <label for="backup-time" class="form-label small">Waktu backup</label>
                        <input type="time" class="form-control form-control-sm" id="backup-time" value="02:00">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <!-- Header -->
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-list"></i> Daftar Backup
            </h5>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal Backup</th>
                        <th>Ukuran File</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>2024-04-28 02:00</strong></td>
                        <td>250 MB</td>
                        <td><span class="badge bg-success">Sukses</span></td>
                        <td>Backup otomatis</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" title="Download">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>2024-04-27 02:00</strong></td>
                        <td>248 MB</td>
                        <td><span class="badge bg-success">Sukses</span></td>
                        <td>Backup otomatis</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" title="Download">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>2024-04-26 02:00</strong></td>
                        <td>245 MB</td>
                        <td><span class="badge bg-warning">Peringatan</span></td>
                        <td>Backup otomatis (ukuran besar)</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" title="Download">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="card-footer">
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item"><a class="page-link" href="#">Sebelumnya</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">Selanjutnya</a></li>
                </ul>
            </nav>
        </div>
    </div>
@endsection
<th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
    Aksi</th>
</tr>
</thead>
<tbody class="divide-y divide-gray-200 dark:divide-gray-700">
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">29 Apr 2026 - 10:30</td>
        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">245 MB</td>
        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">Otomatis</td>
        <td class="px-6 py-4 text-sm">
            <span
                class="px-3 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full text-xs font-medium">Sukses</span>
        </td>
        <td class="px-6 py-4 text-sm">
            <button class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 text-sm">Download</button>
            <span class="mx-2 text-gray-300">/</span>
            <button class="text-red-600 hover:text-red-900 dark:hover:text-red-400 text-sm">Hapus</button>
        </td>
    </tr>
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">28 Apr 2026 - 02:00</td>
        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">242 MB</td>
        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">Otomatis</td>
        <td class="px-6 py-4 text-sm">
            <span
                class="px-3 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full text-xs font-medium">Sukses</span>
        </td>
        <td class="px-6 py-4 text-sm">
            <button class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 text-sm">Download</button>
            <span class="mx-2 text-gray-300">/</span>
            <button class="text-red-600 hover:text-red-900 dark:hover:text-red-400 text-sm">Hapus</button>
        </td>
    </tr>
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">27 Apr 2026 - 02:00</td>
        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">240 MB</td>
        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">Otomatis</td>
        <td class="px-6 py-4 text-sm">
            <span
                class="px-3 py-1 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 rounded-full text-xs font-medium">Gagal</span>
        </td>
        <td class="px-6 py-4 text-sm">
            <button class="text-gray-400 cursor-not-allowed text-sm">Download</button>
            <span class="mx-2 text-gray-300">/</span>
            <button class="text-red-600 hover:text-red-900 dark:hover:text-red-400 text-sm">Hapus</button>
        </td>
    </tr>
</tbody>
</table>
</div>
</div>
