@extends('layouts.app')
@section('title', 'Akses & Kredensial • SIPRESPRO')
@section('content')
<div class="space-y-6 mx-auto p-2 sm:p-4 text-left">
    <div class="bg-white dark:bg-slate-800 p-5 sm:p-8 rounded-4xl sm:rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-primary-500/5 rounded-full blur-3xl"></div>
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Akses & Kredensial</h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-medium mt-0.5">Monitoring akses masuk dan keamanan akun sistem utama secara real-time</p>
        </div>
    </div>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        @foreach([
            ['label' => 'Mhs Aktif', 'val' => $stats['mahasiswa'], 'icon' => 'fa-user-graduate', 'color' => 'emerald'],
            ['label' => 'Dosen Aktif', 'val' => $stats['dosen'], 'icon' => 'fa-chalkboard-teacher', 'color' => 'violet'],
            ['label' => 'Akses Terbuka', 'val' => $stats['active'], 'icon' => 'fa-unlock', 'color' => 'sky'],
            ['label' => 'Terkunci', 'val' => $stats['inactive'], 'icon' => 'fa-user-shield', 'color' => 'red']
        ] as $item)
        <div class="bg-white dark:bg-slate-800 border border-slate-150 dark:border-slate-700 rounded-2xl sm:rounded-3xl p-4 shadow-xs transition duration-300 hover:shadow-md">
            <div class="flex items-center justify-between gap-2">
                <div class="min-w-0">
                    <p class="text-[9px] sm:text-[10px] text-slate-400 dark:text-slate-500 uppercase font-black tracking-wider truncate">{{ $item['label'] }}</p>
                    <p class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white mt-0.5 sm:mt-1">{{ $item['val'] }}</p>
                </div>
                <div class="w-9 h-9 sm:w-11 sm:h-11 shrink-0 bg-{{ $item['color'] }}-50 dark:bg-{{ $item['color'] }}-900/20 rounded-xl sm:rounded-2xl flex items-center justify-center text-{{ $item['color'] }}-600 dark:text-{{ $item['color'] }}-400">
                    <i class="fas {{ $item['icon'] }} text-xs sm:text-sm"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <form method="GET" action="{{ route('admin.users.index') }}" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl sm:rounded-3xl p-3 sm:p-4 flex flex-col sm:flex-row gap-3 items-stretch sm:items-center shadow-xs">
        <div class="relative flex-1">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs sm:text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, ID, atau email..." 
                class="w-full pl-9 pr-4 py-2 sm:py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white focus:ring-2 focus:ring-primary-500/50 outline-none transition-all font-bold">
        </div>
        <div class="flex gap-2 w-full sm:w-auto">
            <select name="role" class="flex-1 sm:flex-initial px-3 py-2 sm:py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-white font-bold outline-none focus:ring-2 focus:ring-primary-500/50">
                <option value="">Semua Role</option>
                <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                <option value="dosen" {{ request('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
            </select>
            <button type="submit" class="px-5 py-2 sm:py-2.5 text-xs sm:text-sm font-black uppercase tracking-wider text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition shadow-md shadow-primary-500/10 shrink-0">
                Apply
            </button>
            <a href="{{ route('admin.users.export.excel', request()->query()) }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 sm:py-2.5 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-xl text-xs sm:text-sm font-black uppercase tracking-wider transition shrink-0">
                <i class="fas fa-file-excel text-emerald-600"></i>
                <span class="hidden xs:inline">Export</span>
            </a>
        </div>
    </form>
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-4xl sm:rounded-[2.5rem] overflow-hidden shadow-xs">
        <div class="hidden md:block overflow-x-auto p-4 sm:p-6">
            <table class="w-full text-left border-separate border-spacing-y-2">
                <thead class="text-slate-400">
                    <tr>
                        <th class="px-6 py-3 text-[10px] font-black uppercase tracking-widest">User Account</th>
                        <th class="px-6 py-3 text-[10px] font-black uppercase tracking-widest">Identitas / ID</th>
                        <th class="px-6 py-3 text-[10px] font-black uppercase tracking-widest text-center">Tipe Akun</th>
                        <th class="px-6 py-3 text-[10px] font-black uppercase tracking-widest text-center">Akses</th>
                        <th class="px-6 py-3 text-right text-[10px] font-black uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="bg-slate-50 dark:bg-slate-900/50 hover:bg-white dark:hover:bg-slate-800 transition-all cursor-pointer shadow-xs" @click="MicroModal.show('modal-detail-{{ $user->id }}')">
                        <td class="px-6 py-4 rounded-l-2xl">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center font-black text-primary-600 dark:text-primary-400">
                                    {{ strtoupper(substr($user->email, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-black text-slate-800 dark:text-white leading-tight truncate max-w-[200px]">{{ $user->mahasiswa->nama ?? $user->dosen->nama ?? 'Sistem Admin' }}</p>
                                    <p class="text-[11px] font-mono text-slate-400 mt-0.5 truncate max-w-[220px]">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-mono font-bold text-slate-700 dark:text-slate-200">{{ $user->mahasiswa->nim ?? $user->dosen->nip ?? '-' }}</p>
                            <p class="text-[10px] text-slate-400 font-bold mt-0.5 uppercase tracking-tighter">Join: {{ $user->created_at->format('d M Y') }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider border {{ $user->role == 'dosen' ? 'bg-violet-50 text-violet-700 border-violet-100 dark:bg-violet-900/30 dark:text-violet-400' : 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400' }}">
                                <i class="fas {{ $user->role == 'dosen' ? 'fa-chalkboard-teacher' : 'fa-user-graduate' }} mr-1.5 text-[8px]"></i>
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 {{ $user->is_active ? 'text-emerald-500' : 'text-red-500' }}">
                                <i class="fas {{ $user->is_active ? 'fa-unlock' : 'fa-lock' }} text-xs"></i>
                                <span class="text-[10px] font-black uppercase tracking-wider">{{ $user->is_active ? 'Aktif' : 'Lock' }}</span>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right rounded-r-2xl" @click.stop>
                            <button type="button" @click="MicroModal.show('modal-edit-{{ $user->id }}')" 
                                class="w-9 h-9 inline-flex items-center justify-center rounded-xl bg-slate-100 hover:bg-primary-600 dark:bg-slate-700 text-slate-500 dark:text-slate-400 hover:text-white dark:hover:bg-primary-600 dark:hover:text-white transition-all shadow-xs border border-slate-200/60 dark:border-slate-600">
                                <i class="fas fa-cog text-xs"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-20 text-center text-slate-400 italic">Data akun tidak ditemukan.</td></tr>
                    @endempty
                </tbody>
            </table>
        </div>
        <div class="block md:hidden p-4 space-y-3">
            @forelse($users as $user)
            <div class="bg-slate-50 dark:bg-slate-900/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-800/80 shadow-xs space-y-3" @click="MicroModal.show('modal-detail-{{ $user->id }}')">
                <div class="flex justify-between items-start gap-2">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 shrink-0 rounded-lg bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center font-black text-primary-600 dark:text-primary-400 text-xs">
                            {{ strtoupper(substr($user->email, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-black text-slate-800 dark:text-white text-sm leading-tight truncate">{{ $user->mahasiswa->nama ?? $user->dosen->nama ?? 'Sistem Admin' }}</h4>
                            <p class="text-[10px] font-mono text-slate-400 truncate mt-0.5">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="shrink-0 flex flex-col items-end gap-1.5">
                        <span class="px-2 py-0.5 rounded-md text-[8px] font-black uppercase border {{ $user->role == 'dosen' ? 'bg-violet-50 text-violet-600 border-violet-100 dark:bg-violet-950/30 dark:text-violet-400' : 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-950/30 dark:text-emerald-400' }}">{{ $user->role }}</span>
                        <span class="inline-flex items-center gap-1 text-[9px] font-black uppercase tracking-wide {{ $user->is_active ? 'text-emerald-500' : 'text-red-500' }}">
                            <i class="fas {{ $user->is_active ? 'fa-unlock' : 'fa-lock' }} text-[9px]"></i> {{ $user->is_active ? 'Aktif' : 'Lock' }}
                        </span>
                    </div>
                </div>
                <div class="pt-2.5 border-t border-slate-200/50 dark:border-slate-700/50 flex justify-between items-center text-[11px]" @click.stop>
                    <div class="font-mono text-slate-600 dark:text-slate-300 font-bold">
                        ID: {{ $user->mahasiswa->nim ?? $user->dosen->nip ?? '-' }}
                    </div>
                    <button type="button" @click="MicroModal.show('modal-edit-{{ $user->id }}')"
                        class="px-3 py-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-primary-600 dark:text-primary-400 font-black uppercase text-[9px] tracking-wider rounded-xl shadow-xs flex items-center gap-1">
                        <i class="fas fa-cog"></i> Akses
                    </button>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-slate-400 italic text-xs">Data akun tidak ditemukan.</div>
            @endforelse
        </div>
        @if($users->hasPages())
        <div class="px-4 py-3 sm:px-6 sm:py-4 bg-slate-50/50 dark:bg-slate-900/20 border-t border-slate-100 dark:border-slate-700">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@foreach($users as $user)
<div class="modal micromodal-slide" id="modal-detail-{{ $user->id }}" aria-hidden="true">
    <div class="modal__overlay fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 transition-all duration-300 ease-out" tabindex="-1" data-micromodal-close>
        <div class="modal__container bg-white dark:bg-slate-800 rounded-4xl sm:rounded-[2.5rem] p-6 sm:p-8 shadow-2xl w-full max-w-lg relative transform transition-all duration-300 scale-95 origin-center" role="dialog" aria-modal="true">
            <header class="flex justify-between items-center mb-6 pb-3 border-b border-slate-100 dark:border-slate-700">
                <h2 class="text-lg sm:text-xl font-black dark:text-white text-slate-800 uppercase tracking-tight">Detail Lengkap User</h2>
                <button type="button" class="text-slate-400 hover:text-red-500 transition-colors" data-micromodal-close><i class="fas fa-times"></i></button>
            </header>
            <div class="space-y-4 sm:space-y-5 text-left">
                <div class="flex items-center gap-3 sm:gap-4 p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-150 dark:border-slate-800">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 shrink-0 rounded-2xl bg-linear-to-tr from-primary-600 to-indigo-600 flex items-center justify-center text-white text-xl sm:text-2xl font-black shadow-lg shadow-primary-500/10">
                        {{ strtoupper(substr($user->email, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-base sm:text-lg font-black text-slate-800 dark:text-white leading-tight wrap-break-word">{{ $user->mahasiswa->nama ?? $user->dosen->nama ?? 'Administrator' }}</p>
                        <p class="text-xs text-slate-400 font-mono mt-0.5 sm:mt-1 break-all">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 bg-white dark:bg-slate-800 border border-slate-150 dark:border-slate-700 rounded-xl shadow-xs">
                        <p class="text-[9px] sm:text-[10px] text-slate-400 uppercase font-black tracking-widest">NIM / NIP</p>
                        <p class="font-mono font-black text-sm text-slate-700 dark:text-slate-200 mt-0.5 truncate">{{ $user->mahasiswa->nim ?? $user->dosen->nip ?? '-' }}</p>
                    </div>
                    <div class="p-3 bg-white dark:bg-slate-800 border border-slate-150 dark:border-slate-700 rounded-xl shadow-xs">
                        <p class="text-[9px] sm:text-[10px] text-slate-400 uppercase font-black tracking-widest">Tipe Akun</p>
                        <p class="capitalize font-black text-sm text-slate-700 dark:text-slate-200 mt-0.5">{{ $user->role }}</p>
                    </div>
                    <div class="p-3 bg-white dark:bg-slate-800 border border-slate-150 dark:border-slate-700 rounded-xl shadow-xs">
                        <p class="text-[9px] sm:text-[10px] text-slate-400 uppercase font-black tracking-widest">Status Akses</p>
                        <p class="font-black text-xs sm:text-sm mt-0.5 {{ $user->is_active ? 'text-emerald-500' : 'text-red-500' }}">{{ $user->is_active ? 'AKTIF' : 'TERKUNCI' }}</p>
                    </div>
                    <div class="p-3 bg-white dark:bg-slate-800 border border-slate-150 dark:border-slate-700 rounded-xl shadow-xs">
                        <p class="text-[9px] sm:text-[10px] text-slate-400 uppercase font-black tracking-widest">No. WhatsApp</p>
                        <p class="font-black text-slate-700 dark:text-slate-200 text-xs sm:text-sm mt-0.5 truncate">{{ $user->mahasiswa->no_hp ?? $user->dosen->no_hp ?? '-' }}</p>
                    </div>
                </div>
                <div class="p-4 bg-indigo-50/50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800/60 rounded-xl">
                    <p class="text-[9px] sm:text-[10px] text-indigo-600 dark:text-indigo-400 font-black uppercase tracking-widest mb-1">Alamat Terdaftar</p>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed italic font-medium max-h-24 overflow-y-auto">
                        {{ $user->mahasiswa->alamat ?? $user->dosen->alamat ?? 'Data alamat tidak ditemukan dalam profil akademik.' }}
                    </p>
                </div>
            </div>
            <div class="mt-6">
                <button type="button" data-micromodal-close class="w-full py-3 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl font-black uppercase text-xs tracking-wider hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                    Tutup Detail
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal micromodal-slide" id="modal-edit-{{ $user->id }}" aria-hidden="true">
    <div class="modal__overlay fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 transition-all duration-300 ease-out" tabindex="-1" data-micromodal-close>
        <div class="modal__container bg-white dark:bg-slate-800 rounded-4xl p-6 sm:p-8 shadow-2xl w-full max-w-md text-center relative transform transition-all duration-300 scale-95" role="dialog" aria-modal="true">
            <div class="w-14 h-14 bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-primary-50 dark:border-primary-900/50">
                <i class="fas fa-user-shield text-xl"></i>
            </div>
            <h2 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight">Kontrol Akses</h2>
            <p class="text-[10px] text-slate-400 font-mono mb-6 truncate max-w-[200px] mx-auto">{{ $user->email }}</p>
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="flex gap-2">
                    <label class="flex-1 cursor-pointer group">
                        <input type="radio" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }} class="sr-only peer">
                        <div class="py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-400 font-bold peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-500 transition-all">
                            <i class="fas fa-check-circle mb-0.5 block text-xs"></i>
                            <span class="text-[9px] font-black uppercase tracking-wider">Aktif</span>
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer group">
                        <input type="radio" name="is_active" value="0" {{ !$user->is_active ? 'checked' : '' }} class="sr-only peer">
                        <div class="py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-400 font-bold peer-checked:bg-red-500 peer-checked:text-white peer-checked:border-red-500 transition-all">
                            <i class="fas fa-lock mb-0.5 block text-xs"></i>
                            <span class="text-[9px] font-black uppercase tracking-wider">Kunci</span>
                        </div>
                    </label>
                </div>
                <div class="mt-6 flex gap-2">
                    <button type="button" data-micromodal-close class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-black uppercase text-[10px] tracking-wider transition">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-primary-600 text-white rounded-xl font-black uppercase text-[10px] tracking-wider shadow-md shadow-primary-500/10 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
@push('styles')
<style>
    /* MODAL SAKTI ANIMATED SLIDE MICROMODAL STANDARD */
    .modal { display: none; }
    .modal.is-open { display: flex; }
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
    @keyframes mmFadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes mmFadeOut { from { opacity: 1; } to { opacity: 0; } }
    @keyframes mmSlideIn { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    @keyframes mmSlideOut { from { transform: scale(1); opacity: 1; } to { transform: scale(0.95); opacity: 0; } }
</style>
@endpush