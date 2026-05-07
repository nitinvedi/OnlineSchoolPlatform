<x-app-layout>
    <div class="result-center">
        <svg class="result-check" viewBox="0 0 120 120">
            <circle cx="60" cy="60" r="40" stroke="#FF3B30" stroke-width="6" fill="none" stroke-linecap="round" stroke-linejoin="round"></circle>
            <line x1="44" y1="44" x2="76" y2="76" stroke="#FF3B30" stroke-width="6" stroke-linecap="round"></line>
            <line x1="76" y1="44" x2="44" y2="76" stroke="#FF3B30" stroke-width="6" stroke-linecap="round"></line>
        </svg>

        <h2 class="result-headline text-[#FF3B30]">PAYMENT UNSUCCESSFUL</h2>
        <p class="text-[13px] text-[#555]">Your card was declined. Please check the details or try a different card.</p>

        <div class="mt-6 flex gap-3 justify-center">
            <a href="{{ route('checkout') }}" class="cta-primary">TRY AGAIN →</a>
            <button class="cta-ghost">USE DIFFERENT CARD</button>
        </div>
    </div>
</x-app-layout>
