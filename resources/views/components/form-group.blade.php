{{-- Accessible Form Group Component --}}
@props([
    'label' => null,
    'name' => '',
    'required' => false,
    'error' => null,
    'help' => null,
])

<div class="form-group">
    @if ($label)
        <label for="{{ $name }}" class="form-label {{ $required ? 'form-label-required' : '' }}">
            {{ $label }}
        </label>
    @endif

    {{ $slot }}

    @if ($error)
        <span class="form-error" id="{{ $name }}-error">
            {{ $error }}
        </span>
    @endif

    @if ($help && !$error)
        <span class="form-help" id="{{ $name }}-help">
            {{ $help }}
        </span>
    @endif
</div>
