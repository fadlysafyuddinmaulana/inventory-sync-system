@extends('layouts.app')

@section('page_title', 'Products')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-box"></i> Product List
                            @if ($total > 0)
                                <small class="text-muted">({{ $total }} products)</small>
                            @endif
                        </h3>
                        <a href="{{ route('backup-data') }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-save"></i> Backup
                        </a>
                    </div>
                </div>

                <!-- Search & Filter Section -->
                {{-- <div class="card-body border-bottom">
                    <form method="GET" action="{{ route('products') }}" class="form-inline">
                        <div class="input-group" style="width: 100%;">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search by product name or SKU..." value="{{ $search }}"
                                autocomplete="off">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i> Search
                                </button>
                                @if ($search)
                                    <a href="{{ route('products') }}" class="btn btn-secondary ml-2">
                                        <i class="fas fa-times"></i> Clear
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div> --}}

                <!-- Products Content -->
                <div class="card-body">
                    <table id="productsTable" class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>SKU</th>
                                <th>Price</th>
                                <th>Qty On Hand</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td style="width:120px;">
                                        @if (!empty($product['image_url']))
                                            <img src="{{ $product['image_url'] }}"
                                                alt="{{ data_get($product, 'name', '') }}" class="img-thumbnail"
                                                style="width:120px;height:120px;object-fit:cover;" />
                                        @else
                                            <div class="img-placeholder"
                                                style="width:120px;height:120px;display:flex;align-items:center;justify-content:center;background:#f0f0f0;border:1px solid #ddd;border-radius:4px;font-size:11px;color:#999;">
                                                NO IMG</div>
                                        @endif
                                    </td>
                                    <td>{{ data_get($product, 'name', 'N/A') }}</td>
                                    <td>{{ data_get($product, 'default_code', 'N/A') }}</td>
                                    <td>{{ data_get($product, 'list_price', 'N/A') }}</td>
                                    <td>{{ data_get($product, 'qty_on_hand', 'N/A') }}</td>
                                    <td>
                                        <a href="{{ route('products.show', data_get($product, 'id')) }}"
                                            class="btn btn-sm btn-info" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No products found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra_css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endsection

@section('extra_js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#productsTable').DataTable({
                responsive: true,
                paging: true,
                pageLength: 10,
                ordering: true,
                columnDefs: [{
                    orderable: false,
                    targets: []
                }]
            });
        });
    </script>
@endsection
