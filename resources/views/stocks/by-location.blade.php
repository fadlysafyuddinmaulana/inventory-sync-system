@extends('layouts.app')

@section('page_title', 'Stock by Location')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Stock by Location</h3>
                </div>
                <div class="card-body">
                    @if (isset($error))
                        <div class="alert alert-danger">{{ $error }}</div>
                    @endif

                    @if (count($locations) > 0)
                        <div class="table-responsive">
                            <table id="stocksByLocationTable" class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Location</th>
                                        <th>Total Lines</th>
                                        <th>Total Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($locations as $location)
                                        <tr>
                                            <td><strong>{{ $location->location_name }}</strong></td>
                                            <td>
                                                <span class="badge badge-secondary">{{ $location->total_lines }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-info">{{ number_format($location->total_quantity, 0) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> No locations found
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
            $('#stocksByLocationTable').DataTable({
                responsive: true,
                pageLength: 25,
            });
        });
    </script>
@endsection
