@extends('layouts.app')

@section('content')
  <div class="space-y-8">
    {{-- Header --}}
    <div>
      <h1 class="text-3xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Rekap Presensi</h1>
      <p class="text-sm text-slate-500 font-bold uppercase tracking-widest">Ringkasan Kehadiran Seluruh Kelas</p>
    </div>

    {{-- Grid Rekap Per Kelas --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($dataRekap as $rekap)
        <div
          class="group bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-xl hover:border-primary-500/50 transition-all duration-300 relative overflow-hidden">
          {{-- Background Decoration --}}
          <div
            class="absolute -right-5 -top-5 w-24 h-24 bg-primary-500/5 rounded-full blur-2xl group-hover:bg-primary-500/10 transition-colors">
          </div>

          <div class="relative space-y-6">
            <div>
              <span
                class="px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-900 text-slate-500 text-[10px] font-black uppercase tracking-widest border border-slate-200 dark:border-slate-700">
                {{ $rekap['kode'] }}
              </span>
              <h3
                class="text-xl font-black text-slate-800 dark:text-white mt-3 leading-tight group-hover:text-primary-600 transition-colors">
                {{ $rekap['matkul'] }}
              </h3>
              <p class="text-xs text-slate-400 font-bold uppercase mt-1">{{ $rekap['kelas'] }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-50 dark:border-slate-700">
              <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Mahasiswa</p>
                <p class="text-lg font-black text-slate-800 dark:text-white">{{ $rekap['total_mhs'] }}</p>
              </div>
              <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Sesi Selesai</p>
                <p class="text-lg font-black text-slate-800 dark:text-white">{{ $rekap['pertemuan_jalan'] }}</p>
              </div>
            </div>

            {{-- Progress Bar Kehadiran Rata-rata Kelas --}}
            <div class="space-y-2">
              <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest">
                <span class="text-slate-400">Rerata Kehadiran</span>
                <span class="{{ $rekap['persentase'] < 75 ? 'text-rose-500' : 'text-emerald-500' }}">
                  {{ number_format($rekap['persentase'], 1) }}%
                </span>
              </div>
              <div class="w-full h-2 bg-slate-100 dark:bg-slate-900 rounded-full overflow-hidden">
                <div
                  class="h-full transition-all duration-500 {{ $rekap['persentase'] < 75 ? 'bg-rose-500' : 'bg-emerald-500' }}"
                  style="width: {{ $rekap['persentase'] }}%"></div>
              </div>
            </div>

            <a href="{{ route('dosen.kelas.show', $rekap['id']) }}"
              class="flex items-center justify-center w-full py-4 bg-slate-50 dark:bg-slate-900 hover:bg-primary-600 text-slate-600 dark:text-slate-400 hover:text-white rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all">
              Detail Sesi & Rekap
            </a>
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endsection