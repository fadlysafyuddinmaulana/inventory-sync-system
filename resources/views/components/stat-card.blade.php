{{-- Bootstrap Stat Card Component --}}
<div class="stat-card {{ $color ?? 'blue' }}">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <p class="stat-label">{{ $title }}</p>
            <p class="stat-value">{{ $value }}</p>
            @if ($subtitle ?? false)
                <small class="text-{{ $subtitleColor ?? 'success' }}">
                    {{ $subtitle }}
                </small>
            @endif
        </div>
        <div class="stat-icon {{ $color ?? 'blue' }}">
            {!! $icon !!}
        </div>
    </div>
</div>
