@extends('layouts.app') @section('title', 'Data Semester • SIPRESPRO')

@section('content')
  <div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Manajemen Semester</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Total {{ $semesters->total() }} periode akademik terdaftar</p>
      </div>
      <div class="flex items-center gap-3">
        <button @click="MicroModal.show('modal-create-semester')"
          class="flex items-center gap-2 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold transition-all shadow-lg shadow-primary-200 dark:shadow-none">
          <i class="fas fa-plus-circle"></i>
          <span>Tambah Semester</span>
        </button>
      </div>
    </div>

    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm transition-colors duration-300">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
            <tr>
              <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">No</th>
              <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Informasi Semester</th>
              <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center">Tahun Ajaran</th>
              <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Status</th>
              <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
            @forelse($semesters as $semester)
              <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-all group">
                <td class="px-6 py-4">
                  <span class="text-sm font-mono text-slate-400">{{ $loop->iteration }}</span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold border border-primary-200 dark:border-primary-800">
                      <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div>
                      <p class="font-bold text-slate-800 dark:text-slate-100">{{ $semester->nama }}</p>
                      <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">ID: SEM-0{{ $semester->id }}</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 text-center">
                  <span class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $semester->tahun_ajaran }}</span>
                </td>
                <td class="px-6 py-4">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800">
                    Aktif
                  </span>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex justify-end gap-2">
                    <button @click="MicroModal.show('modal-edit-{{ $semester->id }}')"
                      class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-500 transition-all duration-300 shadow-sm border border-blue-100 dark:border-blue-800">
                      <i class="fas fa-edit text-xs"></i>
                    </button>
                    <button @click="MicroModal.show('modal-delete-{{ $semester->id }}')"
                      class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white dark:hover:bg-red-500 transition-all duration-300 shadow-sm border border-red-100 dark:border-red-800">
                      <i class="fas fa-trash text-xs"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-6 py-20 text-center">
                  <i class="fas fa-folder-open text-slate-300 text-4xl mb-3"></i>
                  <p class="text-slate-400 italic">Data semester belum tersedia.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($semesters->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
            {{ $semesters->links() }}
        </div>
      @endif
    </div>
  </div>

  <div class="modal" id="modal-create-semester" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
      <div class="modal__container w-full max-w-lg" role="dialog" @click.stop>
        <header class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-700">
          <div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-white">Tambah Semester</h2>
            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wider font-semibold">Pastikan format tahun ajaran benar</p>
          </div>
          <button class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700" data-micromodal-close>
            <i class="fas fa-times"></i>
          </button>
        </header>

        <form action="{{ route('admin.semester.store') }}" method="POST">
          @csrf
          <div class="space-y-5">
            <div>
              <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-widest">Nama Semester <span class="text-red-500">*</span></label>
              <input type="text" name="nama" required placeholder="Contoh: Ganjil, Genap, atau Semester 1"
                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 outline-none transition-all">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-widest">Tahun Ajaran <span class="text-red-500">*</span></label>
              <input type="text" name="tahun_ajaran" required placeholder="Contoh: 2024/2025"
                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 outline-none transition-all">
            </div>
          </div>

          <div class="mt-8 flex gap-3">
            <button type="button" data-micromodal-close
              class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-bold transition-colors">Batal</button>
            <button type="submit"
              class="flex-1 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold shadow-lg shadow-primary-200 transition-all dark:shadow-lg dark:shadow-slate-900">Simpan Data</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @foreach($semesters as $semester)
    <div class="modal" id="modal-edit-{{ $semester->id }}" aria-hidden="true">
      <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container w-full max-w-lg" role="dialog" @click.stop>
          <header class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-700">
            <div>
              <h2 class="text-xl font-bold text-slate-800 dark:text-white">Edit Semester</h2>
              <p class="text-[10px] text-slate-400 mt-0.5 uppercase tracking-widest font-bold text-primary-500">Update data periode akademik</p>
            </div>
            <button class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors" data-micromodal-close>
              <i class="fas fa-times"></i>
            </button>
          </header>

          <form action="{{ route('admin.semester.update', $semester->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="space-y-5">
              <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-widest">Nama Semester</label>
                <input type="text" name="nama" value="{{ $semester->nama }}" required
                  class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-widest">Tahun Ajaran</label>
                <input type="text" name="tahun_ajaran" value="{{ $semester->tahun_ajaran }}" required
                  class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
              </div>
            </div>

            <div class="mt-8 flex gap-3">
              <button type="button" data-micromodal-close
                class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-bold transition-all">Batal</button>
              <button type="submit"
                class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-200 dark:shadow-lg dark:shadow-slate-900 transition-all">Update Semester</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal" id="modal-delete-{{ $semester->id }}" aria-hidden="true">
      <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container w-full max-w-sm text-center" role="dialog" @click.stop>
          <div class="w-20 h-20 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center mx-auto mb-5 border-4 border-red-50 dark:border-red-900/20">
            <i class="fas fa-exclamation-triangle text-3xl"></i>
          </div>
          <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Hapus Semester?</h2>
          <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 px-4 leading-relaxed text-center">
            Data <b>{{ $semester->nama }} {{ $semester->tahun_ajaran }}</b> akan dihapus permanen. Aksi ini tidak bisa dibatalkan!
          </p>
          <form action="{{ route('admin.semester.destroy', $semester->id) }}" method="POST" class="flex gap-3 px-2">
            @csrf @method('DELETE')
            <button type="button" data-micromodal-close
              class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-bold transition-all">Batal</button>
            <button type="submit"
              class="flex-1 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold shadow-lg shadow-red-200 dark:shadow-lg dark:shadow-slate-900">Ya, Hapus</button>
          </form>
        </div>
      </div>
    </div>
  @endforeach

@endsection

@push('styles')
<style>
  .modal { display: none; }
  .modal.is-open { display: block; }
  .modal__overlay {
    position: fixed; top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(5px);
    display: flex; justify-content: center; align-items: center; z-index: 9999;
  }
  .modal__container {
    background-color: #fff; padding: 2rem; border-radius: 1.75rem;
    max-height: 90vh; overflow-y: auto; position: relative;
    border: 1px solid #f1f5f9;
  }
  .dark .modal__container { background-color: #1e293b; border-color: #334155; }
  input:focus { border-color: #10b981 !important; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1) !important; }
</style>
@endpush