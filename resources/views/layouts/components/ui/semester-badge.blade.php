@props([
  'semester' => '',
  'period' => '',
  'groups' => '',
  'classes' => '',
])

<div {{ $attributes->merge(['class' => 'bg-primary-600 dark:bg-primary-800 rounded-2xl p-5 text-white']) }}>
  <div class="flex items-start justify-between">
    <div>
      <p class="text-xs opacity-80 uppercase tracking-wide">Semester Aktif</p>
      <p class="text-xl font-bold mt-1">{{ $semester }}</p>
      <p class="text-sm opacity-90 mt-2">{{ $period }}</p>
      <div class="flex gap-2 mt-4">
        @if($groups)<span class="px-3 py-1 bg-white/20 rounded-full text-xs font-medium">{{ $groups }}</span>@endif
        @if($classes)<span class="px-3 py-1 bg-white/20 rounded-full text-xs font-medium">{{ $classes }}</span>@endif
      </div>
    </div>
    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
      <i class="fas fa-graduation-cap text-xl"></i>
    </div>
  </div>
</div>