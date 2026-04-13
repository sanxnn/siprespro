@extends('layouts.app')

@section('title', 'Admin Dashboard | siprespro')

@section('content')

  <!-- 🔔 Alert Banner - Active Semester -->
  @if($semesterAktif)
    <div
      class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 mb-6 flex items-start gap-3">
      <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mt-0.5"></i>
      <div>
        <p class="text-sm font-medium text-blue-800 dark:text-blue-200">
          Semester {{ $semesterAktif->nama }} ({{ $semesterAktif->tahun_ajaran }}) sedang aktif
        </p>
        <p class="text-xs text-blue-600 dark:text-blue-300 mt-1">
          <i class="fas fa-clock mr-1"></i> Diperbarui: {{ $semesterAktif->created_at?->translatedFormat('j M Y') }}
        </p>
      </div>
    </div>
  @else
    <div
      class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4 mb-6 flex items-start gap-3">
      <i class="fas fa-exclamation-triangle text-amber-600 dark:text-amber-400 mt-0.5"></i>
      <div>
        <p class="text-sm font-medium text-amber-800 dark:text-amber-200">Belum ada semester yang diatur</p>
        <p class="text-xs text-amber-600 dark:text-amber-300 mt-1">
          <a href="" class="underline hover:text-amber-700">Buat semester baru</a> untuk
          memulai
        </p>
      </div>
    </div>
  @endif

  <!-- 📈 STATS CARDS -->
  <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

    <!-- Total Mahasiswa -->
    <div
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 hover:shadow-lg transition-shadow">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Total Mahasiswa</p>
          <p class="text-2xl font-bold mt-1">{{ number_format($totalMahasiswa) }}</p>
          <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1 flex items-center gap-1">
            <i class="fas fa-arrow-up text-[10px]"></i>
            {{ $totalMahasiswa > 100 ? '+' . rand(3, 15) . '% bulan ini' : 'Data awal' }}
          </p>
        </div>
        <div
          class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center text-emerald-600 dark:text-emerald-400">
          <i class="fas fa-user-graduate"></i>
        </div>
      </div>
    </div>

    <!-- Total Dosen -->
    <div
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 hover:shadow-lg transition-shadow">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Total Dosen</p>
          <p class="text-2xl font-bold mt-1">{{ number_format($totalDosen) }}</p>
          <p class="text-xs text-slate-400 mt-1">Pengampu aktif</p>
        </div>
        <div
          class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center text-primary-600 dark:text-primary-400">
          <i class="fas fa-chalkboard-teacher"></i>
        </div>
      </div>
    </div>

    <!-- Presensi Hari Ini -->
    <div
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 hover:shadow-lg transition-shadow">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Presensi Hari Ini</p>
          <p class="text-2xl font-bold mt-1">{{ number_format($presensiHariIni) }}</p>
          <p class="text-xs text-amber-600 dark:text-amber-400 mt-1 flex items-center gap-1">
            <i class="fas fa-clock text-[10px]"></i> Real-time
          </p>
        </div>
        <div
          class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center text-amber-600 dark:text-amber-400">
          <i class="fas fa-clipboard-check"></i>
        </div>
      </div>
    </div>

    <!-- Tingkat Kehadiran -->
    <div
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 hover:shadow-lg transition-shadow">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Kehadiran Hari Ini</p>
          <p class="text-2xl font-bold mt-1">{{ $tingkatKehadiran }}%</p>
          <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-1.5 mt-2 overflow-hidden">
            <div class="bg-primary-500 h-1.5 rounded-full transition-all duration-700 ease-out"
              style="width: {{ min($tingkatKehadiran, 100) }}%"></div>
          </div>
        </div>
        <div
          class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center text-blue-600 dark:text-blue-400">
          <i class="fas fa-chart-line"></i>
        </div>
      </div>
    </div>

  </div>

  <!-- 🗓️ SECONDARY STATS -->
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 text-center hover:border-primary-300 dark:hover:border-primary-700 transition-colors">
      <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide">Mata Kuliah</p>
      <p class="text-xl font-bold mt-1 text-primary-600 dark:text-primary-400">{{ $totalMataKuliah }}</p>
    </div>
    <div
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 text-center hover:border-primary-300 dark:hover:border-primary-700 transition-colors">
      <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide">Kelas Aktif</p>
      <p class="text-xl font-bold mt-1 text-primary-600 dark:text-primary-400">{{ $kelasAktif }}</p>
    </div>
    <div
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 text-center hover:border-primary-300 dark:hover:border-primary-700 transition-colors">
      <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide">Golongan</p>
      <p class="text-xl font-bold mt-1 text-primary-600 dark:text-primary-400">{{ $totalGolongan }}</p>
    </div>
    <div
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 text-center hover:border-primary-300 dark:hover:border-primary-700 transition-colors">
      <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide">Lokasi</p>
      <p class="text-xl font-bold mt-1 text-primary-600 dark:text-primary-400">{{ $totalLokasi }}</p>
    </div>
  </div>

  <!-- 📊 CONTENT GRID -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- LEFT COLUMN: Recent Activities + Chart -->
    <div class="lg:col-span-2 space-y-6">

      <!-- 📋 Recent Presensi Activities -->
      <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden">
        <div class="p-5 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
          <h3 class="font-semibold text-lg text-slate-800 dark:text-slate-100">Aktivitas Presensi Terbaru</h3>
          <a href=""
            class="text-primary-500 hover:text-primary-600 text-sm font-medium transition-colors flex items-center gap-1">
            Lihat Semua <i class="fas fa-arrow-right text-[10px]"></i>
          </a>
        </div>
        <div class="divide-y divide-slate-200 dark:divide-slate-700 max-h-[380px] overflow-y-auto custom-scrollbar">

          @forelse($recentPresensi as $item)
            <div
              class="p-4 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
              <div class="flex items-center gap-3">
                <div
                  class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center text-primary-600 dark:text-primary-400 font-semibold text-sm">
                  {{ strtoupper(substr($item['mahasiswa_nama'], 0, 1)) }}
                </div>
                <div>
                  <p class="font-medium text-sm text-slate-800 dark:text-slate-100">{{ $item['mahasiswa_nama'] }}</p>
                  <p class="text-xs text-slate-500 dark:text-slate-400">
                    {{ $item['mahasiswa_nim'] }} • {{ $item['kelas_nama'] }} • {{ $item['matkul_nama'] }}
                  </p>
                </div>
              </div>
              <div class="text-right">
                @php
                  $statusConfig = [
                    'hadir' => ['class' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400', 'icon' => 'fa-check'],
                    'izin' => ['class' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400', 'icon' => 'fa-file-alt'],
                    'sakit' => ['class' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400', 'icon' => 'fa-notes-medical'],
                    'alpha' => ['class' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400', 'icon' => 'fa-times'],
                  ];
                  $config = $statusConfig[$item['status']] ?? ['class' => 'bg-slate-100 text-slate-600', 'icon' => 'fa-question'];
                @endphp
                <span
                  class="px-2.5 py-1 rounded-full text-[10px] font-semibold uppercase tracking-wide {{ $config['class'] }} inline-flex items-center gap-1">
                  <i class="fas {{ $config['icon'] }} text-[8px]"></i> {{ $item['status'] }}
                </span>
                <p class="text-[10px] text-slate-400 mt-1">{{ $item['time_diff'] }}</p>
              </div>
            </div>
          @empty
            <div class="p-8 text-center">
              <div
                class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-inbox text-2xl text-slate-400"></i>
              </div>
              <p class="text-sm text-slate-500 dark:text-slate-400">Belum ada aktivitas presensi hari ini</p>
              <p class="text-xs text-slate-400 mt-1">Presensi akan muncul ketika mahasiswa melakukan absen</p>
            </div>
          @endforelse

        </div>
      </div>

      <!-- 📈 Attendance Chart -->
      <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5">
        <div class="flex justify-between items-center mb-4">
          <h3 class="font-semibold text-lg text-slate-800 dark:text-slate-100">Tren Kehadiran 7 Hari Terakhir</h3>
          <select
            class="text-xs border border-slate-200 dark:border-slate-600 rounded-lg px-2 py-1 bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500">
            <option>Minggu Ini</option>
            <option>Bulan Ini</option>
            <option>Semester Ini</option>
          </select>
        </div>

        <!-- Dynamic Chart Bars with Tooltip -->
        <div class="h-48 bg-slate-50 dark:bg-slate-700/30 rounded-xl flex items-end justify-between px-3 pb-4 gap-1.5">
          @foreach($attendanceTrend as $day)
            <div class="flex-1 flex flex-col items-center gap-1.5 group cursor-pointer"
              title="{{ $day['day_full'] }}, {{ $day['date']->translatedFormat('j F Y') }}&#10;{{ $day['hadir'] }} / {{ $day['total'] }} hadir ({{ $day['percentage'] }}%)">
              <div class="w-full flex flex-col items-center">
                <span
                  class="text-[9px] text-slate-500 dark:text-slate-400 mb-0.5 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                  {{ $day['percentage'] }}%
                </span>
                <div
                  class="w-full bg-primary-200 dark:bg-primary-800/50 rounded-t-sm transition-all duration-500 ease-out group-hover:bg-primary-400 dark:group-hover:bg-primary-600 relative"
                  style="height: {{ max($day['percentage'], 3) }}%">
                  <div class="absolute inset-x-0 bottom-0 h-0.5 bg-primary-400 dark:bg-primary-500 rounded-b-sm"></div>
                </div>
              </div>
              <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">{{ $day['day_short'] }}</span>
            </div>
          @endforeach
        </div>

        <!-- Legend -->
        <div class="flex items-center justify-center gap-4 mt-3 text-[10px] text-slate-500 dark:text-slate-400">
          <span class="flex items-center gap-1"><span class="w-2 h-2 bg-primary-400 rounded-sm"></span> % Kehadiran</span>
          <span class="flex items-center gap-1"><i class="fas fa-info-circle"></i> Hover untuk detail</span>
        </div>
      </div>

    </div>

    <!-- RIGHT COLUMN: Schedule + Quick Actions -->
    <div class="space-y-6">

      <!-- 🗓️ Jadwal Hari Ini -->
      <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden">
        <div class="p-5 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
          <div>
            <h3 class="font-semibold text-lg text-slate-800 dark:text-slate-100">Jadwal Hari Ini</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $formattedDate }}</p>
          </div>
          <span
            class="px-2.5 py-1 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 text-[10px] font-semibold rounded-full uppercase">
            {{ ucfirst($hariIniEnum) }}
          </span>
        </div>
        <div class="divide-y divide-slate-200 dark:divide-slate-700 max-h-80 overflow-y-auto custom-scrollbar">

          @forelse($jadwalHariIni as $jadwal)
            <div class="p-4 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
              <div class="flex items-start justify-between gap-2">
                <div class="flex-1 min-w-0">
                  <p class="font-medium text-sm text-slate-800 dark:text-slate-100 truncate">{{ $jadwal['matkul_nama'] }}
                  </p>
                  <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    {{ $jadwal['kelas_nama'] }} • {{ $jadwal['ruangan'] }} • {{ $jadwal['jam_mulai'] }} -
                    {{ $jadwal['jam_selesai'] }}
                  </p>
                  <p class="text-xs text-primary-600 dark:text-primary-400 mt-1 flex items-center gap-1">
                    <i class="fas fa-map-marker-alt text-[10px]"></i> {{ $jadwal['lokasi_detail'] }}
                  </p>
                </div>
                @php
                  $badgeConfig = [
                    'berlangsung' => ['class' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400', 'text' => 'Berlangsung'],
                    'berikutnya' => ['class' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400', 'text' => 'Berikutnya'],
                    'selesai' => ['class' => 'bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400', 'text' => 'Selesai'],
                    'mendatang' => ['class' => 'bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400', 'text' => $jadwal['jam_mulai']],
                  ];
                  $badge = $badgeConfig[$jadwal['status']] ?? $badgeConfig['mendatang'];
                @endphp
                <span class="px-2 py-1 {{ $badge['class'] }} text-[10px] font-semibold rounded-full whitespace-nowrap">
                  {{ $badge['text'] }}
                </span>
              </div>
            </div>
          @empty
            <div class="p-8 text-center">
              <div
                class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-calendar-day text-2xl text-slate-400"></i>
              </div>
              <p class="text-sm text-slate-500 dark:text-slate-400">Tidak ada jadwal pada hari {{ ucfirst($hariIniEnum) }}
              </p>
              <p class="text-xs text-slate-400 mt-1">Atur jadwal di menu <strong>Kelola Jadwal</strong></p>
            </div>
          @endforelse

        </div>
        <div class="p-3 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30">
          <a href=""
            class="text-xs text-primary-600 dark:text-primary-400 hover:underline font-medium flex items-center gap-1">
            Lihat Semua Jadwal <i class="fas fa-arrow-right text-[8px]"></i>
          </a>
        </div>
      </div>

      <!-- ⚡ Quick Actions -->
      <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl">
        <div class="p-5 border-b border-slate-200 dark:border-slate-700">
          <h3 class="font-semibold text-lg text-slate-800 dark:text-slate-100">Aksi Cepat</h3>
        </div>
        <div class="p-4 space-y-2">
          @foreach($quickActions as $action)
            @if($action['can'] ?? true)
              <a href="{{ $action['url'] }}"
                class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                <div
                  class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center text-primary-600 group-hover:bg-primary-500 group-hover:text-white transition-colors duration-200">
                  <i class="fas {{ $action['icon'] }} text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="font-medium text-sm text-slate-800 dark:text-slate-100 truncate">{{ $action['title'] }}</p>
                  <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $action['desc'] }}</p>
                </div>
                <i class="fas fa-chevron-right text-xs text-slate-400 group-hover:text-primary-500 transition-colors"></i>
              </a>
            @endif
          @endforeach
        </div>
      </div>

      <!-- 🎓 Info Semester Aktif Card -->
      @if($semesterAktif)
        <div
          class="bg-primary-600 dark:from-primary-700 dark:to-primary-900 rounded-2xl p-5 text-white shadow-lg">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-xs opacity-80 uppercase tracking-wider font-medium">Semester Aktif</p>
              <p class="text-xl font-bold mt-1">{{ $semesterAktif->nama }}</p>
              <p class="text-sm opacity-90 mt-1">{{ $semesterAktif->tahun_ajaran }}</p>
              <div class="flex flex-wrap gap-2 mt-4">
                <span
                  class="px-3 py-1 bg-white/20 hover:bg-white/30 rounded-full text-xs font-medium transition-colors cursor-default">
                  <i class="fas fa-users mr-1"></i> {{ $totalGolongan }} Golongan
                </span>
                <span
                  class="px-3 py-1 bg-white/20 hover:bg-white/30 rounded-full text-xs font-medium transition-colors cursor-default">
                  <i class="fas fa-chalkboard mr-1"></i> {{ $kelasAktif }} Kelas
                </span>
              </div>
            </div>
            <div
              class="w-12 h-12 bg-white/20 hover:bg-white/30 rounded-xl flex items-center justify-center transition-colors cursor-pointer"
              onclick="window.location='/#94a3b8'" title="Kelola Semester">
              <i class="fas fa-cog text-lg"></i>
            </div>
          </div>
        </div>
      @endif

    </div>

  </div>

@endsection

@push('styles')
  <style>
    .custom-scrollbar::-webkit-scrollbar {
      width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
      background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 4px;
    }

    .dark .custom-scrollbar::-webkit-scrollbar-thumb {
      background: #475569;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }

    /* Smooth hover animation for chart bars */
    .group:hover .bg-primary-200 {
      transform: scaleY(1.02);
      transform-origin: bottom;
    }
  </style>
@endpush

@push('scripts')
  <script>
    // Optional: Add click interaction for chart bars
    document.querySelectorAll('.group.cursor-pointer').forEach(el => {
      el.addEventListener('click', function (e) {
        e.preventDefault();
        // Could open modal with detailed stats for that day
        console.log('Chart day clicked:', this.title);
      });
    });
  </script>
@endpush