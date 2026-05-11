{{-- Responsive Card Component --}}
@props([
    'title' => '',
    'footer' => false,
])

<div class="card">
    @if ($title)
        <div class="card-header">
            <h3 class="text-lg font-bold text-slate-900">{{ $title }}</h3>
        </div>
    @endif

    <div class="card-body">
        {{ $slot }}
    </div>

    @if ($footer)
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endif
</div>
