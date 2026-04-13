@props([
  'href' => '#',
  'icon' => '',
  'title' => '',
  'description' => '',
])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group']) }}>
  <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center text-primary-600 group-hover:bg-primary-500 group-hover:text-white transition-colors">
    <i class="fas {{ $icon }} text-sm"></i>
  </div>
  <div class="flex-1 min-w-0">
    <p class="font-medium text-sm truncate">{{ $title }}</p>
    <p class="text-xs text-slate-500 truncate">{{ $description }}</p>
  </div>
  <i class="fas fa-chevron-right text-xs text-slate-400 group-hover:text-primary-500 transition-colors"></i>
</a>