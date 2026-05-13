@extends('layouts.app')

@section('content')
  <div class="space-y-6">
    <!-- Header Section (Tetap Sama) -->
    <div
      class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm relative overflow-hidden">
      <div class="absolute -right-10 -top-10 w-40 h-40 bg-primary-500/5 rounded-full blur-3xl"></div>
      <div class="relative flex flex-col md:flex-row justify-between items-start gap-6">
        <div class="space-y-1">
          <span
            class="inline-flex items-center px-3 py-1 rounded-full bg-primary-50 dark:bg-primary-900/30 text-primary-600 text-[10px] font-black uppercase tracking-widest border border-primary-100 dark:border-primary-800">
            {{ $kelas->mataKuliah->kode_mk }}
          </span>
          <h1 class="text-3xl font-black text-slate-800 dark:text-white leading-tight">
            {{ $kelas->mataKuliah->nama }}
          </h1>
          <p class="text-sm text-slate-500 font-bold flex items-center gap-2">
            {{ $kelas->nama_kelas }} <span class="text-slate-300">•</span> {{ ucfirst($kelas->tipe_kelas) }}
          </p>
        </div>
        <div class="flex flex-wrap gap-2">
          @foreach($kelas->golongans as $gol)
            <span
              class="px-4 py-2 bg-slate-100 dark:bg-slate-700 rounded-xl text-xs font-black text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600">
              {{ $gol->nama }}
            </span>
          @endforeach
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
      <!-- Kolom Kiri: Info Ruangan & Lokasi (Sekarang jadi Ringkasan) -->
      <div class="lg:col-span-4 space-y-6">
        <div
          class="bg-white dark:bg-slate-800 p-6 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm">
          <h2 class="font-black text-slate-800 dark:text-white uppercase text-xs tracking-widest italic mb-6">Informasi
            Kelas</h2>
          <div class="space-y-4">
            <div class="p-5 rounded-3xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700">
              <p class="text-[10px] font-black text-primary-600 uppercase tracking-widest mb-1">Ruangan Utama</p>
              <p class="text-xl font-black text-slate-800 dark:text-white">{{ $kelas->ruang->nama }}</p>
              <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">{{ $kelas->ruang->gedung }}</p>
            </div>

            {{-- Pertemuan Terdekat --}}
            @php $next = $kelas->pertemuans->where('tanggal', '>=', now()->format('Y-m-d'))->first(); @endphp
            @if($next)
              <div
                class="p-5 rounded-3xl bg-primary-50 dark:bg-primary-900/20 border border-primary-100 dark:border-primary-800">
                <p class="text-[10px] font-black text-primary-600 uppercase tracking-widest mb-1">Sesi Terdekat</p>
                <p class="text-lg font-black text-slate-800 dark:text-white">Pertemuan #{{ $next->pertemuan_ke }}</p>
                <p class="text-xs text-slate-500 font-bold">
                  {{ \Carbon\Carbon::parse($next->tanggal)->translatedFormat('l, d M Y') }}</p>
              </div>
            @endif
          </div>
        </div>
      </div>

      <!-- Kolom Kanan: Daftar Pertemuan (Main Logic) -->
      <div class="lg:col-span-8">
        <div
          class="bg-white dark:bg-slate-800 p-6 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm">
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
              <h2 class="font-black text-slate-800 dark:text-white uppercase text-xs tracking-widest italic mb-1">
                Manajemen Sesi Pertemuan</h2>
              <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Total Sesi:
                {{ $kelas->pertemuans->count() }}</p>
            </div>
            <button @click="MicroModal.show('modal-add-pertemuan')"
              class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl text-xs font-black shadow-xl shadow-primary-500/20 transition-all transform hover:-translate-y-1">
              <i class="fas fa-plus-circle mr-2"></i>BUAT SESI BARU
            </button>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-y-3">
              <thead>
                <tr>
                  <th class="px-6 text-[10px] font-black uppercase text-slate-400 tracking-widest">Ke</th>
                  <th class="px-6 text-[10px] font-black uppercase text-slate-400 tracking-widest">Waktu & Lokasi</th>
                  <th class="px-6 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Status Sesi
                  </th>
                  <th class="px-6 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($kelas->pertemuans->sortBy('pertemuan_ke') as $p)
                  <tr
                    class="bg-slate-50 dark:bg-slate-900/50 hover:bg-white dark:hover:bg-slate-800 border border-slate-100 dark:border-slate-700 transition-all shadow-sm">
                    <td class="px-6 py-4 font-black text-slate-400 rounded-l-2xl">#{{ $p->pertemuan_ke }}</td>
                    <td class="px-6 py-4">
                      <p class="font-black text-slate-800 dark:text-white leading-tight">{{ $p->materi }}</p>
                      <div class="flex flex-col gap-0.5 mt-1">
                        <p class="text-[10px] text-primary-600 font-bold uppercase italic">
                          {{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('l, d F Y') }}</p>
                        <p class="text-[9px] text-slate-400 font-bold italic uppercase">
                          <i class="fas fa-clock mr-1"></i>{{ substr($p->jam_mulai, 0, 5) }} -
                          {{ substr($p->jam_selesai, 0, 5) }}
                          <span class="mx-1 text-slate-300">|</span>
                          <i class="fas fa-location-dot mr-1 text-primary-500"></i>{{ $p->lokasi->nama }}
                        </p>
                      </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                      {{-- Gunakan accessor status_absensi dari model --}}
                      @php $status = $p->status_absensi; @endphp
                      <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black uppercase 
                                                {{ $status == 'Aktif' ? 'bg-emerald-100 text-emerald-600 animate-pulse' : 'bg-slate-200 text-slate-500' }}">
                        @if($status == 'Aktif') <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> @endif
                        {{ $status }}
                      </span>
                    </td>
                    <td class="px-6 py-4 text-right rounded-r-2xl">
                      <div class="flex justify-end gap-2">
                        <form action="{{ route('dosen.pertemuan.toggle', $p->id) }}" method="POST">
                          @csrf @method('PATCH')
                          <button type="submit"
                            class="w-9 h-9 flex items-center justify-center rounded-xl {{ $p->status == 'dibuka' ? 'bg-red-50 text-red-600 border-red-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100' }} border hover:scale-110 transition-all shadow-sm">
                            <i class="fas {{ $p->status == 'dibuka' ? 'fa-stop-circle' : 'fa-play' }} text-sm"></i>
                          </button>
                        </form>
                        <a href="#"
                          class="w-9 h-9 flex items-center justify-center bg-white dark:bg-slate-800 text-slate-600 border border-slate-200 dark:border-slate-700 rounded-xl hover:text-primary-600 transition-all">
                          <i class="fas fa-users-viewfinder text-sm"></i>
                        </a>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- MODAL ADD PERTEMUAN (Struktur Baru) --}}
  <div class="modal" id="modal-add-pertemuan" aria-hidden="true">
    <div class="modal__overlay fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center"
      tabindex="-1" data-micromodal-close>
      <div class="modal__container w-full max-w-md bg-white dark:bg-slate-800 rounded-[2.5rem] p-10 shadow-2xl mx-4"
        role="dialog" @click.stop>
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Set Sesi Baru</h2>
          <button data-micromodal-close class="text-slate-400 hover:text-red-500"><i class="fas fa-times"></i></button>
        </div>
        <form action="{{ route('dosen.kelas.pertemuan.store', $kelas->id) }}" method="POST" class="space-y-4">
          @csrf
          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Sesi Ke-</label>
              <input type="number" name="pertemuan_ke" value="{{ $kelas->pertemuans->count() + 1 }}"
                class="w-full px-5 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-bold outline-none focus:border-primary-500 transition-all"
                required>
            </div>
            <div class="space-y-1">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal</label>
              <input type="date" name="tanggal" value="{{ date('Y-m-d') }}"
                class="w-full px-5 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-bold outline-none focus:border-primary-500 transition-all"
                required>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Jam Mulai</label>
              <input type="time" name="jam_mulai"
                class="w-full px-5 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-bold focus:border-primary-500 transition-all"
                required>
            </div>
            <div class="space-y-1">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Jam Selesai</label>
              <input type="time" name="jam_selesai"
                class="w-full px-5 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-bold focus:border-primary-500 transition-all"
                required>
            </div>
          </div>

          <div class="space-y-1">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Lokasi Presensi</label>
            <select name="lokasi_id"
              class="w-full px-5 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-bold focus:border-primary-500 transition-all">
              @foreach($lokasis as $lok)
                <option value="{{ $lok->id }}">{{ $lok->nama }} ({{ $lok->radius_meter }}m)</option>
              @endforeach
            </select>
          </div>

          <div class="space-y-1">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Materi Bahasan</label>
            <textarea name="materi" rows="2"
              class="w-full px-5 py-3 rounded-2xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-bold focus:border-primary-500 transition-all"
              placeholder="Input topik pertemuan..." required></textarea>
          </div>

          <button type="submit"
            class="w-full py-4 bg-primary-600 text-white rounded-2xl font-black shadow-xl shadow-primary-500/30 transform transition-all hover:-translate-y-1 mt-2">
            KONFIRMASI & SIMPAN SESI
          </button>
        </form>
      </div>
    </div>
  </div>
@endsection