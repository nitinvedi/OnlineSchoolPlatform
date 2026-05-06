<x-admin-layout>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">Platform Settings</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">General Settings</h3>
                <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Platform Name</label>
                            <input type="text" name="platform_name" value="{{ $settings['platform_name'] }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Logo</label>
                            <input type="file" name="logo" accept="image/*" class="mt-1 block w-full">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Favicon</label>
                            <input type="file" name="favicon" accept="image/*" class="mt-1 block w-full">
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Changes</button>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Features</h3>
                <div class="space-y-4">
                    @foreach($settings['features'] as $feature => $enabled)
                        <div class="flex items-center">
                            <input type="checkbox" {{ $enabled ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-offset-0 focus:ring-indigo-200 focus:ring-opacity-50">
                            <label class="ml-2 text-sm text-gray-700">{{ ucfirst(str_replace('_', ' ', $feature)) }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Maintenance Mode</h3>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Put the platform in maintenance mode</p>
                    <p class="text-xs text-gray-500">Users will see a maintenance page when enabled</p>
                </div>
                <form method="POST" action="{{ route('admin.settings.toggle-maintenance') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded {{ $settings['maintenance_mode'] ? 'bg-green-600 text-white hover:bg-green-700' : 'bg-gray-600 text-white hover:bg-gray-700' }}">
                        {{ $settings['maintenance_mode'] ? 'Disable' : 'Enable' }}
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Coupons & Discounts</h3>
            <form method="POST" action="{{ route('admin.settings.create-coupon') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <input type="text" name="code" placeholder="Coupon Code" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                    <input type="number" name="discount" placeholder="Discount %" min="1" max="100" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                    <input type="date" name="expires_at" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create Coupon</button>
            </form>
            <div class="mt-4">
                <p class="text-sm text-gray-600">No active coupons.</p>
            </div>
        </div>
    </div>
</x-admin-layout>