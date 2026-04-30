<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Produk Odoo</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(180deg, #f8fafc 0%, #eef2ff 100%);
        }

        .hero-band {
            background: linear-gradient(135deg, #0d6efd, #20c997);
            color: #fff;
        }

        .table thead th {
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="hero-band rounded-4 p-4 p-md-5 shadow-sm mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
                <div>
                    <p class="text-white-50 mb-2 text-uppercase small fw-semibold">Inventory Sync System</p>
                    <h1 class="display-6 fw-bold mb-2">Data Produk Odoo</h1>
                    <p class="mb-0 text-white-75">Tabel ini memakai Bootstrap CDN dan DataTables CDN untuk pencarian,
                        paging, dan sorting yang lebih nyaman.</p>
                </div>
                <form action="/backup" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-light btn-lg fw-semibold shadow-sm">Backup ke SQL
                        Server</button>
                </form>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger border-0 shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="produkTable" class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-4">ID Odoo</th>
                                <th>Nama Produk</th>
                                <th class="text-end">Harga Jual</th>
                                <th class="text-center">Stok</th>
                                <th class="text-end pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td class="ps-4 fw-semibold">{{ $product->odoo_product_id }}</td>
                                    <td>{{ $product->product_name ?? 'N/A' }}</td>
                                    <td class="text-end">Rp {{ number_format($product->list_price ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge rounded-pill {{ $product->qty_on_hand <= 0 ? 'text-bg-danger' : 'text-bg-success' }}">
                                            {{ number_format($product->qty_on_hand ?? 0, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        @if ($product->qty_on_hand <= 0)
                                            <span class="badge text-bg-warning">Stok menipis</span>
                                        @elseif ($product->qty_on_hand < 10)
                                            <span class="badge text-bg-info">Perlu dipantau</span>
                                        @else
                                            <span class="badge text-bg-success">Aman</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        Data tidak ditemukan atau koneksi Odoo bermasalah.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0Y3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdlE7N7N6jI5l1xgq" crossorigin="anonymous">
    </script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(function() {
            $('#produkTable').DataTable({
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                order: [
                    [0, 'desc']
                ],
                language: {
                    search: 'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    infoEmpty: 'Tidak ada data yang ditampilkan',
                    zeroRecords: 'Data tidak ditemukan',
                    paginate: {
                        previous: 'Sebelumnya',
                        next: 'Berikutnya'
                    }
                }
            });
        });
    </script>
</body>

</html>
