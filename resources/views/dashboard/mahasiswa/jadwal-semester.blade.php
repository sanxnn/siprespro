@extends('layouts.app')

@section('title', 'Jadwal Semester')
@section('page_title', 'Jadwal Semester')

@section('content')
<div class="space-y-6">

    <div class="bg-gradient-to-r from-emerald-600 to-teal-500 rounded-3xl p-6 text-white shadow-sm">
        <h1 class="text-2xl font-bold">Jadwal Semester</h1>
        <p class="text-sm opacity-90 mt-2">
            Jadwal resmi perkuliahan Anda selama semester berjalan.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm">
            <p class="text-sm text-slate-500">Total Jadwal</p>
            <h3 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">
                {{ $jadwals->count() }}
            </h3>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm">
            <p class="text-sm text-slate-500">Golongan</p>
            <h3 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">
                {{ $mahasiswa->golongan->nama ?? '-' }}
            </h3>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm">
            <p class="text-sm text-slate-500">Semester</p>
            <h3 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">
                {{ $mahasiswa->semester->nama ?? '-' }}
            </h3>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-800 dark:text-white">
                    Daftar Jadwal Kuliah
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Menampilkan jadwal berdasarkan golongan dan kelas perkuliahan Anda.
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-700 text-slate-500">
                        <th class="text-left p-3">No</th>
                        <th class="text-left p-3">Hari</th>
                        <th class="text-left p-3">Jam Mulai</th>
                        <th class="text-left p-3">Jam Selesai</th>
                        <th class="text-left p-3">Durasi</th>
                        <th class="text-left p-3">Mata Kuliah</th>
                        <th class="text-left p-3">Dosen Pengampu</th>
                        <th class="text-left p-3">Ruang</th>
                        <th class="text-left p-3">Lokasi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($jadwals as $index => $jadwal)
                        @php
                            $jamMulai = $jadwal->jam_mulai ? \Carbon\Carbon::parse($jadwal->jam_mulai) : null;
                            $jamSelesai = $jadwal->jam_selesai ? \Carbon\Carbon::parse($jadwal->jam_selesai) : null;

                            $durasiText = '-';

                            if ($jamMulai && $jamSelesai) {
                                $durasiMenit = $jamMulai->diffInMinutes($jamSelesai);
                                $jam = floor($durasiMenit / 60);
                                $menit = $durasiMenit % 60;

                                if ($jam > 0 && $menit > 0) {
                                    $durasiText = $jam . ' jam ' . $menit . ' menit';
                                } elseif ($jam > 0) {
                                    $durasiText = $jam . ' jam';
                                } else {
                                    $durasiText = $menit . ' menit';
                                }
                            }
                        @endphp

                        <tr class="border-b border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/40 transition">
                            <td class="p-3">
                                {{ $index + 1 }}
                            </td>

                            <td class="p-3 capitalize font-semibold text-slate-700 dark:text-slate-200">
                                {{ $jadwal->hari ?? '-' }}
                            </td>

                            <td class="p-3 text-slate-600 dark:text-slate-300">
                                {{ $jamMulai ? $jamMulai->format('H:i') : '-' }}
                            </td>

                            <td class="p-3 text-slate-600 dark:text-slate-300">
                                {{ $jamSelesai ? $jamSelesai->format('H:i') : '-' }}
                            </td>

                            <td class="p-3">
                                @php
                                    $badgeClass = match($jadwal->hari) {
                                        'senin' => 'bg-blue-100 text-blue-700',
                                        'selasa' => 'bg-emerald-100 text-emerald-700',
                                        'rabu' => 'bg-violet-100 text-violet-700',
                                        'kamis' => 'bg-orange-100 text-orange-700',
                                        'jumat' => 'bg-pink-100 text-pink-700',
                                        default => 'bg-slate-100 text-slate-700'
                                    };
                                @endphp

                                <span class="inline-flex px-3 py-1 rounded-full {{ $badgeClass }} text-xs font-bold">
                                    {{ $durasiText }}
                                </span>
                            </td>

                            <td class="p-3 font-semibold text-slate-800 dark:text-white">
                                {{ $jadwal->kelasPerkuliahan?->mataKuliah?->nama ?? '-' }}
                            </td>

                            <td class="p-3 text-slate-600 dark:text-slate-300">
                                {{ $jadwal->kelasPerkuliahan?->dosen?->nama ?? '-' }}
                            </td>

                            <td class="p-3 text-slate-600 dark:text-slate-300">
                                {{ $jadwal->kelasPerkuliahan?->ruang?->nama ?? '-' }}
                            </td>

                            <td class="p-3 text-slate-600 dark:text-slate-300">
                                {{ $jadwal->lokasi?->nama ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-slate-500">
                                Belum ada jadwal semester.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection