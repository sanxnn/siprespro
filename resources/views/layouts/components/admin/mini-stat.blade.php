@props([
  'label' => '',
  'value' => '',
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 text-center']) }}>
  <p class="text-xs text-slate-500 dark:text-slate-400">{{ $label }}</p>
  <p class="text-xl font-bold mt-1">{{ $value }}</p>
</div>