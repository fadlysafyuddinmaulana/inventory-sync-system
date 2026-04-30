@extends('layouts.app')

@section('title', 'Produk')
@section('page-title', 'Daftar Produk')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Produk</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="card shadow-sm border-0">
        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-boxes"></i> Daftar Produk
            </h5>
            <button class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Produk
            </button>
        </div>

        <!-- Search & Filter -->
        <div class="card-body border-bottom">
            <div class="row g-3">
                <div class="col-md-8">
                    <input type="text" class="form-control form-control-sm" placeholder="Cari produk...">
                </div>
                <div class="col-md-4">
                    <select class="form-select form-select-sm">
                        <option>Semua Kategori</option>
                        <option>Elektronik</option>
                        <option>Pakaian</option>
                        <option>Makanan</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama Produk</th>
                        <th>SKU</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#001</td>
                        <td><strong>Barang A</strong></td>
                        <td>SKU-001</td>
                        <td><span class="badge bg-info">Elektronik</span></td>
                        <td>Rp 100.000</td>
                        <td><span class="badge bg-success">Aktif</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>#002</td>
                        <td><strong>Barang B</strong></td>
                        <td>SKU-002</td>
                        <td><span class="badge bg-warning">Pakaian</span></td>
                        <td>Rp 50.000</td>
                        <td><span class="badge bg-success">Aktif</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
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
<tbody class="divide-y divide-gray-200 dark:divide-gray-700">
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">001</td>
        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">Laptop Dell XPS</td>
        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">DELL-XPS-13</td>
        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">Rp 12.000.000</td>
        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">15</td>
        <td class="px-6 py-4 text-sm">
            <span
                class="px-3 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full text-xs font-medium">Aktif</span>
        </td>
        <td class="px-6 py-4 text-sm">
            <button class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 text-sm">Edit</button>
            <span class="mx-2 text-gray-300">/</span>
            <button class="text-red-600 hover:text-red-900 dark:hover:text-red-400 text-sm">Hapus</button>
        </td>
    </tr>
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">002</td>
        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">Monitor LG 27"</td>
        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">LG-MON-27</td>
        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">Rp 3.500.000</td>
        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">8</td>
        <td class="px-6 py-4 text-sm">
            <span
                class="px-3 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full text-xs font-medium">Aktif</span>
        </td>
        <td class="px-6 py-4 text-sm">
            <button class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 text-sm">Edit</button>
            <span class="mx-2 text-gray-300">/</span>
            <button class="text-red-600 hover:text-red-900 dark:hover:text-red-400 text-sm">Hapus</button>
        </td>
    </tr>
</tbody>
</table>
</div>

<!-- Pagination -->
<div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
    <p class="text-sm text-gray-600 dark:text-gray-400">Menampilkan 1-2 dari 2 produk</p>
    <div class="flex gap-2">
        <button
            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors disabled">←
            Sebelumnya</button>
        <button
            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors disabled">Berikutnya
            →</button>
    </div>
</div>
</div>
