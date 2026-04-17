@extends('layouts.app')

@section('title', 'Master Lokasi • SIPRESPRO')

@section('content')
  <div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Master Lokasi GPS</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Atur titik koordinat dan radius aman presensi
          mahasiswa</p>
      </div>
      <button @click="MicroModal.show('modal-create-lokasi')"
        class="flex items-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold transition-all shadow-lg shadow-red-200 dark:shadow-none">
        <i class="fas fa-map-location-dot"></i>
        <span>Tambah Titik</span>
      </button>
    </div>

    <div
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm transition-colors duration-300">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
            <tr>
              <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Nama
                Lokasi</th>
              <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                Koordinat (Lat, Long)</th>
              <th
                class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center">
                Radius Aman</th>
              <th
                class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
            @forelse($lokasis as $lokasi)
              <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-all group">
                <td class="px-6 py-4 text-left">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-900/40 flex items-center justify-center text-red-600 dark:text-red-400 font-bold border border-red-200 dark:border-red-800">
                      <i class="fas fa-location-crosshairs"></i>
                    </div>
                    <div>
                      <p class="font-bold text-slate-800 dark:text-slate-100">{{ $lokasi->nama }}</p>
                      <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">ID:
                        LOK-{{ str_pad($lokasi->id, 3, '0', STR_PAD_LEFT) }}</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex flex-col gap-1">
                    <div class="flex items-center gap-2 group/coord">
                      <code
                        class="text-[11px] font-mono text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-900 px-2 py-0.5 rounded border dark:border-slate-700">
                        {{ $lokasi->latitude }}, {{ $lokasi->longitude }}
                      </code>
                      <button onclick="navigator.clipboard.writeText('{{ $lokasi->latitude }},{{ $lokasi->longitude }}')"
                        class="opacity-0 group-hover/coord:opacity-100 text-slate-400 hover:text-primary-500 transition-all">
                        <i class="fas fa-copy text-[10px]"></i>
                      </button>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 text-center">
                  <span
                    class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[10px] font-black border border-blue-100 dark:border-blue-800 uppercase tracking-widest">
                    {{ $lokasi->radius_meter }} Meter
                  </span>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex justify-end gap-2">
                    <a href="https://www.google.com/maps?q={{ $lokasi->latitude }},{{ $lokasi->longitude }}" target="_blank"
                      class="w-9 h-9 flex items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-600 hover:text-white transition-all border border-emerald-100 dark:border-emerald-800">
                      <i class="fas fa-map text-xs"></i>
                    </a>
                    <button @click="MicroModal.show('modal-edit-{{ $lokasi->id }}')"
                      class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white transition-all border border-blue-100 dark:border-blue-800">
                      <i class="fas fa-edit text-xs"></i>
                    </button>
                    <button @click="MicroModal.show('modal-delete-{{ $lokasi->id }}')"
                      class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white transition-all border border-red-100 dark:border-red-800">
                      <i class="fas fa-trash text-xs"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-6 py-20 text-center">
                  <i class="fas fa-map-location text-slate-300 dark:text-slate-600 text-5xl mb-4 block text-center"></i>
                  <p class="text-slate-400 italic">Data lokasi belum diatur jancok!</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="modal" id="modal-create-lokasi" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
      <div
        class="modal__container w-full max-w-lg bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 shadow-2xl border-none dark:border dark:border-slate-700"
        role="dialog" @click.stop>
        <header class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-700/50">
          <div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-white">Tambah Lokasi Baru</h2>
            <p class="text-[11px] text-red-500 uppercase font-black tracking-widest mt-1">Pinpoint GPS Coordinates</p>
          </div>
          <button
            class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
            data-micromodal-close>
            <i class="fas fa-times"></i>
          </button>
        </header>

        <form action="{{ route('admin.lokasi.store') }}" method="POST" class="space-y-5 text-left">
          @csrf
          <div>
            <label
              class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Nama
              Titik Lokasi</label>
            <input type="text" name="nama" required placeholder="Contoh: Gedung J - Lab Komputer"
              class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-red-500/50 outline-none transition-all">
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label
                class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Latitude</label>
              <input type="text" name="latitude" required placeholder="-8.16xxxxx"
                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-red-500/50 outline-none transition-all">
            </div>
            <div>
              <label
                class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Longitude</label>
              <input type="text" name="longitude" required placeholder="113.72xxxxx"
                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-red-500/50 outline-none transition-all">
            </div>
          </div>

          <div>
            <label
              class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Radius
              Jangkauan (Meter)</label>
            <div class="flex items-center gap-4">
              <input type="number" name="radius_meter" required placeholder="50" min="5"
                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-red-500/50 outline-none transition-all">
              <span class="text-xs font-bold text-slate-400">METERS</span>
            </div>
            <p class="mt-2 text-[10px] text-slate-400 italic leading-relaxed">*Radius di bawah 15 meter mungkin sulit
              dideteksi GPS HP standar.</p>
          </div>

          <div class="mt-8 flex gap-3">
            <button type="button" data-micromodal-close
              class="flex-1 py-3 bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 rounded-xl font-bold transition-all hover:bg-slate-200 dark:hover:bg-slate-700">Batal</button>
            <button type="submit"
              class="flex-1 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold shadow-lg shadow-red-200 dark:shadow-none transition-all active:scale-95">
              Simpan Titik GPS
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @foreach($lokasis as $lokasi)
    <div class="modal" id="modal-edit-{{ $lokasi->id }}" aria-hidden="true">
      <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div
          class="modal__container w-full max-w-lg bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 shadow-2xl border-none dark:border dark:border-slate-700"
          role="dialog" @click.stop>

          <header class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-700/50">
            <div>
              <h2 class="text-xl font-bold text-slate-800 dark:text-white">Edit Titik Lokasi</h2>
              <p class="text-[10px] text-blue-500 dark:text-blue-400 mt-0.5 uppercase tracking-widest font-bold font-mono">
                ID LOKASI: #LOK-{{ str_pad($lokasi->id, 3, '0', STR_PAD_LEFT) }}</p>
            </div>
            <button
              class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition-all"
              data-micromodal-close>
              <i class="fas fa-times"></i>
            </button>
          </header>

          <form action="{{ route('admin.lokasi.update', $lokasi->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-5 text-left">
              <div>
                <label
                  class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Nama
                  Titik Lokasi</label>
                <input type="text" name="nama" value="{{ $lokasi->nama }}" required
                  class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500/50 outline-none transition-all">
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label
                    class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Latitude</label>
                  <input type="text" name="latitude" value="{{ $lokasi->latitude }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500/50 outline-none transition-all font-mono">
                </div>
                <div>
                  <label
                    class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Longitude</label>
                  <input type="text" name="longitude" value="{{ $lokasi->longitude }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500/50 outline-none transition-all font-mono">
                </div>
              </div>

              <div>
                <label
                  class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Radius
                  Jangkauan (Meter)</label>
                <input type="number" name="radius_meter" value="{{ $lokasi->radius_meter }}" required min="5"
                  class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500/50 outline-none transition-all">
              </div>
            </div>

            <div class="mt-8 flex gap-3">
              <button type="button" data-micromodal-close
                class="flex-1 py-3 bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 rounded-xl font-bold transition-all hover:bg-slate-200 dark:hover:bg-slate-700">Batal</button>
              <button type="submit"
                class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-200 dark:shadow-none transition-all">Update
                Lokasi</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal" id="modal-delete-{{ $lokasi->id }}" aria-hidden="true">
      <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div
          class="modal__container w-full max-w-sm text-center bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 shadow-2xl border-none dark:border dark:border-slate-700"
          role="dialog" @click.stop>
          <div
            class="w-20 h-20 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-red-50 dark:border-red-900/10">
            <i class="fas fa-map-location-dot text-3xl"></i>
          </div>
          <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Hapus Lokasi?</h2>
          <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 leading-relaxed">
            Menghapus <b>{{ $lokasi->nama }}</b> akan memutus relasi pada jadwal kuliah yang menggunakan titik ini jancok!
          </p>
          <form action="{{ route('admin.lokasi.destroy', $lokasi->id) }}" method="POST" class="flex gap-3">
            @csrf
            @method('DELETE')
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