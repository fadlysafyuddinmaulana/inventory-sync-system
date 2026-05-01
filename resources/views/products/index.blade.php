@extends('layouts.app')

@section('page_title', 'Products')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Product List</h3>
                        <a href="{{ route('backup-data') }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-save"></i> Backup
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if (isset($error))
                        <div class="alert alert-danger">{{ $error }}</div>
                    @endif

                    @if (count($products) > 0)
                        <div class="table-responsive">
                            <table id="productsTable" class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Product Name</th>
                                        <th>SKU</th>
                                        <th>Price</th>
                                        <th>Quantity On Hand</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>#{{ $product->id }}</td>
                                            <td><strong>{{ $product->name }}</strong></td>
                                            <td>{{ $product->default_code ?? 'N/A' }}</td>
                                            <td>Rp {{ number_format($product->list_price, 0, ',', '.') }}</td>
                                            <td>
                                                <span
                                                    class="badge badge-info">{{ number_format($product->qty_on_hand, 0) }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('products.show', $product->id) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> No products found
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra_css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@endsection

@section('extra_js')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#productsTable').DataTable({
                responsive: true,
                pageLength: 25,
                columnDefs: [{
                    orderable: false,
                    targets: -1
                }]
            });
        });
    </script>
@endsection
