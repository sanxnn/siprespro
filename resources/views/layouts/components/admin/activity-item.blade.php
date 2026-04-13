@props([
  'initial' => '',
  'name' => '',
  'info' => '',
  'status' => 'hadir',
  'time' => '',
  'statusColors' => [
    'hadir' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400',
    'izin' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400',
    'sakit' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
    'alpha' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
  ]
])

@php
  $statusClass = $statusColors[$status] ?? 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300';
@endphp

<div {{ $attributes->merge(['class' => 'p-4 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors']) }}>
  <div class="flex items-center gap-3">
    <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center text-primary-600 dark:text-primary-400 font-semibold text-sm">
      {{ strtoupper($initial) }}
    </div>
    <div>
      <p class="font-medium text-sm">{{ $name }}</p>
      <p class="text-xs text-slate-500">{{ $info }}</p>
    </div>
  </div>
  <div class="text-right">
    <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold uppercase tracking-wide {{ $statusClass }}">
      {{ $status }}
    </span>
    <p class="text-[10px] text-slate-400 mt-1">{{ $time }}</p>
  </div>
</div>