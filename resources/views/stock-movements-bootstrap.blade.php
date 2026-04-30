@extends('layouts.app')

@section('title', 'Pergerakan Barang')
@section('page-title', 'Pergerakan Barang')

@section('content')
    <!-- Filter & Action Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div class="input-group" style="max-width: 300px;">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control border-start-0" id="movementSearch"
                        placeholder="Cari pergerakan...">
                </div>

                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newMovementModal">
                    <i class="bi bi-plus-circle"></i> Buat Pergerakan
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4 gy-3">
        <div class="col-md-6 col-lg-3">
            @include('components.stat-card', [
                'title' => 'Hari Ini',
                'value' => '48',
                'color' => 'blue',
                'icon' => '<i class="bi bi-arrow-left-right"></i>',
            ])
        </div>
        <div class="col-md-6 col-lg-3">
            @include('components.stat-card', [
                'title' => 'Minggu Ini',
                'value' => '328',
                'color' => 'green',
                'icon' => '<i class="bi bi-calendar-week"></i>',
            ])
        </div>
        <div class="col-md-6 col-lg-3">
            @include('components.stat-card', [
                'title' => 'Pending',
                'value' => '12',
                'color' => 'orange',
                'icon' => '<i class="bi bi-hourglass-split"></i>',
            ])
        </div>
        <div class="col-md-6 col-lg-3">
            @include('components.stat-card', [
                'title' => 'Selesai Bulan Ini',
                'value' => '2,485',
                'color' => 'red',
                'icon' => '<i class="bi bi-check-circle"></i>',
            ])
        </div>
    </div>

    <!-- Movement Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-arrow-left-right"></i> Riwayat Pergerakan
            </h5>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID Pergerakan</th>
                        <th>Produk</th>
                        <th class="text-center">Jumlah</th>
                        <th>Dari</th>
                        <th>Ke</th>
                        <th class="text-center">Status</th>
                        <th class="text-end">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="badge bg-secondary">#MOV001</span></td>
                        <td>Laptop Dell XPS 13</td>
                        <td class="text-center"><strong>5</strong></td>
                        <td>Warehouse A - Jakarta</td>
                        <td>Warehouse B - Bandung</td>
                        <td class="text-center">
                            @include('components.badge', [
                                'variant' => 'success',
                                'icon' => 'check-circle',
                                'text' => 'Selesai',
                            ])
                        </td>
                        <td class="text-end text-muted small">2024-04-28</td>
                    </tr>
                    <tr>
                        <td><span class="badge bg-secondary">#MOV002</span></td>
                        <td>Monitor Samsung 27"</td>
                        <td class="text-center"><strong>12</strong></td>
                        <td>Warehouse B - Bandung</td>
                        <td>Warehouse C - Surabaya</td>
                        <td class="text-center">
                            @include('components.badge', [
                                'variant' => 'warning',
                                'icon' => 'hourglass-split',
                                'text' => 'Dalam Transit',
                            ])
                        </td>
                        <td class="text-end text-muted small">2024-04-28</td>
                    </tr>
                    <tr>
                        <td><span class="badge bg-secondary">#MOV003</span></td>
                        <td>Keyboard Mechanical</td>
                        <td class="text-center"><strong>25</strong></td>
                        <td>Warehouse A - Jakarta</td>
                        <td>Warehouse B - Bandung</td>
                        <td class="text-center">
                            @include('components.badge', [
                                'variant' => 'warning',
                                'icon' => 'hourglass-split',
                                'text' => 'Pending',
                            ])
                        </td>
                        <td class="text-end text-muted small">2024-04-27</td>
                    </tr>
                    <tr>
                        <td><span class="badge bg-secondary">#MOV004</span></td>
                        <td>Mouse Logitech</td>
                        <td class="text-center"><strong>50</strong></td>
                        <td>Warehouse C - Surabaya</td>
                        <td>Warehouse A - Jakarta</td>
                        <td class="text-center">
                            @include('components.badge', [
                                'variant' => 'success',
                                'icon' => 'check-circle',
                                'text' => 'Selesai',
                            ])
                        </td>
                        <td class="text-end text-muted small">2024-04-25</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal untuk buat pergerakan baru -->
    <div class="modal fade" id="newMovementModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Buat Pergerakan Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="movementProduct" class="form-label">Produk</label>
                                <select class="form-select" id="movementProduct" required>
                                    <option selected disabled>Pilih produk...</option>
                                    <option>Laptop Dell XPS 13</option>
                                    <option>Monitor Samsung 27"</option>
                                    <option>Keyboard Mechanical</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="movementQty" class="form-label">Jumlah</label>
                                <input type="number" class="form-control" id="movementQty" min="1" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="movementFrom" class="form-label">Dari Warehouse</label>
                                <select class="form-select" id="movementFrom" required>
                                    <option selected disabled>Pilih warehouse...</option>
                                    <option>Warehouse A - Jakarta</option>
                                    <option>Warehouse B - Bandung</option>
                                    <option>Warehouse C - Surabaya</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="movementTo" class="form-label">Ke Warehouse</label>
                                <select class="form-select" id="movementTo" required>
                                    <option selected disabled>Pilih warehouse...</option>
                                    <option>Warehouse A - Jakarta</option>
                                    <option>Warehouse B - Bandung</option>
                                    <option>Warehouse C - Surabaya</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="movementNotes" class="form-label">Catatan</label>
                            <textarea class="form-control" id="movementNotes" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary">Buat Pergerakan</button>
                </div>
            </div>
        </div>
    </div>
@endsection
