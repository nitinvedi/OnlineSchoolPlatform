{{-- Accessible Form Input Component --}}
@props([
    'type' => 'text',
    'name' => '',
    'value' => '',
    'placeholder' => '',
    'disabled' => false,
    'error' => false,
    'ariaLabel' => '',
    'ariaDescribedBy' => '',
])

<input
    type="{{ $type }}"
    name="{{ $name }}"
    id="{{ $name }}"
    value="{{ $value }}"
    placeholder="{{ $placeholder }}"
    class="form-input {{ $error ? 'error' : '' }}"
    {{ $disabled ? 'disabled' : '' }}
    {{ $ariaLabel ? 'aria-label="' . $ariaLabel . '"' : '' }}
    {{ $ariaDescribedBy ? 'aria-describedby="' . $ariaDescribedBy . '"' : '' }}
    aria-invalid="{{ $error ? 'true' : 'false' }}"
    {{ $attributes->merge() }}
/>
