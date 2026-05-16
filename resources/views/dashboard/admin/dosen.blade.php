@extends('layouts.app')
@section('title', 'Data Dosen • SIPRESPRO')
@section('content')
  <div class="space-y-6 mx-auto p-2 sm:p-4 text-left">
    <div
      class="bg-white dark:bg-slate-800 p-5 sm:p-8 rounded-4xl sm:rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm relative overflow-hidden">
      <div class="absolute -right-10 -top-10 w-40 h-40 bg-primary-500/5 rounded-full blur-3xl"></div>
      <div class="relative flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Database
            Dosen</h1>
          <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-medium mt-0.5">Total {{ $dosens->total() }}
            dosen pengampu terdaftar dalam sistem</p>
        </div>
        <button type="button" @click="MicroModal.show('modal-create-dosen')"
          class="w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl sm:rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-lg shadow-primary-500/20 hover:-translate-y-0.5 shrink-0">
          <i class="fas fa-plus-circle text-sm"></i>
          <span>Tambah Dosen</span>
        </button>
      </div>
    </div>
    <form method="GET" action="{{ route('admin.dosen.index') }}"
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl sm:rounded-3xl p-3 sm:p-4 flex flex-col sm:flex-row gap-3 items-stretch sm:items-center shadow-xs">
      <div class="relative flex-1">
        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs sm:text-sm"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIP, Nama, atau Email..."
          class="w-full pl-9 pr-4 py-2 sm:py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white font-bold outline-none focus:ring-2 focus:ring-primary-500 transition-all">
      </div>
      <div class="flex gap-2 w-full sm:w-auto">
        <select name="status"
          class="flex-1 sm:flex-initial px-3 py-2 sm:py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-white font-bold outline-none focus:ring-2 focus:ring-primary-500">
          <option value="">Semua Status</option>
          <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
          <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        <button type="submit"
          class="px-5 py-2 sm:py-2.5 text-xs sm:text-sm font-black uppercase tracking-wider text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition shadow-md shadow-primary-500/10 shrink-0">
          Apply
        </button>
        <a href="{{ route('admin.dosen.export.excel', request()->query()) }}"
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
              <th class="px-6 py-3 text-[10px] font-black uppercase tracking-widest">Profil Dosen</th>
              <th class="px-6 py-3 text-[10px] font-black uppercase tracking-widest">NIP / NIDN</th>
              <th class="px-6 py-3 text-[10px] font-black uppercase tracking-widest">Jabatan</th>
              <th class="px-6 py-3 text-[10px] font-black uppercase tracking-widest">Status</th>
              <th class="px-6 py-3 text-[10px] font-black uppercase tracking-widest">Kontak</th>
              <th class="px-6 py-3 text-right text-[10px] font-black uppercase tracking-widest">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($dosens as $dosen)
              <tr class="bg-slate-50 dark:bg-slate-900/50 hover:bg-white dark:hover:bg-slate-800 transition-all shadow-xs">
                <td class="px-6 py-4 rounded-l-2xl">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black">
                      {{ substr($dosen->nama, 0, 1) }}
                    </div>
                    <div class="min-w-0">
                      <p class="font-black text-slate-800 dark:text-slate-100 leading-tight truncate max-w-[200px]">
                        {{ $dosen->nama }}</p>
                      <p class="text-[11px] text-slate-400 font-mono mt-0.5 truncate max-w-[220px]">
                        {{ $dosen->user->email ?? '-' }}</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span
                    class="text-sm font-bold text-slate-700 dark:text-slate-200 block font-mono">{{ $dosen->nip ?? '-' }}</span>
                  <span class="text-[10px] text-slate-400 block font-semibold mt-0.5">NIDN: {{ $dosen->nidn ?? '-' }}</span>
                </td>
                <td class="px-6 py-4">
                  <span
                    class="inline-flex items-center px-2.5 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-[10px] font-black uppercase border border-indigo-100 dark:border-indigo-800/60">
                    {{ $dosen->jabatan ?? 'N/A' }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  @if($dosen->user && $dosen->user->is_active)
                    <span
                      class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-100 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 text-[10px] font-black uppercase border border-emerald-200 dark:border-emerald-900/30">
                      <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                      Aktif
                    </span>
                  @else
                    <span
                      class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-150 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-[10px] font-black uppercase border border-slate-200 dark:border-slate-700">
                      <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                      Nonaktif
                    </span>
                  @endif
                </td>
                <td class="px-6 py-4">
                  <p class="text-xs font-bold text-slate-600 dark:text-slate-300"><i
                      class="fas fa-phone text-[10px] mr-1.5 text-primary-500"></i> {{ $dosen->no_hp ?? '-' }}</p>
                  <p class="text-[10px] text-slate-400 font-medium truncate max-w-[150px] mt-0.5"><i
                      class="fas fa-map-marker-alt mr-1.5 text-rose-500"></i> {{ $dosen->alamat ?? '-' }}</p>
                </td>
                <td class="px-6 py-4 text-right rounded-r-2xl">
                  <div class="flex justify-end gap-2">
                    <button type="button" @click="MicroModal.show('modal-edit-{{ $dosen->id }}')"
                      class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-100 dark:border-blue-800">
                      <i class="fas fa-edit text-xs"></i>
                    </button>
                    <button type="button" @click="MicroModal.show('modal-delete-{{ $dosen->id }}')"
                      class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white transition-all shadow-sm border border-red-100 dark:border-red-800">
                      <i class="fas fa-trash text-xs"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-6 py-20 text-center text-slate-400 italic text-sm">Data dosen belum tersedia.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="block md:hidden p-4 space-y-3">
        @forelse($dosens as $dosen)
          <div
            class="bg-slate-50 dark:bg-slate-900/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-800/80 shadow-xs space-y-3">
            <div class="flex justify-between items-start gap-2">
              <div class="flex items-center gap-3 min-w-0">
                <div
                  class="w-10 h-10 shrink-0 rounded-xl bg-indigo-150 dark:bg-indigo-900/30 flex items-center justify-center font-black text-indigo-600 dark:text-indigo-400 text-sm">
                  {{ substr($dosen->nama, 0, 1) }}
                </div>
                <div class="min-w-0">
                  <h4 class="font-black text-slate-800 dark:text-white text-sm leading-tight truncate">{{ $dosen->nama }}
                  </h4>
                  <p class="text-[10px] font-mono text-slate-400 truncate mt-0.5">{{ $dosen->user->email ?? '-' }}</p>
                </div>
              </div>
              <div class="shrink-0 flex flex-col items-end gap-1.5">
                @if($dosen->user && $dosen->user->is_active)
                  <span
                    class="px-2 py-0.5 rounded-md text-[8px] font-black uppercase bg-emerald-100 text-emerald-600 border border-emerald-200">Aktif</span>
                @else
                  <span
                    class="px-2 py-0.5 rounded-md text-[8px] font-black uppercase bg-slate-200 text-slate-500 border border-slate-300">Off</span>
                @endif
              </div>
            </div>
            <div class="text-[11px] text-slate-500 dark:text-slate-400 space-y-0.5 font-medium">
              <p><span class="font-bold text-slate-700 dark:text-slate-300">NIP:</span> <span
                  class="font-mono">{{ $dosen->nip ?? '-' }}</span></p>
              <p><span class="font-bold text-slate-700 dark:text-slate-300">Jabatan:</span> {{ $dosen->jabatan ?? 'N/A' }}
              </p>
              <p class="truncate"><i class="fas fa-phone text-[9px] mr-1 text-primary-500"></i> {{ $dosen->no_hp ?? '-' }}
              </p>
            </div>
            <div class="pt-2 border-t border-slate-200/50 dark:border-slate-700/50 flex justify-end gap-1.5">
              <button type="button" @click="MicroModal.show('modal-edit-{{ $dosen->id }}')"
                class="px-3 py-1.5 bg-amber-50 text-amber-600 border border-amber-100 rounded-xl text-[10px] font-black uppercase tracking-wider flex items-center gap-1">
                <i class="fas fa-edit"></i> Edit
              </button>
              <button type="button" @click="MicroModal.show('modal-delete-{{ $dosen->id }}')"
                class="px-3 py-1.5 bg-rose-50 text-rose-600 border border-rose-100 rounded-xl text-[10px] font-black uppercase tracking-wider flex items-center gap-1">
                <i class="fas fa-trash"></i> Hapus
              </button>
            </div>
          </div>
        @empty
          <div class="p-8 text-center text-slate-400 italic text-xs">Data dosen belum tersedia.</div>
        @endforelse
      </div>
      @if($dosens->hasPages())
        <div
          class="px-4 py-3 sm:px-6 sm:py-4 bg-slate-50/50 dark:bg-slate-900/20 border-t border-slate-100 dark:border-slate-700">
          {{ $dosens->links() }}
        </div>
      @endif
    </div>
  </div>
  <div class="modal micromodal-slide" id="modal-create-dosen" aria-hidden="true">
    <div
      class="modal__overlay fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 transition-all duration-300 ease-out"
      tabindex="-1" data-micromodal-close>
      <div
        class="modal__container bg-white dark:bg-slate-800 rounded-4xl p-6 sm:p-10 shadow-2xl w-full max-w-4xl relative transform transition-all duration-300 scale-95"
        role="dialog" aria-modal="true">
        <header class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-700">
          <div>
            <h2 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Registrasi Dosen Baru
            </h2>
            <p
              class="text-[10px] sm:text-[11px] text-slate-400 dark:text-slate-500 mt-1 uppercase tracking-wider font-bold">
              Username & Pass default adalah NIP</p>
          </div>
          <button type="button"
            class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
            data-micromodal-close>
            <i class="fas fa-times"></i>
          </button>
        </header>
        <form action="{{ route('admin.dosen.store') }}" method="POST">
          @csrf
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 text-left">
            <div class="space-y-4">
              <h4 class="text-[10px] font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400">Data
                Akademik</h4>
              <div>
                <label class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">NIP <span
                    class="text-red-500">*</span></label>
                <input type="text" name="nip" required placeholder="Contoh: 19880312..."
                  class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-medium outline-none">
              </div>
              <div>
                <label class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">NIDN</label>
                <input type="text" name="nidn" placeholder="Nomor Induk Dosen Nasional"
                  class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-medium outline-none">
              </div>
              <div>
                <label class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">Nama Lengkap <span
                    class="text-red-500">*</span></label>
                <input type="text" name="nama" required placeholder="Nama Lengkap & Gelar"
                  class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-medium outline-none">
              </div>
              <div>
                <label class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">Email Institusi <span
                    class="text-red-500">*</span></label>
                <input type="email" name="email" required placeholder="dosen@polije.ac.id"
                  class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-medium outline-none">
              </div>
            </div>
            <div class="space-y-4">
              <h4 class="text-[10px] font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400">Informasi
                Pribadi</h4>
              <div>
                <label class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">Jabatan</label>
                <input type="text" name="jabatan" placeholder="Contoh: Lektor Kepala"
                  class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-medium outline-none">
              </div>
              <div>
                <label class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">NIK</label>
                <input type="text" name="nik" placeholder="16 Digit No. Kependudukan"
                  class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-medium outline-none">
              </div>
              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">WhatsApp</label>
                  <input type="text" name="no_hp" placeholder="08xxxxxxxxxx"
                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-medium outline-none">
                </div>
                <div>
                  <label class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">Tgl Lahir</label>
                  <input type="date" name="tanggal_lahir"
                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-medium outline-none">
                </div>
              </div>
              <div>
                <label class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">Alamat Lengkap</label>
                <textarea name="alamat" rows="2" placeholder="Alamat domisili saat ini..."
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
  @foreach($dosens as $dosen)
    <div class="modal micromodal-slide" id="modal-edit-{{ $dosen->id }}" aria-hidden="true">
      <div
        class="modal__overlay fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 transition-all duration-300"
        tabindex="-1" data-micromodal-close>
        <div
          class="modal__container bg-white dark:bg-slate-800 rounded-4xl p-6 sm:p-10 shadow-2xl w-full max-w-2xl relative transform transition-all duration-300 scale-95"
          role="dialog" aria-modal="true">
          <header class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-700">
            <div>
              <h2 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Edit Profil Dosen</h2>
              <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 uppercase tracking-widest font-black">NIP:
                {{ $dosen->nip }}</p>
            </div>
            <button type="button"
              class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
              data-micromodal-close>
              <i class="fas fa-times"></i>
            </button>
          </header>
          <form action="{{ route('admin.dosen.update', $dosen->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 text-left">
              <div class="space-y-4">
                <h4 class="text-[10px] font-black uppercase tracking-widest text-blue-600 dark:text-blue-400">Data Akademik
                </h4>
                <div>
                  <label class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">Nama Lengkap</label>
                  <input type="text" name="nama" value="{{ $dosen->nama }}" required
                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-medium outline-none">
                </div>
                <div>
                  <label class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">NIP</label>
                  <input type="text" name="nip" value="{{ $dosen->nip }}"
                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-medium outline-none">
                </div>
                <div>
                  <label class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">Email Akun</label>
                  <input type="email" name="email" value="{{ $dosen->user->email ?? '' }}" required
                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-medium outline-none">
                </div>
                <div>
                  <label class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">Jabatan</label>
                  <input type="text" name="jabatan" value="{{ $dosen->jabatan }}"
                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-medium outline-none">
                </div>
              </div>
              <div class="space-y-4">
                <h4 class="text-[10px] font-black uppercase tracking-widest text-blue-600 dark:text-blue-400">Data Personal
                </h4>
                <div>
                  <label class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">NIK</label>
                  <input type="text" name="nik" value="{{ $dosen->nik }}"
                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-medium outline-none">
                </div>
                <div>
                  <label class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">No. WhatsApp</label>
                  <input type="text" name="no_hp" value="{{ $dosen->no_hp }}"
                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-medium outline-none">
                </div>
                <div>
                  <label class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">Tanggal Lahir</label>
                  <input type="date" name="tanggal_lahir" value="{{ $dosen->tanggal_lahir }}"
                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 font-medium outline-none">
                </div>
                <div>
                  <label class="block text-xs font-black text-slate-700 dark:text-slate-300 mb-1.5">Alamat</label>
                  <textarea name="alamat" rows="2"
                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 resize-none outline-none font-medium">{{ $dosen->alamat }}</textarea>
                </div>
              </div>
            </div>
            <div class="mt-8 flex flex-col sm:flex-row gap-3">
              <button type="button" data-micromodal-close
                class="w-full sm:flex-1 py-3.5 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-black uppercase text-xs tracking-wider transition-colors">Batal</button>
              <button type="submit"
                class="w-full sm:flex-1 py-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-black uppercase text-xs tracking-wider shadow-lg shadow-blue-500/10 transition-all">Update
                Data Dosen</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="modal micromodal-slide" id="modal-delete-{{ $dosen->id }}" aria-hidden="true">
      <div
        class="modal__overlay fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 transition-all duration-300"
        tabindex="-1" data-micromodal-close>
        <div
          class="modal__container bg-white dark:bg-slate-800 rounded-4xl p-6 sm:p-8 shadow-2xl w-full max-w-sm text-center relative transform transition-all duration-300 scale-95"
          role="dialog" aria-modal="true">
          <div
            class="w-16 h-14 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-3xl flex items-center justify-center mx-auto mb-5 border-4 border-red-50 dark:border-red-900/20">
            <i class="fas fa-trash-alt text-2xl"></i>
          </div>
          <h2 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight mb-2">Hapus Dosen?</h2>
          <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mb-6 px-2 leading-relaxed">Menghapus profil
            <b>{{ $dosen->nama }}</b> juga akan melenyapkan kredensial login akun secara permanen.</p>
          <form action="{{ route('admin.dosen.destroy', $dosen->id) }}" method="POST" class="flex gap-2 w-full">
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
    /* MODAL SAKTI ANIMATED SLIDE MICROMODAL ENGINE */
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