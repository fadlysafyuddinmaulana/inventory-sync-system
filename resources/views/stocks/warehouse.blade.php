@extends('layouts.app')

@section('page_title', 'Stock Warehouse')

@section('content')
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Stock Filter</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('stock-warehouse') }}" class="form-inline" id="filterForm">
                        <div class="form-group mr-3">
                            <label for="warehouse" class="mr-2">Warehouse:</label>
                            <select name="warehouse" id="warehouse" class="form-control">
                                <option value="">All Warehouses</option>
                                @foreach ($warehouses as $wh)
                                    <option value="{{ $wh->id }}"
                                        {{ $selectedWarehouse == $wh->id ? 'selected' : '' }}>
                                        {{ $wh->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mr-3">
                            <label for="search" class="mr-2">Search:</label>
                            <input type="text" name="search" id="search" class="form-control"
                                placeholder="Product name or SKU" value="{{ $search }}">
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('stock.export') }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Export
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Stock List</h3>
                </div>
                <div class="card-body">
                    @if (isset($error))
                        <div class="alert alert-danger">{{ $error }}</div>
                    @endif

                    @if (count($stocks) > 0)
                        <div class="table-responsive">
                            <table id="stocksWarehouseTable" class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Product Name</th>
                                        <th>SKU</th>
                                        <th>Warehouse</th>
                                        <th>Location</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stocks as $stock)
                                        <tr>
                                            <td><strong>{{ $stock->product_name }}</strong></td>
                                            <td>{{ $stock->default_code ?? 'N/A' }}</td>
                                            <td>{{ $stock->warehouse_name ?? 'N/A' }}</td>
                                            <td>{{ $stock->location_name }}</td>
                                            <td>
                                                <span
                                                    class="badge badge-info">{{ number_format($stock->quantity, 0) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> No stocks found
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
            $('#stocksWarehouseTable').DataTable({
                responsive: true,
                pageLength: 25,
            });
        });
    </script>
@endsection
