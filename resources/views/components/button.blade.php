{{-- Responsive Button Component --}}
@props([
    'variant' => 'primary', // primary, secondary, ghost, danger
    'size' => 'md', // sm, md, lg
    'type' => 'button',
    'disabled' => false,
])

@php
    $baseClasses = 'btn';
    $variantClasses = match ($variant) {
        'primary' => 'btn-primary',
        'secondary' => 'btn-secondary',
        'ghost' => 'btn-ghost',
        'danger' => 'btn-danger',
        default => 'btn-primary',
    };
    $sizeClasses = match ($size) {
        'sm' => 'btn-sm',
        'lg' => 'btn-lg',
        default => '',
    };
@endphp

<button
    type="{{ $type }}"
    class="{{ $baseClasses }} {{ $variantClasses }} {{ $sizeClasses }}"
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge() }}
>
    {{ $slot }}
</button>
