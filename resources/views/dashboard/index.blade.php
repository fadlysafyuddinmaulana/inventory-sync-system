@extends('layouts.app')

@section('page_title', 'Dashboard')

@section('content')
    <div class="row">
        <!-- Total Products -->
        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalProducts }}</h3>
                    <p>Total Products</p>
                </div>
                <div class="icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <a href="{{ route('products') }}" class="small-box-footer">More info <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Total Stock -->
        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($totalStocks, 0) }}</h3>
                    <p>Total Stock Quantity</p>
                </div>
                <div class="icon">
                    <i class="fas fa-cubes"></i>
                </div>
                <a href="{{ route('stock-warehouse') }}" class="small-box-footer">More info <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Total Warehouses -->
        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalWarehouses }}</h3>
                    <p>Total Warehouses</p>
                </div>
                <div class="icon">
                    <i class="fas fa-warehouse"></i>
                </div>
                <a href="{{ route('stock-warehouse') }}" class="small-box-footer">More info <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Total Movements -->
        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $totalMovements }}</h3>
                    <p>Total Movements</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <a href="{{ route('movement-items') }}" class="small-box-footer">More info <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    @if (isset($error))
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger">
                    <strong>Error:</strong> {{ $error }}
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- Recent Movements -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Movements</h3>
                </div>
                <div class="card-body">
                    <table id="recentMovementsTable" class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentMovements as $move)
                                <tr>
                                    <td>{{ $move->product_name ?? 'N/A' }}</td>
                                    <td>{{ $move->quantity_done }}</td>
                                    <td>{{ $move->source_location ?? 'N/A' }}</td>
                                    <td>{{ $move->destination_location ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-success">{{ ucfirst($move->state) }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($move->create_date)->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No recent movements</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Warehouse Summary -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Warehouse Summary</h3>
                </div>
                <div class="card-body">
                    @forelse($warehouseSummary as $warehouse)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $warehouse->warehouse_name ?? 'Unknown' }}</strong>
                                <span class="badge badge-primary">{{ number_format($warehouse->total_quantity, 0) }}</span>
                            </div>
                            <small class="text-muted">{{ $warehouse->total_lines }} items</small>
                            <div class="progress progress-sm mt-2">
                                @php
                                    $percent =
                                        $totalStocks > 0
                                            ? min(($warehouse->total_quantity / $totalStocks) * 100, 100)
                                            : 0;
                                @endphp
                                <div class="progress-bar" style="width: {{ round($percent, 2) }}%">
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">No warehouse data available</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @section('extra_css')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    @endsection

    @section('extra_js')
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#recentMovementsTable').DataTable({
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

@endsection
