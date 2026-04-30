{{-- Bootstrap Button Component --}}
<button type="{{ $type ?? 'button' }}"
    class="btn btn-{{ $variant ?? 'primary' }} btn-{{ $size ?? 'md' }} {{ $class ?? '' }}"
    @if ($disabled ?? false) disabled @endif
    @if ($href ?? false) onclick="window.location.href='{{ $href }}'" @endif {{ $attributes }}>
    @if ($icon ?? false)
        <i class="bi bi-{{ $icon }} me-2"></i>
    @endif
    {{ $slot }}
</button>
