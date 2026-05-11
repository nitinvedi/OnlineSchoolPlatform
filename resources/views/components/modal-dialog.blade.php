{{-- Alpine Modal Component --}}
@props([
    'id' => 'modal-' . uniqid(),
    'title' => '',
    'confirmText' => 'Confirm',
    'cancelText' => 'Cancel',
])

<div x-data="modal()" @keydown.escape="closeModal()">
    {{-- Modal Trigger Button (optional) --}}
    {{ $trigger ?? '' }}

    {{-- Modal Overlay --}}
    <div
        x-show="open"
        @click.self="closeModal()"
        class="modal-overlay"
        x-transition
    >
        {{-- Modal Content --}}
        <div class="modal" @click.stop>
            {{-- Header --}}
            <div class="modal-header">
                <h2 class="text-xl font-bold text-slate-900">{{ $title }}</h2>
                <button
                    @click="closeModal()"
                    class="text-slate-500 hover:text-slate-700 text-2xl"
                    aria-label="Close modal"
                >
                    ×
                </button>
            </div>

            {{-- Body --}}
            <div class="modal-body">
                {{ $slot }}
            </div>

            {{-- Footer --}}
            @if ($footer ?? false)
                <div class="modal-footer">
                    {{ $footer }}
                </div>
            @else
                <div class="modal-footer">
                    <button
                        @click="closeModal()"
                        class="btn btn-secondary"
                    >
                        {{ $cancelText }}
                    </button>
                    <button
                        @click="closeModal()"
                        class="btn btn-primary"
                    >
                        {{ $confirmText }}
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
