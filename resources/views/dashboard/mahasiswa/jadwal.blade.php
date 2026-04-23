@extends('layouts.app')

@section('title', 'Jadwal Kuliah')
@section('page_title', 'Jadwal Kuliah Saya')

@section('content')
<div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">

    <h2 class="text-lg font-bold mb-4">Jadwal Mingguan</h2>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b">
                    <th class="text-left p-3">Hari</th>
                    <th class="text-left p-3">Mata Kuliah</th>
                    <th class="text-left p-3">Jam</th>
                    <th class="text-left p-3">Dosen</th>
                    <th class="text-left p-3">Ruang</th>
                    <th class="text-left p-3">Lokasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwals as $jadwal)
                    <tr class="border-b">
                        <td class="p-3 capitalize">{{ $jadwal->hari }}</td>

                        <td class="p-3">
                            {{ optional(optional($jadwal->kelasPerkuliahan)->mataKuliah)->nama ?? '-' }}
                        </td>

                        <td class="p-3">
                            {{ substr($jadwal->jam_mulai,0,5) }} - {{ substr($jadwal->jam_selesai,0,5) }}
                        </td>

                        <td class="p-3">
                            {{ optional(optional($jadwal->kelasPerkuliahan)->dosen)->nama ?? '-' }}
                        </td>

                        <td class="p-3">
                            {{ optional(optional($jadwal->kelasPerkuliahan)->ruang)->nama ?? '-' }}
                        </td>

                        <td class="p-3">
                            {{ optional($jadwal->lokasi)->nama ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-3 text-center text-slate-500">
                            Tidak ada jadwal
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection