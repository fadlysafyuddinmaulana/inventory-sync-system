@extends('layouts.app')

@section('page_title', 'Pergerakan Barang')

@section('content')
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Movement Filter</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('movement-items') }}" class="form-inline">
                        <div class="form-group mr-3">
                            <label for="status" class="mr-2">Status:</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="done" {{ $statusFilter == 'done' ? 'selected' : '' }}>Done</option>
                                <option value="pending" {{ $statusFilter == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="cancelled" {{ $statusFilter == 'cancelled' ? 'selected' : '' }}>Cancelled
                                </option>
                            </select>
                        </div>
                        <div class="form-group mr-3">
                            <label for="search" class="mr-2">Search:</label>
                            <input type="text" name="search" id="search" class="form-control"
                                placeholder="Product name" value="{{ $search }}">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Movement List</h3>
                </div>
                <div class="card-body">
                    @if (isset($error))
                        <div class="alert alert-danger">{{ $error }}</div>
                    @endif

                    @if (count($movements) > 0)
                        <div class="table-responsive">
                            <table id="movementsTable" class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($movements as $move)
                                        <tr>
                                            <td>#{{ $move->id }}</td>
                                            <td><strong>{{ $move->product_name ?? 'N/A' }}</strong></td>
                                            <td>
                                                <span
                                                    class="badge badge-warning">{{ number_format($move->quantity_done, 0) }}</span>
                                            </td>
                                            <td>{{ $move->source_location ?? 'N/A' }}</td>
                                            <td>{{ $move->destination_location ?? 'N/A' }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $move->state == 'done' ? 'badge-success' : 'badge-secondary' }}">
                                                    {{ ucfirst($move->state) }}
                                                </span>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($move->create_date)->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> No movements found
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
            $('#movementsTable').DataTable({
                responsive: true,
                pageLength: 20,
                columnDefs: [{
                    orderable: false,
                    targets: []
                }]
            });
        });
    </script>
@endsection
