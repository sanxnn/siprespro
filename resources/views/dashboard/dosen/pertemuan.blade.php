@extends('layouts.app')

@section('content')
  <div class="space-y-6">
    <!-- Header: Info Pertemuan -->
    <div
      class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm relative overflow-hidden">
      <div class="absolute -right-10 -top-10 w-40 h-40 bg-primary-500/5 rounded-full blur-3xl"></div>
      <div class="relative flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="flex items-center gap-5">
          <div
            class="w-16 h-16 bg-primary-600 text-white rounded-3xl flex items-center justify-center shadow-xl shadow-primary-500/20 font-black text-2xl">
            {{ $pertemuan->pertemuan_ke }}
          </div>
          <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white uppercase leading-tight">
              Rekap Presensi Sesi #{{ $pertemuan->pertemuan_ke }}
            </h1>
            <p class="text-sm text-slate-500 font-bold flex items-center gap-2 uppercase tracking-widest">
              {{ $pertemuan->kelasPerkuliahan->mataKuliah->nama }} <span class="text-slate-300">•</span>
              {{ $pertemuan->kelasPerkuliahan->nama_kelas }}
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      @php
        $totalMhs = $mahasiswas->count();
        $hadir = $presensis->where('status', 'hadir')->count();
        $alfa = $totalMhs - $hadir;
      @endphp
      <div
        class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm text-center">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Mahasiswa</p>
        <p class="text-2xl font-black text-slate-800 dark:text-white">{{ $totalMhs }}</p>
      </div>
      <div
        class="bg-emerald-50 dark:bg-emerald-900/20 p-6 rounded-3xl border border-emerald-100 dark:border-emerald-800 shadow-sm text-center">
        <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-1">Hadir</p>
        <p class="text-2xl font-black text-emerald-600">{{ $hadir }}</p>
      </div>
      <div
        class="bg-rose-50 dark:bg-rose-900/20 p-6 rounded-3xl border border-rose-100 dark:border-rose-800 shadow-sm text-center">
        <p class="text-[10px] font-black text-rose-600 uppercase tracking-widest mb-1">Tidak Hadir</p>
        <p class="text-2xl font-black text-rose-600">{{ $alfa }}</p>
      </div>
      <div
        class="bg-primary-50 dark:bg-primary-900/20 p-6 rounded-3xl border border-primary-100 dark:border-primary-800 shadow-sm text-center">
        <p class="text-[10px] font-black text-primary-600 uppercase tracking-widest mb-1">Persentase</p>
        <p class="text-2xl font-black text-primary-600">{{ $totalMhs > 0 ? round(($hadir / $totalMhs) * 100) : 0 }}%</p>
      </div>
    </div>

    <!-- Main Table -->
    <div
      class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
      <div class="p-8 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
        <h2 class="font-black text-slate-800 dark:text-white uppercase text-sm tracking-widest">Daftar Kehadiran</h2>
        <div class="text-[10px] font-black px-3 py-1 bg-slate-100 dark:bg-slate-900 text-slate-500 rounded-full italic">
          Sesi: {{ substr($pertemuan->jam_mulai, 0, 5) }} - {{ substr($pertemuan->jam_selesai, 0, 5) }}
        </div>
      </div>
      <div class="overflow-x-auto p-4">
        <table class="w-full text-left border-separate border-spacing-y-2">
          <thead>
            <tr>
              <th class="px-6 py-3 text-[10px] font-black uppercase text-slate-400 tracking-widest">Mahasiswa</th>
              <th class="px-6 py-3 text-[10px] font-black uppercase text-slate-400 tracking-widest">Waktu Absen</th>
              <th class="px-6 py-3 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Status
              </th>
              <th class="px-6 py-3 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">Keterangan
              </th>
            </tr>
          </thead>
          <tbody>
            @foreach($mahasiswas as $mhs)
              @php $presensi = $presensis->get($mhs->id); @endphp
              <tr
                class="bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-900 transition-all shadow-sm">
                <td class="px-6 py-4 rounded-l-2xl">
                  <p class="font-black text-slate-800 dark:text-white leading-tight">{{ $mhs->nama }}</p>
                  <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ $mhs->nim }}</p>
                </td>
                <td class="px-6 py-4">
                  @if($presensi)
                    <span class="text-xs font-bold text-slate-600 dark:text-slate-300">
                      <i class="far fa-clock mr-1 text-primary-500"></i>{{ $presensi->created_at->format('H:i') }} WIB
                    </span>
                  @else
                    <span class="text-xs text-slate-300 italic font-medium">Belum Absen</span>
                  @endif
                </td>
                <td class="px-6 py-4 text-center">
                  @if($presensi && $presensi->status == 'hadir')
                    <span
                      class="px-3 py-1 bg-emerald-100 text-emerald-600 rounded-full text-[9px] font-black uppercase tracking-widest border border-emerald-200">
                      HADIR
                    </span>
                  @else
                    <span
                      class="px-3 py-1 bg-rose-100 text-rose-600 rounded-full text-[9px] font-black uppercase tracking-widest border border-rose-200">
                      ALFA
                    </span>
                  @endif
                </td>
                <td class="px-6 py-4 text-right rounded-r-2xl">
                  @if($presensi)
                    <p class="text-[10px] text-slate-500 font-bold">{{ $presensi->keterangan ?? '-' }}</p>
                  @else
                    <span class="text-[10px] text-rose-400 font-black uppercase">Absensi Kosong</span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection