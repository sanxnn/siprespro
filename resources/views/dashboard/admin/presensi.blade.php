@extends('layouts.app')

@section('title', 'Data Presensi • SIPRESPRO')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 text-left">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Log Presensi Mahasiswa</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Monitoring kehadiran real-time berdasarkan koordinat GPS</p>
        </div>
        
        <form action="{{ route('admin.presensi.index') }}" method="GET" class="flex items-center gap-2">
            <select name="pertemuan_id" onchange="this.form.submit()" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold outline-none focus:ring-2 focus:ring-primary-500/50 transition-all">
                <option value="">Semua Pertemuan</option>
                @foreach($pertemuans as $p)
                    <option value="{{ $p->id }}" {{ request('pertemuan_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->kelasPerkuliahan->mataKuliah->nama }} (Pertemuan {{ $p->pertemuan_ke }})
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm transition-colors duration-300">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Mahasiswa</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center">Waktu Presensi</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Koordinat GPS</th>
                        <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-left">
                    @forelse($presensis as $presensi)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-all group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-900 flex items-center justify-center font-bold text-slate-500 dark:text-slate-400 text-xs border border-slate-200 dark:border-slate-700">
                                    {{ substr($presensi->mahasiswa->nama, 0, 1) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-800 dark:text-slate-100">{{ $presensi->mahasiswa->nama }}</span>
                                    <span class="text-[10px] text-slate-400 font-mono">{{ $presensi->mahasiswa->nim }}</span>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex flex-col items-center">
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-200">{{ date('H:i', strtotime($presensi->waktu_presensi)) }}</span>
                                <span class="text-[9px] text-slate-400 uppercase font-black">{{ date('d M Y', strtotime($presensi->waktu_presensi)) }}</span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @php
                                $colors = [
                                    'hadir' => 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800',
                                    'izin' => 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800',
                                    'sakit' => 'bg-blue-50 text-blue-600 border-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800',
                                    'alpha' => 'bg-rose-50 text-rose-600 border-rose-100 dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase border {{ $colors[$presensi->status] }}">
                                {{ $presensi->status }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <a href="https://www.google.com/maps?q={{ $presensi->latitude }},{{ $presensi->longitude }}" target="_blank" class="flex items-center gap-2 hover:text-primary-500 transition-colors">
                                <i class="fas fa-location-dot text-red-500 text-xs"></i>
                                <span class="text-[10px] font-mono text-slate-500 dark:text-slate-400 truncate max-w-[120px]">
                                    {{ $presensi->latitude }}, {{ $presensi->longitude }}
                                </span>
                            </a>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button @click="MicroModal.show('modal-edit-{{ $presensi->id }}')" class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors"><i class="fas fa-edit text-xs"></i></button>
                                <button @click="MicroModal.show('modal-delete-{{ $presensi->id }}')" class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors"><i class="fas fa-trash text-xs"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center text-slate-400 italic">Data presensi belum ada jancok!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($presensis->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
                {{ $presensis->links() }}
            </div>
        @endif
    </div>
</div>

@foreach($presensis as $presensi)
    <div class="modal" id="modal-edit-{{ $presensi->id }}" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
            <div class="modal__container w-full max-w-sm bg-white dark:bg-slate-800 rounded-4xl p-8 shadow-2xl" role="dialog" @click.stop>
                <header class="mb-6 text-center">
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">Koreksi Presensi</h2>
                    <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest mt-1">{{ $presensi->mahasiswa->nama }}</p>
                </header>

                <form action="{{ route('admin.presensi.update', $presensi->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="space-y-4 text-left">
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Pilih Status Baru</label>
                        <select name="status" class="w-full px-4 py-3 rounded-xl border dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 outline-none focus:ring-2 focus:ring-blue-500/50">
                            <option value="hadir" {{ $presensi->status == 'hadir' ? 'selected' : '' }}>HADIR</option>
                            <option value="izin" {{ $presensi->status == 'izin' ? 'selected' : '' }}>IZIN</option>
                            <option value="sakit" {{ $presensi->status == 'sakit' ? 'selected' : '' }}>SAKIT</option>
                            <option value="alpha" {{ $presensi->status == 'alpha' ? 'selected' : '' }}>ALPHA</option>
                        </select>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <button type="button" data-micromodal-close class="flex-1 py-3 bg-slate-100 dark:bg-slate-700/50 text-slate-600 rounded-xl font-bold transition-all">Batal</button>
                        <button type="submit" class="flex-1 py-3 bg-blue-600 text-white rounded-xl font-bold shadow-lg shadow-blue-200">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@endsection