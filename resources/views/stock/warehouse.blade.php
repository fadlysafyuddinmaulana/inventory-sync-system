@extends('layouts.app')

@section('title', 'Stok Warehouse')
@section('page-title', 'Stok Warehouse')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Stok Warehouse</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="card shadow-sm border-0">
        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-warehouse"></i> Daftar Stok Warehouse
            </h5>
            <button class="btn btn-primary btn-sm">
                <i class="fas fa-sync"></i> Update Stok
            </button>
        </div>

        <!-- Filters -->
        <div class="card-body border-bottom">
            <div class="row g-3">
                <div class="col-md-8">
                    <input type="text" class="form-control form-control-sm" placeholder="Cari produk...">
                </div>
                <div class="col-md-4">
                    <select class="form-select form-select-sm">
                        <option>Semua Warehouse</option>
                        <option>Warehouse A</option>
                        <option>Warehouse B</option>
                        <option>Warehouse C</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Produk</th>
                        <th>SKU</th>
                        <th>Warehouse</th>
                        <th>Stok Saat Ini</th>
                        <th>Stok Minimal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Barang A</strong></td>
                        <td>SKU-001</td>
                        <td>Warehouse A</td>
                        <td>150 unit</td>
                        <td>50 unit</td>
                        <td><span class="badge bg-success">Normal</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Barang B</strong></td>
                        <td>SKU-002</td>
                        <td>Warehouse B</td>
                        <td>25 unit</td>
                        <td>30 unit</td>
                        <td><span class="badge bg-warning">Rendah</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Barang C</strong></td>
                        <td>SKU-003</td>
                        <td>Warehouse C</td>
                        <td>5 unit</td>
                        <td>20 unit</td>
                        <td><span class="badge bg-danger">Kritis</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
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
    Lokasi</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
    Terakhir Update</th>
<th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
    Aksi</th>
</tr>
</thead>
<tbody class="divide-y divide-gray-200 dark:divide-gray-700">
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">Laptop Dell XPS</td>
        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">DELL-XPS-13</td>
        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">Warehouse A</td>
        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
            <span
                class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded text-xs font-medium">15</span>
        </td>
        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">Rak A-05</td>
        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">29 Apr 2026</td>
        <td class="px-6 py-4 text-sm">
            <button class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 text-sm">Edit</button>
        </td>
    </tr>
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">Monitor LG 27"</td>
        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">LG-MON-27</td>
        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">Warehouse B</td>
        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
            <span
                class="px-3 py-1 bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200 rounded text-xs font-medium">3</span>
        </td>
        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">Rak B-12</td>
        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">28 Apr 2026</td>
        <td class="px-6 py-4 text-sm">
            <button class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 text-sm">Edit</button>
        </td>
    </tr>
</tbody>
</table>
</div>
</div>
