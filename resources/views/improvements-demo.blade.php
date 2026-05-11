{{-- Component Library Demo Page --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Component Library & Improvements Demo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50">
    {{-- Toast Container --}}
    <x-toast />

    <div class="container-lg py-2xl">
        {{-- Header --}}
        <div class="mb-2xl">
            <h1>Frontend Improvements Demo</h1>
            <p class="text-lg text-slate-600 mt-md">
                This page demonstrates improvements #1, #2, #3, #8, and #10
            </p>
        </div>

        {{-- Section 1: Typography & Spacing (#10) --}}
        <section class="mb-4xl">
            <h2 class="mb-xl">Typography & Spacing System</h2>
            <div class="card">
                <div class="card-body">
                    <h3 class="mb-lg">Heading Levels</h3>
                    <p class="mb-md">All headings use consistent spacing and sizing:</p>

                    <div class="space-y-lg">
                        <div>
                            <h1 class="mb-md">Heading 1 (5xl - 48px)</h1>
                            <p class="text-sm text-slate-600">Perfect for page titles</p>
                        </div>
                        <div>
                            <h2 class="mb-md">Heading 2 (4xl - 36px)</h2>
                            <p class="text-sm text-slate-600">Great for section headers</p>
                        </div>
                        <div>
                            <h3 class="mb-md">Heading 3 (3xl - 30px)</h3>
                            <p class="text-sm text-slate-600">Subsection headers</p>
                        </div>
                        <div>
                            <h4 class="mb-md">Heading 4 (2xl - 24px)</h4>
                            <p class="text-sm text-slate-600">Sub-subsection headers</p>
                        </div>
                    </div>

                    <hr class="my-xl">

                    <h4 class="mb-lg">Spacing System (4px base)</h4>
                    <p class="mb-md">Consistent spacing utilities: xs (4px) → 2xl (32px)</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-lg">
                        <div class="bg-white border border-slate-200 p-lg">
                            <div class="bg-brand-100 p-xs text-center text-xs text-brand-700 rounded">xs (4px)</div>
                        </div>
                        <div class="bg-white border border-slate-200 p-lg">
                            <div class="bg-brand-100 p-sm text-center text-xs text-brand-700 rounded">sm (8px)</div>
                        </div>
                        <div class="bg-white border border-slate-200 p-lg">
                            <div class="bg-brand-100 p-md text-center text-xs text-brand-700 rounded">md (12px)</div>
                        </div>
                        <div class="bg-white border border-slate-200 p-lg">
                            <div class="bg-brand-100 p-lg text-center text-xs text-brand-700 rounded">lg (16px)</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Section 2: Skeleton Loading (#2) --}}
        <section class="mb-4xl">
            <h2 class="mb-xl">Skeleton Loading States</h2>
            <p class="text-slate-600 mb-lg">Shows smooth shimmer animation while content loads</p>

            <div class="grid grid-auto gap-lg">
                <x-skeleton-loader type="card" />
                <x-skeleton-loader type="card" />
                <x-skeleton-loader type="card" />
            </div>

            <p class="text-sm text-slate-600 mt-lg">
                ✓ Reduces perceived load time
                <br>
                ✓ Improves user experience
                <br>
                ✓ Shows content is coming
            </p>
        </section>

        {{-- Section 3: Accessible Forms (#3) --}}
        <section class="mb-4xl">
            <h2 class="mb-xl">Accessible Form Components</h2>
            <div class="card">
                <div class="card-body">
                    <form class="space-y-lg max-w-md">
                        <x-form-group label="Full Name" name="fullname" required help="Enter your complete name">
                            <x-form-input-accessible
                                type="text"
                                name="fullname"
                                placeholder="John Doe"
                                aria-described-by="fullname-help"
                            />
                        </x-form-group>

                        <x-form-group label="Email Address" name="email" required help="We'll never share your email">
                            <x-form-input-accessible
                                type="email"
                                name="email"
                                placeholder="john@example.com"
                                aria-described-by="email-help"
                            />
                        </x-form-group>

                        <x-form-group label="Password" name="password" required help="Minimum 8 characters">
                            <x-form-input-accessible
                                type="password"
                                name="password"
                                placeholder="••••••••"
                                aria-described-by="password-help"
                            />
                        </x-form-group>

                        <div class="form-checkbox-group">
                            <label class="form-checkbox-item">
                                <input type="checkbox" class="form-checkbox">
                                <span class="form-checkbox-label">I agree to the terms and conditions</span>
                            </label>
                            <label class="form-checkbox-item">
                                <input type="checkbox" class="form-checkbox">
                                <span class="form-checkbox-label">Send me updates about new courses</span>
                            </label>
                        </div>

                        <div class="flex gap-lg pt-lg">
                            <x-button variant="primary">Submit</x-button>
                            <x-button variant="secondary">Cancel</x-button>
                        </div>
                    </form>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-lg mt-xl">
                        <p class="text-sm text-blue-900">
                            ✓ Full ARIA labels and descriptions
                            <br>
                            ✓ Error state handling
                            <br>
                            ✓ Focus management
                            <br>
                            ✓ Keyboard navigation
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Section 4: Interactive Component Library (#1) --}}
        <section class="mb-4xl">
            <h2 class="mb-xl">Interactive Component Library</h2>

            {{-- Modals --}}
            <h3 class="mb-lg">Modal Dialog</h3>
            <div class="mb-2xl">
                <x-modal-dialog title="Welcome" confirm-text="Get Started" cancel-text="Later">
                    <p class="text-slate-700">
                        This is an Alpine.js powered modal component with automatic focus management,
                        escape key handling, and smooth animations.
                    </p>

                    <div slot="trigger" class="mb-xl">
                        <button
                            class="btn btn-primary"
                            @click="$el.closest('[x-data*=\'modal\']').querySelector('[x-data*=\'modal\']').__x_dataStack[0].openModal()"
                        >
                            Open Modal
                        </button>
                    </div>
                </x-modal-dialog>
            </div>

            {{-- Tabs --}}
            <h3 class="mb-lg">Tabs Component</h3>
            <div class="card mb-2xl">
                <div class="card-body">
                    <x-tab-group :tabs="[
                        ['label' => 'Overview', 'id' => 'overview'],
                        ['label' => 'Features', 'id' => 'features'],
                        ['label' => 'Settings', 'id' => 'settings'],
                    ]">
                        <div class="py-lg">
                            <p class="text-slate-700">Tab content goes here. Switch between tabs using Alpine.js.</p>
                        </div>
                    </x-tab-group>
                </div>
            </div>

            {{-- Badges --}}
            <h3 class="mb-lg">Badges</h3>
            <div class="flex flex-wrap gap-lg mb-2xl">
                <x-badge variant="primary">Primary</x-badge>
                <x-badge variant="success">Success</x-badge>
                <x-badge variant="warning">Warning</x-badge>
                <x-badge variant="danger">Danger</x-badge>
            </div>

            {{-- Cards --}}
            <h3 class="mb-lg">Card Component</h3>
            <div class="grid grid-auto gap-lg">
                <x-card title="Card with Title">
                    <p class="text-slate-700">This card demonstrates the new card component with consistent spacing and styling.</p>
                </x-card>
                <x-card title="Another Card">
                    <p class="text-slate-700">Cards have automatic hover effects and proper shadow elevation.</p>
                </x-card>
                <x-card title="Third Card">
                    <p class="text-slate-700">Perfect for displaying course previews, assignments, or other content.</p>
                </x-card>
            </div>
        </section>

        {{-- Section 5: Mobile Responsiveness (#8) --}}
        <section class="mb-4xl">
            <h2 class="mb-xl">Mobile-First Responsive Design</h2>
            <div class="card">
                <div class="card-body">
                    <p class="mb-lg text-slate-700">
                        This page is fully responsive. Try resizing your browser or viewing on mobile.
                    </p>

                    <h4 class="mb-md">Responsive Grid (1 column on mobile → 4 columns on large screens)</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-lg mb-2xl">
                        @for ($i = 1; $i <= 8; $i++)
                            <div class="bg-gradient-to-br from-brand-100 to-brand-50 p-lg rounded-lg text-center font-semibold text-brand-700">
                                Item {{ $i }}
                            </div>
                        @endfor
                    </div>

                    <h4 class="mb-md">Responsive Padding (changes on breakpoints)</h4>
                    <div class="bg-slate-100 p-mobile rounded-lg text-center text-slate-700">
                        Padding: md on mobile, lg on small, xl on medium+ screens
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-lg mt-xl">
                        <p class="text-sm text-blue-900 font-semibold mb-md">Responsive Breakpoints:</p>
                        <ul class="text-sm text-blue-900 space-y-xs">
                            <li>• <strong>xs</strong>: 360px (extra small phones)</li>
                            <li>• <strong>sm</strong>: 640px (phones)</li>
                            <li>• <strong>md</strong>: 768px (tablets)</li>
                            <li>• <strong>lg</strong>: 1024px (small laptops)</li>
                            <li>• <strong>xl</strong>: 1280px (desktops)</li>
                            <li>• <strong>2xl</strong>: 1536px (large displays)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        {{-- Summary --}}
        <section class="bg-gradient-to-r from-brand-50 to-brand-100 border border-brand-200 rounded-lg p-2xl mb-2xl">
            <h2 class="mb-lg">Implementation Summary</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-xl">
                <div>
                    <h4 class="mb-md text-brand-900">✓ Improvement #1: Component Library</h4>
                    <p class="text-sm text-brand-800">8 reusable Alpine.js components for modals, dropdowns, tabs, accordions, forms, toasts, carousels, and search filters.</p>
                </div>
                <div>
                    <h4 class="mb-md text-brand-900">✓ Improvement #2: Skeleton Loading</h4>
                    <p class="text-sm text-brand-800">Shimmer animation utilities for cards, text, thumbnails, and avatars to improve perceived performance.</p>
                </div>
                <div>
                    <h4 class="mb-md text-brand-900">✓ Improvement #3: Accessible Forms</h4>
                    <p class="text-sm text-brand-800">ARIA labels, error handling, help text, validation states, and keyboard navigation support.</p>
                </div>
                <div>
                    <h4 class="mb-md text-brand-900">✓ Improvement #8: Mobile Responsive</h4>
                    <p class="text-sm text-brand-800">Mobile-first breakpoints (xs-2xl), responsive utilities, and tested layouts for all devices.</p>
                </div>
            </div>
            <div class="mt-xl pt-xl border-t border-brand-200">
                <h4 class="mb-md text-brand-900">✓ Improvement #10: Typography & Spacing</h4>
                <p class="text-sm text-brand-800">
                    Professional type scale (xs-5xl), consistent spacing system (4px base), improved line heights and letter spacing,
                    and semantic color palette.
                </p>
            </div>
        </section>
    </div>
</body>
</html>
