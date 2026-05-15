@extends('layouts.app')

@section('title', 'Dashboard Dosen | SIPRESPRO')

@section('content')

  {{-- 1. Alert Semester Aktif --}}
  @if($semesterAktif)
    <div
      class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 mb-6 flex items-start gap-3">
      <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mt-0.5"></i>
      <div>
        <p class="text-sm font-medium text-blue-800 dark:text-blue-200">
          Semester {{ $semesterAktif->nama }} sedang aktif
        </p>
        <p class="text-xs text-blue-600 dark:text-blue-300 mt-1">
          <i class="fas fa-user-tie mr-1"></i> Login sebagai: {{ auth()->user()->dosen->nama }}
        </p>
      </div>
    </div>
  @endif

  {{-- 2. Statistik Utama Dosen --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 hover:shadow-lg transition-shadow">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Kelas Diampu</p>
          <p class="text-2xl font-bold mt-1 text-slate-800 dark:text-white">{{ $totalKelas }}</p>
          <p class="text-[10px] font-bold text-primary-600 dark:text-primary-400 uppercase tracking-wider mt-1">Semester
            Aktif</p>
        </div>
        <div
          class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center text-primary-600 dark:text-primary-400">
          <i class="fas fa-layer-group"></i>
        </div>
      </div>
    </div>

    <div
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 hover:shadow-lg transition-shadow">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Mahasiswa Diampu</p>
          <p class="text-2xl font-bold mt-1 text-slate-800 dark:text-white">{{ $totalMahasiswaDiampu }}</p>
          <p class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider mt-1">Total Individu
          </p>
        </div>
        <div
          class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center text-blue-600 dark:text-blue-400">
          <i class="fas fa-users"></i>
        </div>
      </div>
    </div>

    <div
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 hover:shadow-lg transition-shadow">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Sesi Selesai</p>
          <p class="text-2xl font-bold mt-1 text-slate-800 dark:text-white">{{ $pertemuanSelesai }}</p>
          <p class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider mt-1">Sesi
            Terlaksana</p>
        </div>
        <div
          class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center text-emerald-600 dark:text-emerald-400">
          <i class="fas fa-calendar-check"></i>
        </div>
      </div>
    </div>

    <div
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 hover:shadow-lg transition-shadow">
      <div class="flex items-center justify-between">
        <div class="flex-1 min-w-0">
          <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Avg Kehadiran</p>
          <p class="text-2xl font-bold mt-1 text-slate-800 dark:text-white">{{ $avgKehadiran }}%</p>
          <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-1.5 mt-2 overflow-hidden">
            <div class="bg-primary-500 h-1.5 rounded-full transition-all duration-700"
              style="width: {{ $avgKehadiran }}%"></div>
          </div>
        </div>
        <div
          class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center text-indigo-600 dark:text-indigo-400 ml-3">
          <i class="fas fa-chart-line"></i>
        </div>
      </div>
    </div>
  </div>


  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Aktivitas Presensi Terbaru di Kelas Dosen --}}
    <div class="lg:col-span-2 space-y-6">
      <div
        class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm transition-all hover:shadow-md">
        {{-- Header --}}
        <div
          class="p-5 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
          <div>
            <h3 class="font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
              <span class="w-2 h-4 bg-primary-500 rounded-full"></span>
              Log Presensi Terbaru
            </h3>
          </div>
          <div class="flex items-center gap-2">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em]">Live
              Feed</span>
          </div>
        </div>

        {{-- Body Log --}}
        <div class="divide-y divide-slate-100 dark:divide-slate-700/50 max-h-[450px] overflow-y-auto custom-scrollbar">
          @forelse($recentPresensi as $item)
            <div
              class="p-4 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-all group">
              <div class="flex items-center gap-4">
                {{-- Avatar / Inisial --}}
                <div
                  class="w-11 h-11 bg-linear-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-600 rounded-xl flex items-center justify-center text-slate-700 dark:text-slate-200 font-bold shadow-sm group-hover:scale-105 transition-transform">
                  {{ strtoupper(substr($item->mahasiswa->nama, 0, 1)) }}
                </div>

                <div class="min-w-0">
                  <p class="font-bold text-sm text-slate-800 dark:text-slate-100 leading-tight truncate">
                    {{ $item->mahasiswa->nama }}
                  </p>
                  <div class="flex items-center gap-2 mt-1">
                    <span
                      class="text-[10px] font-bold px-1.5 py-0.5 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 rounded uppercase">
                      {{ $item->mahasiswa->golongan->nama }}
                    </span>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate max-w-[150px] md:max-w-[200px]">
                      {{ $item->pertemuan->kelasPerkuliahan->mataKuliah->nama }}
                    </p>
                  </div>
                </div>
              </div>

              <div class="text-right flex flex-col items-end gap-1.5">
                @php
                  $statusClasses = [
                    'hadir' => 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-100 dark:border-emerald-500/20',
                    'izin' => 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-100 dark:border-blue-500/20',
                    'sakit' => 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-100 dark:border-amber-500/20',
                    'alpha' => 'bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-100 dark:border-rose-500/20',
                  ];
                  $class = $statusClasses[strtolower($item->status)] ?? 'bg-slate-100 text-slate-600';
                @endphp

                <span class="px-2.5 py-1 border rounded-lg text-[9px] font-black uppercase tracking-widest {{ $class }}">
                  {{ $item->status }}
                </span>

                <div class="flex items-center gap-1 text-[9px] text-slate-400 font-medium">
                  <i class="far fa-clock"></i>
                  {{ $item->waktu_presensi->diffForHumans() }}
                </div>
              </div>
            </div>
          @empty
            <div class="flex flex-col items-center justify-center py-20 px-6">
              <div class="w-16 h-16 bg-slate-50 dark:bg-slate-700/50 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-stream text-slate-300 dark:text-slate-600 text-2xl"></i>
              </div>
              <p class="text-sm font-medium text-slate-500 dark:text-slate-400 italic">Belum ada aktivitas hari ini.</p>
            </div>
          @endforelse
        </div>
      </div>
    </div>

    {{-- Jadwal Hari Ini & Shortcut --}}
    <div class="space-y-6">
      <div
        class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm text-left transition-all hover:shadow-md">
        {{-- Header --}}
        <div
          class="p-5 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/20">
          <div class="flex items-center gap-2">
            <i class="fas fa-calendar-alt text-primary-500"></i>
            <h3 class="font-bold text-slate-800 dark:text-white tracking-tight">Agenda Mengajar</h3>
          </div>
          <span
            class="px-2.5 py-1 bg-primary-600 text-white text-[10px] font-black rounded-lg uppercase tracking-wider shadow-sm shadow-primary-500/20">
            {{ now()->translatedFormat('l') }}
          </span>
        </div>

        {{-- List Jadwal --}}
        <div class="divide-y divide-slate-100 dark:divide-slate-700">
          @forelse($jadwalHariIni as $j)
            @php
              $currentTime = now()->format('H:i:s');
              $isPassed = $currentTime > $j->jam_selesai;
              $isCurrent = ($currentTime >= $j->jam_mulai && $currentTime <= $j->jam_selesai);
            @endphp

            <a href="{{ route('dosen.kelas.show', $j->kelas_perkuliahan_id) }}"
              class="p-5 block hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-all group relative {{ $isPassed ? 'opacity-60 grayscale-[0.5]' : '' }}">

              {{-- Indikator Kelas Berlangsung --}}
              @if($isCurrent)
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500"></div>
              @endif

              <div class="flex justify-between items-start gap-4">
                <div class="min-w-0">
                  <p
                    class="font-black text-sm text-slate-800 dark:text-slate-100 group-hover:text-primary-600 transition-colors leading-tight truncate">
                    {{ $j->kelasPerkuliahan->mataKuliah->nama }}
                  </p>

                  <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2">
                    <span
                      class="text-[10px] font-bold text-slate-500 dark:text-slate-400 flex items-center gap-1.5 uppercase">
                      <i class="far fa-clock text-primary-500"></i>
                      {{ date('H:i', strtotime($j->jam_mulai)) }} - {{ date('H:i', strtotime($j->jam_selesai)) }}
                    </span>
                    <span
                      class="text-[10px] font-bold text-slate-500 dark:text-slate-400 flex items-center gap-1.5 uppercase">
                      <i class="fas fa-map-marker-alt text-rose-500"></i>
                      {{ $j->kelasPerkuliahan->ruang->nama }}
                    </span>
                  </div>
                </div>

                {{-- Badge Status Waktu --}}
                <div>
                  @if($isPassed)
                    <span
                      class="text-[9px] font-black uppercase px-2 py-0.5 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 rounded">Selesai</span>
                  @elseif($isCurrent)
                    <span
                      class="text-[9px] font-black uppercase px-2 py-0.5 bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded animate-pulse border border-emerald-200 dark:border-emerald-500/30">Sekarang</span>
                  @else
                    <span
                      class="text-[9px] font-black uppercase px-2 py-0.5 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded">Nanti</span>
                  @endif
                </div>
              </div>
            </a>
          @empty
            <div class="flex flex-col items-center justify-center py-16 px-6">
              <!-- Icon Container dengan Ring Lembut -->
              <div
                class="w-16 h-16 bg-slate-50 dark:bg-slate-800/50 rounded-2xl flex items-center justify-center mb-5 border border-slate-100 dark:border-slate-700/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none"
                  viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9 3.75h.008v.008H12v-.008z" />
                </svg>
              </div>

              <!-- Text Content -->
              <div class="text-center">
                <h4 class="text-sm font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                  Tidak Ada Jadwal
                </h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed max-w-[200px]">
                  Agenda mengajar Anda untuk hari ini tidak ditemukan atau telah selesai.
                </p>
              </div>
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>

@endsection