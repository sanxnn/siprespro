@extends('layouts.app')
@section('content')
  <div class="mx-auto p-3 sm:p-6 space-y-4 sm:space-y-6">
    <div
      class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white dark:bg-slate-800 p-4 sm:p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
      <div>
        <h1 class="text-lg sm:text-xl font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
          <i class="fas fa-clipboard-list text-primary-500"></i> Presensi Hari Ini
        </h1>
        <p class="text-xs text-slate-400 mt-0.5 sm:mt-1">Silakan ambil absensi sesuai dengan jam operasional kelas
          perkuliahan.</p>
      </div>
      <div
        class="text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-700/50 px-3 sm:px-4 py-2 rounded-xl border border-slate-100 dark:border-slate-700 self-start sm:self-auto">
        <i class="far fa-calendar-alt mr-1.5 text-primary-500"></i> {{ date('d F Y') }}
      </div>
    </div>
    @if($pertemuanHariIni->isEmpty())
      <div
        class="bg-white dark:bg-slate-800 rounded-2xl p-8 sm:p-12 border border-dashed border-slate-200 dark:border-slate-700 text-center shadow-sm">
        <div
          class="w-12 h-12 sm:w-16 sm:h-16 bg-slate-50 dark:bg-slate-700/30 rounded-full flex items-center justify-center mx-auto text-slate-400 mb-4">
          <i class="fas fa-calendar-times text-xl sm:text-2xl"></i>
        </div>
        <h4 class="text-sm font-bold text-slate-700 dark:text-slate-300">Tidak Ada Jadwal Pertemuan Hari Ini</h4>
        <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto px-4">Semua kelas untuk golongan Anda hari ini kosong atau
          belum dikonfigurasi oleh akademik.</p>
      </div>
    @else
      <div class="grid grid-cols-1 gap-4">
        @foreach($pertemuanHariIni as $pertemuan)
          <div
            class="bg-white dark:bg-slate-800 rounded-2xl p-4 sm:p-5 border border-slate-100 dark:border-slate-700/50 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 w-full lg:w-auto">
              <div
                class="p-3 rounded-xl bg-slate-50 dark:bg-slate-700/40 border border-slate-100 dark:border-slate-700 text-slate-600 dark:text-slate-300 text-center flex sm:flex-col justify-between sm:justify-center items-center sm:min-w-[100px] gap-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 sm:block">Waktu Kuliah</span>
                <div class="flex sm:block items-center gap-1">
                  <span
                    class="text-xs sm:text-sm font-extrabold text-primary-600 dark:text-primary-400">{{ date('H:i', strtotime($pertemuan->jam_mulai)) }}</span>
                  <span class="text-[10px] text-slate-400 mx-0.5 sm:block">s/d</span>
                  <span
                    class="text-xs sm:text-sm font-extrabold text-primary-600 dark:text-primary-400">{{ date('H:i', strtotime($pertemuan->jam_selesai)) }}</span>
                </div>
              </div>
              <div class="space-y-1.5 flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                  <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-primary-50 text-primary-700 dark:bg-primary-950/40 dark:text-primary-400 border border-primary-100 dark:border-primary-900/50">
                    Pertemuan #{{ $pertemuan->pertemuan_ke }}
                  </span>
                  <span class="text-slate-300 dark:text-slate-600 text-xs">•</span>
                  <span class="text-[11px] font-medium text-slate-400 tracking-wide">{{ $pertemuan->kode_mk }}</span>
                </div>
                <h3 class="text-sm sm:text-base font-bold text-slate-800 dark:text-slate-100 tracking-tight wrap-break-word">
                  {{ $pertemuan->nama_mk }}
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1.5 truncate">
                  <i class="fas fa-user-tie text-slate-400 text-[11px] shrink-0"></i>
                  <span class="truncate">{{ $pertemuan->nama_dosen }}</span>
                </p>
                <div
                  class="flex flex-wrap items-center gap-x-3 gap-y-1 pt-1 text-[11px] sm:text-xs font-medium text-slate-400">
                  <span class="flex items-center gap-1"><i class="fas fa-map-marker-alt text-rose-500 text-[11px]"></i> Ruang:
                    <span class="text-slate-600 dark:text-slate-300 font-semibold">{{ $pertemuan->ruangan }}</span></span>
                  <span class="text-slate-200 dark:text-slate-700 hidden sm:inline">|</span>
                  <span class="flex items-center gap-1"><i class="fas fa-layer-group text-[11px]"></i> Beban: <span
                      class="text-slate-600 dark:text-slate-300 font-semibold">{{ $pertemuan->sks }} SKS</span></span>
                </div>
              </div>
            </div>
            <div
              class="border-t md:border-t-0 pt-4 md:pt-0 border-slate-100 dark:border-slate-700 flex items-center justify-between md:justify-end gap-4 min-w-[140px]">
              <span class="text-xs text-slate-400 font-semibold lg:hidden uppercase tracking-wider">Status Presensi</span>
              @if($pertemuan->status_absen_mhs)
                @if($pertemuan->status_absen_mhs == 'hadir')
                  <span
                    class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold shadow-sm border w-auto justify-center bg-green-50 text-green-700 border-green-100 dark:bg-green-950/20 dark:text-green-400 dark:border-green-900/30">
                    <i class="fas fa-check-circle mr-1.5 text-sm sm:text-xs"></i> {{ ucwords($pertemuan->status_absen_mhs) }}
                  </span>
                @elseif(in_array($pertemuan->status_absen_mhs, ['sakit', 'izin']))
                  <span
                    class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold shadow-sm border w-auto justify-center bg-amber-50 text-amber-700 border-amber-100 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-900/30">
                    <i class="fas fa-check-circle mr-1.5 text-sm sm:text-xs"></i> {{ ucwords($pertemuan->status_absen_mhs) }}
                  </span>
                @else
                  <span
                    class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold shadow-sm border w-auto justify-center bg-rose-50 text-rose-700 border-rose-100 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-900/30">
                    <i class="fas fa-check-circle mr-1.5 text-sm sm:text-xs"></i> {{ ucwords($pertemuan->status_absen_mhs) }}
                  </span>
                @endif
              @else
                @if($pertemuan->status_buka_absen == 'dibuka')
                  <a href="{{ route('mahasiswa.presensi.show', $pertemuan->pertemuan_id) }}"
                    class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold transition-all shadow-md shadow-primary-100 dark:shadow-none hover:-translate-y-0.5 w-auto sm:w-auto text-center">
                    <i class="fas fa-fingerprint mr-2 text-sm"></i> Ambil Absen
                  </a>
                @else
                  <span
                    class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-50 dark:bg-slate-700/50 text-slate-400 dark:text-slate-500 border border-slate-100 dark:border-slate-700 w-auto justify-center">
                    <i class="fas fa-lock mr-1.5 text-[11px]"></i> Belum Dibuka
                  </span>
                @endif
              @endif
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
@endsection