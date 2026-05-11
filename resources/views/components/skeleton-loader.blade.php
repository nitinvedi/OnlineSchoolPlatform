{{-- Skeleton Loading Card Component --}}
@props([
    'count' => 1,
    'type' => 'card', // card, text, thumbnail, avatar
])

@for ($i = 0; $i < $count; $i++)
    @switch($type)
        @case('card')
            <div class="skeleton-card">
                <div class="skeleton skeleton-thumbnail"></div>
                <div class="p-lg space-y-md">
                    <div class="skeleton skeleton-heading h-6 w-2/3"></div>
                    <div class="skeleton skeleton-text"></div>
                    <div class="skeleton skeleton-text"></div>
                    <div class="flex gap-sm pt-md">
                        <div class="skeleton skeleton-text flex-1"></div>
                        <div class="skeleton skeleton-text w-20"></div>
                    </div>
                </div>
            </div>
            @break

        @case('text')
            <div class="space-y-sm">
                <div class="skeleton skeleton-text"></div>
                <div class="skeleton skeleton-text w-5/6"></div>
                <div class="skeleton skeleton-text w-4/6"></div>
            </div>
            @break

        @case('thumbnail')
            <div class="skeleton skeleton-thumbnail"></div>
            @break

        @case('avatar')
            <div class="skeleton skeleton-avatar"></div>
            @break
    @endswitch
@endfor
