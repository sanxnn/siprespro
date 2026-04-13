@extends('layouts.app')

@section('title', 'Data Mahasiswa • SIPRESPRO')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Database Mahasiswa</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Total {{ $mahasiswas->total() }} mahasiswa terdaftar dalam sistem</p>
        </div>
        <button @click="MicroModal.show('modal-create-mhs')" 
            class="flex items-center gap-2 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold transition-all shadow-lg shadow-primary-200 dark:shadow-none">
            <i class="fas fa-plus-circle"></i>
            <span>Tambah Mahasiswa</span>
        </button>
    </div>

    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm transition-colors duration-300">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Profil Mahasiswa</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Identitas</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Akademik</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Kontak</th>
                        <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($mahasiswas as $mhs)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-all group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold">
                                    {{ substr($mhs->nama, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 dark:text-slate-100">{{ $mhs->nama }}</p>
                                    <p class="text-[11px] text-slate-400 font-mono">{{ $mhs->user->email ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-200 block">{{ $mhs->nim }}</span>
                            <span class="text-[10px] text-slate-400 block italic">NIK: {{ $mhs->nik ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[10px] font-bold uppercase mb-1 border border-blue-100 dark:border-blue-800">
                                {{ $mhs->golongan->nama ?? 'N/A' }}
                            </span>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Angkatan {{ $mhs->angkatan }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs text-slate-600 dark:text-slate-300"><i class="fas fa-phone text-[10px] mr-1 text-primary-500"></i> {{ $mhs->no_hp ?? '-' }}</p>
                            <p class="text-[10px] text-slate-400 truncate max-w-[150px]"><i class="fas fa-map-marker-alt mr-1 text-red-400"></i> {{ $mhs->alamat ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button @click="MicroModal.show('modal-edit-{{ $mhs->id }}')" 
                                    class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-500 dark:hover:text-white transition-all duration-300 shadow-sm border border-blue-100 dark:border-blue-800">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <button @click="MicroModal.show('modal-delete-{{ $mhs->id }}')" 
                                    class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white dark:hover:bg-red-500 dark:hover:text-white transition-all duration-300 shadow-sm border border-red-100 dark:border-red-800">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">
                            <img src="https://illustrations.popsy.co/slate/shaking-hands.svg" class="w-32 mx-auto mb-4 opacity-50 dark:invert">
                            <p class="text-slate-400 italic">Belum ada data mahasiswa yang terdaftar.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($mahasiswas->hasPages())
        <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-700">
            {{ $mahasiswas->links() }}
        </div>
        @endif
    </div>
</div>

<div class="modal" id="modal-create-mhs" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container w-full max-w-2xl" role="dialog" @click.stop>
            <header class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-700">
                <div>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">Registrasi Mahasiswa</h2>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wider font-semibold">Sistem Presensi Polije</p>
                </div>
                <button class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors" data-micromodal-close>
                    <i class="fas fa-times"></i>
                </button>
            </header>

            <form action="{{ route('admin.mahasiswa.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <h4 class="text-[10px] font-bold uppercase tracking-widest text-primary-600 dark:text-primary-400">Data Login & Identitas</h4>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">NIM <span class="text-red-500">*</span></label>
                            <input type="text" name="nim" required placeholder="Contoh: E4122144"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" required placeholder="Nama sesuai ijazah"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Email Institusi <span class="text-red-500">*</span></label>
                            <input type="email" name="email" required placeholder="user@polije.ac.id"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 outline-none transition-all">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Angkatan</label>
                                <input type="number" name="angkatan" value="{{ date('Y') }}"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Golongan</label>
                                <select name="golongan_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100">
                                    <option value="">Pilih...</option>
                                    @foreach($golongans as $g)
                                        <option value="{{ $g->id }}">{{ $g->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h4 class="text-[10px] font-bold uppercase tracking-widest text-primary-600 dark:text-primary-400">Informasi Tambahan</h4>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">NIK</label>
                            <input type="text" name="nik" placeholder="16 digit NIK"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">No. WhatsApp</label>
                            <input type="text" name="no_hp" placeholder="08xxxxxxxxxx"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Tgl Lahir</label>
                            <input type="date" name="tanggal_lahir"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Alamat</label>
                            <textarea name="alamat" rows="2" placeholder="Alamat lengkap..."
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 resize-none transition-all focus:ring-2 focus:ring-primary-500 outline-none"></textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" @click="MicroModal.close('modal-create-mhs')" class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-bold transition-colors">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold shadow-lg shadow-primary-200 transition-all dark:shadow-lg dark:shadow-slate-900">Simpan & Buat Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($mahasiswas as $mhs)
<div class="modal" id="modal-edit-{{ $mhs->id }}" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" @click="MicroModal.close('modal-edit-{{ $mhs->id }}')">
        <div class="modal__container w-full max-w-2xl" role="dialog" @click.stop>
            <header class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-700">
                <div>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">Edit Profil</h2>
                    <p class="text-[10px] text-slate-400 mt-0.5 uppercase tracking-widest font-bold">NIM: {{ $mhs->nim }}</p>
                </div>
                <button class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700" @click="MicroModal.close('modal-edit-{{ $mhs->id }}')">
                    <i class="fas fa-times"></i>
                </button>
            </header>

            <form action="{{ route('admin.mahasiswa.update', $mhs->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                    <div class="space-y-4">
                        <h4 class="text-[10px] font-bold uppercase tracking-widest text-blue-600 dark:text-blue-400">Data Akademik</h4>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ $mhs->nama }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Email Akun</label>
                            <input type="email" name="email" value="{{ $mhs->user->email ?? '' }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Angkatan</label>
                                <input type="number" name="angkatan" value="{{ $mhs->angkatan }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Golongan</label>
                                <select name="golongan_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100">
                                    <option value="">Pilih...</option>
                                    @foreach($golongans as $g)
                                        <option value="{{ $g->id }}" {{ $mhs->golongan_id == $g->id ? 'selected' : '' }}>{{ $g->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <h4 class="text-[10px] font-bold uppercase tracking-widest text-blue-600 dark:text-blue-400">Kontak & Alamat</h4>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nomor HP</label>
                            <input type="text" name="no_hp" value="{{ $mhs->no_hp }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">NIK</label>
                            <input type="text" name="nik" value="{{ $mhs->nik }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Alamat</label>
                            <textarea name="alamat" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 resize-none focus:ring-2 focus:ring-blue-500 outline-none">{{ $mhs->alamat }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="mt-8 flex gap-3">
                    <button type="button" @click="MicroModal.close('modal-edit-{{ $mhs->id }}')" class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-bold transition-colors">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-200 transition-all dark:shadow-lg dark:shadow-slate-900">Update Profil</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="modal-delete-{{ $mhs->id }}" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" @click="MicroModal.close('modal-delete-{{ $mhs->id }}')">
        <div class="modal__container w-full max-w-sm text-center" role="dialog" @click.stop>
            <div class="w-20 h-20 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center mx-auto mb-5 animate-pulse border-4 border-red-50 dark:border-red-900/20">
                <i class="fas fa-trash-alt text-3xl"></i>
            </div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Hapus Mahasiswa?</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 px-4">
                Profil <b>{{ $mhs->nama }}</b> akan dihapus permanen beserta akun loginnya.
            </p>
            <form action="{{ route('admin.mahasiswa.destroy', $mhs->id) }}" method="POST" class="flex gap-3 px-2">
                @csrf @method('DELETE')
                <button type="button" @click="MicroModal.close('modal-delete-{{ $mhs->id }}')" 
                    class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-bold transition-all">Batal</button>
                <button type="submit" 
                    class="flex-1 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold shadow-lg shadow-red-200 transition-all dark:shadow-lg dark:shadow-slate-900">Ya, Hapus</button>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('styles')
<style>
    /* Micromodal Base - Fixed Hierarchy */
    .modal { display: none; }
    .modal.is-open { display: block; }
    
    .modal__overlay {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(5px);
        display: flex; justify-content: center; align-items: center; z-index: 9999;
        animation: mmFadeIn .3s ease-out;
    }
    
    .modal__container {
        background-color: #ffffff; padding: 2rem; border-radius: 1.75rem;
        max-height: 90vh; overflow-y: auto; position: relative;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        animation: mmSlideIn .3s cubic-bezier(0, 0, .2, 1);
        border: 1px solid #f1f5f9;
    }
    
    .dark .modal__container { 
        background-color: #1e293b; 
        border-color: #334155;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    
    @keyframes mmFadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes mmSlideIn { from { transform: scale(0.95) translateY(10px); opacity: 0; } to { transform: scale(1) translateY(0); opacity: 1; } }

    /* Custom Input States */
    input, select, textarea { transition: all 0.2s ease-in-out; }
    input:focus, select:focus, textarea:focus {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1) !important;
    }
</style>
@endpush