@extends('layouts.app')
@section('title', 'Detail Kehadiran • SIPRESPRO')
@section('content')
  <div class="mx-auto p-3 sm:p-6 space-y-6">
    <div class="flex items-center justify-between">
      <a href="{{ route('mahasiswa.presensi.riwayat') }}"
        class="inline-flex items-center gap-2 text-xs font-bold text-slate-400 hover:text-primary-500 transition-colors group">
        <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> KEMBALI KE RIWAYAT
      </a>
      <span class="text-[10px] font-black px-3 py-1 bg-slate-100 dark:bg-slate-700 text-slate-500 rounded-full">
        SEMESTER AKTIF
      </span>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
      <div
        class="lg:col-span-2 bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm flex flex-col justify-center">
        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-primary-500 mb-1">{{ $kelas->kode_mk }}</span>
        <h1 class="text-xl sm:text-2xl font-bold text-slate-800 dark:text-white leading-tight mb-2">{{ $kelas->nama_mk }}
        </h1>
        <div class="flex items-center gap-3 text-xs text-slate-500 font-medium">
          <span class="flex items-center gap-1.5"><i class="fas fa-user-tie text-primary-500"></i>
            {{ $kelas->nama_dosen }}</span>
        </div>
      </div>
      <div
        class="bg-primary-600 rounded-2xl p-6 shadow-lg shadow-primary-200 dark:shadow-none flex flex-col justify-center text-white relative overflow-hidden">
        <i class="fas fa-chart-pie absolute -right-4 -bottom-4 text-8xl text-white/10 rotate-12"></i>
        <p class="text-xs font-bold text-primary-100 uppercase tracking-widest mb-1 text-center">Persentase Kehadiran</p>
        @php
          $total = $daftarPertemuan->count();
          $masuk = $daftarPertemuan->whereIn('status_absen', ['hadir', 'sakit', 'izin'])->count();
          $persen = $total > 0 ? round(($masuk / $total) * 100) : 100;
        @endphp
        <h2 class="text-4xl font-black text-center">{{ $persen }}%</h2>
        <p class="text-[10px] text-primary-100 mt-2 text-center font-medium uppercase tracking-tighter">
          {{ $masuk }} DARI {{ $total }} PERTEMUAN TERDATA
        </p>
      </div>
    </div>
    <div
      class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm overflow-hidden">
      <div class="px-6 py-4 border-b border-slate-50 dark:border-slate-700/50 flex items-center justify-between">
        <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">Log Aktivitas Kelas</h3>
        <i class="fas fa-list-ul text-slate-300"></i>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-slate-50/50 dark:bg-slate-700/30 border-b border-slate-100 dark:border-slate-700">
              <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Pertemuan</th>
              <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">Tanggal &
                Waktu</th>
              <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Status
                Kehadiran</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50 dark:divide-slate-700/50">
            @forelse($daftarPertemuan as $p)
              <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition-colors">
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-[10px] font-black text-slate-500">
                      #{{ $p->pertemuan_ke }}
                    </div>
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-200">Sesi Ke-{{ $p->pertemuan_ke }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 text-center">
                  <div class="inline-flex flex-col items-center">
                    <span
                      class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ date('d M Y', strtotime($p->tanggal)) }}</span>
                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter italic">
                      <i class="far fa-clock mr-1"></i>{{ date('H:i', strtotime($p->jam_mulai)) }} WIB
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 text-right">
                  @php
                    $status = trim(strtolower($p->status_absen)) ?: 'alfa';
                    $config = [
                      'hadir' => ['label' => 'HADIR', 'class' => 'bg-green-50 text-green-700 border-green-100 dark:bg-green-900/20 dark:text-green-400 dark:border-green-800'],
                      'sakit' => ['label' => 'SAKIT', 'class' => 'bg-amber-50 text-amber-700 border-amber-100 dark:bg-amber-900/20 dark:text-amber-400 dark:border-amber-800'],
                      'izin' => ['label' => 'IZIN', 'class' => 'bg-blue-50 text-blue-700 border-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-800'],
                      'alfa' => ['label' => 'ALPA', 'class' => 'bg-rose-50 text-rose-700 border-rose-100 dark:bg-rose-900/20 dark:text-rose-400 dark:border-rose-800'],
                    ];
                    $res = $config[$status] ?? $config['alfa'];
                  @endphp
                  <span
                    class="inline-flex items-center px-3 py-1.5 rounded-xl text-[10px] font-black tracking-widest border {{ $res['class'] }}">
                    @if($status == 'hadir') <i class="fas fa-check-circle mr-1.5"></i>
                    @elseif($status == 'alfa') <i class="fas fa-times-circle mr-1.5"></i>
                    @else <i class="fas fa-info-circle mr-1.5"></i> @endif
                    {{ $res['label'] }}
                  </span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="px-6 py-12 text-center">
                  <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Belum ada data pertemuan yang
                    ditutup</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection