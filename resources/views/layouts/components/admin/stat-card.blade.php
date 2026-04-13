@props([
  'title' => '',
  'value' => '',
  'trend' => null,
  'trendLabel' => '',
  'icon' => '',
  'iconBg' => 'bg-primary-100',
  'iconColor' => 'text-primary-600',
  'darkIconBg' => 'dark:bg-primary-900/30',
  'darkIconColor' => 'dark:text-primary-400',
  'trendColor' => 'text-emerald-600',
  'darkTrendColor' => 'dark:text-emerald-400',
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 hover:shadow-lg transition-shadow']) }}>
  <div class="flex items-center justify-between">
    <div
     >
      <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">{{ $title }}</p>
      <p class="text-2xl font-bold mt-1">{{ $value }}</p>
      @if($trend)
        <p class="text-xs {{ $trendColor }} {{ $darkTrendColor }} mt-1 flex items-center gap-1">
          <i class="fas fa-arrow-{{ $trend > 0 ? 'up' : 'down' }} text-[10px]"></i> 
          {{ abs($trend) }}% {{ $trendLabel }}
        </p>
      @elseif($trendLabel)
        <p class="text-xs text-slate-400 mt-1">{{ $trendLabel }}</p>
      @endif
    </div>
    <div class="w-12 h-12 {{ $iconBg }} {{ $darkIconBg }} rounded-xl flex items-center justify-center {{ $iconColor }} {{ $darkIconColor }}">
      <i class="fas {{ $icon }}"></i>
    </div>
  </div>
</div>