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
                <div class="card-body border-bottom">
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
                </div>

                <!-- Products Content -->
                <div class="card-body">
                    @if (isset($error))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> {{ $error }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (count($products) > 0)
                        <div class="table-responsive">
                            <table id="productsTable" class="table table-striped table-hover table-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 70px;">Image</th>
                                        <th>Product Name</th>
                                        <th style="width: 120px;">SKU</th>
                                        <th style="width: 120px;">Price</th>
                                        <th style="width: 100px;">Qty On Hand</th>
                                        <th style="width: 80px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>
                                                <img src="{{ route('products.image', [$product['id'], 128]) }}"
                                                    alt="{{ $product['name'] }}"
                                                    onerror="this.src='{{ asset(config('odoo.image.fallback', 'images/no-image.png')) }}';"
                                                    style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;"
                                                    loading="lazy">
                                            </td>
                                            <td>
                                                <strong>{{ $product['name'] }}</strong>
                                            </td>
                                            <td>
                                                <code>{{ $product['default_code'] ?? 'N/A' }}</code>
                                            </td>
                                            <td>
                                                <strong>Rp
                                                    {{ number_format($product['list_price'] ?? 0, 0, ',', '.') }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ number_format($product['qty_on_hand'] ?? 0, 0) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('products.show', $product['id']) }}"
                                                    class="btn btn-sm btn-info" title="View Details">
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
                            <i class="fas fa-info-circle"></i>
                            @if ($search)
                                No products found matching "<strong>{{ $search }}</strong>"
                            @else
                                No products found
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra_css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.bootstrap5.min.css">
    <style>
        .input-group {
            max-width: 100%;
        }

        .table-sm td {
            vertical-align: middle;
        }

        .badge {
            font-size: 0.85rem;
            padding: 0.5rem 0.75rem;
        }

        .dataTables_wrapper .dataTables_paginate {
            margin-top: 15px;
        }

        .dataTables_wrapper .dataTables_info {
            padding-top: 15px;
            font-size: 14px;
        }

        .dt-buttons {
            margin-bottom: 15px;
        }

        .btn-group-sm>.btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
    </style>
@endsection

@section('extra_js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#productsTable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                language: {
                    search: "Search products:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ products",
                    infoFiltered: "(filtered from _MAX_ total products)",
                    infoEmpty: "No products found",
                    zeroRecords: "No matching products found",
                    emptyTable: "No products available",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                columnDefs: [{
                        orderable: false,
                        targets: 0
                    }, // Image column
                    {
                        orderable: false,
                        targets: 5
                    } // Action column
                ],
                order: [
                    [1, 'asc']
                ], // Sort by product name by default
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                    '<"row"<"col-sm-12"tr>>' +
                    '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                buttons: [{
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-sm btn-success',
                        exportOptions: {
                            columns: [1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-sm btn-danger',
                        exportOptions: {
                            columns: [1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        className: 'btn btn-sm btn-info',
                        exportOptions: {
                            columns: [1, 2, 3, 4]
                        }
                    }
                ]
            });

            // Add buttons above the table
            var buttons = new $.fn.dataTable.Buttons('#productsTable', {
                buttons: [{
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-sm btn-success',
                        exportOptions: {
                            columns: [1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-sm btn-danger',
                        exportOptions: {
                            columns: [1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        className: 'btn btn-sm btn-info',
                        exportOptions: {
                            columns: [1, 2, 3, 4]
                        }
                    }
                ]
            }).container();

            $(buttons).appendTo($('.dataTables_wrapper .dataTables_filter').parent().prepend(
                '<div class="btn-group btn-group-sm" style="margin-bottom: 15px; margin-right: 10px;"></div>'
            ).children(':first'));
        });
    </script>
@endsection
