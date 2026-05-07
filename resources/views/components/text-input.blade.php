@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border border-gray-300 focus:border-violet-500 focus:ring-violet-500 rounded-lg shadow-sm transition-colors px-4 py-3 text-gray-900 bg-white placeholder-gray-500']) }}>

