@extends('layouts.app')

@section('title', 'Stok Warehouse')
@section('page-title', 'Stok Warehouse')

@section('content')
    <!-- Filter & Action Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div class="input-group" style="max-width: 300px;">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control border-start-0" id="warehouseSearch"
                        placeholder="Cari warehouse...">
                </div>

                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWarehouseModal">
                    <i class="bi bi-plus-circle"></i> Tambah Warehouse
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4 gy-3">
        <div class="col-md-6 col-lg-3">
            @include('components.stat-card', [
                'title' => 'Total Warehouse',
                'value' => '12',
                'color' => 'blue',
                'icon' => '<i class="bi bi-building"></i>',
            ])
        </div>
        <div class="col-md-6 col-lg-3">
            @include('components.stat-card', [
                'title' => 'Total Stok',
                'value' => '25,480',
                'color' => 'green',
                'icon' => '<i class="bi bi-boxes"></i>',
            ])
        </div>
        <div class="col-md-6 col-lg-3">
            @include('components.stat-card', [
                'title' => 'Warehouse Penuh',
                'value' => '3',
                'color' => 'orange',
                'icon' => '<i class="bi bi-exclamation-triangle"></i>',
            ])
        </div>
        <div class="col-md-6 col-lg-3">
            @include('components.stat-card', [
                'title' => 'Warehouse Kosong',
                'value' => '2',
                'color' => 'red',
                'icon' => '<i class="bi bi-x-circle"></i>',
            ])
        </div>
    </div>

    <!-- Warehouse Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-building"></i> Daftar Warehouse
            </h5>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nama Warehouse</th>
                        <th>Lokasi</th>
                        <th class="text-center">Total Item</th>
                        <th class="text-center">Kapasitas</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Warehouse A - Jakarta</strong></td>
                        <td>Jl. Industri No. 1, Jakarta</td>
                        <td class="text-center"><span class="badge bg-secondary">5,240</span></td>
                        <td>
                            <div class="progress" style="height: 24px;">
                                <div class="progress-bar bg-success" style="width: 75%;">75%</div>
                            </div>
                        </td>
                        <td class="text-center">
                            @include('components.badge', [
                                'variant' => 'success',
                                'icon' => 'check-circle',
                                'text' => 'Aktif',
                            ])
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <button class="btn btn-outline-primary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Warehouse B - Bandung</strong></td>
                        <td>Jl. Logistik No. 5, Bandung</td>
                        <td class="text-center"><span class="badge bg-secondary">8,920</span></td>
                        <td>
                            <div class="progress" style="height: 24px;">
                                <div class="progress-bar bg-warning" style="width: 90%;">90%</div>
                            </div>
                        </td>
                        <td class="text-center">
                            @include('components.badge', [
                                'variant' => 'warning',
                                'icon' => 'exclamation-circle',
                                'text' => 'Penuh',
                            ])
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <button class="btn btn-outline-primary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Warehouse C - Surabaya</strong></td>
                        <td>Jl. Gudang No. 12, Surabaya</td>
                        <td class="text-center"><span class="badge bg-secondary">3,150</span></td>
                        <td>
                            <div class="progress" style="height: 24px;">
                                <div class="progress-bar bg-danger" style="width: 95%;">95%</div>
                            </div>
                        </td>
                        <td class="text-center">
                            @include('components.badge', [
                                'variant' => 'danger',
                                'icon' => 'exclamation-triangle',
                                'text' => 'Sangat Penuh',
                            ])
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <button class="btn btn-outline-primary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal untuk tambah warehouse -->
    <div class="modal fade" id="addWarehouseModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Warehouse Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="warehouseName" class="form-label">Nama Warehouse</label>
                            <input type="text" class="form-control" id="warehouseName" required>
                        </div>
                        <div class="mb-3">
                            <label for="warehouseLocation" class="form-label">Lokasi</label>
                            <input type="text" class="form-control" id="warehouseLocation" required>
                        </div>
                        <div class="mb-3">
                            <label for="warehouseCapacity" class="form-label">Kapasitas</label>
                            <input type="number" class="form-control" id="warehouseCapacity" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection
