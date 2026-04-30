{{-- Bootstrap Card Component --}}
<div class="card shadow-sm border-0 h-100">
    @if ($showHeader ?? true)
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                @if ($icon ?? false)
                    <i class="bi bi-{{ $icon }}"></i>
                @endif
                {{ $title }}
            </h5>
        </div>
    @endif
    <div class="card-body">
        {{ $slot }}
    </div>
    @if ($showFooter ?? false)
        <div class="card-footer bg-light">
            {{ $footer }}
        </div>
    @endif
</div>
