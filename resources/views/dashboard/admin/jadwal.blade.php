@extends('layouts.app')

@section('title', 'Jadwal Kuliah • SIPRESPRO')

@section('content')
  <div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Jadwal Perkuliahan</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Atur waktu dan titik lokasi presensi mahasiswa
        </p>
      </div>
      <button @click="MicroModal.show('modal-create-jadwal')"
        class="flex items-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold transition-all shadow-lg shadow-primary-200 dark:shadow-none">
        <i class="fas fa-calendar-plus"></i>
        <span>Tambah Jadwal</span>
      </button>
    </div>

    <div
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm transition-colors">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
            <tr>
              <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Mata
                Kuliah & Kelas</th>
              <th
                class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center">
                Hari & Waktu</th>
              <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Titik
                Lokasi</th>
              <th
                class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
            @forelse($jadwals as $jadwal)
              <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-all group">
                <td class="px-6 py-4">
                  <div class="flex items-start gap-3">
                    <div
                      class="w-10 h-10 shrink-0 rounded-xl bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center text-amber-600 dark:text-amber-400 font-bold border border-amber-200 dark:border-amber-800">
                      <i class="fas fa-book"></i>
                    </div>
                    <div>
                      <p class="font-bold text-slate-800 dark:text-slate-100 leading-tight">
                        {{ $jadwal->kelasPerkuliahan->mataKuliah->nama }}</p>
                      <div class="flex items-center gap-2 mt-1">
                        <span
                          class="text-[10px] text-slate-400 font-mono font-bold uppercase">{{ $jadwal->kelasPerkuliahan->mataKuliah->kode_mk }}</span>
                        <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                        <span class="text-[10px] font-bold text-primary-600 uppercase">Kelas
                          {{ $jadwal->kelasPerkuliahan->nama_kelas }}</span>
                      </div>
                    </div>
                  </div>
                </td>

                <td class="px-6 py-4 text-center">
                  <div class="inline-flex flex-col items-center">
                    <span class="text-xs font-black uppercase text-slate-700 dark:text-slate-200">{{ $jadwal->hari }}</span>
                    <div
                      class="mt-1 flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700">
                      <i class="fas fa-clock text-[10px] text-primary-500"></i>
                      <span class="text-[10px] font-bold text-slate-600 dark:text-slate-300">
                        {{ date('H:i', strtotime($jadwal->jam_mulai)) }} -
                        {{ date('H:i', strtotime($jadwal->jam_selesai)) }}
                      </span>
                    </div>
                  </div>
                </td>

                <td class="px-6 py-4">
                  <div class="flex items-center gap-2">
                    <i class="fas fa-location-dot text-red-500 text-xs"></i>
                    <div class="flex flex-col">
                      <span class="text-xs font-bold text-slate-700 dark:text-slate-200">{{ $jadwal->lokasi->nama }}</span>
                      <span class="text-[9px] text-slate-400 italic">Radius: {{ $jadwal->lokasi->radius_meter }}m</span>
                    </div>
                  </div>
                </td>

                <td class="px-6 py-4 text-right">
                  <div class="flex justify-end gap-2">
                    <button @click="MicroModal.show('modal-edit-{{ $jadwal->id }}')"
                      class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 border border-blue-100 dark:border-blue-800 transition-all"><i
                        class="fas fa-edit text-xs"></i></button>
                    <button @click="MicroModal.show('modal-delete-{{ $jadwal->id }}')"
                      class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 dark:bg-red-900/30 text-red-600 border border-red-100 dark:border-red-800 transition-all"><i
                        class="fas fa-trash text-xs"></i></button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-6 py-20 text-center text-slate-400 italic font-medium">Belum ada jadwal kuliah
                  jancok!</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="modal" id="modal-create-jadwal" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
      <div class="modal__container w-full max-w-lg bg-white dark:bg-slate-800 rounded-4xl p-8" role="dialog" @click.stop>
        <header class="flex justify-between items-center mb-6 pb-4 border-b dark:border-slate-700/50">
          <div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-white">Tambah Jadwal</h2>
            <p class="text-[11px] text-primary-600 uppercase font-bold mt-1">Plotting waktu kuliah</p>
          </div>
          <button class="text-slate-400 hover:text-slate-600" data-micromodal-close><i
              class="fas fa-times text-lg"></i></button>
        </header>

        <form action="{{ route('admin.jadwal.store') }}" method="POST" class="space-y-4 text-left">
          @csrf
          <div>
            <label
              class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Pilih
              Kelas</label>
            <select name="kelas_perkuliahan_id" required
              class="w-full px-4 py-3 rounded-xl border dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary-500/50 outline-none transition-all">
              @foreach($kelases as $k)
                <option value="{{ $k->id }}">[{{ $k->mataKuliah->kode_mk }}] {{ $k->mataKuliah->nama }} - Kelas
                  {{ $k->nama_kelas }}</option>
              @endforeach
            </select>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label
                class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Hari</label>
              <select name="hari" required
                class="w-full px-4 py-3 rounded-xl border dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 outline-none">
                @foreach($hari_list as $h) <option value="{{ $h }}">{{ strtoupper($h) }}</option> @endforeach
              </select>
            </div>
            <div>
              <label
                class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Titik
                Lokasi</label>
              <select name="lokasi_id" required
                class="w-full px-4 py-3 rounded-xl border dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 outline-none">
                @foreach($lokasis as $l) <option value="{{ $l->id }}">{{ $l->nama }}</option> @endforeach
              </select>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label
                class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Jam
                Mulai</label>
              <input type="time" name="jam_mulai" required
                class="w-full px-4 py-3 rounded-xl border dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 outline-none">
            </div>
            <div>
              <label
                class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Jam
                Selesai</label>
              <input type="time" name="jam_selesai" required
                class="w-full px-4 py-3 rounded-xl border dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 outline-none">
            </div>
          </div>

          <div class="mt-8 flex gap-3">
            <button type="button" data-micromodal-close
              class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-bold transition-all">Batal</button>
            <button type="submit"
              class="flex-1 py-3 bg-primary-600 text-white rounded-xl font-bold shadow-lg shadow-primary-200">Simpan
              Jadwal</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @foreach($jadwals as $jadwal)
    <div class="modal" id="modal-edit-{{ $jadwal->id }}" aria-hidden="true">
      <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div
          class="modal__container w-full max-w-lg bg-white dark:bg-slate-800 rounded-4xl p-8 shadow-2xl border-none dark:border dark:border-slate-700"
          role="dialog" @click.stop>

          <header class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-700/50">
            <div>
              <h2 class="text-xl font-bold text-slate-800 dark:text-white">Edit Jadwal Kuliah</h2>
              <p class="text-[10px] text-blue-500 dark:text-blue-400 mt-0.5 uppercase tracking-widest font-bold font-mono">
                ID JADWAL: #JD-{{ $jadwal->id }}</p>
            </div>
            <button
              class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition-all"
              data-micromodal-close>
              <i class="fas fa-times"></i>
            </button>
          </header>

          <form action="{{ route('admin.jadwal.update', $jadwal->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4 text-left">
              <div>
                <label
                  class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Kelas
                  Perkuliahan</label>
                <div class="relative">
                  <select name="kelas_perkuliahan_id" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 appearance-none focus:ring-2 focus:ring-blue-500/50 outline-none transition-all">
                    @foreach($kelases as $k)
                      <option value="{{ $k->id }}" {{ $jadwal->kelas_perkuliahan_id == $k->id ? 'selected' : '' }}>
                        [{{ $k->mataKuliah->kode_mk }}] {{ $k->mataKuliah->nama }} - Kelas {{ $k->nama_kelas }}
                      </option>
                    @endforeach
                  </select>
                  <i
                    class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none"></i>
                </div>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label
                    class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Hari</label>
                  <div class="relative">
                    <select name="hari" required
                      class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 appearance-none focus:ring-2 focus:ring-blue-500/50 outline-none transition-all uppercase font-bold text-xs">
                      @foreach($hari_list as $h)
                        <option value="{{ $h }}" {{ $jadwal->hari == $h ? 'selected' : '' }}>{{ strtoupper($h) }}</option>
                      @endforeach
                    </select>
                    <i
                      class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none"></i>
                  </div>
                </div>
                <div>
                  <label
                    class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Titik
                    Lokasi</label>
                  <div class="relative">
                    <select name="lokasi_id" required
                      class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 appearance-none focus:ring-2 focus:ring-blue-500/50 outline-none transition-all">
                      @foreach($lokasis as $l)
                        <option value="{{ $l->id }}" {{ $jadwal->lokasi_id == $l->id ? 'selected' : '' }}>{{ $l->nama }}
                        </option>
                      @endforeach
                    </select>
                    <i
                      class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none"></i>
                  </div>
                </div>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label
                    class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Jam
                    Mulai</label>
                  <input type="time" name="jam_mulai" value="{{ date('H:i', strtotime($jadwal->jam_mulai)) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500/50 outline-none transition-all">
                </div>
                <div>
                  <label
                    class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Jam
                    Selesai</label>
                  <input type="time" name="jam_selesai" value="{{ date('H:i', strtotime($jadwal->jam_selesai)) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500/50 outline-none transition-all">
                </div>
              </div>
            </div>

            <div class="mt-8 flex gap-3">
              <button type="button" data-micromodal-close
                class="flex-1 py-3 bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 rounded-xl font-bold transition-all">Batal</button>
              <button type="submit"
                class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-200 dark:shadow-none transition-all">Update
                Jadwal</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal" id="modal-delete-{{ $jadwal->id }}" aria-hidden="true">
      <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div
          class="modal__container w-full max-w-sm text-center bg-white dark:bg-slate-800 rounded-4xl p-8 shadow-2xl border-none dark:border dark:border-slate-700"
          role="dialog" @click.stop>
          <div
            class="w-20 h-20 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-red-50 dark:border-red-900/10">
            <i class="fas fa-calendar-xmark text-3xl"></i>
          </div>
          <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-2 tracking-tight">Hapus Jadwal?</h2>
          <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 leading-relaxed">
            Menghapus jadwal hari <b>{{ strtoupper($jadwal->hari) }}</b> ini akan berpengaruh pada sistem presensi pertemuan
            jancok!
          </p>
          <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST" class="flex gap-3">
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