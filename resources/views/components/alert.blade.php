{{-- Bootstrap Alert Component --}}
<div class="alert alert-{{ $type ?? 'info' }} alert-dismissible fade show d-flex align-items-start" role="alert">
    <i class="bi bi-{{ $icon ?? 'info-circle' }} me-2 flex-shrink-0 mt-1"></i>
    <div class="flex-grow-1">
        @if ($title ?? false)
            <strong>{{ $title }}</strong>
            {{ $message ?? ($slot ?? '') }}
        @else
            {{ $message ?? ($slot ?? '') }}
        @endif
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
