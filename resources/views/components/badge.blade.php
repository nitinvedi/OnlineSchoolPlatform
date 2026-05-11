{{-- Badge Component --}}
@props([
    'variant' => 'primary', // primary, success, warning, danger
])

@php
    $variantClasses = match ($variant) {
        'primary' => 'badge-primary',
        'success' => 'badge-success',
        'warning' => 'badge-warning',
        'danger' => 'badge-danger',
        default => 'badge-primary',
    };
@endphp

<span class="badge {{ $variantClasses }}">
    {{ $slot }}
</span>
