@extends('layouts.app')

@section('title', 'Data Mata Kuliah • SIPRESPRO')

@section('content')
  <div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Daftar Mata Kuliah</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Manajemen kurikulum dan beban SKS per semester</p>
      </div>
      <button @click="MicroModal.show('modal-create-matkul')"
        class="flex items-center gap-2 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold transition-all shadow-lg shadow-primary-200">
        <i class="fas fa-plus-circle"></i>
        <span>Tambah Matkul</span>
      </button>
    </div>

    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
            <tr>
              <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Kode & Nama Matkul</th>
              <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center">SKS</th>
              <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center">Semester</th>
              <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
            @forelse($matkuls as $matkul)
              <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-all">
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center text-amber-600 dark:text-amber-400 font-bold border border-amber-200 dark:border-amber-800">
                      <i class="fas fa-book"></i>
                    </div>
                    <div>
                      <p class="font-bold text-slate-800 dark:text-slate-100">{{ $matkul->nama }}</p>
                      <p class="text-[10px] text-slate-400 font-mono">{{ $matkul->kode_mk }}</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 text-center">
                  <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs border border-slate-200 dark:border-slate-600">
                    {{ $matkul->sks }}
                  </span>
                </td>
                <td class="px-6 py-4 text-center">
                  <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400 uppercase">{{ $matkul->semester->nama ?? '-' }}</span>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex justify-end gap-2">
                    <button @click="MicroModal.show('modal-edit-{{ $matkul->id }}')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 border border-blue-100 dark:border-blue-800 transition-all">
                      <i class="fas fa-edit text-xs"></i>
                    </button>
                    <button @click="MicroModal.show('modal-delete-{{ $matkul->id }}')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 dark:bg-red-900/30 text-red-600 border border-red-100 dark:border-red-800 transition-all">
                      <i class="fas fa-trash text-xs"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-6 py-20 text-center text-slate-400 italic font-medium">Data matkul masih kosong jancok!</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($matkuls->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 text-white">
            {{ $matkuls->links() }}
        </div>
      @endif
    </div>
  </div>

  <div class="modal" id="modal-create-matkul" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
      <div class="modal__container w-full max-w-md" role="dialog" @click.stop>
        <header class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-700 text-left">
          <h2 class="text-xl font-bold text-slate-800 dark:text-white">Tambah Mata Kuliah</h2>
          <button class="text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 w-8 h-8 rounded-lg" data-micromodal-close><i class="fas fa-times"></i></button>
        </header>

        <form action="{{ route('admin.mata-kuliah.store') }}" method="POST" class="text-left">
          @csrf
          <div class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-widest">Kode Matkul <span class="text-red-500">*</span></label>
              <input type="text" name="kode_mk" required placeholder="Contoh: TIF101" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 focus:ring-2 focus:ring-primary-500 outline-none">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-widest">Nama Mata Kuliah <span class="text-red-500">*</span></label>
              <input type="text" name="nama" required placeholder="Nama lengkap matkul" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 focus:ring-2 focus:ring-primary-500 outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-widest">Beban SKS</label>
                <input type="number" name="sks" required min="1" max="6" placeholder="2" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 focus:ring-2 focus:ring-primary-500 outline-none">
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-widest">Semester</label>
                <select name="semester_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 outline-none">
                  @foreach($semesters as $sem)
                    <option value="{{ $sem->id }}">{{ $sem->nama }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="mt-8 flex gap-3">
            <button type="button" data-micromodal-close class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 rounded-xl font-bold">Batal</button>
            <button type="submit" class="flex-1 py-3 bg-primary-600 text-white rounded-xl font-bold">Simpan Data</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @foreach($matkuls as $matkul)
    <div class="modal" id="modal-edit-{{ $matkul->id }}" aria-hidden="true">
      <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container w-full max-w-md" role="dialog" @click.stop>
          <header class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-700 text-left">
            <h2 class="text-xl font-bold text-slate-800 dark:text-white">Edit Mata Kuliah</h2>
            <button class="text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 w-8 h-8 rounded-lg" data-micromodal-close><i class="fas fa-times"></i></button>
          </header>

          <form action="{{ route('admin.mata-kuliah.update', $matkul->id) }}" method="POST" class="text-left">
            @csrf @method('PUT')
            <div class="space-y-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-widest">Kode Matkul</label>
                <input type="text" name="kode_mk" value="{{ $matkul->kode_mk }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 outline-none focus:ring-2 focus:ring-blue-500">
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-widest">Nama Matkul</label>
                <input type="text" name="nama" value="{{ $matkul->nama }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 outline-none focus:ring-2 focus:ring-blue-500">
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-widest">SKS</label>
                  <input type="number" name="sks" value="{{ $matkul->sks }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 outline-none">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-widest">Semester</label>
                  <select name="semester_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 outline-none">
                    @foreach($semesters as $sem)
                      <option value="{{ $sem->id }}" {{ $matkul->semester_id == $sem->id ? 'selected' : '' }}>{{ $sem->nama }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="mt-8 flex gap-3">
              <button type="button" data-micromodal-close class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 rounded-xl font-bold">Batal</button>
              <button type="submit" class="flex-1 py-3 bg-blue-600 text-white rounded-xl font-bold">Update Data</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal" id="modal-delete-{{ $matkul->id }}" aria-hidden="true">
      <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container w-full max-w-sm text-center" role="dialog" @click.stop>
          <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-red-50 dark:border-red-900/20">
            <i class="fas fa-trash text-2xl"></i>
          </div>
          <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Hapus Matkul?</h2>
          <p class="text-sm text-slate-500 mb-6">Matkul <b>{{ $matkul->nama }}</b> akan dihapus permanen, Aksi ini tidak bisa dibatalkan!</p>
          <form action="{{ route('admin.mata-kuliah.destroy', $matkul->id) }}" method="POST" class="flex gap-3">
            @csrf @method('DELETE')
            <button type="button" data-micromodal-close class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 rounded-xl font-bold">Batal</button>
            <button type="submit" class="flex-1 py-3 bg-red-600 text-white rounded-xl font-bold">Ya, Hapus</button>
          </form>
        </div>
      </div>
    </div>
  @endforeach
@endsection