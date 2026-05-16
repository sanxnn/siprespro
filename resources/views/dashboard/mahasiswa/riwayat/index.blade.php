@extends('layouts.app')
@section('content')
  <div class="mx-auto p-3 sm:p-6 space-y-4 sm:space-y-6">
    <div
      class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white dark:bg-slate-800 p-4 sm:p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm">
      <div>
        <h1 class="text-lg sm:text-xl font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
          <i class="fas fa-history text-primary-500"></i> Riwayat Kehadiran
        </h1>
        <p class="text-xs text-slate-400 mt-0.5 sm:mt-1">Rekapitulasi seluruh aktivitas presensi Anda di semester ini.</p>
      </div>
      <div class="flex items-center gap-2">
        <div
          class="text-[10px] sm:text-xs font-bold text-slate-500 bg-slate-100 dark:bg-slate-700 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-600">
          Total: {{ $riwayatPresensi->count() }} Mata Kuliah
        </div>
      </div>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
      @php
        $totalHadir = $riwayatPresensi->sum('jumlah_hadir');
        $totalSakit = $riwayatPresensi->sum('jumlah_sakit');
        $totalIzin = $riwayatPresensi->sum('jumlah_izin');
        $totalAlfa = $riwayatPresensi->sum('jumlah_alfa');
      @endphp
      <div
        class="bg-white dark:bg-slate-800 p-3 rounded-xl border border-slate-100 dark:border-slate-700/50 shadow-sm flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-green-50 dark:bg-green-950/30 flex items-center justify-center text-green-600">
          <i class="fas fa-check-circle text-xs"></i>
        </div>
        <div>
          <p class="text-[10px] text-slate-400 font-bold uppercase">Hadir</p>
          <p class="text-sm font-bold dark:text-white">{{ $totalHadir }}</p>
        </div>
      </div>
      <div
        class="bg-white dark:bg-slate-800 p-3 rounded-xl border border-slate-100 dark:border-slate-700/50 shadow-sm flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/30 flex items-center justify-center text-amber-600">
          <i class="fas fa-medkit text-xs"></i>
        </div>
        <div>
          <p class="text-[10px] text-slate-400 font-bold uppercase">Sakit</p>
          <p class="text-sm font-bold dark:text-white">{{ $totalSakit }}</p>
        </div>
      </div>
      <div
        class="bg-white dark:bg-slate-800 p-3 rounded-xl border border-slate-100 dark:border-slate-700/50 shadow-sm flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/30 flex items-center justify-center text-blue-600"><i
            class="fas fa-envelope text-xs"></i></div>
        <div>
          <p class="text-[10px] text-slate-400 font-bold uppercase">Izin</p>
          <p class="text-sm font-bold dark:text-white">{{ $totalIzin }}</p>
        </div>
      </div>
      <div
        class="bg-white dark:bg-slate-800 p-3 rounded-xl border border-slate-100 dark:border-slate-700/50 shadow-sm flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-950/30 flex items-center justify-center text-rose-600"><i
            class="fas fa-times-circle text-xs"></i></div>
        <div>
          <p class="text-[10px] text-slate-400 font-bold uppercase">Alfa</p>
          <p class="text-sm font-bold dark:text-white">{{ $totalAlfa }}</p>
        </div>
      </div>
    </div>
    @if($riwayatPresensi->isEmpty())
      <div
        class="bg-white dark:bg-slate-800 rounded-2xl p-12 border border-dashed border-slate-200 dark:border-slate-700 text-center">
        <div
          class="w-16 h-16 bg-slate-50 dark:bg-slate-700/30 rounded-full flex items-center justify-center mx-auto text-slate-400 mb-4">
          <i class="fas fa-folder-open text-2xl"></i>
        </div>
        <h4 class="text-sm font-bold text-slate-700 dark:text-slate-300">Belum Ada Data</h4>
        <p class="text-xs text-slate-400 mt-1">Belum ada kelas yang diselesaikan atau diisi absensinya.</p>
      </div>
    @else
      <div class="grid grid-cols-1 gap-4">
        @foreach($riwayatPresensi as $row)
          @php
            $totalMasuk = $row->jumlah_hadir + $row->jumlah_sakit + $row->jumlah_izin;
            $persentase = $row->total_pertemuan > 0
              ? round(($totalMasuk / $row->total_pertemuan) * 100)
              : 100; // Default 100 kalau baru mulai
            $isWarning = $persentase < 75;
          @endphp
          <div
            class="bg-white dark:bg-slate-800 rounded-2xl p-4 sm:p-5 border border-slate-100 dark:border-slate-700/50 shadow-sm flex flex-col gap-4">
            <div class="flex flex-col md:flex-row justify-between gap-4">
              <div class="space-y-1">
                <div class="flex items-center gap-2">
                  <span
                    class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600">
                    {{ $row->kode_mk }}
                  </span>
                  <span class="text-slate-300 dark:text-slate-600 text-xs">•</span>
                  <span class="text-[11px] font-medium text-slate-400 tracking-wide">Rekap Semester</span>
                </div>
                <h3 class="text-sm sm:text-base font-bold text-slate-800 dark:text-slate-100">{{ $row->nama_mk }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1.5 font-medium">
                  <i class="fas fa-user-tie text-[10px]"></i> {{ $row->nama_dosen }}
                </p>
              </div>
              <div class="min-w-[180px] space-y-2">
                <div class="flex justify-between items-end">
                  <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Persentase Hadir</span>
                  <span
                    class="text-sm font-black {{ $isWarning ? 'text-rose-500' : 'text-green-500' }}">{{ $persentase }}%</span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2 overflow-hidden flex">
                  <div class="{{ $isWarning ? 'bg-rose-500' : 'bg-green-500' }} h-full transition-all duration-500"
                    style="width: {{ $persentase }}%"></div>
                </div>
                @if($isWarning && $row->total_pertemuan > 0)
                  <p class="text-[9px] font-bold text-rose-500 italic text-right uppercase leading-none mt-1">
                    <i class="fas fa-exclamation-triangle"></i> Terancam Cekal Ujian
                  </p>
                @endif
              </div>
            </div>
            <div
              class="flex flex-wrap items-center justify-between gap-3 pt-4 border-t border-slate-50 dark:border-slate-700/50">
              <div class="flex items-center gap-4">
                <div class="flex flex-col">
                  <span class="text-[9px] uppercase font-bold text-slate-400 leading-none mb-1">Hadir</span>
                  <span class="text-xs font-bold text-green-600 dark:text-green-400">{{ $row->jumlah_hadir }}</span>
                </div>
                <div class="w-px h-6 bg-slate-100 dark:bg-slate-700"></div>
                <div class="flex flex-col">
                  <span class="text-[9px] uppercase font-bold text-slate-400 leading-none mb-1">Izin/Sakit</span>
                  <span
                    class="text-xs font-bold text-amber-600 dark:text-amber-400">{{ $row->jumlah_sakit + $row->jumlah_izin }}</span>
                </div>
                <div class="w-px h-6 bg-slate-100 dark:bg-slate-700"></div>
                <div class="flex flex-col">
                  <span class="text-[9px] uppercase font-bold text-slate-400 leading-none mb-1">Alfa</span>
                  <span class="text-xs font-bold text-rose-600 dark:text-rose-400">{{ $row->jumlah_alfa }}</span>
                </div>
              </div>
              <a href="{{ route('mahasiswa.presensi.detail-riwayat', $row->kelas_id) }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-primary-600 hover:text-white dark:hover:bg-primary-600 text-slate-600 dark:text-slate-300 text-xs font-bold transition-all group">
                Detail Log <i class="fas fa-chevron-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
              </a>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
@endsection