<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Data Produk Odoo</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">

        <h2 class="mb-4">📦 Data Produk Odoo</h2>

        {{-- Alert Success --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Alert Error --}}
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- Button Backup --}}
        <form action="/backup" method="POST" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-primary">
                🔄 Backup ke SQL Server
            </button>
        </form>

        {{-- Table Card --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="produkTable" class="table table-striped table-bordered align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>ID Odoo</th>
                                <th>Nama Produk</th>
                                <th>Harga Jual</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $p)
                                <tr>
                                    <td class="text-center">{{ $p->odoo_product_id }}</td>
                                    <td>{{ $p->product_name ?? 'N/A' }}</td>
                                    <td class="text-end">
                                        Rp {{ number_format($p->list_price, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="fw-bold {{ $p->qty_on_hand <= 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($p->qty_on_hand, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">
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

    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#produkTable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                language: {
                    search: "🔍 Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    paginate: {
                        next: "Next",
                        previous: "Prev"
                    },
                    zeroRecords: "Data tidak ditemukan"
                }
            });
        });
    </script>

</body>

</html>
