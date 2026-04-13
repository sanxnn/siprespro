@props([
  'title' => 'Chart',
  'chartId' => 'attendanceChart',
  'chartType' => 'line',
  'labels' => [],
  'datasets' => [],
  'options' => [],
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5']) }}>
  <div class="flex justify-between items-center mb-4">
    <h3 class="font-semibold text-lg">{{ $title }}</h3>
    {{ $slot }}
  </div>
  
   <div class="h-48">
    <canvas id="{{ $chartId }}"></canvas>
  </div>

  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('{{ $chartId }}');
        if (ctx) {
          new Chart(ctx, {
            type: '{{ $chartType }}',
            data: {
              labels: @json($labels),
              datasets: @json($datasets)
            },
            options: @json(array_merge([
              'responsive' => true,
              'maintainAspectRatio' => false,
              'plugins' => ['legend' => ['display' => false]],
              'scales' => [
                'y' => ['beginAtZero' => false, 'min' => 50, 'max' => 100, 'grid' => ['color' => 'rgba(0,0,0,0.05)']],
                'x' => ['grid' => ['display' => false]]
              ]
            ], $options))
          });
        }
      });
    </script>
  @endpush
</div>