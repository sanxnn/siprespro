@extends('layouts.app')

@section('title', 'Data Dosen • SIPRESPRO')

@section('content')
  <div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Database Dosen</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Total {{ $dosens->total() }} dosen pengampu terdaftar</p>
      </div>
      <div class="flex items-center gap-3">
        <button @click="MicroModal.show('modal-create-dosen')"
          class="flex items-center gap-2 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold transition-all shadow-lg shadow-primary-200 dark:shadow-none">
          <i class="fas fa-plus-circle"></i>
          <span>Tambah Dosen</span>
        </button>
      </div>
    </div>

    <div
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm transition-colors duration-300">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
            <tr>
              <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Profil Dosen</th>
              <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">NIP / NIDN</th>
              <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Jabatan</th>
              <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Kontak</th>
              <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
            @forelse($dosens as $dosen)
              <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-all group">
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold">
                      {{ substr($dosen->nama, 0, 1) }}
                    </div>
                    <div>
                      <p class="font-bold text-slate-800 dark:text-slate-100">{{ $dosen->nama }}</p>
                      <p class="text-[11px] text-slate-400 font-mono">{{ $dosen->user->email ?? '-' }}</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span
                    class="text-sm font-semibold text-slate-700 dark:text-slate-200 block">{{ $dosen->nip ?? '-' }}</span>
                  <span class="text-[10px] text-slate-400 block italic">NIDN: {{ $dosen->nidn ?? '-' }}</span>
                </td>
                <td class="px-6 py-4">
                  <span
                    class="inline-flex items-center px-2 py-0.5 rounded bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-[10px] font-bold uppercase mb-1 border border-indigo-100 dark:border-indigo-800">
                    {{ $dosen->jabatan ?? 'N/A' }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <p class="text-xs text-slate-600 dark:text-slate-300"><i
                      class="fas fa-phone text-[10px] mr-1 text-primary-500"></i> {{ $dosen->no_hp ?? '-' }}</p>
                  <p class="text-[10px] text-slate-400 truncate max-w-[150px]"><i
                      class="fas fa-map-marker-alt mr-1 text-red-400"></i> {{ $dosen->alamat ?? '-' }}</p>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex justify-end gap-2">
                    <button @click="MicroModal.show('modal-edit-{{ $dosen->id }}')"
                      class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-500 transition-all duration-300 shadow-sm border border-blue-100 dark:border-blue-800">
                      <i class="fas fa-edit text-xs"></i>
                    </button>
                    <button @click="MicroModal.show('modal-delete-{{ $dosen->id }}')"
                      class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white dark:hover:bg-red-500 transition-all duration-300 shadow-sm border border-red-100 dark:border-red-800">
                      <i class="fas fa-trash text-xs"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-6 py-20 text-center">
                  <p class="text-slate-400 italic">Data dosen belum tersedia.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="modal" id="modal-create-dosen" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
      <div class="modal__container w-full max-w-4xl" role="dialog" @click.stop>
        <header class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-700">
          <div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-white">Registrasi Dosen Baru</h2>
            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wider font-semibold">Username
              & Pass default adalah NIP</p>
          </div>
          <button
            class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700"
            data-micromodal-close>
            <i class="fas fa-times"></i>
          </button>
        </header>

        <form action="{{ route('admin.dosen.store') }}" method="POST">
          @csrf
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
              <h4 class="text-[10px] font-bold uppercase tracking-widest text-indigo-600 dark:text-indigo-400">Data
                Akademik</h4>
              <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">NIP <span
                    class="text-red-500">*</span></label>
                <input type="text" name="nip" required placeholder="Contoh: 19880312..."
                  class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 outline-none transition-all">
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">NIDN</label>
                <input type="text" name="nidn" placeholder="Nomor Induk Dosen Nasional"
                  class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 outline-none transition-all">
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nama Lengkap <span
                    class="text-red-500">*</span></label>
                <input type="text" name="nama" required placeholder="Nama Lengkap & Gelar"
                  class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 outline-none transition-all">
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Email Institusi <span
                    class="text-red-500">*</span></label>
                <input type="email" name="email" required placeholder="dosen@polije.ac.id"
                  class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 outline-none transition-all">
              </div>
            </div>

            <div class="space-y-4">
              <h4 class="text-[10px] font-bold uppercase tracking-widest text-indigo-600 dark:text-indigo-400">Informasi
                Pribadi</h4>
              <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Jabatan</label>
                <input type="text" name="jabatan" placeholder="Contoh: Lektor Kepala"
                  class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100">
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">NIK</label>
                <input type="text" name="nik" placeholder="16 Digit No. Kependudukan"
                  class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100">
              </div>
              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">WhatsApp</label>
                  <input type="text" name="no_hp" placeholder="08xxxxxxxxxx"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Tgl Lahir</label>
                  <input type="date" name="tanggal_lahir"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100">
                </div>
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Alamat Lengkap</label>
                <textarea name="alamat" rows="2" placeholder="Alamat domisili saat ini..."
                  class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 resize-none outline-none focus:ring-2 focus:ring-primary-500"></textarea>
              </div>
            </div>
          </div>

          <div class="mt-8 flex gap-3">
            <button type="button" @click="MicroModal.close('modal-create-dosen')"
              class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-bold transition-colors">Batal</button>
            <button type="submit"
              class="flex-1 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold shadow-lg shadow-primary-200 transition-all dark:shadow-lg dark:shadow-slate-900">Simpan
              & Buat Akun</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @foreach($dosens as $dosen)
    <div class="modal" id="modal-edit-{{ $dosen->id }}" aria-hidden="true">
      <div class="modal__overlay" tabindex="-1" @click="MicroModal.close('modal-edit-{{ $dosen->id }}')">
        <div class="modal__container w-full max-w-2xl" role="dialog" @click.stop>
          <header class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-700">
            <div>
              <h2 class="text-xl font-bold text-slate-800 dark:text-white">Edit Profil Dosen</h2>
              <p class="text-[10px] text-slate-400 mt-0.5 uppercase tracking-widest font-bold">NIP: {{ $dosen->nip }}</p>
            </div>
            <button
              class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
              @click="MicroModal.close('modal-edit-{{ $dosen->id }}')">
              <i class="fas fa-times"></i>
            </button>
          </header>

          <form action="{{ route('admin.dosen.update', $dosen->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">

              <div class="space-y-4">
                <h4 class="text-[10px] font-bold uppercase tracking-widest text-blue-600 dark:text-blue-400">Data Akademik
                </h4>
                <div>
                  <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nama Lengkap</label>
                  <input type="text" name="nama" value="{{ $dosen->nama }}" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">NIDN</label>
                  <input type="text" name="nidn" value="{{ $dosen->nidn }}"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Email Akun</label>
                  <input type="email" name="email" value="{{ $dosen->user->email ?? '' }}" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Jabatan</label>
                  <input type="text" name="jabatan" value="{{ $dosen->jabatan }}"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
              </div>

              <div class="space-y-4">
                <h4 class="text-[10px] font-bold uppercase tracking-widest text-blue-600 dark:text-blue-400">Data Personal
                </h4>
                <div>
                  <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">NIK</label>
                  <input type="text" name="nik" value="{{ $dosen->nik }}"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">No. WhatsApp</label>
                  <input type="text" name="no_hp" value="{{ $dosen->no_hp }}"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Tanggal Lahir</label>
                  <input type="date" name="tanggal_lahir" value="{{ $dosen->tanggal_lahir }}"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Alamat</label>
                  <textarea name="alamat" rows="2"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 resize-none outline-none focus:ring-2 focus:ring-blue-500 transition-all">{{ $dosen->alamat }}</textarea>
                </div>
              </div>
            </div>

            <div class="mt-8 flex gap-3">
              <button type="button" @click="MicroModal.close('modal-edit-{{ $dosen->id }}')"
                class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-bold transition-all hover:bg-slate-200 dark:hover:bg-slate-600">Batal</button>
              <button type="submit"
                class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-200 dark:shadow-lg dark:shadow-slate-900 transition-all">Update
                Data Dosen</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal" id="modal-delete-{{ $dosen->id }}" aria-hidden="true">
      <div class="modal__overlay" tabindex="-1" @click="MicroModal.close('modal-delete-{{ $dosen->id }}')">
        <div class="modal__container w-full max-w-sm text-center" role="dialog" @click.stop>
          <div
            class="w-20 h-20 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center mx-auto mb-5 border-4 border-red-50 dark:border-red-900/20">
            <i class="fas fa-trash-alt text-3xl"></i>
          </div>
          <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Hapus Dosen?</h2>
          <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 px-4 leading-relaxed">
            Menghapus profil <b>{{ $dosen->nama }}</b> juga akan menghapus kredensial login.
          </p>
          <form action="{{ route('admin.dosen.destroy', $dosen->id) }}" method="POST" class="flex gap-3 px-2">
            @csrf @method('DELETE')
            <button type="button" @click="MicroModal.close('modal-delete-{{ $dosen->id }}')"
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

    input:focus,
    select:focus,
    textarea:focus {
      border-color: #10b981 !important;
      box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1) !important;
    }
  </style>
@endpush