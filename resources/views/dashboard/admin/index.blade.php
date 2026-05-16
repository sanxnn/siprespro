@extends('layouts.app')
@section('title', 'Admin Dashboard | siprespro')
@section('content')
  @if($semesterAktif)
    <div
      class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 mb-6 flex items-start gap-4 transition-all hover:shadow-md">
      <div class="shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-800/50 rounded-full flex items-center justify-center">
        <i class="fas fa-calendar-check text-blue-600 dark:text-blue-400"></i>
      </div>
      <div class="flex-1">
        <div class="flex items-center gap-2">
          <p class="text-sm font-bold text-blue-800 dark:text-blue-200">
            Semester {{ $semesterAktif->nama }} ({{ $semesterAktif->tahun_ajaran }})
          </p>
          <span class="flex h-2 w-2 relative">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
          </span>
          <span class="text-[10px] font-black uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Aktif</span>
        </div>
        <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
          <i class="fas fa-history mr-1 text-[10px]"></i>
          Terakhir diperbarui: {{ $semesterAktif->updated_at?->translatedFormat('j F Y, H:i') }} WIB
        </p>
      </div>
    </div>
  @else
    <div
      class="bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 rounded-xl p-4 mb-6 flex items-start gap-3 animate-pulse">
      <div class="shrink-0 text-rose-600 dark:text-rose-400">
        <i class="fas fa-shield-alert text-xl"></i>
      </div>
      <div>
        <p class="text-sm font-bold text-rose-800 dark:text-rose-200 uppercase">Sistem Belum Siap</p>
        <p class="text-xs text-rose-600 dark:text-rose-300 mt-1">
          Belum ada semester yang diatur sebagai aktif.
          <a href="{{ route('admin.semester.index') }}" class="font-bold underline hover:text-rose-700 transition-colors">
            Klik di sini untuk konfigurasi semester
          </a>
        </p>
      </div>
    </div>
  @endif
  <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 hover:shadow-xl transition-all duration-300 group">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-500 dark:text-slate-400 font-bold uppercase tracking-tight">Total Mahasiswa</p>
          <p class="text-3xl font-black mt-1 text-slate-800 dark:text-white">{{ number_format($totalMahasiswa) }}</p>
          <p class="text-[10px] text-emerald-600 dark:text-emerald-400 mt-2 flex items-center gap-1 font-bold">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            DATA TERVERIFIKASI
          </p>
        </div>
        <div
          class="w-14 h-14 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform">
          <i class="fas fa-user-graduate text-xl"></i>
        </div>
      </div>
    </div>
    <div
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 hover:shadow-xl transition-all duration-300 group">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-500 dark:text-slate-400 font-bold uppercase tracking-tight">Total Dosen</p>
          <p class="text-3xl font-black mt-1 text-slate-800 dark:text-white">{{ number_format($totalDosen) }}</p>
          <p class="text-[10px] text-slate-400 mt-2 font-bold italic">Pengampu Semester Aktif</p>
        </div>
        <div
          class="w-14 h-14 bg-primary-50 dark:bg-primary-900/20 rounded-2xl flex items-center justify-center text-primary-600 dark:text-primary-400 group-hover:scale-110 transition-transform">
          <i class="fas fa-chalkboard-teacher text-xl"></i>
        </div>
      </div>
    </div>
    <div
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 hover:shadow-xl transition-all duration-300 group">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-500 dark:text-slate-400 font-bold uppercase tracking-tight">Presensi Hari Ini</p>
          <p class="text-3xl font-black mt-1 text-slate-800 dark:text-white">{{ number_format($presensiHariIni) }}</p>
          <p class="text-[10px] text-amber-600 dark:text-amber-400 mt-2 flex items-center gap-1 font-bold">
            <i class="fas fa-sync-alt animate-spin text-[10px]"></i> UPDATE TERBARU
          </p>
        </div>
        <div
          class="w-14 h-14 bg-amber-50 dark:bg-amber-900/20 rounded-2xl flex items-center justify-center text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform">
          <i class="fas fa-fingerprint text-xl"></i>
        </div>
      </div>
    </div>
    <div
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 hover:shadow-xl transition-all duration-300 group">
      <div class="flex items-center justify-between">
        <div class="w-full mr-4">
          <p class="text-sm text-slate-500 dark:text-slate-400 font-bold uppercase tracking-tight">Rasio Kehadiran
            Real-Time</p>
          <p class="text-3xl font-black mt-1 text-slate-800 dark:text-white">{{ $tingkatKehadiran }}%</p>
          <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium mt-1 italic">
            * {{ $hadirHariIni }} dari {{ $totalKapasitas }} mahasiswa terpantau hadir hari ini.
          </p>
          <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2 mt-3 overflow-hidden">
            <div
              class="h-2 rounded-full transition-all duration-1000 ease-out {{ $tingkatKehadiran >= 80 ? 'bg-emerald-500' : ($tingkatKehadiran >= 50 ? 'bg-amber-500' : 'bg-rose-500') }}"
              style="width: {{ min($tingkatKehadiran, 100) }}%"></div>
          </div>
        </div>
        <div
          class="w-14 h-14 bg-blue-50 dark:bg-blue-900/20 rounded-2xl shrink-0 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
          <i class="fas fa-percent text-xl"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div
      class="bg-white/50 dark:bg-slate-800/50 backdrop-blur-sm border border-slate-200 dark:border-slate-700 rounded-2xl p-4 transition-all hover:bg-white dark:hover:bg-slate-800 shadow-sm">
      <div class="flex flex-col items-center text-center">
        <div
          class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 mb-2">
          <i class="fas fa-book-open text-xs"></i>
        </div>
        <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Mata Kuliah</p>
        <p class="text-2xl font-black mt-0.5 text-slate-800 dark:text-white">{{ $totalMataKuliah }}</p>
      </div>
    </div>
    <div
      class="bg-white/50 dark:bg-slate-800/50 backdrop-blur-sm border border-slate-200 dark:border-slate-700 rounded-2xl p-4 transition-all hover:bg-white dark:hover:bg-slate-800 shadow-sm">
      <div class="flex flex-col items-center text-center">
        <div
          class="w-8 h-8 rounded-lg bg-violet-50 dark:bg-violet-900/20 flex items-center justify-center text-violet-600 dark:text-violet-400 mb-2">
          <i class="fas fa-door-closed text-xs"></i>
        </div>
        <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Kelas Aktif</p>
        <p class="text-2xl font-black mt-0.5 text-slate-800 dark:text-white">{{ $kelasAktif }}</p>
      </div>
    </div>
    <div
      class="bg-white/50 dark:bg-slate-800/50 backdrop-blur-sm border border-slate-200 dark:border-slate-700 rounded-2xl p-4 transition-all hover:bg-white dark:hover:bg-slate-800 shadow-sm">
      <div class="flex flex-col items-center text-center">
        <div
          class="w-8 h-8 rounded-lg bg-fuchsia-50 dark:bg-fuchsia-900/20 flex items-center justify-center text-fuchsia-600 dark:text-fuchsia-400 mb-2">
          <i class="fas fa-layer-group text-xs"></i>
        </div>
        <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Golongan</p>
        <p class="text-2xl font-black mt-0.5 text-slate-800 dark:text-white">{{ $totalGolongan }}</p>
      </div>
    </div>
    <div
      class="bg-white/50 dark:bg-slate-800/50 backdrop-blur-sm border border-slate-200 dark:border-slate-700 rounded-2xl p-4 transition-all hover:bg-white dark:hover:bg-slate-800 shadow-sm">
      <div class="flex flex-col items-center text-center">
        <div
          class="w-8 h-8 rounded-lg bg-sky-50 dark:bg-sky-900/20 flex items-center justify-center text-sky-600 dark:text-sky-400 mb-2">
          <i class="fas fa-map-marked-alt text-xs"></i>
        </div>
        <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Titik Lokasi</p>
        <p class="text-2xl font-black mt-0.5 text-slate-800 dark:text-white">{{ $totalLokasi }}</p>
      </div>
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
                    {{ $item['kelas_nama'] }} • {{ $item['matkul_nama'] }}
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
            <div class="flex flex-col items-center justify-center py-20 px-6">
              <div
                class="w-20 h-20 flex items-center justify-center rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/50 mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-300 dark:text-slate-600" fill="none"
                  viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
              </div>
              <div class="text-center space-y-1">
                <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100">
                  Tidak Ada Aktivitas Presensi
                </h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 max-w-xs mx-auto leading-relaxed">
                  Data presensi mahasiswa untuk hari ini belum tersedia atau belum ada yang melakukan sinkronisasi.
                </p>
              </div>
              <div
                class="mt-8 flex items-center gap-2 px-3 py-1 bg-primary-50 dark:bg-primary-900/20 border border-primary-100 dark:border-primary-800/30 rounded-md">
                <span class="relative flex h-2 w-2">
                  <span
                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                </span>
                <span class="text-[11px] font-medium text-primary-700 dark:text-primary-500 uppercase tracking-wider">
                  ONLINE
                </span>
              </div>
            </div>
          @endforelse
        </div>
      </div>
      <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5">
        <h3 class="font-semibold text-lg text-slate-800 dark:text-slate-100 mb-4">Tren Kehadiran 7 Hari Terakhir</h3>
        <div class="h-64 bg-slate-50 dark:bg-slate-900/40 rounded-2xl flex items-end justify-between px-4 pb-6 gap-2">
          @foreach($attendanceTrend as $day)
            <div class="flex-1 flex flex-col items-center justify-end h-full gap-2 group relative cursor-help">
              <div
                class="absolute -top-12 left-1/2 -translate-x-1/2 px-2 py-1.5 bg-slate-800 text-white text-[10px] rounded-lg opacity-0 group-hover:opacity-100 transition-opacity z-10 whitespace-nowrap shadow-lg pointer-events-none">
                <span class="font-black text-emerald-400">{{ $day['hadir'] }}</span>/{{ $day['total'] }} Mhs
                ({{ $day['percentage'] }}%)
              </div>
              <div class="w-full flex items-end justify-center h-32 mb-1">
                <div
                  class="w-full rounded-t-lg transition-all duration-500 shadow-xs group-hover:scale-x-105 min-h-2 {{ $day['percentage'] >= 80 ? 'bg-emerald-500' : ($day['percentage'] >= 50 ? 'bg-primary-500' : 'bg-amber-500') }}"
                  style="height: {{ $day['percentage'] }}%">
                </div>
              </div>
              <span
                class="text-[10px] text-slate-400 dark:text-slate-500 font-bold tracking-tighter shrink-0">{{ $day['day_short'] }}</span>
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
            <div class="p-4 relative group hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-all">
              <div class="flex justify-between items-start">
                <div>
                  <p class="font-bold text-sm text-slate-800 dark:text-slate-100 truncate max-w-[150px]">
                    {{ $jadwal['matkul_nama'] }}
                  </p>
                  <p class="text-[10px] text-slate-500 mt-1 uppercase font-bold">{{ $jadwal['kelas_nama'] }} •
                    {{ $jadwal['jam_mulai'] }} - {{ $jadwal['jam_selesai'] }}
                  </p>
                </div>
                @if($jadwal['status'] === 'berlangsung')
                  <span class="flex h-2 w-2 mt-1">
                    <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                  </span>
                @endif
              </div>
            </div>
          @empty
            <div class="flex flex-col items-center justify-center py-12 px-4">
              <div class="relative mb-4">
                <div
                  class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-700/50">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                  </svg>
                </div>
                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                  <span
                    class="relative inline-flex rounded-full h-4 w-4 bg-slate-200 dark:bg-slate-700 text-[8px] items-center justify-center text-slate-500 dark:text-slate-400 font-bold border border-white dark:border-slate-900">0</span>
                </span>
              </div>
              <div class="text-center">
                <h4 class="text-sm font-bold text-slate-700 dark:text-slate-200 uppercase tracking-tight">Agenda Kosong</h4>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 max-w-[180px] leading-relaxed">
                  Tidak ada jadwal perkuliahan yang terjadwal untuk hari ini.
                </p>
              </div>
              <div class="mt-6 flex items-center gap-2">
                <span class="h-px w-4 bg-slate-200 dark:bg-slate-700"></span>
                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                  {{ now()->translatedFormat('l, d M') }}
                </span>
                <span class="h-px w-4 bg-slate-200 dark:bg-slate-700"></span>
              </div>
            </div>
          @endforelse
        </div>
      </div>
      <div
        class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm">
        <div
          class="p-5 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/50">
          <h3 class="font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
            <i class="fas fa-bolt text-amber-500 text-xs"></i>
            Aksi Cepat
          </h3>
        </div>
        <div class="p-3 space-y-1">
          @foreach($quickActions as $action)
            <a href="{{ $action['url'] }}"
              class="flex items-center justify-between p-3 rounded-xl hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-all duration-200 group">
              <div class="flex items-center gap-4">
                <div
                  class="w-11 h-11 bg-slate-100 dark:bg-slate-700 group-hover:bg-primary-100 dark:group-hover:bg-primary-900/40 rounded-xl flex items-center justify-center text-slate-600 dark:text-slate-400 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors shadow-sm border border-transparent group-hover:border-primary-200 dark:group-hover:border-primary-800">
                  <i class="fas {{ $action['icon'] }} text-lg"></i>
                </div>
                <div>
                  <p
                    class="text-sm font-bold text-slate-700 dark:text-slate-200 group-hover:text-primary-700 dark:group-hover:text-primary-300 transition-colors">
                    {{ $action['title'] }}
                  </p>
                  <p
                    class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 opacity-80 group-hover:opacity-100 transition-opacity">
                    {{ $action['desc'] }}
                  </p>
                </div>
              </div>
              <div
                class="opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all text-primary-500 dark:text-primary-400">
                <i class="fas fa-chevron-right text-xs"></i>
              </div>
            </a>
          @endforeach
        </div>
      </div>
    </div>
  </div>
@endsection