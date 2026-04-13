@props([
  'subject' => '',
  'classInfo' => '',
  'location' => '',
  'status' => 'Berlangsung',
  'statusVariant' => 'success', // success, warning, secondary
])
@php
  $statusClasses = [
    'success' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400',
    'warning' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400',
    'secondary' => 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300',
  ];
  $badgeClass = $statusClasses[$statusVariant] ?? $statusClasses['secondary'];
@endphp

<div {{ $attributes->merge(['class' => 'p-4 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors']) }}>
  <div class="flex items-start justify-between">
    <div>
      <p class="font-medium text-sm">{{ $subject }}</p>
      <p class="text-xs text-slate-500 mt-0.5">{{ $classInfo }}</p>
      <p class="text-xs text-primary-600 dark:text-primary-400 mt-1 flex items-center gap-1">
        <i class="fas fa-map-marker-alt text-[10px]"></i> {{ $location }}
      </p>
    </div>
    <span class="px-2 py-1 {{ $badgeClass }} text-[10px] font-semibold rounded-full">
      {{ $status }}
    </span>
  </div>
</div>