<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-violet-600 border border-transparent rounded-xl font-bold text-sm text-white tracking-wide hover:bg-violet-500 focus:bg-violet-500 active:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 focus:ring-offset-white transition ease-in-out duration-200 shadow-md hover:shadow-lg hover:-translate-y-0.5']) }}>
    {{ $slot }}
</button>
