<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="checkout-layout">
            <div class="checkout-main">
                <h1 class="checkout-title">CHECKOUT</h1>

                <div class="mt-8">
                    <div class="section-label">PAYMENT & ORDER</div>

                    <div class="coupon-row">
                        <input class="coupon-input" placeholder="Coupon code">
                        <button class="coupon-apply">APPLY →</button>
                    </div>
                    <p class="coupon-success mt-2 hidden">COUPON APPLIED — 20% OFF</p>
                </div>

                <div class="mt-8">
                    <div class="section-label">PAYMENT DETAILS</div>
                    <div class="grid grid-cols-1 gap-4">
                        <div class="stripe-element">Card number (Stripe Element)</div>
                        <div class="flex gap-3">
                            <div class="stripe-element flex-1">MM / YY</div>
                            <div class="stripe-element w-36">CVC</div>
                        </div>
                    </div>
                </div>

                <div class="trust-row">🔒 256-BIT SSL · 30-DAY GUARANTEE · INSTANT ACCESS</div>

                <div class="mt-8">
                    <button class="checkout-submit">COMPLETE PURCHASE — $49</button>
                </div>
            </div>

            <aside class="checkout-summary">
                <div class="flex gap-4 items-center">
                    <div class="w-20 h-20 bg-[#0A0A0A] border border-[#1E1E1E] rounded-md"></div>
                    <div>
                        <div class="text-[14px] font-bold">Course Title</div>
                        <div class="text-[12px] text-[#555]">Instructor Name</div>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="flex justify-between text-[#555]"><span>SUBTOTAL</span><span>$49</span></div>
                    <div class="flex justify-between text-[#555]"><span>DISCOUNT</span><span>—</span></div>
                    <div class="flex justify-between text-[#555]"><span>TAX</span><span>$0</span></div>
                    <div class="flex justify-between mt-4 text-[24px] font-display"><span>TOTAL</span><span>$49</span></div>
                </div>

                <div class="mt-6 flex gap-3 items-center text-[#333]">
                    <svg class="w-8 h-6" viewBox="0 0 24 24" fill="currentColor"><rect width="24" height="16" y="4" rx="2"></rect></svg>
                    <svg class="w-8 h-6" viewBox="0 0 24 24" fill="currentColor"><rect width="24" height="16" y="4" rx="2"></rect></svg>
                    <svg class="w-8 h-6" viewBox="0 0 24 24" fill="currentColor"><rect width="24" height="16" y="4" rx="2"></rect></svg>
                </div>
            </aside>
        </div>
    </div>
</x-app-layout>
