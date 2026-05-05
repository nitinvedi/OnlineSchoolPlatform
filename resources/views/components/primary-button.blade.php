<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-sky-600 border border-transparent rounded-xl font-bold text-sm text-white tracking-wide hover:bg-sky-500 focus:bg-sky-500 active:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition ease-in-out duration-200 shadow-md hover:shadow-lg hover:-translate-y-0.5']) }}>
    {{ $slot }}
</button>
