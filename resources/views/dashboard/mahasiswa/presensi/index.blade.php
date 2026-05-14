@extends('layouts.app')

@section('title', 'Riwayat Kehadiran')
@section('page_title', 'Riwayat Kehadiran Saya')

@section('content')
<div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-white">
                Riwayat Kehadiran
            </h2>

            <p class="text-sm text-slate-500 mt-1">
                Semua data presensi mahasiswa
            </p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b dark:border-slate-700">
                    <th class="text-left p-3">No</th>
                    <th class="text-left p-3">Mata Kuliah</th>
                    <th class="text-left p-3">Dosen</th>
                    <th class="text-left p-3">Status</th>
                    <th class="text-left p-3">Waktu Presensi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($presensis as $index => $presensi)
                    <tr class="border-b dark:border-slate-700">

                        <td class="p-3">
                            {{ $presensis->firstItem() + $index }}
                        </td>

                        <td class="p-3">
                            {{ optional(optional(optional($presensi->pertemuan)->kelasPerkuliahan)->mataKuliah)->nama ?? '-' }}
                        </td>

                        <td class="p-3">
                            {{ optional(optional($presensi->pertemuan)->kelasPerkuliahan->dosen)->nama ?? '-' }}
                        </td>

                        <td class="p-3">
                            @php
                                $status = strtolower($presensi->status);
                            @endphp

                            <span class="
                                px-3 py-1 rounded-full text-xs font-semibold

                                @if($status == 'hadir')
                                    bg-emerald-100 text-emerald-700
                                @elseif($status == 'izin')
                                    bg-amber-100 text-amber-700
                                @elseif($status == 'sakit')
                                    bg-blue-100 text-blue-700
                                @else
                                    bg-red-100 text-red-700
                                @endif
                            ">
                                {{ strtoupper($presensi->status) }}
                            </span>
                        </td>

                        <td class="p-3">
                            {{ $presensi->waktu_presensi
                                ? \Carbon\Carbon::parse($presensi->waktu_presensi)->format('d M Y H:i')
                                : '-' }}
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-slate-500">
                            Belum ada data presensi
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $presensis->links() }}
    </div>

</div>
@endsection