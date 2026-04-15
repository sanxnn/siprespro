@extends('layouts.app')

@section('title', 'Data Ruang • SIPRESPRO')

@section('content')
  <div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Manajemen Ruang</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Data lokasi fisik dan kapasitas ruangan
          perkuliahan</p>
      </div>
      <button @click="MicroModal.show('modal-create-ruang')"
        class="flex items-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold transition-all shadow-lg shadow-primary-200 dark:shadow-none">
        <i class="fas fa-plus-circle"></i>
        <span>Tambah Ruang</span>
      </button>
    </div>

    <div
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm transition-colors duration-300">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
            <tr>
              <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Nama
                Ruangan</th>
              <th
                class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center">
                Lokasi Gedung</th>
              <th
                class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center">
                Kapasitas</th>
              <th
                class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
            @forelse($ruangs as $ruang)
              <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-all group">
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400 font-bold border border-emerald-200 dark:border-emerald-800">
                      <i class="fas fa-door-open"></i>
                    </div>
                    <div>
                      <p class="font-bold text-slate-800 dark:text-slate-100">{{ $ruang->nama }}</p>
                      <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">ID:
                        RM-{{ str_pad($ruang->id, 3, '0', STR_PAD_LEFT) }}</p>
                    </div>
                  </div>
                </td>

                <td class="px-6 py-4 text-center">
                  <span
                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-300 text-xs font-bold border border-slate-200 dark:border-slate-700 transition-colors">
                    <i class="fas fa-building text-primary-500"></i>
                    {{ $ruang->gedung }}
                  </span>
                </td>

                <td class="px-6 py-4 text-center">
                  <span
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-bold text-xs border border-indigo-100 dark:border-indigo-800">
                    <i class="fas fa-users text-[10px]"></i>
                    {{ $ruang->kapasitas }} <span class="hidden md:inline">Kursi</span>
                  </span>
                </td>

                <td class="px-6 py-4 text-right">
                  <div class="flex justify-end gap-2">
                    <button @click="MicroModal.show('modal-edit-{{ $ruang->id }}')"
                      class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-500 transition-all duration-300 border border-blue-100 dark:border-blue-800 shadow-sm shadow-blue-100 dark:shadow-none">
                      <i class="fas fa-edit text-xs"></i>
                    </button>
                    <button @click="MicroModal.show('modal-delete-{{ $ruang->id }}')"
                      class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white dark:hover:bg-red-500 transition-all duration-300 border border-red-100 dark:border-red-800 shadow-sm shadow-red-100 dark:shadow-none">
                      <i class="fas fa-trash text-xs"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-6 py-20 text-center">
                  <div class="flex flex-col items-center justify-center">
                    <i class="fas fa-map-location-dot text-slate-300 dark:text-slate-600 text-5xl mb-4"></i>
                    <p class="text-slate-400 italic">Data ruangan belum tersedia di SIPRESPRO.</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="modal" id="modal-create-ruang" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
      <div class="modal__container w-full max-w-md bg-white dark:bg-slate-800 rounded-4xl p-8" role="dialog" @click.stop>
        <header class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-700/50">
          <div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-white">Tambah Ruangan</h2>
            <p
              class="text-[11px] text-primary-600 dark:text-primary-400 mt-1 uppercase tracking-wider font-bold text-left">
              Input Lokasi Perkuliahan</p>
          </div>
          <button
            class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
            data-micromodal-close>
            <i class="fas fa-times"></i>
          </button>
        </header>

        <form action="{{ route('admin.ruang.store') }}" method="POST" class="text-left space-y-5">
          @csrf
          <div>
            <label
              class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Nama
              Ruang <span class="text-red-500">*</span></label>
            <input type="text" name="nama" required placeholder="Contoh: Lab Jaringan 1"
              class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary-500/50 outline-none transition-all">
          </div>
          <div>
            <label
              class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Gedung
              <span class="text-red-500">*</span></label>
            <input type="text" name="gedung" required placeholder="Contoh: Gedung Teknologi Informasi"
              class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary-500/50 outline-none transition-all">
          </div>
          <div>
            <label
              class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Kapasitas
              (Orang)</label>
            <input type="number" name="kapasitas" required min="1" placeholder="30"
              class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary-500/50 outline-none transition-all">
          </div>
          <div class="mt-8 flex gap-3">
            <button type="button" data-micromodal-close
              class="flex-1 py-3 bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 rounded-xl font-bold transition-all">Batal</button>
            <button type="submit"
              class="flex-1 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold shadow-lg shadow-primary-200 dark:shadow-none transition-all">Simpan
              Ruang</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @foreach($ruangs as $ruang)
    <div class="modal" id="modal-edit-{{ $ruang->id }}" aria-hidden="true">
      <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container w-full max-w-md bg-white dark:bg-slate-800 rounded-4xl p-8" role="dialog" @click.stop>
          <header
            class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-700/50 text-left">
            <div>
              <h2 class="text-xl font-bold text-slate-800 dark:text-white">Edit Ruangan</h2>
              <p class="text-[10px] text-blue-500 dark:text-blue-400 mt-0.5 uppercase tracking-widest font-bold">Update
                informasi lokasi perkuliahan</p>
            </div>
            <button
              class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
              data-micromodal-close>
              <i class="fas fa-times"></i>
            </button>
          </header>

          <form action="{{ route('admin.ruang.update', $ruang->id) }}" method="POST" class="space-y-5 text-left">
            @csrf @method('PUT')
            <div>
              <label
                class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Nama
                Ruang</label>
              <input type="text" name="nama" value="{{ $ruang->nama }}" required
                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500/50 outline-none transition-all">
            </div>
            <div>
              <label
                class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Gedung</label>
              <input type="text" name="gedung" value="{{ $ruang->gedung }}" required
                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500/50 outline-none transition-all">
            </div>
            <div>
              <label
                class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Kapasitas</label>
              <input type="number" name="kapasitas" value="{{ $ruang->kapasitas }}" required
                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500/50 outline-none transition-all">
            </div>
            <div class="mt-8 flex gap-3">
              <button type="button" data-micromodal-close
                class="flex-1 py-3 bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 rounded-xl font-bold transition-all">Batal</button>
              <button type="submit"
                class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition-all shadow-lg shadow-blue-200 dark:shadow-none">Update
                Data</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal" id="modal-delete-{{ $ruang->id }}" aria-hidden="true">
      <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container w-full max-w-sm text-center bg-white dark:bg-slate-800 rounded-4xl p-8" role="dialog"
          @click.stop>
          <div
            class="w-20 h-20 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-red-50 dark:border-red-900/10">
            <i class="fas fa-trash-alt text-3xl"></i>
          </div>
          <h2 class="text-xl text-slate-800 dark:text-white mb-2 font-bold">Hapus Ruangan?</h2>
          <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 leading-relaxed">
            Ruang <b>{{ $ruang->nama }}</b> akan dihapus permanen. Jadwal yang menggunakan ruang ini mungkin terpengaruh!
          </p>
          <form action="{{ route('admin.ruang.destroy', $ruang->id) }}" method="POST" class="flex gap-3">
            @csrf @method('DELETE')
            <button type="button" data-micromodal-close
              class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-bold transition-all">Batal</button>
            <button type="submit"
              class="flex-1 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold shadow-lg shadow-red-200 dark:shadow-none transition-all">Ya,
              Hapus</button>
          </form>
        </div>
      </div>
    </div>
  @endforeach
@endsection