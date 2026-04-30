@extends('layouts.app')

@section('title', 'Produk')
@section('page-title', 'Data Produk')

@section('content')
    <!-- Filter & Action Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div class="input-group" style="max-width: 300px;">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control border-start-0" id="productSearch" placeholder="Cari produk...">
                </div>

                <div class="d-flex gap-2">
                    <form action="{{ route('backup.execute') }}" method="POST" class="mb-0">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-cloud-check"></i> Backup ke SQL Server
                        </button>
                    </form>
                    <button class="btn btn-outline-secondary" data-bs-toggle="offcanvas" data-bs-target="#filterPanel">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                </div>
            </div>

            <!-- Alerts -->
            @if (session('success'))
                @include('components.alert', [
                    'type' => 'success',
                    'icon' => 'check-circle',
                    'title' => 'Sukses!',
                    'message' => session('success'),
                ])
            @endif

            @if (session('error'))
                @include('components.alert', [
                    'type' => 'danger',
                    'icon' => 'x-circle',
                    'title' => 'Error!',
                    'message' => session('error'),
                ])
            @endif
        </div>
    </div>

    <!-- Products Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-box"></i> Daftar Produk Odoo
            </h5>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">ID Odoo</th>
                        <th>Nama Produk</th>
                        <th>SKU</th>
                        <th class="text-end">Harga Jual</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td class="ps-4">
                                <span class="badge bg-secondary">{{ $product->odoo_product_id }}</span>
                            </td>
                            <td>
                                <strong>{{ $product->product_name ?? 'N/A' }}</strong>
                            </td>
                            <td>
                                <code>{{ $product->default_code ?? '-' }}</code>
                            </td>
                            <td class="text-end">
                                <strong>Rp {{ number_format($product->list_price ?? 0, 0, ',', '.') }}</strong>
                            </td>
                            <td class="text-center">
                                <span
                                    class="badge rounded-pill {{ $product->qty_on_hand > 10 ? 'bg-success' : ($product->qty_on_hand > 0 ? 'bg-warning' : 'bg-danger') }}">
                                    {{ number_format($product->qty_on_hand ?? 0, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if ($product->qty_on_hand <= 0)
                                    @include('components.badge', [
                                        'variant' => 'danger',
                                        'icon' => 'exclamation-triangle',
                                        'text' => 'Stok Habis',
                                    ])
                                @elseif ($product->qty_on_hand < 10)
                                    @include('components.badge', [
                                        'variant' => 'warning',
                                        'icon' => 'exclamation-circle',
                                        'text' => 'Stok Rendah',
                                    ])
                                @else
                                    @include('components.badge', [
                                        'variant' => 'success',
                                        'icon' => 'check-circle',
                                        'text' => 'Aman',
                                    ])
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-primary" title="View">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                    <p class="mt-3">Data tidak ditemukan atau koneksi Odoo bermasalah.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($products instanceof \Illuminate\Pagination\Paginator)
            <div class="card-footer bg-light py-3 d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Showing <strong>{{ $products->count() }}</strong> of <strong>{{ $products->total() }}</strong> products
                </small>
                <div>
                    {{ $products->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Filter Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="filterPanel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Filter Produk</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <form id="filterForm">
                <div class="mb-3">
                    <label class="form-label">Status Stok</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="filterStockOk" value="ok">
                        <label class="form-check-label" for="filterStockOk">
                            Stok Aman
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="filterStockLow" value="low">
                        <label class="form-check-label" for="filterStockLow">
                            Stok Rendah
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="filterStockEmpty" value="empty">
                        <label class="form-check-label" for="filterStockEmpty">
                            Stok Habis
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="priceRange" class="form-label">Kisaran Harga</label>
                    <div class="input-group input-group-sm mb-2">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="priceMin" placeholder="Min">
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="priceMax" placeholder="Max">
                    </div>
                </div>

                <button type="reset" class="btn btn-sm btn-outline-secondary w-100">Reset Filter</button>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.getElementById('productSearch').addEventListener('keyup', function() {
            // Implement search functionality
            console.log('Searching for:', this.value);
        });
    </script>
@endpush
