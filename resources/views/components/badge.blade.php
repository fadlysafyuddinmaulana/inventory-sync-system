{{-- Bootstrap Badge Component --}}
<span class="badge bg-{{ $variant ?? 'primary' }} {{ $class ?? '' }}">
    @if ($icon ?? false)
        <i class="bi bi-{{ $icon }} me-1"></i>
    @endif
    {{ $text ?? ($slot ?? '') }}
</span>
