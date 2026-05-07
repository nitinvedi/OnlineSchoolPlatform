<x-app-layout>
    <div class="result-center">
        <svg class="result-check" viewBox="0 0 120 120">
            <circle cx="60" cy="60" r="40" stroke="#2255FF" stroke-width="6" fill="none" stroke-linecap="round" stroke-linejoin="round"></circle>
            <polyline points="42,62 56,76 78,48" fill="none" stroke="#F0EDE6" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"></polyline>
        </svg>

        <h2 class="result-headline">ENROLLMENT CONFIRMED</h2>
        <p class="result-sub">WELCOME TO THE COURSE</p>

        <div class="receipt-block">
            <div class="receipt-row"><div class="text-[9px] text-[#333]">ORDER ID</div><div class="text-[12px] text-[#F0EDE6]">#12345</div></div>
            <div class="receipt-row"><div class="text-[9px] text-[#333]">DATE</div><div class="text-[12px] text-[#F0EDE6]">May 6, 2026</div></div>
            <div class="receipt-row"><div class="text-[9px] text-[#333]">AMOUNT</div><div class="text-[12px] text-[#F0EDE6]">$49</div></div>
            <div class="receipt-row"><div class="text-[9px] text-[#333]">EMAIL</div><div class="text-[12px] text-[#F0EDE6]">you@example.com</div></div>
        </div>

        <div class="mt-6 flex gap-3 justify-center">
            <button class="cta-primary">START LEARNING →</button>
            <button class="cta-ghost">DOWNLOAD RECEIPT</button>
        </div>
    </div>
</x-app-layout>
