<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="pricing-header">CHOOSE YOUR PLAN</h1>

        <div class="mt-8 flex items-center justify-center">
            <div x-data="{ annual: false }" class="plan-toggle">
                <button :class="{'active': !annual}" @click="annual = false">MONTHLY</button>
                <div class="px-2 py-1 border-l border-r border-[#1E1E1E]">·</div>
                <button :class="{'active': annual}" @click="annual = true">ANNUAL <span x-show="annual" class="save-badge">SAVE 40%</span></button>
            </div>
        </div>

        <div class="mt-10 plans-grid">
            <div class="plan-card">
                <div class="plan-name">Free</div>
                <div class="plan-tagline">Get started</div>
                <div class="mt-6 plan-price">$0 <span class="plan-cycle">/MO</span></div>
                <div class="feature-list">
                    <div class="feature-item">→ ACCESS TO BASIC COURSES</div>
                    <div class="feature-item unavailable">— CERTIFICATES</div>
                    <div class="feature-item">→ COMMUNITY SUPPORT</div>
                </div>
                <div class="mt-6">
                    <button class="cta-ghost">Enroll Free</button>
                </div>
            </div>

            <div class="plan-card popular">
                <div class="plan-ribbon">MOST POPULAR</div>
                <div class="plan-name text-center">Pro</div>
                <div class="plan-tagline text-center">For Professionals</div>
                <div class="mt-6 plan-price text-center">$49 <span class="plan-cycle">/MO</span></div>
                <div class="feature-list">
                    <div class="feature-item">→ ALL FREE FEATURES</div>
                    <div class="feature-item">→ CERTIFICATE</div>
                    <div class="feature-item">→ PRIORITY SUPPORT</div>
                </div>
                <div class="mt-6 text-center">
                    <button class="cta-primary">Start Pro</button>
                </div>
            </div>

            <div class="plan-card">
                <div class="plan-name">Enterprise</div>
                <div class="plan-tagline">Custom solutions</div>
                <div class="mt-6 plan-price">CONTACT <span class="plan-cycle">SALES</span></div>
                <div class="feature-list">
                    <div class="feature-item">→ ALL PRO FEATURES</div>
                    <div class="feature-item">→ SSO & REPORTING</div>
                    <div class="feature-item">→ SLA</div>
                </div>
                <div class="mt-6 text-center">
                    <button class="cta-ghost">CONTACT SALES →</button>
                </div>
            </div>
        </div>

        <div class="mt-12">
            <table class="comparison-table sticky-header">
                <thead>
                    <tr>
                        <th></th>
                        <th>Free</th>
                        <th>Pro</th>
                        <th>Enterprise</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td class="text-left">Courses</td><td>✓</td><td>UNLIMITED</td><td>UNLIMITED</td></tr>
                    <tr><td class="text-left">Certificates</td><td class="comparison-unavail">—</td><td class="comparison-check">✓</td><td class="comparison-check">✓</td></tr>
                    <tr><td class="text-left">Support</td><td class="comparison-unavail">—</td><td>Priority</td><td>Dedicated</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
