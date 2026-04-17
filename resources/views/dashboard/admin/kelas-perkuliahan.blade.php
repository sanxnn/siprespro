@extends('layouts.app')

@section('title', 'Kelas Perkuliahan • SIPRESPRO')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Manajemen Kelas</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Pengaturan relasi matkul, dosen pengampu, dan lokasi ruang</p>
        </div>
        <button @click="MicroModal.show('modal-create-kelas')"
            class="flex items-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold transition-all shadow-lg shadow-primary-200 dark:shadow-none">
            <i class="fas fa-plus-circle"></i>
            <span>Buka Kelas Baru</span>
        </button>
    </div>

    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm transition-colors duration-300">
  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
      <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
        <tr>
          <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Informasi Mata Kuliah & Kelas</th>
          <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Dosen Pengampu</th>
          <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center">Ruang</th>
          <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center">Tipe</th>
          <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
        @forelse($kelases as $kelas)
          <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-all group">
            
            <td class="px-6 py-4 min-w-[280px]">
              <div class="flex items-start gap-3">
                <div class="w-10 h-10 shrink-0 rounded-xl bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center text-amber-600 dark:text-amber-400 font-bold border border-amber-200 dark:border-amber-800">
                  <i class="fas fa-book"></i>
                </div>
                <div class="space-y-1">
                  <div class="flex flex-col">
                    <p class="font-bold text-slate-800 dark:text-slate-100 leading-tight">
                        {{ $kelas->mataKuliah->nama }}
                    </p>
                    <p class="text-[10px] text-slate-400 font-mono font-bold uppercase tracking-wider">
                        {{ $kelas->mataKuliah->kode_mk }}
                    </p>
                  </div>
                  <div class="flex mt-1">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-300 text-[10px] font-bold border border-indigo-100 dark:border-indigo-800 max-w-[200px] truncate" title="{{ $kelas->nama_kelas }}">
                        <i class="fas fa-tag mr-1 text-[9px]"></i> {{ $kelas->nama_kelas }}
                    </span>
                  </div>
                </div>
              </div>
            </td>

            <td class="px-6 py-4">
              <div class="flex flex-col">
                <span class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $kelas->dosen->nama }}</span>
                <span class="text-[10px] text-slate-400 italic">NIP: {{ $kelas->dosen->nip ?? '-' }}</span>
              </div>
            </td>

            <td class="px-6 py-4 text-center">
              <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 text-xs font-bold border border-emerald-100 dark:border-emerald-800">
                <i class="fas fa-door-closed mr-1.5 text-[10px]"></i> {{ $kelas->ruang->nama }}
              </span>
            </td>

            <td class="px-6 py-4 text-center">
              <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded {{ $kelas->tipe_kelas == 'reguler' ? 'text-blue-600 bg-blue-50 dark:bg-blue-900/30 border border-blue-100' : 'text-purple-600 bg-purple-50 dark:bg-purple-900/30 border border-purple-100' }}">
                {{ $kelas->tipe_kelas }}
              </span>
            </td>

            <td class="px-6 py-4 text-right">
              <div class="flex justify-end gap-2">
                <button @click="MicroModal.show('modal-edit-{{ $kelas->id }}')"
                  class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 border border-blue-100 dark:border-blue-800 transition-all">
                  <i class="fas fa-edit text-xs"></i>
                </button>
                <button @click="MicroModal.show('modal-delete-{{ $kelas->id }}')"
                  class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 dark:bg-red-900/30 text-red-600 border border-red-100 dark:border-red-800 transition-all">
                  <i class="fas fa-trash text-xs"></i>
                </button>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-6 py-20 text-center text-slate-400 italic font-medium">Data kelas masih kosong jancok!</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
</div>

<div class="modal" id="modal-create-kelas" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container w-full max-w-2xl bg-white dark:bg-slate-800 rounded-4xl p-8" role="dialog" @click.stop>
            <header class="flex justify-between items-center mb-6 pb-4 border-b dark:border-slate-700/50">
                <h2 class="text-xl font-bold text-slate-800 dark:text-white tracking-tight">Buka Kelas Perkuliahan</h2>
                <button class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200" data-micromodal-close><i class="fas fa-times text-lg"></i></button>
            </header>

            <form action="{{ route('admin.kelas-perkuliahan.store') }}" method="POST" class="space-y-5 text-left">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Nama Kelas / Label <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_kelas" required placeholder="Contoh: TIF 22 A atau Gabungan Kelas A & B" class="w-full px-4 py-3 rounded-xl border dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary-500/50 outline-none transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Mata Kuliah</label>
                        <div class="relative">
                            <select name="mata_kuliah_id" required class="w-full px-4 py-3 rounded-xl border dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 appearance-none focus:ring-2 focus:ring-primary-500/50 outline-none transition-all">
                                <option value="" disabled selected>Pilih Matkul...</option>
                                @foreach($matkuls as $m) <option value="{{ $m->id }}">{{ $m->kode_mk }} - {{ $m->nama }}</option> @endforeach
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[10px] text-slate-400"></i>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Dosen Pengampu</label>
                        <div class="relative">
                            <select name="dosen_id" required class="w-full px-4 py-3 rounded-xl border dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 appearance-none focus:ring-2 focus:ring-primary-500/50 outline-none transition-all">
                                <option value="" disabled selected>Pilih Dosen...</option>
                                @foreach($dosens as $d) <option value="{{ $d->id }}">{{ $d->nama }}</option> @endforeach
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[10px] text-slate-400"></i>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Ruangan</label>
                        <div class="relative">
                            <select name="ruang_id" required class="w-full px-4 py-3 rounded-xl border dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 appearance-none focus:ring-2 focus:ring-primary-500/50 outline-none transition-all">
                                <option value="" disabled selected>Pilih Ruang...</option>
                                @foreach($ruangs as $r) <option value="{{ $r->id }}">{{ $r->nama }} ({{ $r->gedung }})</option> @endforeach
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[10px] text-slate-400"></i>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Tipe Kelas</label>
                        <div class="relative">
                            <select name="tipe_kelas" required class="w-full px-4 py-3 rounded-xl border dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 appearance-none focus:ring-2 focus:ring-primary-500/50 outline-none transition-all">
                                <option value="reguler">Reguler</option>
                                <option value="gabungan">Gabungan</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[10px] text-slate-400"></i>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" data-micromodal-close class="flex-1 py-3 bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 rounded-xl font-bold transition-all">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold shadow-lg shadow-primary-200 dark:shadow-none transition-all">Buat Kelas</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($kelases as $kelas)
    <div class="modal" id="modal-edit-{{ $kelas->id }}" aria-hidden="true">
      <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container w-full max-w-2xl bg-white dark:bg-slate-800 rounded-4xl p-8 shadow-2xl border-none dark:border dark:border-slate-700" role="dialog" @click.stop>
          
          <header class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-700/50">
            <div>
              <h2 class="text-xl font-bold text-slate-800 dark:text-white">Edit Kelas Perkuliahan</h2>
              <p class="text-[10px] text-blue-500 dark:text-blue-400 mt-0.5 uppercase tracking-widest font-bold font-mono">ID KELAS: #{{ $kelas->id }}</p>
            </div>
            <button class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition-all" data-micromodal-close>
              <i class="fas fa-times"></i>
            </button>
          </header>

          <form action="{{ route('admin.kelas-perkuliahan.update', $kelas->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-left">
              
              <div class="md:col-span-2">
                <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Nama Kelas / Label</label>
                <input type="text" name="nama_kelas" value="{{ $kelas->nama_kelas }}" required 
                  class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500/50 outline-none transition-all">
              </div>

              <div>
                <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Mata Kuliah</label>
                <div class="relative">
                  <select name="mata_kuliah_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 appearance-none focus:ring-2 focus:ring-blue-500/50 outline-none transition-all">
                    @foreach($matkuls as $m)
                      <option value="{{ $m->id }}" {{ $kelas->mata_kuliah_id == $m->id ? 'selected' : '' }}>{{ $m->kode_mk }} - {{ $m->nama }}</option>
                    @endforeach
                  </select>
                  <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none"></i>
                </div>
              </div>

              <div>
                <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Dosen Pengampu</label>
                <div class="relative">
                  <select name="dosen_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 appearance-none focus:ring-2 focus:ring-blue-500/50 outline-none transition-all">
                    @foreach($dosens as $d)
                      <option value="{{ $d->id }}" {{ $kelas->dosen_id == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>
                    @endforeach
                  </select>
                  <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none"></i>
                </div>
              </div>

              <div>
                <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Ruangan</label>
                <div class="relative">
                  <select name="ruang_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 appearance-none focus:ring-2 focus:ring-blue-500/50 outline-none transition-all">
                    @foreach($ruangs as $r)
                      <option value="{{ $r->id }}" {{ $kelas->ruang_id == $r->id ? 'selected' : '' }}>{{ $r->nama }} ({{ $r->gedung }})</option>
                    @endforeach
                  </select>
                  <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none"></i>
                </div>
              </div>

              <div>
                <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Tipe Kelas</label>
                <div class="relative">
                  <select name="tipe_kelas" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 appearance-none focus:ring-2 focus:ring-blue-500/50 outline-none transition-all">
                    <option value="reguler" {{ $kelas->tipe_kelas == 'reguler' ? 'selected' : '' }}>Reguler</option>
                    <option value="gabungan" {{ $kelas->tipe_kelas == 'gabungan' ? 'selected' : '' }}>Gabungan</option>
                  </select>
                  <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none"></i>
                </div>
              </div>
            </div>

            <div class="mt-8 flex gap-3">
              <button type="button" data-micromodal-close class="flex-1 py-3 bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 rounded-xl font-bold transition-all hover:bg-slate-200 dark:hover:bg-slate-700">Batal</button>
              <button type="submit" class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-200 dark:shadow-none transition-all">Update Kelas</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal" id="modal-delete-{{ $kelas->id }}" aria-hidden="true">
      <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container w-full max-w-sm text-center bg-white dark:bg-slate-800 rounded-4xl p-8 shadow-2xl border-none dark:border dark:border-slate-700" role="dialog" @click.stop>
          <div class="w-20 h-20 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-red-50 dark:border-red-900/10">
            <i class="fas fa-exclamation-triangle text-3xl"></i>
          </div>
          <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Hapus Kelas?</h2>
          <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 leading-relaxed">
            Menghapus kelas <b>{{ $kelas->nama_kelas }}</b> akan berdampak pada data pertemuan dan presensi yang sudah ada jancok!
          </p>
          <form action="{{ route('admin.kelas-perkuliahan.destroy', $kelas->id) }}" method="POST" class="flex gap-3">
            @csrf
            @method('DELETE')
            <button type="button" data-micromodal-close class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-bold transition-all">Batal</button>
            <button type="submit" class="flex-1 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold shadow-lg shadow-red-200 dark:shadow-none transition-all">Ya, Hapus</button>
          </form>
        </div>
      </div>
    </div>
  @endforeach
@endsection