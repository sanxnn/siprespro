@extends('layouts.app')

@section('title', 'Akses & Kredensial • SIPRESPRO')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Akses & Kredensial</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Monitoring akses masuk dan keamanan akun sistem</p>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['label' => 'Mhs Aktif', 'val' => $stats['mahasiswa'], 'icon' => 'fa-user-graduate', 'color' => 'emerald'],
            ['label' => 'Dosen Aktif', 'val' => $stats['dosen'], 'icon' => 'fa-chalkboard-teacher', 'color' => 'violet'],
            ['label' => 'Akses Terbuka', 'val' => $stats['active'], 'icon' => 'fa-unlock', 'color' => 'sky'],
            ['label' => 'Terkunci', 'val' => $stats['inactive'], 'icon' => 'fa-user-shield', 'color' => 'red']
        ] as $item)
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 shadow-sm transition hover:shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 uppercase font-bold tracking-wider">{{ $item['label'] }}</p>
                    <p class="text-2xl font-bold text-slate-800 dark:text-white mt-1">{{ $item['val'] }}</p>
                </div>
                <div class="w-10 h-10 bg-{{ $item['color'] }}-100 dark:bg-{{ $item['color'] }}-900/30 rounded-xl flex items-center justify-center text-{{ $item['color'] }}-600">
                    <i class="fas {{ $item['icon'] }}"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <form method="GET" action="{{ route('admin.users.index') }}" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 flex flex-wrap gap-3 items-center">
        <div class="relative flex-1 min-w-60">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, ID, atau email..." 
                class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white focus:ring-2 focus:ring-primary-500 outline-none transition">
        </div>
        
        <select name="role" class="px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
            <option value="">Semua Role</option>
            <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
            <option value="dosen" {{ request('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
        </select>

        <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition shadow-lg shadow-primary-200 dark:shadow-slate-900">
            Apply
        </button>
        
        <a href="{{ route('admin.users.export.excel', request()->query()) }}" class="inline-flex items-center gap-2 px-4 py-2.5 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-xl text-sm font-bold transition">
            <i class="fas fa-file-excel text-emerald-600"></i>
            <span>Export</span>
        </a>
    </form>

    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700 text-slate-500">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">User Account</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Identitas / ID</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center">Tipe Akun</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center">Akses</th>
                        <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/40 transition-all cursor-pointer group" @click="MicroModal.show('modal-detail-{{ $user->id }}')">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center font-bold text-primary-600 dark:text-primary-400">
                                    {{ strtoupper(substr($user->email, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800 dark:text-white leading-tight">{{ $user->mahasiswa->nama ?? $user->dosen->nama ?? 'Sistem' }}</p>
                                    <p class="text-[11px] font-mono text-slate-400 mt-0.5">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-mono font-semibold text-slate-700 dark:text-slate-200">{{ $user->mahasiswa->nim ?? $user->dosen->nip ?? '-' }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5 uppercase">Join: {{ $user->created_at->format('d M Y') }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider border
                                {{ $user->role == 'dosen' ? 'bg-violet-50 text-violet-700 border-violet-100 dark:bg-violet-900/30 dark:text-violet-400 dark:border-violet-800' : 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800' }}">
                                <i class="fas {{ $user->role == 'dosen' ? 'fa-chalkboard-teacher' : 'fa-user-graduate' }} mr-1.5 text-[8px]"></i>
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 {{ $user->is_active ? 'text-emerald-500' : 'text-red-500' }}">
                                <i class="fas {{ $user->is_active ? 'fa-unlock' : 'fa-lock' }} text-xs"></i>
                                <span class="text-[10px] font-bold uppercase">{{ $user->is_active ? 'Aktif' : 'Lock' }}</span>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button @click.stop="MicroModal.show('modal-edit-{{ $user->id }}')" 
                                class="w-9 h-9 inline-flex items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 hover:bg-primary-600 hover:text-white dark:hover:bg-primary-600 dark:hover:text-white transition-all shadow-sm border border-slate-200 dark:border-slate-600">
                                <i class="fas fa-cog text-xs"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-20 text-center text-slate-400 italic">Data akun tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-900/20 border-t border-slate-100 dark:border-slate-700">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

@foreach($users as $user)
<div class="modal" id="modal-detail-{{ $user->id }}" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container w-full max-w-lg" role="dialog" @click.stop>
            <header class="flex justify-between items-center mb-6 pb-4 border-b dark:border-slate-700">
                <h2 class="text-xl font-bold dark:text-white text-slate-800">Detail Lengkap User</h2>
                <button class="text-slate-400 hover:text-red-500" data-micromodal-close><i class="fas fa-times"></i></button>
            </header>
            
            <div class="space-y-6">
                <div class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-800">
                    <div class="w-16 h-16 rounded-2xl bg-primary-600 flex items-center justify-center text-white text-2xl font-bold">
                        {{ strtoupper(substr($user->email, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-lg font-bold text-slate-800 dark:text-white leading-tight">{{ $user->mahasiswa->nama ?? $user->dosen->nama ?? 'Administrator' }}</p>
                        <p class="text-sm text-slate-500 font-mono mt-1">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl shadow-sm">
                        <p class="text-[10px] text-slate-400 uppercase font-bold mb-1 tracking-widest">NIM / NIP</p>
                        <p class="font-mono font-bold text-slate-700 dark:text-slate-200">{{ $user->mahasiswa->nim ?? $user->dosen->nip ?? '-' }}</p>
                    </div>
                    <div class="p-3 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl shadow-sm">
                        <p class="text-[10px] text-slate-400 uppercase font-bold mb-1 tracking-widest">Tipe Akun</p>
                        <p class="capitalize font-bold text-slate-700 dark:text-slate-200">{{ $user->role }}</p>
                    </div>
                    <div class="p-3 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl shadow-sm">
                        <p class="text-[10px] text-slate-400 uppercase font-bold mb-1 tracking-widest">Status</p>
                        <p class="font-bold {{ $user->is_active ? 'text-emerald-500' : 'text-red-500' }}">{{ $user->is_active ? 'AKTIF' : 'TERKUNCI' }}</p>
                    </div>
                    <div class="p-3 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl shadow-sm">
                        <p class="text-[10px] text-slate-400 uppercase font-bold mb-1 tracking-widest">No. WhatsApp</p>
                        <p class="font-bold text-slate-700 dark:text-slate-200 text-sm">{{ $user->mahasiswa->no_hp ?? $user->dosen->no_hp ?? '-' }}</p>
                    </div>
                </div>

                <div class="p-4 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800 rounded-xl">
                    <p class="text-[10px] text-indigo-600 dark:text-indigo-400 font-bold uppercase tracking-widest mb-1.5">Alamat Terdaftar</p>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed italic">
                        {{ $user->mahasiswa->alamat ?? $user->dosen->alamat ?? 'Data alamat tidak ditemukan dalam profil akademik.' }}
                    </p>
                </div>
            </div>

            <div class="mt-8">
                <button data-micromodal-close class="w-full py-3 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl font-bold hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal-edit-{{ $user->id }}" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container w-full max-w-sm text-center" role="dialog" @click.stop>
            <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/30 text-primary-600 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-primary-50">
                <i class="fas fa-user-shield text-2xl"></i>
            </div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-white">Kontrol Akses</h2>
            <p class="text-xs text-slate-500 mb-6 font-mono">{{ $user->email }}</p>
            
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="flex gap-2">
                    <label class="flex-1 cursor-pointer group">
                        <input type="radio" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }} class="sr-only peer">
                        <div class="py-3 rounded-xl border border-slate-200 dark:border-slate-700 peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-500 transition-all">
                            <i class="fas fa-check-circle mb-1 block"></i>
                            <span class="text-[10px] font-bold uppercase">Aktif</span>
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer group">
                        <input type="radio" name="is_active" value="0" {{ !$user->is_active ? 'checked' : '' }} class="sr-only peer">
                        <div class="py-3 rounded-xl border border-slate-200 dark:border-slate-700 peer-checked:bg-red-500 peer-checked:text-white peer-checked:border-red-500 transition-all">
                            <i class="fas fa-lock mb-1 block"></i>
                            <span class="text-[10px] font-bold uppercase">Kunci</span>
                        </div>
                    </label>
                </div>
                
                <div class="mt-8 flex gap-3">
                    <button type="button" data-micromodal-close class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-bold">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-primary-600 text-white rounded-xl font-bold shadow-lg shadow-primary-200 dark:shadow-slate-900">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('styles')
<style>
    .modal { display: none; }
    .modal.is-open { display: flex; }
    .modal__overlay {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(5px);
        display: flex; justify-content: center; align-items: center; z-index: 9999;
    }
    .modal__container {
        background-color: #fff; padding: 2rem; border-radius: 1.5rem;
        max-height: 95vh; overflow-y: auto; position: relative;
        border: 1px solid #f1f5f9;
    }
    .dark .modal__container { background-color: #1e293b; color: #fff; border: 1px solid #334155; }
</style>
@endpush