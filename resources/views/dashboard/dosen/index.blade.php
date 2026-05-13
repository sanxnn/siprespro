@extends('layouts.app')

@section('title', 'Dashboard Dosen | SIPRESPRO')

@section('content')

  {{-- 1. Alert Semester Aktif --}}
  @if($semesterAktif)
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 mb-6 flex items-start gap-3">
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
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 hover:shadow-lg transition-shadow">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Kelas Diampu</p>
          <p class="text-2xl font-bold mt-1">{{ $totalKelas }}</p>
          <p class="text-xs text-primary-600 dark:text-primary-400 mt-1">Semester Aktif</p>
        </div>
        <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center text-primary-600 dark:text-primary-400">
          <i class="fas fa-layer-group"></i>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 hover:shadow-lg transition-shadow">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Sesi Selesai</p>
          <p class="text-2xl font-bold mt-1">{{ $pertemuanSelesai }}</p>
          <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">Total Pertemuan</p>
        </div>
        <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center text-emerald-600 dark:text-emerald-400">
          <i class="fas fa-calendar-check"></i>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 hover:shadow-lg transition-shadow">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Presensi Hari Ini</p>
          <p class="text-2xl font-bold mt-1">{{ $presensiHariIni }}</p>
          <p class="text-xs text-amber-600 dark:text-amber-400 mt-1">Mahasiswa Absen</p>
        </div>
        <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center text-amber-600 dark:text-amber-400">
          <i class="fas fa-user-check"></i>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 hover:shadow-lg transition-shadow">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Avg Kehadiran</p>
          <p class="text-2xl font-bold mt-1">{{ $avgKehadiran }}%</p>
          <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-1.5 mt-2 overflow-hidden">
            <div class="bg-primary-500 h-1.5 rounded-full transition-all duration-700" style="width: {{ $avgKehadiran }}%"></div>
          </div>
        </div>
        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center text-blue-600 dark:text-blue-400">
          <i class="fas fa-chart-line"></i>
        </div>
      </div>
    </div>
  </div>

  {{-- 3. Aksi Cepat & Info --}}
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 text-center">
        <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide">Total Mahasiswa</p>
        <p class="text-xl font-bold mt-1 text-primary-600">{{ $totalMahasiswaDiampu }}</p>
    </div>
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 text-center">
        <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide">Status Dosen</p>
        <p class="text-xl font-bold mt-1 text-emerald-600">Aktif</p>
    </div>
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 text-center">
        <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide">Materi Terinput</p>
        <p class="text-xl font-bold mt-1 text-primary-600">{{ $pertemuanSelesai }}</p>
    </div>
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 text-center">
        <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide">Hari Ini</p>
        <p class="text-xl font-bold mt-1 text-primary-600">{{ $jadwalHariIni->count() }} Sesi</p>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Aktivitas Presensi Terbaru di Kelas Dosen --}}
    <div class="lg:col-span-2 space-y-6">
      <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
          <h3 class="font-semibold text-lg text-slate-800 dark:text-slate-100">Log Presensi Terbaru</h3>
          <span class="text-xs font-bold text-slate-400 uppercase tracking-widest italic">Info Real-time</span>
        </div>
        <div class="divide-y divide-slate-200 dark:divide-slate-700 max-h-[400px] overflow-y-auto custom-scrollbar text-left">
          @forelse($recentPresensi as $item)
            <div class="p-4 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center text-primary-600 dark:text-primary-400 font-semibold text-sm uppercase">
                  {{ substr($item->mahasiswa->nama, 0, 1) }}
                </div>
                <div>
                  <p class="font-medium text-sm text-slate-800 dark:text-slate-100 leading-tight">{{ $item->mahasiswa->nama }}</p>
                  <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1">
                    {{ $item->pertemuan->kelasPerkuliahan->mataKuliah->nama }} (GOL {{ $item->mahasiswa->golongan->nama }})
                  </p>
                </div>
              </div>
              <div class="text-right">
                <span class="px-2 py-1 rounded-full text-[9px] font-black uppercase tracking-wider {{ $item->status == 'hadir' ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }}">
                  {{ $item->status }}
                </span>
                <p class="text-[9px] text-slate-400 mt-1">{{ $item->created_at->diffForHumans() }}</p>
              </div>
            </div>
          @empty
            <div class="p-8 text-center text-slate-400 italic">Belum ada aktivitas presensi hari ini.</div>
          @endforelse
        </div>
      </div>
    </div>

    {{-- Jadwal Hari Ini & Shortcut --}}
    <div class="space-y-6">
      <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm text-left">
        <div class="p-5 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/20">
          <h3 class="font-semibold text-slate-800 dark:text-white">Jadwal Mengajar</h3>
          <span class="px-2 py-1 bg-primary-600 text-white text-[9px] font-black rounded-md uppercase">{{ now()->translatedFormat('l') }}</span>
        </div>
        <div class="divide-y divide-slate-100 dark:divide-slate-700">
          @forelse($jadwalHariIni as $j)
            <a href="{{ route('dosen.kelas.show', $j->kelas_perkuliahan_id) }}" class="p-4 block hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-all group">
              <p class="font-bold text-sm text-slate-800 dark:text-slate-100 group-hover:text-primary-600 transition-colors leading-tight">{{ $j->kelasPerkuliahan->mataKuliah->nama }}</p>
              <div class="flex items-center gap-2 mt-2">
                <span class="text-[10px] font-black text-slate-400 uppercase"><i class="fas fa-clock mr-1"></i> {{ $j->jam_mulai }} - {{ $j->jam_selesai }}</span>
                <span class="text-[10px] font-black text-emerald-600 uppercase border-l pl-2 border-slate-200"><i class="fas fa-door-open mr-1"></i> {{ $j->kelasPerkuliahan->ruang->nama }}</span>
              </div>
            </a>
          @empty
            <div class="p-8 text-center text-slate-400 text-xs italic">Tidak ada jadwal mengajar hari ini.</div>
          @endforelse
        </div>
      </div>

      {{-- Shortcut Card --}}
      <div class="bg-primary-600 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden group">
        <div class="relative z-10">
          <p class="text-xs opacity-80 uppercase tracking-widest font-black mb-1">Akses Cepat</p>
          <h3 class="text-lg font-bold mb-4 italic">Kelola Kelas Perkuliahan</h3>
          <a href="{{ route('dosen.kelas.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-primary-600 rounded-xl text-xs font-black uppercase tracking-wider hover:bg-primary-50 transition-all">
            Lihat Semua Kelas <i class="fas fa-arrow-right"></i>
          </a>
        </div>
        <i class="fas fa-chalkboard-teacher absolute -right-4 -bottom-4 text-8xl opacity-10 group-hover:scale-110 transition-transform"></i>
      </div>
    </div>
  </div>

@endsection