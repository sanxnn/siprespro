@extends('layouts.app')

@section('title', 'Data Mata Kuliah • SIPRESPRO')

@section('content')
  <div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      {{-- Info Semester Aktif (Makan 2 Kolom) --}}
      <div
        class="md:col-span-2 relative overflow-hidden flex flex-col sm:flex-row sm:items-center justify-between p-6 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl shadow-sm transition-all group">
        {{-- Background Decoration --}}
        <div
          class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-50 dark:bg-indigo-900/10 rounded-full blur-3xl group-hover:bg-indigo-100 transition-colors">
        </div>

        <div class="flex items-center gap-4 relative z-10">
          <div
            class="w-14 h-14 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-xl shadow-indigo-200 dark:shadow-none transform group-hover:scale-105 transition-transform">
            <i class="fas fa-graduation-cap text-xl"></i>
          </div>
          <div>
            <h4 class="text-lg font-bold text-slate-800 dark:text-white leading-tight">
              Kurikulum {{ $semesterAktif->nama ?? 'Sistem' }}
            </h4>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 max-w-[300px]">
              Menampilkan daftar mata kuliah yang sedang berjalan di semester aktif.
            </p>
          </div>
        </div>

        <div class="mt-4 sm:mt-0 relative z-10">
          @if(request('semester_id'))
            <a href="{{ route('admin.mata-kuliah.index') }}"
              class="flex items-center gap-2 px-5 py-2.5 bg-slate-100 dark:bg-slate-700 hover:bg-indigo-600 hover:text-white text-slate-600 dark:text-slate-200 rounded-xl font-bold text-xs transition-all">
              <i class="fas fa-undo-alt"></i>
              Reset Filter
            </a>
          @endif
        </div>
      </div>

      {{-- Stats Ringkas --}}
      <div
        class="flex flex-col justify-center p-6 bg-indigo-600 rounded-3xl shadow-xl shadow-indigo-100 dark:shadow-none text-white relative overflow-hidden">
        <i class="fas fa-book-open absolute -right-4 -bottom-4 text-8xl opacity-10 rotate-12"></i>
        <p class="text-indigo-100 text-xs font-bold uppercase tracking-widest mb-1">Total Matakuliah</p>
        <div class="flex items-baseline gap-2">
          <h2 class="text-4xl font-black">{{ $matkuls->total() }}</h2>
          <span class="text-xs font-medium text-indigo-100">MK Terdaftar</span>
        </div>
        <div class="mt-4 pt-4 border-t border-indigo-500/50 flex justify-between items-center">
          <span class="text-[10px] font-bold text-indigo-200 uppercase">Status Semester</span>
          <span class="px-2 py-0.5 rounded-md bg-emerald-400 text-[10px] text-emerald-950 font-black">ACTIVE</span>
        </div>
      </div>
    </div>

    {{-- Filter Row --}}
    <div class="flex flex-col md:flex-row gap-4 mb-6">
      <form action="{{ route('admin.mata-kuliah.index') }}" method="GET" class="flex-1 flex gap-2">
        <div class="relative flex-1">
          <i class="fas fa-filter absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
          <select name="semester_id" onchange="this.form.submit()"
            class="w-full pl-10 pr-4 py-3 text-sm rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none appearance-none">
            <option value="">-- Pilih Semester Lain --</option>
            @foreach($semesters as $s)
              <option value="{{ $s->id }}" {{ request('semester_id') == $s->id ? 'selected' : '' }}>
                Semester {{ $s->nama }} {{ $s->status == 'aktif' ? '(Aktif)' : '' }}
              </option>
            @endforeach
          </select>
        </div>
      </form>
    </div>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Daftar Mata Kuliah</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Manajemen kurikulum dan beban SKS per semester
        </p>
      </div>
      <button @click="MicroModal.show('modal-create-matkul')"
        class="flex items-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold transition-all shadow-lg shadow-primary-200 dark:shadow-none">
        <i class="fas fa-plus-circle"></i>
        <span>Tambah Matkul</span>
      </button>
    </div>

    <div
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm transition-colors duration-300">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
            <tr>
              <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Kode &
                Nama Matkul</th>
              <th
                class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center">
                SKS</th>
              <th
                class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center">
                Semester</th>
              <th
                class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
            @forelse($matkuls as $matkul)
              <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-all group">
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center text-amber-600 dark:text-amber-400 font-bold border border-amber-200 dark:border-amber-800">
                      <i class="fas fa-book"></i>
                    </div>
                    <div>
                      <p class="font-bold text-slate-800 dark:text-slate-100">{{ $matkul->nama }}</p>
                      <p class="text-[10px] text-slate-400 font-mono tracking-wider font-bold uppercase">
                        {{ $matkul->kode_mk }}
                      </p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 text-center">
                  <span
                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 font-bold text-xs border border-slate-200 dark:border-slate-700">
                    {{ $matkul->sks }}
                  </span>
                </td>
                <td class="px-6 py-4 text-center">
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-[10px] font-bold uppercase border border-indigo-100 dark:border-indigo-800">
                    {{ $matkul->semester->nama ?? '-' }}
                  </span>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex justify-end gap-2">
                    <button @click="MicroModal.show('modal-edit-{{ $matkul->id }}')"
                      class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-500 transition-all duration-300 shadow-sm border border-blue-100 dark:border-blue-800">
                      <i class="fas fa-edit text-xs"></i>
                    </button>
                    <button @click="MicroModal.show('modal-delete-{{ $matkul->id }}')"
                      class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white dark:hover:bg-red-500 transition-all duration-300 shadow-sm border border-red-100 dark:border-red-800">
                      <i class="fas fa-trash text-xs"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-6 py-20 text-center">
                  <div class="flex flex-col items-center justify-center">
                    <div class="relative mb-4">
                      <i class="fas fa-book-open text-slate-200 dark:text-slate-700 text-6xl"></i>
                      <i class="fas fa-search absolute bottom-0 right-0 text-indigo-500 text-2xl animate-bounce"></i>
                    </div>
                    <h3 class="text-slate-800 dark:text-slate-100 font-bold">Tidak Ada Mata Kuliah</h3>
                    <p class="text-slate-400 text-sm max-w-xs mx-auto italic">
                      Belum ada mata kuliah yang terdaftar untuk <span
                        class="text-indigo-500 font-bold">{{ $semesterAktif->nama ?? 'semester ini' }}</span>.
                    </p>
                    <button @click="MicroModal.show('modal-create-matkul')"
                      class="mt-4 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                      <i class="fas fa-plus mr-1"></i> Tambah Matkul Sekarang
                    </button>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($matkuls->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
          {{ $matkuls->links() }}
        </div>
      @endif
    </div>
  </div>

  <div class="modal" id="modal-create-matkul" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
      <div
        class="modal__container w-full max-w-md bg-white dark:bg-slate-800 border-none dark:border dark:border-slate-700 shadow-2xl rounded-4xl"
        role="dialog" @click.stop>
        <header class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-700/50">
          <div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-white">Tambah Mata Kuliah</h2>
            <p class="text-[11px] text-primary-600 dark:text-primary-400 mt-1 uppercase tracking-wider font-bold">Input
              Data Kurikulum Baru</p>
          </div>
          <button
            class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
            data-micromodal-close>
            <i class="fas fa-times"></i>
          </button>
        </header>

        <form action="{{ route('admin.mata-kuliah.store') }}" method="POST">
          @csrf
          <div class="space-y-5">
            <div>
              <label
                class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Kode
                Matkul <span class="text-red-500">*</span></label>
              <input type="text" name="kode_mk" required placeholder="Contoh: TIF101"
                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary-500/50 outline-none transition-all">
            </div>
            <div>
              <label
                class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Nama
                Matkul <span class="text-red-500">*</span></label>
              <input type="text" name="nama" required placeholder="Nama Lengkap Mata Kuliah"
                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary-500/50 outline-none transition-all">
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label
                  class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Beban
                  SKS</label>
                <input type="number" name="sks" required min="1" max="6" placeholder="2"
                  class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary-500/50 outline-none text-center transition-all">
              </div>
              <div>
                <label
                  class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Semester</label>
                <div class="relative">
                  <select name="semester_id" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary-500/50 outline-none appearance-none transition-all">
                    <option value="" disabled selected>Pilih...</option>
                    @foreach($semesters as $sem)
                      <option value="{{ $sem->id }}">{{ $sem->nama }}</option>
                    @endforeach
                  </select>
                  <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                    <i class="fas fa-chevron-down text-[10px]"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="mt-8 flex gap-3">
            <button type="button" data-micromodal-close
              class="flex-1 py-3 bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 rounded-xl font-bold transition-all hover:bg-slate-200 dark:hover:bg-slate-700">Batal</button>
            <button type="submit"
              class="flex-1 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold shadow-lg shadow-primary-200 dark:shadow-none transition-all">Simpan
              Data</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @foreach($matkuls as $matkul)
    <div class="modal" id="modal-edit-{{ $matkul->id }}" aria-hidden="true">
      <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div
          class="modal__container w-full max-w-md bg-white dark:bg-slate-800 border-none dark:border dark:border-slate-700 shadow-2xl rounded-4xl"
          role="dialog" @click.stop>
          <header class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-700/50">
            <div>
              <h2 class="text-xl font-bold text-slate-800 dark:text-white">Edit Mata Kuliah</h2>
              <p class="text-[10px] text-blue-500 dark:text-blue-400 mt-0.5 uppercase tracking-widest font-bold font-mono">
                {{ $matkul->kode_mk }}
              </p>
            </div>
            <button
              class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
              data-micromodal-close>
              <i class="fas fa-times"></i>
            </button>
          </header>

          <form action="{{ route('admin.mata-kuliah.update', $matkul->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="space-y-5">
              <div>
                <label
                  class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Kode
                  Matkul</label>
                <input type="text" name="kode_mk" value="{{ $matkul->kode_mk }}" required
                  class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500/50 outline-none transition-all">
              </div>
              <div>
                <label
                  class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Nama
                  Matkul</label>
                <input type="text" name="nama" value="{{ $matkul->nama }}" required
                  class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500/50 outline-none transition-all">
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label
                    class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">SKS</label>
                  <input type="number" name="sks" value="{{ $matkul->sks }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500/50 outline-none text-center transition-all">
                </div>
                <div>
                  <label
                    class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Semester</label>
                  <div class="relative">
                    <select name="semester_id" required
                      class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500/50 outline-none appearance-none transition-all">
                      @foreach($semesters as $sem)
                        <option value="{{ $sem->id }}" {{ $matkul->semester_id == $sem->id ? 'selected' : '' }}>{{ $sem->nama }}
                        </option>
                      @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                      <i class="fas fa-chevron-down text-[10px]"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="mt-8 flex gap-3">
              <button type="button" data-micromodal-close
                class="flex-1 py-3 bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 rounded-xl font-bold transition-all">Batal</button>
              <button type="submit"
                class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-200 dark:shadow-none transition-all">Update
                Data</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal" id="modal-delete-{{ $matkul->id }}" aria-hidden="true">
      <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container w-full max-w-sm text-center bg-white dark:bg-slate-800 rounded-4xl p-8" role="dialog"
          @click.stop>
          <div
            class="w-20 h-20 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-red-50 dark:border-red-900/10">
            <i class="fas fa-trash-alt text-3xl"></i>
          </div>
          <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Hapus Matkul?</h2>
          <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 leading-relaxed">
            Matkul <b>{{ $matkul->nama }}</b> akan dihapus permanen. Aksi ini tidak bisa dibatalkan!
          </p>
          <form action="{{ route('admin.mata-kuliah.destroy', $matkul->id) }}" method="POST" class="flex gap-3">
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