@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-300 focus:border-sky-500 focus:ring-sky-500 rounded-xl shadow-sm transition-colors px-4 py-3 text-slate-900 bg-white placeholder-slate-400']) }}>
