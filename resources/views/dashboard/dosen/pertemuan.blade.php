@extends('layouts.app')
@section('content')
  <div class="space-y-6 mx-auto max-w-7xl p-2 sm:p-4 text-left">
    <div
      class="bg-white dark:bg-slate-800 p-5 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm relative overflow-hidden">
      <div class="absolute -right-10 -top-10 w-40 h-40 bg-primary-500/5 rounded-full blur-3xl"></div>
      <div class="relative flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-4 sm:gap-5">
          <div
            class="w-12 h-12 sm:w-16 sm:h-16 bg-primary-600 text-white rounded-2xl sm:rounded-3xl flex items-center justify-center shadow-xl shadow-primary-500/20 font-black text-xl sm:text-2xl shrink-0">
            {{ $pertemuan->pertemuan_ke }}
          </div>
          <div class="min-w-0">
            <h1 class="text-lg sm:text-2xl font-black text-slate-800 dark:text-white uppercase leading-tight truncate">
              Rekap Sesi #{{ $pertemuan->pertemuan_ke }}
            </h1>
            <p
              class="text-[10px] sm:text-sm text-slate-500 font-bold flex flex-wrap items-center gap-1 sm:gap-2 uppercase tracking-widest mt-0.5 sm:mt-1">
              <span class="truncate max-w-[150px] sm:max-w-none">{{ $pertemuan->nama_mk }}</span>
              <span class="text-slate-300">•</span>
              <span>{{ $pertemuan->nama_kelas }}</span>
            </p>
          </div>
        </div>
        <a href="{{ route('dosen.pertemuan.export-excel', $pertemuan->id) }}"
          class="no-print w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl sm:rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-lg shadow-emerald-100 dark:shadow-none hover:-translate-y-0.5">
          <i class="fas fa-file-excel"></i> Export Excel
        </a>
      </div>
    </div>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
      @php
        $totalMhs = $mahasiswas->count();
        $hadir = $presensis->where('status', 'hadir')->count();
        $sakit = $presensis->where('status', 'sakit')->count();
        $izin = $presensis->where('status', 'izin')->count();
        $alfa = $totalMhs - ($hadir + $sakit + $izin);
      @endphp
      <div
        class="bg-white dark:bg-slate-800 p-4 sm:p-5 rounded-2xl sm:rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm text-center">
        <p class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Mahasiswa</p>
        <p class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white">{{ $totalMhs }}</p>
      </div>
      <div
        class="bg-emerald-50 dark:bg-emerald-950/20 p-4 sm:p-5 rounded-2xl sm:rounded-3xl border border-emerald-100 dark:border-emerald-900/30 shadow-sm text-center">
        <p
          class="text-[9px] sm:text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest mb-1">
          Hadir</p>
        <p class="text-xl sm:text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ $hadir }}</p>
      </div>
      <div
        class="bg-amber-50 dark:bg-amber-950/20 p-4 sm:p-5 rounded-2xl sm:rounded-3xl border border-amber-100 dark:border-amber-900/30 shadow-sm text-center">
        <p class="text-[9px] sm:text-[10px] font-black text-amber-600 dark:text-amber-400 uppercase tracking-widest mb-1">
          Sakit / Izin</p>
        <p class="text-xl sm:text-2xl font-black text-amber-600 dark:text-amber-400">{{ $sakit + $izin }}</p>
      </div>
      <div
        class="bg-rose-50 dark:bg-rose-950/20 p-4 sm:p-5 rounded-2xl sm:rounded-3xl border border-rose-100 dark:border-rose-900/30 shadow-sm text-center">
        <p class="text-[9px] sm:text-[10px] font-black text-rose-600 dark:text-rose-400 uppercase tracking-widest mb-1">
          Alfa</p>
        <p class="text-xl sm:text-2xl font-black text-rose-600 dark:text-rose-400">{{ $alfa }}</p>
      </div>
    </div>
    <div
      class="bg-white dark:bg-slate-800 rounded-[2rem] sm:rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
      <div
        class="p-5 sm:p-8 border-b border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row justify-between sm:items-center gap-3">
        <h2 class="font-black text-slate-800 dark:text-white uppercase text-xs sm:text-sm tracking-widest">Daftar
          Kehadiran</h2>
        <div
          class="text-[9px] sm:text-[10px] font-black px-3 py-1.5 bg-slate-100 dark:bg-slate-900 text-slate-500 rounded-full italic self-start sm:self-auto">
          Sesi: {{ substr($pertemuan->jam_mulai, 0, 5) }} - {{ substr($pertemuan->jam_selesai, 0, 5) }} WIB
        </div>
      </div>
      <div class="hidden md:block overflow-x-auto p-6">
        <table class="w-full text-left border-separate border-spacing-y-2">
          <thead>
            <tr>
              <th class="px-6 py-3 text-[10px] font-black uppercase text-slate-400 tracking-widest">Mahasiswa</th>
              <th class="px-6 py-3 text-[10px] font-black uppercase text-slate-400 tracking-widest">Waktu Absen</th>
              <th class="px-6 py-3 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Status
              </th>
              <th class="px-6 py-3 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">Koordinat
                (GPS)</th>
            </tr>
          </thead>
          <tbody>
            @foreach($mahasiswas as $mhs)
              @php $presensi = $presensis->get($mhs->id); @endphp
              <tr
                class="bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-900 transition-all shadow-sm">
                <td class="px-6 py-4 rounded-l-2xl">
                  <p class="font-black text-slate-800 dark:text-white leading-tight break-all">{{ $mhs->nama }}</p>
                  <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter mt-0.5">{{ $mhs->nim }}</p>
                </td>
                <td class="px-6 py-4">
                  @if($presensi)
                    <span class="text-xs font-bold text-slate-600 dark:text-slate-300">
                      <i
                        class="far fa-clock mr-1.5 text-primary-500"></i>{{ date('H:i', strtotime($presensi->waktu_presensi)) }}
                      WIB
                    </span>
                  @else
                    <span class="text-xs text-slate-300 italic font-medium dark:text-slate-600">Belum Mengisi</span>
                  @endif
                </td>
                <td class="px-6 py-4 text-center">
                  @if($presensi)
                    @if($presensi->status == 'hadir')
                      <span
                        class="px-3 py-1 bg-emerald-100 text-emerald-600 rounded-full text-[9px] font-black uppercase tracking-widest border border-emerald-200 dark:bg-emerald-950/30 dark:text-emerald-400 dark:border-emerald-900/30">HADIR</span>
                    @elseif($presensi->status == 'sakit')
                      <span
                        class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-[9px] font-black uppercase tracking-widest border border-blue-200 dark:bg-blue-950/30 dark:text-blue-400 dark:border-blue-900/30">SAKIT</span>
                    @elseif($presensi->status == 'izin')
                      <span
                        class="px-3 py-1 bg-amber-100 text-amber-600 rounded-full text-[9px] font-black uppercase tracking-widest border border-amber-200 dark:bg-amber-950/30 dark:text-amber-400 dark:border-amber-900/30">IZIN</span>
                    @endif
                  @else
                    <span
                      class="px-3 py-1 bg-rose-100 text-rose-600 rounded-full text-[9px] font-black uppercase tracking-widest border border-rose-200 dark:bg-rose-950/30 dark:text-rose-400 dark:border-rose-900/30">ALFA</span>
                  @endif
                </td>
                <td class="px-6 py-4 text-right rounded-r-2xl text-xs font-medium text-slate-500 dark:text-slate-400">
                  @if($presensi)
                    @if($presensi->latitude && $presensi->longitude)
                      <span
                        class="font-mono text-[11px] bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded border border-slate-200 dark:border-slate-700/60">
                        {{ round($presensi->latitude, 5) }}, {{ round($presensi->longitude, 5) }}
                      </span>
                    @else
                      <span class="text-[10px] text-slate-400 italic">No GPS Data (Luar Kampus)</span>
                    @endif
                  @else
                    <span class="text-[10px] text-rose-400 font-black uppercase tracking-wider">Tanpa Keterangan</span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="block md:hidden p-4 space-y-3">
        @foreach($mahasiswas as $mhs)
          @php $presensi = $presensis->get($mhs->id); @endphp
          <div
            class="bg-slate-50 dark:bg-slate-900/50 p-4 rounded-2xl border border-slate-100 dark:border-slate-800/60 shadow-xs space-y-3">
            <div class="flex justify-between items-start gap-2">
              <div class="min-w-0">
                <h4 class="font-black text-slate-800 dark:text-white text-sm leading-tight break-words">{{ $mhs->nama }}
                </h4>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter mt-0.5">{{ $mhs->nim }}</p>
              </div>
              <div class="shrink-0">
                @if($presensi)
                  @if($presensi->status == 'hadir')
                    <span
                      class="px-2.5 py-1 bg-emerald-100 text-emerald-600 rounded-lg text-[8px] font-black uppercase border border-emerald-200 dark:bg-emerald-950/30 dark:text-emerald-400">HADIR</span>
                  @elseif($presensi->status == 'sakit')
                    <span
                      class="px-2.5 py-1 bg-blue-100 text-blue-600 rounded-lg text-[8px] font-black uppercase border border-blue-200 dark:bg-blue-950/30 dark:text-blue-400">SAKIT</span>
                  @elseif($presensi->status == 'izin')
                    <span
                      class="px-2.5 py-1 bg-amber-100 text-amber-600 rounded-lg text-[8px] font-black uppercase border border-amber-200 dark:bg-amber-950/30 dark:text-amber-400">IZIN</span>
                  @endif
                @else
                  <span
                    class="px-2.5 py-1 bg-rose-100 text-rose-600 rounded-lg text-[8px] font-black uppercase border border-rose-200 dark:bg-rose-950/30 dark:text-rose-400">ALFA</span>
                @endif
              </div>
            </div>
            <div
              class="pt-2 border-t border-slate-200/50 dark:border-slate-700/50 flex justify-between items-center text-[11px]">
              <div class="text-slate-500 dark:text-slate-400 font-medium">
                @if($presensi)
                  <i class="far fa-clock text-primary-500 mr-1"></i> {{ date('H:i', strtotime($presensi->waktu_presensi)) }}
                  WIB
                @else
                  <span class="text-slate-300 dark:text-slate-600 italic">Belum mengisi</span>
                @endif
              </div>
              <div>
                @if($presensi)
                  @if($presensi->latitude && $presensi->longitude)
                    <span
                      class="font-mono text-[10px] bg-white dark:bg-slate-800 px-2 py-0.5 rounded border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400">
                      {{ round($presensi->latitude, 4) }}, {{ round($presensi->longitude, 4) }}
                    </span>
                  @else
                    <span class="text-[10px] text-slate-400 italic">No GPS</span>
                  @endif
                @else
                  <span class="text-[10px] text-rose-400 font-bold uppercase">Mangkir</span>
                @endif
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
@endsection