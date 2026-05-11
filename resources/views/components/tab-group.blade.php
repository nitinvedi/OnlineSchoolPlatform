{{-- Alpine Tab Group Component --}}
@props([
    'tabs' => [], // Array of ['label' => '...', 'id' => '...']
])

<div x-data="tabs(0)">
    {{-- Tab Buttons --}}
    <div class="tabs">
        @foreach ($tabs as $index => $tab)
            <button
                type="button"
                x-ref="tab-{{ $index }}"
                @click="setActiveTab({{ $index }})"
                :class="isTabActive({{ $index }}) ? 'active' : ''"
                class="tab"
                :aria-selected="isTabActive({{ $index }})"
                aria-controls="{{ $tab['id'] ?? 'tab-panel-' . $index }}"
            >
                {{ $tab['label'] }}
            </button>
        @endforeach
    </div>

    {{-- Tab Panels --}}
    <div class="mt-lg">
        @foreach ($tabs as $index => $tab)
            <div
                id="{{ $tab['id'] ?? 'tab-panel-' . $index }}"
                x-show="isTabActive({{ $index }})"
                role="tabpanel"
                :aria-labelledby="'tab-{{ $index }}'"
                x-transition
            >
                {{ $slot }}
            </div>
        @endforeach
    </div>
</div>
