{{-- Bootstrap Data Table Component --}}
<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-table"></i> {{ $title }}
        </h5>
        @if ($showSearch ?? false)
            <div class="input-group" style="width: 250px;">
                <input type="text" class="form-control form-control-sm" placeholder="Search..."
                    id="tableSearch{{ $tableId ?? 'table' }}">
            </div>
        @endif
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    @foreach ($headers as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                    @if ($showActions ?? false)
                        <th class="text-center">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                {{ $slot }}
            </tbody>
        </table>
    </div>
    @if ($paginate ?? false)
        <div class="card-footer bg-light d-flex justify-content-between align-items-center py-3">
            <small class="text-muted">
                Showing <strong>{{ $items->count() }}</strong> of <strong>{{ $items->total() }}</strong> items
            </small>
            <div>
                {{ $items->links() }}
            </div>
        </div>
    @endif
</div>
