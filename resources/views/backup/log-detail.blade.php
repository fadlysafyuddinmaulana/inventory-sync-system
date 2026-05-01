@extends('layouts.app')

@section('page_title', 'Backup Log Details')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Log Information</h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Date:</dt>
                        <dd class="col-sm-9">{{ $log->created_at->format('M d, Y H:i:s') }}</dd>

                        <dt class="col-sm-3">Status:</dt>
                        <dd class="col-sm-9">
                            <span
                                class="badge {{ $log->status == 'success' ? 'badge-success' : ($log->status == 'pending' ? 'badge-warning' : 'badge-danger') }}">
                                {{ ucfirst($log->status) }}
                            </span>
                        </dd>

                        <dt class="col-sm-3">Total Data:</dt>
                        <dd class="col-sm-9">{{ number_format($log->total_data) }}</dd>

                        <dt class="col-sm-3">Message:</dt>
                        <dd class="col-sm-9">{{ $log->message }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Actions</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('backup-logs') }}" class="btn btn-primary btn-block">
                        <i class="fas fa-arrow-left"></i> Back to Logs
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
