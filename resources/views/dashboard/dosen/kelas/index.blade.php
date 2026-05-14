@extends('layouts.app')

@section('title', 'Daftar Kelas Saya • SIPRESPRO')

@section('content')
    <div class="space-y-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                    Kelas Saya
                </h1>
                <p class="text-xs font-medium text-slate-500 mt-1">Semester
                    <span class="text-slate-800 dark:text-slate-200 font-bold">{{ $semesterAktif->nama ?? '-' }}</span>
                </p>
            </div>
            <div
                class="flex items-center gap-3 px-5 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm">
                <div
                    class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-900/30 flex items-center justify-center text-primary-600">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Total Mengampu
                    </p>
                    <p class="text-lg font-black text-slate-800 dark:text-white">{{ $kelases->count() }} <span
                            class="text-xs font-bold text-slate-400">Kelas</span></p>
                </div>
            </div>
        </div>

        <!-- Grid Kelas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($kelases as $kelas)
                <div
                    class="group relative bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border-2 border-slate-100 dark:border-slate-700 hover:border-primary-500 transition-all duration-300 shadow-sm hover:shadow-xl hover:shadow-primary-500/10 transform hover:-translate-y-2">

                    <!-- Badge Tipe Kelas -->
                    <div class="absolute -top-3 right-8">
                        <span
                            class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest shadow-sm {{ $kelas->tipe_kelas == 'reguler' ? 'bg-blue-500 text-white' : 'bg-purple-500 text-white' }}">
                            {{ $kelas->tipe_kelas }}
                        </span>
                    </div>

                    <!-- Icon & Initial -->
                    <div class="flex items-center gap-4 mb-6">
                        <div
                            class="w-14 h-14 rounded-2xl bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-700 flex items-center justify-center text-primary-600 group-hover:bg-primary-600 group-hover:text-white transition-colors duration-300">
                            <i class="fas fa-layer-group text-xl"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Kode
                                MK: {{ $kelas->mataKuliah->kode_mk }}</p>
                            <h2 class="text-xl font-black text-slate-800 dark:text-white leading-tight">KELAS
                                {{ $kelas->nama_kelas }}
                            </h2>
                        </div>
                    </div>

                    <!-- Matkul Name -->
                    <div class="mb-6">
                        <h3
                            class="text-lg font-bold text-slate-700 dark:text-slate-200 group-hover:text-primary-600 transition-colors">
                            {{ $kelas->mataKuliah->nama }}
                        </h3>
                    </div>

                    <!-- Stats/Info -->
                    <div class="space-y-3 mb-8">
                        {{-- Row Golongan --}}
                        <div
                            class="flex flex-col sm:flex-row sm:items-center justify-between p-3 gap-2 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700">
                            <span
                                class="text-[10px] font-black text-slate-400 uppercase tracking-wider shrink-0">Golongan</span>

                            {{-- flex-wrap adalah kunci agar tidak overflow di Mac/Safari --}}
                            <div class="flex flex-wrap justify-start sm:justify-end gap-1.5">
                                @foreach($kelas->golongans as $gol)
                                    <span
                                        class="inline-block text-[10px] font-bold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 px-2 py-0.5 rounded border border-slate-200 dark:border-slate-600 whitespace-nowrap shadow-sm">
                                        {{ $gol->nama }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        {{-- Row Ruangan --}}
                        <div
                            class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Ruangan</span>
                            <span class="text-[10px] font-bold text-emerald-600 italic flex items-center">
                                <i class="fas fa-door-open mr-1.5"></i> {{ $kelas->ruang->nama }}
                            </span>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <a href="{{ route('dosen.kelas.show', $kelas->id) }}"
                        class="flex items-center justify-center gap-3 w-full py-4 bg-slate-100 dark:bg-slate-700 group-hover:bg-primary-600 text-slate-600 dark:text-slate-300 group-hover:text-white rounded-2xl font-black transition-all duration-300 uppercase tracking-widest text-xs">
                        <span>Masuk Hub Kelas</span>
                        <i class="fas fa-arrow-right-long group-hover:translate-x-2 transition-transform"></i>
                    </a>
                </div>
            @empty
                <div
                    class="col-span-full py-24 flex flex-col items-center justify-center bg-white dark:bg-slate-800 rounded-[2rem] border border-slate-100 dark:border-slate-700 shadow-sm relative overflow-hidden">
                    {{-- Background Decoration --}}
                    <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.05] pointer-events-none">
                        <svg class="h-full w-full" fill="currentColor">
                            <pattern id="pattern-empty" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                                <path d="M0 40L40 0H20L0 20M40 40V20L20 40H40Z" />
                            </pattern>
                            <rect width="100%" height="100%" fill="url(#pattern-empty)" />
                        </svg>
                    </div>

                    {{-- Icon with Soft Glow --}}
                    <div class="relative flex items-center justify-center mb-6">
                        <div class="absolute inset-0 bg-primary-500/20 blur-2xl rounded-full scale-150 opacity-20"></div>
                        <div
                            class="relative w-24 h-24 bg-slate-50 dark:bg-slate-900 rounded-3xl flex items-center justify-center text-slate-300 dark:text-slate-600 border border-slate-100 dark:border-slate-700 shadow-inner">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c1.097 0 2.16.192 3.142.546m4.5 0A8.967 8.967 0 0118 18c1.051 0 2.061.18 3 .512V4.262A8.987 8.987 0 0018 3.75c-1.097 0-2.16.192-3.142.546m0 14.25v-14.25" />
                            </svg>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="text-center relative z-10">
                        <h3 class="text-lg font-extrabold text-slate-800 dark:text-slate-100 tracking-tight uppercase">
                            Data Perkuliahan Belum Tersedia
                        </h3>
                        <p
                            class="text-xs text-slate-500 dark:text-slate-400 mt-2 max-w-[280px] leading-relaxed mx-auto font-medium">
                            Sistem tidak menemukan jadwal mengampu untuk semester ini. Silakan hubungi bagian akademik untuk
                            konfigurasi jadwal.
                        </p>
                    </div>

                    {{-- Help Button / Status Badge --}}
                    <div class="mt-8">
                        <div
                            class="inline-flex items-center gap-2 px-4 py-2 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-full">
                            <span class="flex h-2 w-2 relative">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                            </span>
                            <span
                                class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Sinkronisasi
                                Jadwal Standby</span>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection