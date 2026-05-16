@extends('layouts.app')

@section('title', 'Log Presensi Global • SIPRESPRO')

@section('content')
  <div class="space-y-6 mx-auto p-2 sm:p-4 text-left">
    <!-- Header Command Center -->
    <div
      class="bg-white dark:bg-slate-800 p-5 sm:p-8 rounded-4xl sm:rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm relative overflow-hidden">
      <div class="absolute -right-10 -top-10 w-40 h-40 bg-primary-500/5 rounded-full blur-3xl"></div>
      <div>
        <h1 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Monitoring Sesi
          & Kehadiran</h1>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-medium mt-0.5">Log kontrol terpusat seluruh
          aktivitas absensi perkuliahan mahasiswa</p>
      </div>
    </div>

    <!-- Filter Multi-Option (Responsive Stacked Grid) -->
    <form method="GET" action="{{ route('admin.presensi.index') }}"
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl sm:rounded-3xl p-3 sm:p-4 flex flex-col lg:flex-row gap-3 items-stretch lg:items-center shadow-xs">

      {{-- Input Search Text --}}
      <div class="relative flex-1">
        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs sm:text-sm"></i>
        <input type="text" name="search" value="{{ request('search') }}"
          placeholder="Cari Mata Kuliah, Kode MK, atau Nama Dosen..."
          class="w-full pl-9 pr-4 py-2 sm:py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold outline-none focus:ring-2 focus:ring-primary-500/50 transition-all">
      </div>

      <div class="flex flex-wrap sm:flex-nowrap gap-2 items-center w-full lg:w-auto">
        {{-- Filter Dropdown Dosen Pengampu --}}
        <select name="dosen_id"
          class="w-full sm:w-64 px-3 py-2 sm:py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-white font-bold outline-none focus:ring-2 focus:ring-primary-500/50">
          <option value="">Semua Dosen Pengampu</option>
          @foreach($dosens as $d)
            <option value="{{ $d->id }}" {{ request('dosen_id') == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>
          @endforeach
        </select>

        {{-- Filter Input Tanggal Sesi --}}
        <input type="date" name="tanggal" value="{{ request('tanggal') }}"
          class="w-full sm:w-auto px-3 py-2 sm:py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-white font-bold outline-none focus:ring-2 focus:ring-primary-500/50">

        <button type="submit"
          class="w-full sm:w-auto px-5 py-2 sm:py-2.5 text-xs sm:text-sm font-black uppercase tracking-wider text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition shadow-md shadow-primary-500/10 shrink-0">
          Filter
        </button>
      </div>
    </form>

    <!-- Main Output Data Area -->
    <div
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-4xl sm:rounded-[2.5rem] overflow-hidden shadow-xs">

      <!-- DESKTOP DISPLAY LAYOUT TABLE (hidden md:block) -->
      <div class="hidden md:block overflow-x-auto p-4 sm:p-6">
        <table class="w-full text-left border-separate border-spacing-y-2">
          <thead class="text-slate-400">
            <tr>
              <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest">Mata Kuliah & Sesi</th>
              <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest">Dosen Pengampu</th>
              <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-center">Status Sesi</th>
              <th class="px-5 py-3 text-[10px] font-black uppercase tracking-widest text-center">Ringkasan Absen</th>
              <th class="px-5 py-3 text-right text-[10px] font-black uppercase tracking-widest">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($logs as $log)
              <tr class="bg-slate-50 dark:bg-slate-900/50 hover:bg-white dark:hover:bg-slate-800 transition-all shadow-xs">
                <td class="px-5 py-4 rounded-l-2xl">
                  <span
                    class="text-[9px] font-black px-2 py-0.5 bg-primary-50 text-primary-600 dark:bg-primary-950/30 dark:text-primary-400 rounded">PERTETUAN
                    #{{ $log->pertemuan_ke }}</span>
                  <p class="text-sm font-black text-slate-800 dark:text-white mt-1.5 leading-tight truncate max-w-[220px]">
                    {{ $log->kelasPerkuliahan->mataKuliah->nama }}</p>
                  <p class="text-[10px] text-slate-400 font-bold uppercase mt-0.5 font-mono">{{ $log->kelasPerkuliahan->nama_kelas }}
                    <span class="text-slate-300 mx-1">|</span>
                    {{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('d M Y') }}</p>
                </td>
                <td class="px-5 py-4">
                  <p class="text-xs font-black text-slate-700 dark:text-slate-300 leading-tight truncate max-w-[180px]">
                    {{ $log->kelasPerkuliahan->dosen->nama ?? 'Sistem' }}</p>
                  <p class="text-[10px] text-slate-400 font-semibold mt-1 italic uppercase"><i
                      class="fas fa-clock mr-1 text-primary-500"></i>{{ substr($log->jam_mulai, 0, 5) }} -
                    {{ substr($log->jam_selesai, 0, 5) }} WIB</p>
                </td>
                <td class="px-5 py-4 text-center">
                  <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wider {{ $log->status == 'dibuka' ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-400' : 'bg-slate-200 text-slate-500 dark:bg-slate-700 dark:text-slate-400' }}">
                    @if($log->status == 'dibuka') <span
                    class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> @endif
                    {{ $log->status == 'dibuka' ? 'Aktif' : 'Tutup' }}
                  </span>
                </td>
                <td class="px-5 py-4 text-center">
                  {{-- Pill Status Kombinasi Indikator Angka --}}
                  <div class="flex items-center justify-center gap-1 text-[10px] font-mono font-bold">
                    <span
                      class="px-1.5 py-0.5 bg-emerald-50 text-emerald-600 dark:bg-emerald-950/20 dark:text-emerald-400 rounded"
                      title="Hadir">H:{{ $log->total_hadir }}</span>
                    <span class="px-1.5 py-0.5 bg-blue-50 text-blue-600 dark:bg-blue-950/20 dark:text-blue-400 rounded"
                      title="Sakit">S:{{ $log->total_sakit }}</span>
                    <span class="px-1.5 py-0.5 bg-amber-50 text-amber-600 dark:bg-amber-950/20 dark:text-amber-400 rounded"
                      title="Izin">I:{{ $log->total_izin }}</span>
                    <span class="px-1.5 py-0.5 bg-rose-50 text-rose-600 dark:bg-rose-950/20 dark:text-rose-400 rounded"
                      title="Alfa">A:{{ $log->total_alfa }}</span>
                  </div>
                </td>
                <td class="px-5 py-4 text-right rounded-r-2xl">
                  <a href="{{ route('admin.presensi.show', $log->id) }}"
                    class="inline-flex w-8 h-8 items-center justify-center rounded-xl bg-white dark:bg-slate-800 text-slate-500 border border-slate-200 dark:border-slate-700 hover:bg-primary-600 hover:text-white dark:hover:bg-primary-600 dark:hover:text-white shadow-xs transition-all duration-200 group">
                    <i class="fas fa-eye text-xs group-hover:scale-110 transition-transform"></i>
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-5 py-16 text-center text-slate-400 italic text-sm">Tidak ada log aktivitas absensi
                  yang tercatat.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- MOBILE DISPLAY LAYOUT CARD (block md:hidden) -->
      <div class="block md:hidden p-4 space-y-3">
        @forelse($logs as $log)
          <div
            class="bg-slate-50 dark:bg-slate-900/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-800/80 shadow-xs space-y-3">
            <div class="flex justify-between items-start gap-2">
              <div class="min-w-0">
                <span
                  class="text-[8px] font-black px-1.5 py-0.5 bg-primary-100 text-primary-600 dark:bg-primary-950/30 dark:text-primary-400 rounded font-mono">SESI
                  #{{ $log->pertemuan_ke }}</span>
                <h4 class="font-black text-slate-800 dark:text-white text-sm leading-tight mt-1.5 truncate">
                  {{ $log->kelasPerkuliahan->mataKuliah->nama }}</h4>
                <p class="text-[10px] text-slate-400 font-bold mt-0.5 uppercase tracking-wide truncate">
                  {{ $log->kelasPerkuliahan->nama_kelas }}</p>
              </div>

              <span
                class="px-2 py-0.5 rounded-md text-[8px] font-black uppercase tracking-wide shrink-0 border {{ $log->status == 'dibuka' ? 'bg-emerald-100 text-emerald-600 border-emerald-200' : 'bg-slate-200 text-slate-500 border-slate-300' }}">
                {{ $log->status == 'dibuka' ? 'Aktif' : 'Tutup' }}
              </span>
            </div>

            <div
              class="text-[11px] text-slate-500 dark:text-slate-400 space-y-0.5 font-medium border-t border-b border-slate-200/50 dark:border-slate-700/50 py-2">
              <p class="truncate"><span class="font-bold text-slate-700 dark:text-slate-300">Dosen:</span>
                {{ $log->kelasPerkuliahan->dosen->nama ?? 'Sistem' }}</p>
              <p><span class="font-bold text-slate-700 dark:text-slate-300">Tanggal:</span>
                {{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('l, d M Y') }}</p>
            </div>

            <div class="flex justify-between items-center pt-1" @click.stop>
              {{-- Stats Box Mini Mobile --}}
              <div class="flex gap-1 text-[9px] font-mono font-black">
                <span class="px-1.5 py-0.5 bg-emerald-50 text-emerald-600 rounded">H:{{ $log->total_hadir }}</span>
                <span class="px-1.5 py-0.5 bg-blue-50 text-blue-600 rounded">S:{{ $log->total_sakit }}</span>
                <span class="px-1.5 py-0.5 bg-amber-50 text-amber-600 rounded">I:{{ $log->total_izin }}</span>
                <span class="px-1.5 py-0.5 bg-rose-50 text-rose-600 rounded">A:{{ $log->total_alfa }}</span>
              </div>

              <a href="{{ route('admin.presensi.show', $log->id) }}"
                class="px-3 py-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-primary-600 dark:text-primary-400 font-black uppercase text-[9px] tracking-wider rounded-xl shadow-xs flex items-center gap-1">
                <i class="fas fa-eye"></i> Detail Logs
              </a>
            </div>
          </div>
        @empty
          <div class="p-8 text-center text-slate-400 italic text-xs">Tidak ada log aktivitas absensi yang tercatat.</div>
        @endforelse
      </div>

      {{-- Pagination Laravel Link Zone --}}
      @if($logs->hasPages())
        <div
          class="px-4 py-3 sm:px-6 sm:py-4 bg-slate-50/50 dark:bg-slate-900/20 border-t border-t-slate-100 dark:border-t-slate-700">
          {{ $logs->links() }}
        </div>
      @endif
    </div>
  </div>
@endsection