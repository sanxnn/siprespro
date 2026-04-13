@props([
  'type' => 'info', // info, success, warning, error
  'icon' => 'fa-info-circle',
  'title' => '',
  'message' => '',
])


@php
  $colors = [
    'info' => 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200',
    'success' => 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200',
    'warning' => 'bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-200',
    'error' => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 text-red-800 dark:text-red-200',
  ];
  $iconColors = [
    'info' => 'text-blue-600 dark:text-blue-400',
    'success' => 'text-emerald-600 dark:text-emerald-400',
    'warning' => 'text-amber-600 dark:text-amber-400',
    'error' => 'text-red-600 dark:text-red-400',
  ];
@endphp
<div {{ $attributes->merge(['class' => "{$colors[$type]} border rounded-xl p-4 mb-6 flex items-start gap-3"]) }}>
  <i class="fas {{ $icon }} {{ $iconColors[$type] }} mt-0.5"></i>
<div>
    @if($title)
      <p class="text-sm font-medium">{{ $title }}</p>
    @endif
    @if($message)
      <p class="text-xs mt-1 opacity-90">{{ $message }}</p>
    @endif
    {{ $slot }}
  </div>
</div>