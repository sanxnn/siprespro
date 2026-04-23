@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa | SIPRESPRO')
@section('page_title', 'Dashboard Mahasiswa')

@section('content')
<div class="space-y-6">

    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm">
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">
            Halo, {{ $mahasiswa->nama }}
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">
            NIM: {{ $mahasiswa->nim ?? '-' }} |
            Semester: {{ optional($mahasiswa->semester)->nama ?? '-' }} |
            Golongan: {{ optional($mahasiswa->golongan)->nama ?? '-' }}
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm">
            <p class="text-sm text-slate-500">Hadir</p>
            <h3 class="text-2xl font-bold text-emerald-600 mt-1">{{ $totalHadir }}</h3>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm">
            <p class="text-sm text-slate-500">Izin</p>
            <h3 class="text-2xl font-bold text-amber-500 mt-1">{{ $totalIzin }}</h3>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm">
            <p class="text-sm text-slate-500">Sakit</p>
            <h3 class="text-2xl font-bold text-blue-500 mt-1">{{ $totalSakit }}</h3>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm">
            <p class="text-sm text-slate-500">Alpha</p>
            <h3 class="text-2xl font-bold text-red-500 mt-1">{{ $totalAlpha }}</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
            <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Jadwal Hari Ini</h2>

            <div class="space-y-4">
                @forelse($jadwalHariIni as $jadwal)
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/30">
                        <div class="font-semibold text-slate-800 dark:text-white">
                            {{ optional(optional($jadwal->kelasPerkuliahan)->mataKuliah)->nama ?? '-' }}
                        </div>
                        <div class="text-sm text-slate-500 mt-1">
                            {{ $jadwal->waktu ?? '-' }}
                        </div>
                        <div class="text-sm text-slate-500">
                            Dosen: {{ optional(optional($jadwal->kelasPerkuliahan)->dosen)->nama ?? '-' }}
                        </div>
                        <div class="text-sm text-slate-500">
                            Ruang: {{ optional(optional($jadwal->kelasPerkuliahan)->ruang)->nama ?? '-' }}
                        </div>
                        <div class="text-sm text-slate-500">
                            Lokasi: {{ optional($jadwal->lokasi)->nama ?? '-' }}
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Tidak ada jadwal kuliah hari ini.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
            <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Riwayat Presensi Terbaru</h2>

            <div class="space-y-4">
                @forelse($riwayatTerbaru as $item)
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/30">
                        <div class="font-semibold text-slate-800 dark:text-white">
                            {{ optional(optional(optional($item->pertemuan)->kelasPerkuliahan)->mataKuliah)->nama ?? '-' }}
                        </div>
                        <div class="text-sm text-slate-500 mt-1">
                            Status: <span class="font-semibold uppercase">{{ $item->status_label ?? strtoupper($item->status) }}</span>
                        </div>
                        <div class="text-sm text-slate-500">
                            {{ $item->waktu_presensi ? $item->waktu_presensi->format('d M Y H:i') : '-' }}
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Belum ada riwayat presensi.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection