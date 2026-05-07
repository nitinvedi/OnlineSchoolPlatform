@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-700 dark:border-slate-700 focus:border-violet-500 focus:ring-violet-500 dark:focus:border-violet-500 dark:focus:ring-violet-500 rounded-xl shadow-sm transition-colors px-4 py-3 text-slate-100 dark:text-slate-100 bg-slate-800 dark:bg-slate-800 placeholder-slate-500 dark:placeholder-slate-500']) }}>

