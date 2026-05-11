{{-- Toast Notification Component --}}
@props([
    'id' => 'toast-' . uniqid(),
])

<div
    x-data="toast()"
    x-init="$watch('visible', value => {
        if (!value) setTimeout(() => { $el.style.display = 'none'; }, 300);
    })"
    :style="!visible && 'display: none;'"
    :class="getClasses()"
    role="alert"
    aria-live="polite"
    x-transition
>
    <div class="flex items-center justify-between gap-lg">
        <span x-text="message"></span>
        <button
            @click="hide()"
            class="text-white hover:text-gray-200 text-xl font-bold"
            aria-label="Close notification"
        >
            ×
        </button>
    </div>
</div>

<script>
    // Usage: window.showToast('Success!', 'success', 3000)
    window.showToast = (message, type = 'info', duration = 3000) => {
        const toast = document.querySelector('[x-data*="toast"]');
        if (toast && toast.__x_dataStack) {
            const toastData = toast.__x_dataStack[0];
            toastData.show(message, type, duration);
        }
    };
</script>
