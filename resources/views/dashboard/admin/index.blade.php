@extends('layouts.app')

@section('title', 'Admin Dashboard | siprespro')

@section('content')

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
          <a href="{{ route('admin.semester.index') }}" class="underline hover:text-amber-700">Buat semester baru</a> untuk
          memulai
        </p>
      </div>
    </div>
  @endif

  <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 hover:shadow-lg transition-shadow">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Total Mahasiswa</p>
          <p class="text-2xl font-bold mt-1">{{ number_format($totalMahasiswa) }}</p>
          <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1 flex items-center gap-1">
            <i class="fas fa-arrow-up text-[10px]"></i> Data Real-time
          </p>
        </div>
        <div
          class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center text-emerald-600 dark:text-emerald-400">
          <i class="fas fa-user-graduate"></i>
        </div>
      </div>
    </div>

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

  <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 text-center">
      <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide">Mata Kuliah</p>
      <p class="text-xl font-bold mt-1 text-primary-600 dark:text-primary-400">{{ $totalMataKuliah }}</p>
    </div>
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 text-center">
      <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide">Kelas Aktif</p>
      <p class="text-xl font-bold mt-1 text-primary-600 dark:text-primary-400">{{ $kelasAktif }}</p>
    </div>
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 text-center">
      <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide">Golongan</p>
      <p class="text-xl font-bold mt-1 text-primary-600 dark:text-primary-400">{{ $totalGolongan }}</p>
    </div>
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 text-center">
      <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide">Lokasi</p>
      <p class="text-xl font-bold mt-1 text-primary-600 dark:text-primary-400">{{ $totalLokasi }}</p>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
      <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden">
        <div class="p-5 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
          <h3 class="font-semibold text-lg text-slate-800 dark:text-slate-100">Aktivitas Presensi Terbaru</h3>
          <a href="{{ route('admin.presensi.index') }}"
            class="text-primary-500 hover:text-primary-600 text-sm font-medium flex items-center gap-1">
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
                  <p class="text-xs text-slate-500 dark:text-slate-400">{{ $item['mahasiswa_nim'] }} •
                    {{ $item['kelas_nama'] }} • {{ $item['matkul_nama'] }}</p>
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
            <div class="p-8 text-center text-slate-500">Belum ada aktivitas hari ini</div>
          @endforelse
        </div>
      </div>

      <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5">
        <h3 class="font-semibold text-lg text-slate-800 dark:text-slate-100 mb-4">Tren Kehadiran 7 Hari Terakhir</h3>
        <div class="h-48 bg-slate-50 dark:bg-slate-700/30 rounded-xl flex items-end justify-between px-3 pb-4 gap-1.5">
          @foreach($attendanceTrend as $day)
            <div class="flex-1 flex flex-col items-center gap-1.5 group cursor-pointer"
              title="{{ $day['day_full'] }} ({{ $day['percentage'] }}%)">
              <div class="w-full bg-primary-200 dark:bg-primary-800/50 rounded-t-sm transition-all"
                style="height: {{ max($day['percentage'], 3) }}%"></div>
              <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">{{ $day['day_short'] }}</span>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <div class="space-y-6">
      <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden">
        <div class="p-5 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
          <h3 class="font-semibold text-lg text-slate-800 dark:text-white">Jadwal Hari Ini</h3>
          <span
            class="px-2.5 py-1 bg-primary-100 dark:bg-primary-900/30 dark:text-primary-400 text-primary-700 text-[10px] font-semibold rounded-full uppercase">{{ ucfirst($hariIniEnum) }}</span>
        </div>
        <div class="divide-y divide-slate-200 dark:divide-slate-700 max-h-80 overflow-y-auto custom-scrollbar">
          @forelse($jadwalHariIni as $jadwal)
            <div class="p-4">
              <p class="font-medium text-sm text-slate-800 dark:text-slate-100 truncate">{{ $jadwal['matkul_nama'] }}</p>
              <p class="text-xs text-slate-500 mt-1">{{ $jadwal['kelas_nama'] }} • {{ $jadwal['jam_mulai'] }} -
                {{ $jadwal['jam_selesai'] }}</p>
            </div>
          @empty
            <div class="p-8 text-center text-slate-400">Tidak ada jadwal hari ini</div>
          @endforelse
        </div>
      </div>

      <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl">
        <div class="p-5 border-b border-slate-200 dark:border-slate-700 font-semibold">Aksi Cepat</div>
        <div class="p-4 space-y-2">
          @foreach($quickActions as $action)
            <a href="{{ $action['url'] }}"
              class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
              <div
                class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center text-primary-600">
                <i class="fas {{ $action['icon'] }}"></i>
              </div>
              <div class="text-sm font-medium text-slate-800 dark:text-slate-100">{{ $action['title'] }}</div>
            </a>
          @endforeach
        </div>
      </div>
      @if($semesterAktif)
        <div class="bg-primary-600 dark:from-primary-700 dark:to-primary-900 rounded-2xl p-5 text-white shadow-lg">
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