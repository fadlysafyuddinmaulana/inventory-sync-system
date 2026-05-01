@extends('layouts.app')

@section('page_title', 'Backup Data')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Backup Odoo to SQL Server</h3>
                </div>
                <div class="card-body">
                    <p>Backup data from Odoo (PostgreSQL) to SQL Server data warehouse including:</p>
                    <ul>
                        <li>Product information from product_template and product_product</li>
                        <li>Stock information from stock_quant</li>
                        <li>Multi-warehouse support from stock_warehouse and stock_location</li>
                    </ul>

                    <div id="backupStatus" class="alert alert-info d-none" role="alert"></div>

                    <button type="button" class="btn btn-primary btn-lg" id="backupBtn" onclick="executeBackup()">
                        <i class="fas fa-save"></i> Start Backup
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Last Backup</h3>
                </div>
                <div class="card-body">
                    @if ($lastBackup)
                        <dl class="row">
                            <dt class="col-sm-6">Status:</dt>
                            <dd class="col-sm-6">
                                <span class="badge badge-success">{{ ucfirst($lastBackup->status) }}</span>
                            </dd>

                            <dt class="col-sm-6">Date:</dt>
                            <dd class="col-sm-6">{{ $lastBackup->completed_at?->format('M d, Y H:i') }}</dd>

                            <dt class="col-sm-6">Data:</dt>
                            <dd class="col-sm-6">{{ number_format($lastBackup->total_data) }}</dd>
                        </dl>
                    @else
                        <p class="text-muted">No backup history</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra_js')
    <script>
        function executeBackup() {
            const btn = document.getElementById('backupBtn');
            const statusDiv = document.getElementById('backupStatus');

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Backing up...';

            fetch('{{ route('backup.execute') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    statusDiv.classList.remove('d-none');
                    if (data.success) {
                        statusDiv.classList.remove('alert-danger');
                        statusDiv.classList.add('alert-success');
                        statusDiv.innerHTML =
                            `<strong>Success!</strong> ${data.message}. Products: ${data.data.product_count}, Stock: ${data.data.stock_count}`;
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        statusDiv.classList.remove('alert-success');
                        statusDiv.classList.add('alert-danger');
                        statusDiv.innerHTML = `<strong>Error!</strong> ${data.message}`;
                    }
                })
                .catch(error => {
                    statusDiv.classList.remove('d-none', 'alert-success');
                    statusDiv.classList.add('alert-danger');
                    statusDiv.innerHTML = `<strong>Error!</strong> ${error.message}`;
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-save"></i> Start Backup';
                });
        }
    </script>
@endsection
