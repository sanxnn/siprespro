@extends('layouts.app')
@section('title', 'Data Mahasiswa • SIPRESPRO')
@section('content')
    <div class="space-y-6 mx-auto p-2 sm:p-4 text-left">
        <div
            class="bg-white dark:bg-slate-800 p-5 sm:p-8 rounded-4xl sm:rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-primary-500/5 rounded-full blur-3xl"></div>
            <div class="relative flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight">
                        Database Mahasiswa</h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-medium mt-0.5">Total
                        {{ $mahasiswas->total() }} mahasiswa terdaftar dalam sistem akademik</p>
                </div>
                <button type="button" @click="MicroModal.show('modal-create-mhs')"
                    class="w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl sm:rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-lg shadow-primary-500/20 hover:-translate-y-0.5 shrink-0">
                    <i class="fas fa-plus-circle text-sm"></i>
                    <span>Tambah Mahasiswa</span>
                </button>
            </div>
        </div>
        <form method="GET" action="{{ route('admin.mahasiswa.index') }}"
            class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl sm:rounded-3xl p-3 sm:p-4 flex flex-col lg:flex-row gap-3 items-stretch lg:items-center shadow-xs">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs sm:text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIM atau Nama..."
                    class="w-full pl-9 pr-4 py-2 sm:py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold outline-none focus:ring-2 focus:ring-primary-500 transition-all">
            </div>
            <div class="flex flex-wrap gap-2 items-center w-full lg:w-auto">
                <select name="golongan"
                    class="flex-1 sm:flex-initial min-w-[140px] px-3 py-2 sm:py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-white font-bold outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua Golongan</option>
                    @foreach($golongans as $g)
                        <option value="{{ $g->id }}" {{ request('golongan') == $g->id ? 'selected' : '' }}>Golongan {{ $g->nama }}
                        </option>
                    @endforeach
                </select>
                <input type="number" name="angkatan" value="{{ request('angkatan') }}" placeholder="Angkatan"
                    class="w-24 px-3 py-2 sm:py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-white font-bold outline-none focus:ring-2 focus:ring-primary-500">
                <button type="submit"
                    class="px-5 py-2 sm:py-2.5 text-xs sm:text-sm font-black uppercase tracking-wider text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition shadow-md shadow-primary-500/10 shrink-0">
                    Apply
                </button>
                <a href="{{ route('admin.mahasiswa.export.excel', request()->query()) }}"
                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2 sm:py-2.5 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-xl text-xs sm:text-sm font-black uppercase tracking-wider transition shrink-0">
                    <i class="fas fa-file-excel text-emerald-600"></i>
                    <span class="hidden xs:inline">Export</span>
                </a>
            </div>
        </form>
        <div
            class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-4xl sm:rounded-[2.5rem] overflow-hidden shadow-xs">
            <div class="hidden md:block overflow-x-auto p-4 sm:p-6">
                <table class="w-full text-left border-separate border-spacing-y-2">
                    <thead class="text-slate-400">
                        <tr>
                            <th class="px-6 py-3 text-[10px] font-black uppercase tracking-widest">Profil Mahasiswa</th>
                            <th class="px-6 py-3 text-[10px] font-black uppercase tracking-widest">Identitas</th>
                            <th class="px-6 py-3 text-[10px] font-black uppercase tracking-widest">Akademik</th>
                            <th class="px-6 py-3 text-[10px] font-black uppercase tracking-widest">Kontak</th>
                            <th class="px-6 py-3 text-right text-[10px] font-black uppercase tracking-widest">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mahasiswas as $mhs)
                            <tr
                                class="bg-slate-50 dark:bg-slate-900/50 hover:bg-white dark:hover:bg-slate-800 transition-all shadow-xs">
                                <td class="px-6 py-4 rounded-l-2xl">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center text-primary-600 dark:text-primary-400 font-black">
                                            {{ substr($mhs->nama, 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p
                                                class="font-black text-slate-800 dark:text-slate-100 leading-tight truncate max-w-[180px]">
                                                {{ $mhs->nama }}</p>
                                            <p class="text-[11px] text-slate-400 font-mono mt-0.5 truncate max-w-[200px]">
                                                {{ $mhs->user->email ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-sm font-bold text-slate-700 dark:text-slate-200 block font-mono">{{ $mhs->nim }}</span>
                                    <span class="text-[10px] text-slate-400 block font-semibold mt-0.5">NIK:
                                        {{ $mhs->nik ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[10px] font-black uppercase border border-blue-100 dark:border-blue-800/60 mb-1">
                                        {{ $mhs->golongan->nama ?? 'N/A' }}
                                    </span>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 font-bold">Angkatan
                                        {{ $mhs->angkatan }} <span class="text-slate-300 dark:text-slate-600">|</span> Sem
                                        {{ $mhs->semester_aktif ?? 'N/A' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs font-bold text-slate-600 dark:text-slate-300"><i
                                            class="fas fa-phone text-[10px] mr-1.5 text-primary-500"></i>
                                        {{ $mhs->no_hp ?? '-' }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium truncate max-w-[150px] mt-0.5"><i
                                            class="fas fa-map-marker-alt mr-1.5 text-rose-500"></i> {{ $mhs->alamat ?? '-' }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-right rounded-r-2xl">
                                    <div class="flex justify-end gap-2">
                                        <button type="button" @click="MicroModal.show('modal-edit-{{ $mhs->id }}')"
                                            class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-100 dark:border-blue-800">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                        <button type="button" @click="MicroModal.show('modal-delete-{{ $mhs->id }}')"
                                            class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-100 dark:border-red-800">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center text-slate-400 italic text-sm">Belum ada data
                                    mahasiswa yang terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="block md:hidden p-4 space-y-3">
                @forelse($mahasiswas as $mhs)
                    <div
                        class="bg-slate-50 dark:bg-slate-900/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-800/80 shadow-xs space-y-3">
                        <div class="flex justify-between items-start gap-2">
                            <div class="flex items-center gap-3 min-w-0">
                                <div
                                    class="w-10 h-10 shrink-0 rounded-xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center font-black text-primary-600 dark:text-primary-400 text-sm">
                                    {{ substr($mhs->nama, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-black text-slate-800 dark:text-white text-sm leading-tight truncate">
                                        {{ $mhs->nama }}</h4>
                                    <p class="text-[10px] font-mono text-slate-400 truncate mt-0.5">
                                        {{ $mhs->user->email ?? '-' }}</p>
                                </div>
                            </div>
                            <span
                                class="px-2 py-0.5 shrink-0 rounded-md text-[8px] font-black uppercase bg-blue-50 text-blue-600 border border-blue-200">Gol
                                {{ $mhs->golongan->nama ?? 'N/A' }}</span>
                        </div>
                        <div class="text-[11px] text-slate-500 dark:text-slate-400 space-y-0.5 font-medium">
                            <p><span class="font-bold text-slate-700 dark:text-slate-300">NIM:</span> <span
                                    class="font-mono">{{ $mhs->nim }}</span></p>
                            <p><span class="font-bold text-slate-700 dark:text-slate-300">Akademik:</span> Angkatan
                                {{ $mhs->angkatan }} <span class="text-slate-300">•</span> Sem {{ $mhs->semester_aktif ?? '1' }}
                            </p>
                            <p class="truncate"><i class="fas fa-phone text-[9px] mr-1 text-primary-500"></i>
                                {{ $mhs->no_hp ?? '-' }}</p>
                        </div>
                        <div class="pt-2 border-t border-slate-200/50 dark:border-slate-700/50 flex justify-end gap-1.5">
                            <button type="button" @click="MicroModal.show('modal-edit-{{ $mhs->id }}')"
                                class="px-3 py-1.5 bg-amber-50 text-amber-600 border border-amber-100 rounded-xl text-[10px] font-black uppercase tracking-wider flex items-center gap-1">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button type="button" @click="MicroModal.show('modal-delete-{{ $mhs->id }}')"
                                class="px-3 py-1.5 bg-rose-50 text-rose-600 border border-rose-100 rounded-xl text-[10px] font-black uppercase tracking-wider flex items-center gap-1">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 italic text-xs">Belum ada data mahasiswa yang terdaftar.</div>
                @endforelse
            </div>
            @if($mahasiswas->hasPages())
                <div
                    class="px-4 py-3 sm:px-6 sm:py-4 bg-slate-50/50 dark:bg-slate-900/20 border-t border-slate-100 dark:border-slate-700">
                    {{ $mahasiswas->links() }}
                </div>
            @endif
        </div>
    </div>
    <div class="modal micromodal-slide" id="modal-create-mhs" aria-hidden="true">
        <div class="modal__overlay fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 transition-all duration-300 ease-out"
            tabindex="-1" data-micromodal-close>
            <div class="modal__container bg-white dark:bg-slate-800 rounded-4xl p-6 sm:p-10 shadow-2xl w-full max-w-3xl relative transform transition-all duration-300 scale-95"
                role="dialog" aria-modal="true">
                <header class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-700">
                    <div>
                        <h2 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Registrasi
                            Mahasiswa</h2>
                        <p
                            class="text-[10px] sm:text-[11px] text-slate-400 dark:text-slate-500 mt-1 uppercase tracking-wider font-bold">
                            Sistem Presensi Polije</p>
                    </div>
                    <button type="button"
                        class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                        data-micromodal-close>
                        <i class="fas fa-times"></i>
                    </button>
                </header>
                <form action="{{ route('admin.mahasiswa.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 text-left">
                        <div class="space-y-4">
                            <h4
                                class="text-[10px] font-black uppercase tracking-widest text-primary-600 dark:text-primary-400">
                                Data Login & Identitas</h4>
                            <div>
                                <label class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">NIM <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="nim" required placeholder="Contoh: E4122144"
                                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-medium outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">Nama
                                    Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="nama" required placeholder="Nama sesuai ijazah"
                                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-medium outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">Email
                                    Institusi <span class="text-red-500">*</span></label>
                                <input type="email" name="email" required placeholder="user@polije.ac.id"
                                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-medium outline-none">
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label
                                        class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">Angkatan</label>
                                    <input type="number" name="angkatan" value="{{ date('Y') }}"
                                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-medium outline-none">
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">Golongan</label>
                                    <select name="golongan_id"
                                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-bold outline-none">
                                        <option value="">Pilih...</option>
                                        @foreach($golongans as $g)
                                            <option value="{{ $g->id }}">{{ $g->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <h4
                                class="text-[10px] font-black uppercase tracking-widest text-primary-600 dark:text-primary-400">
                                Informasi Tambahan</h4>
                            <div>
                                <label
                                    class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">NIK</label>
                                <input type="text" name="nik" placeholder="16 digit NIK"
                                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-medium outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">No.
                                    WhatsApp</label>
                                <input type="text" name="no_hp" placeholder="08xxxxxxxxxx"
                                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-medium outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">Tgl
                                    Lahir</label>
                                <input type="date" name="tanggal_lahir"
                                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-medium outline-none">
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">Alamat</label>
                                <textarea name="alamat" rows="2" placeholder="Alamat lengkap..."
                                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 resize-none outline-none font-medium"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 flex flex-col sm:flex-row gap-3">
                        <button type="button" data-micromodal-close
                            class="w-full sm:flex-1 py-3.5 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-black uppercase text-xs tracking-wider transition-colors">Batal</button>
                        <button type="submit"
                            class="w-full sm:flex-1 py-3.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-black uppercase text-xs tracking-wider shadow-lg shadow-primary-500/10 transition-all">Simpan
                            & Buat Akun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @foreach($mahasiswas as $mhs)
        <div class="modal micromodal-slide" id="modal-edit-{{ $mhs->id }}" aria-hidden="true">
            <div class="modal__overlay fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 transition-all duration-300"
                tabindex="-1" data-micromodal-close>
                <div class="modal__container bg-white dark:bg-slate-800 rounded-4xl p-6 sm:p-10 shadow-2xl w-full max-w-2xl relative transform transition-all duration-300 scale-95"
                    role="dialog" aria-modal="true">
                    <header class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-700">
                        <div>
                            <h2 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Edit Profil
                                Mahasiswa</h2>
                            <p
                                class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 uppercase tracking-widest font-black">
                                NIM: {{ $mhs->nim }}</p>
                        </div>
                        <button type="button"
                            class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                            data-micromodal-close>
                            <i class="fas fa-times"></i>
                        </button>
                    </header>
                    <form action="{{ route('admin.mahasiswa.update', $mhs->id) }}" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="id" value="{{ $mhs->nim }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 text-left">
                            <div class="space-y-4">
                                <h4 class="text-[10px] font-black uppercase tracking-widest text-blue-600 dark:text-blue-400">
                                    Data Akademik</h4>
                                <div>
                                    <label class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">Nama
                                        Lengkap</label>
                                    <input type="text" name="nama" value="{{ $mhs->nama }}" required
                                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-medium outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">Email
                                        Akun</label>
                                    <input type="email" name="email" value="{{ $mhs->user->email ?? '' }}" required
                                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-medium outline-none">
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label
                                            class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">Angkatan</label>
                                        <input type="number" name="angkatan" value="{{ $mhs->angkatan }}"
                                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-medium outline-none">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">Golongan</label>
                                        <select name="golongan_id"
                                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-bold outline-none">
                                            <option value="">Pilih...</option>
                                            @foreach($golongans as $g)
                                                <option value="{{ $g->id }}" {{ $mhs->golongan_id == $g->id ? 'selected' : '' }}>
                                                    {{ $g->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <h4 class="text-[10px] font-black uppercase tracking-widest text-blue-600 dark:text-blue-400">
                                    Kontak & Alamat</h4>
                                <div>
                                    <label class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">Nomor HP /
                                        WA</label>
                                    <input type="text" name="no_hp" value="{{ $mhs->no_hp }}"
                                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-medium outline-none">
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">NIK</label>
                                    <input type="text" name="nik" value="{{ $mhs->nik }}"
                                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-medium outline-none">
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">Alamat</label>
                                    <textarea name="alamat" rows="2"
                                        class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 resize-none outline-none font-medium">{{ $mhs->alamat }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 flex flex-col sm:flex-row gap-3">
                            <button type="button" data-micromodal-close
                                class="w-full sm:flex-1 py-3.5 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-black uppercase text-xs tracking-wider transition-colors">Batal</button>
                            <button type="submit"
                                class="w-full sm:flex-1 py-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-black uppercase text-xs tracking-wider shadow-lg shadow-blue-500/10 transition-all">Update
                                Profil</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal micromodal-slide" id="modal-delete-{{ $mhs->id }}" aria-hidden="true">
            <div class="modal__overlay fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 transition-all duration-300"
                tabindex="-1" data-micromodal-close>
                <div class="modal__container bg-white dark:bg-slate-800 rounded-4xl p-6 sm:p-8 shadow-2xl w-full max-w-sm text-center relative transform transition-all duration-300 scale-95"
                    role="dialog" aria-modal="true">
                    <div
                        class="w-16 h-14 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-3xl flex items-center justify-center mx-auto mb-5 border-4 border-red-50 dark:border-red-900/20">
                        <i class="fas fa-trash-alt text-2xl"></i>
                    </div>
                    <h2 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight mb-2">Hapus Mahasiswa?
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mb-6 px-2 leading-relaxed">Profil
                        <b>{{ $mhs->nama }}</b> beserta seluruh record login sistem akan dilenyapkan secara <strong
                            class="text-rose-600">permanen</strong>.</p>
                    <form action="{{ route('admin.mahasiswa.destroy', $mhs->id) }}" method="POST" class="flex gap-2 w-full">
                        @csrf @method('DELETE')
                        <button type="button" data-micromodal-close
                            class="flex-1 py-3.5 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-black uppercase text-[10px] tracking-wider transition-colors">Batal</button>
                        <button type="submit"
                            class="flex-1 py-3.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-black uppercase text-[10px] tracking-wider shadow-md shadow-rose-500/10 transition-all">Ya,
                            Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
@push('styles')
    <style>
        /* MODAL SAKTI ANIMATED SLIDE MICROMODAL AUTOMATION ENGINE */
        .modal {
            display: none;
        }
        .modal.is-open {
            display: flex;
        }
        .micromodal-slide[aria-hidden="false"] .modal__overlay {
            animation: mmFadeIn .25s cubic-bezier(0.0, 0.0, 0.2, 1);
        }
        .micromodal-slide[aria-hidden="false"] .modal__container {
            animation: mmSlideIn .25s cubic-bezier(0, 0, 0.2, 1);
        }
        .micromodal-slide[aria-hidden="true"] .modal__overlay {
            animation: mmFadeOut .2s cubic-bezier(0.0, 0.0, 0.2, 1);
        }
        .micromodal-slide[aria-hidden="true"] .modal__container {
            animation: mmSlideOut .2s cubic-bezier(0, 0, 0.2, 1);
        }
        @keyframes mmFadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        @keyframes mmFadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }
        @keyframes mmSlideIn {
            from {
                transform: scale(0.95);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
        @keyframes mmSlideOut {
            from {
                transform: scale(1);
                opacity: 1;
            }
            to {
                transform: scale(0.95);
                opacity: 0;
            }
        }
        /* Premium Glow Focus Inputs Override */
        input:focus,
        select:focus,
        textarea:focus {
            border-color: #10b981 !important;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1) !important;
        }
    </style>
@endpush