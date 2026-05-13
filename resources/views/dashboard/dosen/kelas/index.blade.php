@extends('layouts.app')

@section('title', 'Daftar Kelas Saya • SIPRESPRO')

@section('content')
    <div class="space-y-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tight">KELAS SAYA</h1>
                <p class="text-sm text-slate-500 font-bold uppercase tracking-widest italic text-primary-600">
                    Tahun Ajaran {{ $semesterAktif->tahun_ajaran ?? '-' }}
                </p>
            </div>
            <div
                class="px-6 py-3 bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Mengampu:</span>
                <span class="ml-2 font-black text-slate-800 dark:text-white">{{ $kelases->count() }} Kelas</span>
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
                                {{ $kelas->nama_kelas }}</h2>
                        </div>
                    </div>

                    <!-- Matkul Name -->
                    <div class="mb-6">
                        <h3
                            class="text-lg font-bold text-slate-700 dark:text-slate-200 group-hover:text-primary-600 transition-colors">
                            {{ $kelas->mataKuliah->nama }}</h3>
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
                    class="col-span-full py-32 flex flex-col items-center justify-center bg-white dark:bg-slate-800 rounded-[3rem] border-4 border-dashed border-slate-100 dark:border-slate-700">
                    <div
                        class="w-20 h-20 bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center text-slate-300 mb-4 text-3xl">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-400 uppercase">Gak ada kelas cok!</h3>
                    <p class="text-sm text-slate-400 italic">Admin belum nambahin jadwal buat lu.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection