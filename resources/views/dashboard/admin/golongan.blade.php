@extends('layouts.app')

@section('title', 'Data Golongan • SIPRESPRO')

@section('content')
  <div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Master Golongan</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Total {{ $golongans->total() }} golongan kelas terdaftar</p>
      </div>
      <div class="flex items-center gap-3">
        <button @click="MicroModal.show('modal-create-golongan')"
          class="flex items-center gap-2 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold transition-all shadow-lg shadow-primary-200 dark:shadow-none">
          <i class="fas fa-plus-circle"></i>
          <span>Tambah Golongan</span>
        </button>
      </div>
    </div>

    <div
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm transition-colors duration-300">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
            <tr>
              <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">No</th>
              <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Nama Golongan</th>
              <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Semester Terkait</th>
              <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
            @forelse($golongans as $golongan)
              <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-all group">
                <td class="px-6 py-4 font-mono text-sm text-slate-400">{{ $loop->iteration }}</td>
                <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-100">
                  {{ $golongan->nama }}
                </td>
                <td class="px-6 py-4">
                  <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-800">
                    <i class="fas fa-calendar-alt text-[10px]"></i>
                    <span class="text-xs font-bold uppercase">{{ $golongan->semester->nama ?? 'N/A' }}</span>
                  </div>
                  <span class="text-[10px] text-slate-400 ml-2 italic">{{ $golongan->semester->tahun_ajaran }}</span>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex justify-end gap-2">
                    <button @click="MicroModal.show('modal-edit-{{ $golongan->id }}')"
                      class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-500 transition-all border border-blue-100 dark:border-blue-800">
                      <i class="fas fa-edit text-xs"></i>
                    </button>
                    <button @click="MicroModal.show('modal-delete-{{ $golongan->id }}')"
                      class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white dark:hover:bg-red-500 transition-all border border-red-100 dark:border-red-800">
                      <i class="fas fa-trash text-xs"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-6 py-20 text-center text-slate-400 italic font-medium">Data golongan masih kosong
                  jancok!</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($golongans->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
          {{ $golongans->links() }}
        </div>
      @endif
    </div>
  </div>

  <div class="modal" id="modal-create-golongan" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
      <div class="modal__container w-full max-w-md" role="dialog" @click.stop>
        <header
          class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-700 text-left">
          <h2 class="text-xl font-bold text-slate-800 dark:text-white">Tambah Golongan</h2>
          <button class="text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 w-8 h-8 rounded-lg"
            data-micromodal-close><i class="fas fa-times"></i></button>
        </header>

        <form action="{{ route('admin.golongan.store') }}" method="POST" class="text-left">
          @csrf
          <div class="space-y-4">
            <div>
              <label
                class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-widest">Nama
                Golongan <span class="text-red-500">*</span></label>
              <input type="text" name="nama" required placeholder="Contoh: Golongan A, B, atau C"
                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 focus:ring-2 focus:ring-primary-500 outline-none">
            </div>
            <div>
              <label
                class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-widest">Pilih
                Semester <span class="text-red-500">*</span></label>
              <select name="semester_id" required
                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 outline-none">
                <option value="" disabled selected>Pilih Semester...</option>
                @foreach($semesters as $sem)
                  <option value="{{ $sem->id }}">{{ $sem->nama }} ({{ $sem->tahun_ajaran }})</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="mt-8 flex gap-3">
            <button type="button" data-micromodal-close
              class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 rounded-xl font-bold">Batal</button>
            <button type="submit"
              class="flex-1 py-3 bg-primary-600 text-white rounded-xl font-bold shadow-lg shadow-primary-200">Simpan
              Data</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @foreach($golongans as $golongan)
    <div class="modal" id="modal-edit-{{ $golongan->id }}" aria-hidden="true">
      <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container w-full max-w-md" role="dialog" @click.stop>
          <header class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-700">
            <div>
              <h2 class="text-xl font-bold text-slate-800 dark:text-white">Edit Golongan</h2>
              <p class="text-[10px] text-primary-500 mt-0.5 uppercase tracking-widest font-bold">Update informasi golongan
              </p>
            </div>
            <button
              class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
              data-micromodal-close>
              <i class="fas fa-times"></i>
            </button>
          </header>

          <form action="{{ route('admin.golongan.update', $golongan->id) }}" method="POST" class="text-left">
            @csrf
            @method('PUT')
            <div class="space-y-4">
              <div>
                <label
                  class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-widest">Nama
                  Golongan</label>
                <input type="text" name="nama" value="{{ $golongan->nama }}" required
                  class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
              </div>
              <div>
                <label
                  class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-widest">Pilih
                  Semester</label>
                <select name="semester_id" required
                  class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none">
                  @foreach($semesters as $sem)
                    <option value="{{ $sem->id }}" {{ $golongan->semester_id == $sem->id ? 'selected' : '' }}>
                      {{ $sem->nama }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="mt-8 flex gap-3">
              <button type="button" data-micromodal-close
                class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-bold transition-all hover:bg-slate-200 dark:hover:bg-slate-600">Batal</button>
              <button type="submit"
                class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-200 dark:shadow-lg dark:shadow-slate-900 transition-all">Update
                Data</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal" id="modal-delete-{{ $golongan->id }}" aria-hidden="true">
      <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container w-full max-w-sm text-center" role="dialog" @click.stop>
          <div
            class="w-20 h-20 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center mx-auto mb-5 border-4 border-red-50 dark:border-red-900/20">
            <i class="fas fa-trash-alt text-3xl"></i>
          </div>
          <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Hapus Golongan?</h2>
          <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 px-4 leading-relaxed">
            Yakin mau hapus <b>{{ $golongan->nama }}</b>? Data mahasiswa yang terhubung mungkin akan bermasalah, Aksi ini tidak bisa dibatalkan!
          </p>
          <form action="{{ route('admin.golongan.destroy', $golongan->id) }}" method="POST" class="flex gap-3 px-2">
            @csrf
            @method('DELETE')
            <button type="button" data-micromodal-close
              class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-bold transition-all">Batal</button>
            <button type="submit"
              class="flex-1 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold shadow-lg shadow-red-200 dark:shadow-lg dark:shadow-slate-900">Ya,
              Hapus</button>
          </form>
        </div>
      </div>
    </div>
  @endforeach
@endsection

@push('styles')
  <style>
    /* Pake style yang sama persis kayak halaman Semester lu cok */
    .modal {
      display: none;
    }

    .modal.is-open {
      display: block;
    }

    .modal__overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(15, 23, 42, 0.7);
      backdrop-filter: blur(5px);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }

    .modal__container {
      background-color: #fff;
      padding: 2rem;
      border-radius: 1.75rem;
      max-height: 90vh;
      overflow-y: auto;
      position: relative;
      border: 1px solid #f1f5f9;
    }

    .dark .modal__container {
      background-color: #1e293b;
      border-color: #334155;
    }
  </style>
@endpush