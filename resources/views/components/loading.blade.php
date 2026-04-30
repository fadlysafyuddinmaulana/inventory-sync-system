{{-- Bootstrap Loading Spinner Component --}}
<div class="d-flex justify-content-center align-items-center{{ $fullHeight ?? false ? ' min-vh-100' : '' }}">
    <div class="text-center">
        <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
        @if ($message ?? false)
            <p class="text-muted">{{ $message }}</p>
        @else
            <p class="text-muted">Loading...</p>
        @endif
    </div>
</div>
