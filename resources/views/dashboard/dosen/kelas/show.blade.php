@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm relative overflow-hidden">
        {{-- Dekorasi Latar Belakang --}}
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-primary-500/5 rounded-full blur-3xl"></div>
        
        <div class="relative flex flex-col md:flex-row justify-between items-start gap-6">
            <div class="space-y-1">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-primary-50 dark:bg-primary-900/30 text-primary-600 text-[10px] font-black uppercase tracking-widest border border-primary-100 dark:border-primary-800">
                    {{ $kelas->mataKuliah->kode_mk }}
                </span>
                <h1 class="text-3xl font-black text-slate-800 dark:text-white leading-tight">
                    {{ $kelas->mataKuliah->nama }}
                </h1>
                <p class="text-sm text-slate-500 font-bold flex items-center gap-2">
                    {{ $kelas->nama_kelas }} <span class="text-slate-300">•</span> {{ ucfirst($kelas->tipe_kelas) }}
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach($kelas->golongans as $gol)
                    <span class="px-4 py-2 bg-slate-100 dark:bg-slate-700 rounded-xl text-xs font-black text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600">
                        {{ $gol->nama }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Kolom Kiri: Jadwal & Lokasi -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white dark:bg-slate-800 p-6 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="font-black text-slate-800 dark:text-white uppercase text-xs tracking-widest italic">Konfigurasi Jadwal</h2>
                    <button @click="MicroModal.show('modal-add-jadwal')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-primary-50 text-primary-600 hover:bg-primary-600 hover:text-white transition-all">
                        <i class="fas fa-plus text-xs"></i>
                    </button>
                </div>
                
                <div class="space-y-4">
                    @forelse($kelas->jadwals as $j)
                        <div class="p-5 rounded-3xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700 group hover:border-primary-500 transition-colors">
                            <p class="text-[10px] font-black text-primary-600 uppercase tracking-widest mb-1">{{ $j->hari }}</p>
                            <p class="text-xl font-black text-slate-800 dark:text-white mb-2">{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</p>
                            <div class="flex items-center gap-2 text-[10px] text-slate-500 font-bold">
                                <i class="fas fa-location-dot text-primary-500"></i>
                                <span>{{ $j->lokasi->nama }} <span class="text-slate-300 mx-1">|</span> R: {{ $j->lokasi->radius_meter }}m</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 px-4 border-2 border-dashed border-slate-100 dark:border-slate-700 rounded-3xl">
                            <p class="text-xs text-slate-400 italic font-medium">Jadwal operasional belum diatur. Klik icon plus untuk menambah.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Daftar Pertemuan -->
        <div class="lg:col-span-8">
            <div class="bg-white dark:bg-slate-800 p-6 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                    <div>
                        <h2 class="font-black text-slate-800 dark:text-white uppercase text-xs tracking-widest italic mb-1">Log Pertemuan & Presensi</h2>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Total: {{ $kelas->pertemuans->count() }} / 16 Sesi</p>
                    </div>
                    <button @click="MicroModal.show('modal-add-pertemuan')" class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl text-xs font-black shadow-xl shadow-primary-500/20 transition-all transform hover:-translate-y-1">
                        <i class="fas fa-plus-circle mr-2"></i>BUAT PERTEMUAN
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-separate border-spacing-y-3">
                        <thead>
                            <tr>
                                <th class="px-6 text-[10px] font-black uppercase text-slate-400 tracking-widest">P.Ke</th>
                                <th class="px-6 text-[10px] font-black uppercase text-slate-400 tracking-widest">Materi & Tanggal</th>
                                <th class="px-6 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Status</th>
                                <th class="px-6 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kelas->pertemuans->sortBy('pertemuan_ke') as $p)
                                <tr class="bg-slate-50 dark:bg-slate-900/50 hover:bg-white dark:hover:bg-slate-800 border border-slate-100 dark:border-slate-700 transition-all shadow-sm">
                                    <td class="px-6 py-4 font-black text-slate-400 rounded-l-2xl">#{{ $p->pertemuan_ke }}</td>
                                    <td class="px-6 py-4">
                                        <p class="font-black text-slate-800 dark:text-white leading-tight">{{ $p->materi }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold mt-1 uppercase">{{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d F Y') }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($p->status == 'dibuka')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-600 text-[9px] font-black uppercase animate-pulse">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> {{ $p->status }}
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-500 text-[9px] font-black uppercase">
                                                {{ $p->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right rounded-r-2xl">
                                        <div class="flex justify-end gap-2">
                                            <form action="{{ route('dosen.pertemuan.toggle', $p->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-xl {{ $p->status == 'dibuka' ? 'bg-red-50 text-red-600 border-red-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100' }} border hover:scale-110 transition-all shadow-sm" title="{{ $p->status == 'dibuka' ? 'Tutup Absen' : 'Buka Absen' }}">
                                                    <i class="fas {{ $p->status == 'dibuka' ? 'fa-stop-circle' : 'fa-play' }} text-sm"></i>
                                                </button>
                                            </form>
                                            <a href="#" class="w-9 h-9 flex items-center justify-center bg-white dark:bg-slate-800 text-slate-600 border border-slate-200 dark:border-slate-700 rounded-xl hover:text-primary-600 hover:border-primary-500 transition-all shadow-sm">
                                                <i class="fas fa-users-viewfinder text-sm"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL ADD PERTEMUAN --}}
<div class="modal" id="modal-add-pertemuan" aria-hidden="true">
    <div class="modal__overlay fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center" tabindex="-1" data-micromodal-close>
        <div class="modal__container w-full max-w-md bg-white dark:bg-slate-800 rounded-[2.5rem] p-10 shadow-2xl mx-4" role="dialog" @click.stop>
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Set Pertemuan</h2>
                <button data-micromodal-close class="text-slate-400 hover:text-red-500"><i class="fas fa-times"></i></button>
            </div>
            <form action="{{ route('dosen.kelas.pertemuan.store', $kelas->id) }}" method="POST" class="space-y-5">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Sesi Ke-</label>
                        <input type="number" name="pertemuan_ke" value="{{ $kelas->pertemuans->count() + 1 }}" class="w-full px-5 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-bold focus:border-primary-500 outline-none transition-all" required>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full px-5 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-bold focus:border-primary-500 outline-none transition-all" required>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Pokok Bahasan</label>
                    <textarea name="materi" rows="3" class="w-full px-5 py-4 rounded-2xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-bold focus:border-primary-500 outline-none transition-all" placeholder="Misal: Implementasi CRUD pada Laravel" required></textarea>
                </div>
                <button type="submit" class="w-full py-4 bg-primary-600 text-white rounded-2xl font-black shadow-xl shadow-primary-500/30 transform transition-all hover:-translate-y-1">
                    KONFIRMASI PERTEMUAN
                </button>
            </form>
        </div>
    </div>
</div>

{{-- MODAL ADD JADWAL (Tambahkan jika belum ada) --}}
<div class="modal" id="modal-add-jadwal" aria-hidden="true">
    <div class="modal__overlay fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center" tabindex="-1" data-micromodal-close>
        <div class="modal__container w-full max-w-md bg-white dark:bg-slate-800 rounded-[2.5rem] p-10 shadow-2xl mx-4" role="dialog" @click.stop>
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Atur Jadwal</h2>
                <button data-micromodal-close class="text-slate-400 hover:text-red-500"><i class="fas fa-times"></i></button>
            </div>
            <form action="{{ route('dosen.kelas.jadwal.store', $kelas->id) }}" method="POST" class="space-y-5">
                @csrf
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Hari</label>
                    <select name="hari" class="w-full px-5 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-bold focus:border-primary-500 outline-none transition-all">
                        @foreach(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'] as $hari)
                            <option value="{{ $hari }}">{{ ucfirst($hari) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="w-full px-5 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-bold focus:border-primary-500 outline-none transition-all" required>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="w-full px-5 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-bold focus:border-primary-500 outline-none transition-all" required>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Lokasi Presensi</label>
                    <select name="lokasi_id" class="w-full px-5 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-bold focus:border-primary-500 outline-none transition-all">
                        @foreach($lokasis as $lok)
                            <option value="{{ $lok->id }}">{{ $lok->nama }} ({{ $lok->radius_meter }}m)</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full py-4 bg-primary-600 text-white rounded-2xl font-black shadow-xl shadow-primary-500/30 transform transition-all hover:-translate-y-1">
                    SIMPAN JADWAL
                </button>
            </form>
        </div>
    </div>
</div>
@endsection