@extends('layouts.app')
@section('title', 'Detail Kelas & Sesi • SIPRESPRO')
@section('content')
  <div class="space-y-6 mx-auto p-2 sm:p-4 text-left">
    <div
      class="bg-white dark:bg-slate-800 p-5 sm:p-8 rounded-4xl sm:rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm relative overflow-hidden">
      <div class="absolute -right-10 -top-10 w-40 h-40 bg-primary-500/5 rounded-full blur-3xl"></div>
      <div class="relative flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-4 sm:gap-5">
          <div
            class="w-12 h-12 sm:w-16 sm:h-16 bg-primary-600 text-white rounded-2xl sm:rounded-3xl flex items-center justify-center shadow-xl shadow-primary-500/20 font-black text-xl sm:text-2xl shrink-0">
            {{ $kelas->pertemuans->count() }}
          </div>
          <div class="min-w-0">
            <h1 class="text-lg sm:text-2xl font-black text-slate-800 dark:text-white uppercase leading-tight truncate">
              Manajemen Kelas & Sesi
            </h1>
            <p
              class="text-[10px] sm:text-sm text-slate-500 font-bold flex flex-wrap items-center gap-1 sm:gap-2 uppercase tracking-widest mt-0.5 sm:mt-1">
              <span class="truncate max-w-[150px] sm:max-w-none">{{ $kelas->mataKuliah->nama }}</span>
              <span class="text-slate-300">•</span>
              <span>{{ $kelas->nama_kelas }}</span>
            </p>
          </div>
        </div>
      </div>
    </div>

    <div
      class="bg-white dark:bg-slate-800 rounded-4xl sm:rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
      <div
        class="p-5 sm:p-8 border-b border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row justify-between sm:items-center gap-3">
        <h2 class="font-black text-slate-800 dark:text-white uppercase text-xs sm:text-sm tracking-widest">Daftar Sesi
          Perkuliahan</h2>
        <button @click="MicroModal.show('modal-add-pertemuan')"
          class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl text-xs font-black shadow-xl shadow-primary-500/20 transition-all transform hover:-translate-y-0.5">
          <i class="fas fa-plus-circle mr-2"></i>BUAT SESI BARU
        </button>
      </div>
      <div class="hidden md:block overflow-x-auto p-6">
        <table class="w-full text-left border-separate border-spacing-y-3">
          <thead>
            <tr>
              <th class="px-6 text-[10px] font-black uppercase text-slate-400 tracking-widest">Ke</th>
              <th class="px-6 text-[10px] font-black uppercase text-slate-400 tracking-widest">Waktu & Lokasi</th>
              <th class="px-6 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Status Sesi
              </th>
              <th class="px-6 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Aksi</th>
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
                      {{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('l, d F Y') }}
                    </p>
                    <p class="text-[9px] text-slate-400 font-bold italic uppercase">
                      <i class="fas fa-clock mr-1"></i>{{ substr($p->jam_mulai, 0, 5) }} -
                      {{ substr($p->jam_selesai, 0, 5) }}
                      <span class="mx-1 text-slate-300">|</span>
                      <i class="fas fa-location-dot mr-1 text-primary-500"></i>{{ $p->lokasi->nama ?? 'Default Radius' }}
                    </p>
                  </div>
                </td>
                <td class="px-6 py-4 text-center">
                  @php $status = $p->status_absensi; @endphp

                  <span
                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black uppercase {{ $status == 'Aktif' ? 'bg-emerald-100 text-emerald-600 animate-pulse' : 'bg-slate-200 text-slate-500' }}">
                    @if($status == 'Aktif') <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> @endif
                    {{ $status }}
                  </span>
                </td>
                <td class="px-6 py-4 text-right rounded-r-2xl">
                  <div class="flex justify-end gap-2">
                    <a href="{{ route('dosen.pertemuan.show', $p->id) }}"
                      class="w-9 h-9 flex items-center justify-center bg-primary-50 text-primary-600 border border-primary-100 rounded-xl hover:bg-primary-600 hover:text-white transition-all shadow-sm group">
                      <i class="fas fa-clipboard-user text-sm group-hover:scale-110 transition-transform"></i>
                    </a>
                    <form action="{{ route('dosen.pertemuan.toggle', $p->id) }}" method="POST">
                      @csrf @method('PATCH')
                      <button type="submit"
                        class="w-9 h-9 flex items-center justify-center rounded-xl {{ $p->status == 'dibuka' ? 'bg-red-50 text-red-600 border-red-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100' }} border hover:scale-110 transition-all shadow-sm">
                        <i class="fas {{ $p->status == 'dibuka' ? 'fa-stop-circle' : 'fa-play' }} text-sm"></i>
                      </button>
                    </form>
                    <button type="button" @click="MicroModal.show('modal-edit-{{ $p->id }}')"
                      class="w-9 h-9 flex items-center justify-center bg-amber-50 text-amber-600 border border-amber-100 rounded-xl hover:bg-amber-600 hover:text-white transition-all shadow-sm">
                      <i class="fas fa-edit text-sm"></i>
                    </button>
                    <button type="button" @click="MicroModal.show('modal-delete-{{ $p->id }}')"
                      class="w-9 h-9 flex items-center justify-center bg-rose-50 text-rose-600 border border-rose-100 rounded-xl hover:bg-rose-600 hover:text-white transition-all shadow-sm">
                      <i class="fas fa-trash text-sm"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="block md:hidden p-4 space-y-3">
        @foreach($kelas->pertemuans->sortBy('pertemuan_ke') as $p)
          <div
            class="bg-slate-50 dark:bg-slate-900/50 p-4 rounded-2xl border border-slate-100 dark:border-slate-800/60 shadow-xs space-y-3">
            <div class="flex justify-between items-start gap-2">
              <div class="min-w-0">
                <span
                  class="text-[10px] font-black px-2 py-0.5 bg-slate-200 dark:bg-slate-800 text-slate-500 rounded font-mono">SESI
                  #{{ $p->pertemuan_ke }}</span>
                <h4 class="font-black text-slate-800 dark:text-white text-sm leading-tight mt-1.5 wrap-break-word">
                  {{ $p->materi }}</h4>
              </div>
              <div class="shrink-0">
                @php $status = $p->status_absensi ?? ($p->status == 'dibuka' ? 'Aktif' : 'Ditutup'); @endphp
                <span
                  class="px-2.5 py-1 rounded-lg text-[8px] font-black uppercase border {{ $status == 'Aktif' ? 'bg-emerald-100 text-emerald-600 border-emerald-200' : 'bg-slate-200 text-slate-500 border-slate-300' }}">{{ $status }}</span>
              </div>
            </div>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">
              <i class="far fa-calendar mr-1 text-primary-500"></i>
              {{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('l, d M Y') }}
              <br>
              <i class="far fa-clock mr-1 text-primary-500 mt-1"></i> {{ substr($p->jam_mulai, 0, 5) }} -
              {{ substr($p->jam_selesai, 0, 5) }} WIB
            </p>
            <div class="pt-2 border-t border-slate-200/60 dark:border-slate-700/60 flex justify-end gap-1.5">
              <a href="{{ route('dosen.pertemuan.show', $p->id) }}"
                class="px-3 py-1.5 bg-primary-50 text-primary-600 rounded-lg text-[11px] font-bold border border-primary-100"><i
                  class="fas fa-clipboard-user mr-1"></i> Rekap</a>
              <form action="{{ route('dosen.pertemuan.toggle', $p->id) }}" method="POST" class="inline">
                @csrf @method('PATCH')
                <button type="submit"
                  class="px-3 py-1.5 {{ $p->status == 'dibuka' ? 'bg-red-50 text-red-600 border-red-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100' }} border rounded-lg text-[11px] font-bold">
                  <i class="fas {{ $p->status == 'dibuka' ? 'fa-stop-circle' : 'fa-play' }} mr-1"></i>
                  {{ $p->status == 'dibuka' ? 'Tutup' : 'Buka' }}
                </button>
              </form>
              <button type="button" @click="MicroModal.show('modal-edit-{{ $p->id }}')"
                class="px-2.5 py-1.5 bg-amber-50 text-amber-600 rounded-lg border border-amber-100"><i
                  class="fas fa-edit"></i></button>
              <button type="button" @click="MicroModal.show('modal-delete-{{ $p->id }}')"
                class="px-2.5 py-1.5 bg-rose-50 text-rose-600 rounded-lg border border-rose-100"><i
                  class="fas fa-trash"></i></button>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
  @foreach($kelas->pertemuans as $p)
    <div class="modal micromodal-slide" id="modal-edit-{{ $p->id }}" aria-hidden="true">
      <div
        class="modal__overlay fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 transition-opacity duration-300"
        tabindex="-1" data-micromodal-close>
        <div
          class="modal__container bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 sm:p-10 shadow-2xl w-full max-w-md relative transform transition-all duration-300 scale-95"
          role="dialog" aria-modal="true">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Edit Sesi
              #{{ $p->pertemuan_ke }}</h2>
            <button type="button" data-micromodal-close class="text-slate-400 hover:text-red-500 transition-colors"><i
                class="fas fa-times"></i></button>
          </div>
          <form action="{{ route('dosen.pertemuan.update', $p->id) }}" method="POST" class="space-y-4 text-left">
            @csrf @method('PATCH')
            <div class="grid grid-cols-2 gap-4">
              <div class="space-y-1">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Sesi Ke-</label>
                <input type="number" name="pertemuan_ke" value="{{ $p->pertemuan_ke }}"
                  class="w-full px-5 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-bold outline-none focus:border-primary-500"
                  required>
              </div>
              <div class="space-y-1">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal</label>
                <input type="date" name="tanggal"
                  value="{{ $p->tanggal ? \Carbon\Carbon::parse($p->tanggal)->format('Y-m-d') : date('Y-m-d') }}"
                  class="w-full px-5 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-bold outline-none focus:border-primary-500"
                  required>
              </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div class="space-y-1">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Jam Mulai</label>
                <input type="time" name="jam_mulai" value="{{ substr($p->jam_mulai, 0, 5) }}"
                  class="w-full px-5 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-bold focus:border-primary-500"
                  required>
              </div>
              <div class="space-y-1">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Jam Selesai</label>
                <input type="time" name="jam_selesai" value="{{ substr($p->jam_selesai, 0, 5) }}"
                  class="w-full px-5 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-bold focus:border-primary-500"
                  required>
              </div>
            </div>
            <div class="space-y-1">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Lokasi Presensi</label>
              <select name="lokasi_id"
                class="w-full px-5 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-bold focus:border-primary-500">
                @foreach($lokasis as $lok)
                  <option value="{{ $lok->id }}" {{ $p->lokasi_id == $lok->id ? 'selected' : '' }}>{{ $lok->nama }}
                    ({{ $lok->radius_meter }}m)</option>
                @endforeach
              </select>
            </div>
            <div class="space-y-1">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Materi Bahasan</label>
              <textarea name="materi" rows="2"
                class="w-full px-5 py-3 rounded-2xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-bold focus:border-primary-500"
                required>{{ $p->materi }}</textarea>
            </div>
            <button type="submit"
              class="w-full py-4 bg-amber-500 text-white rounded-2xl font-black shadow-xl shadow-amber-500/30 transform transition-all hover:-translate-y-0.5 mt-2">SIMPAN
              PERUBAHAN</button>
          </form>
        </div>
      </div>
    </div>
    <div class="modal micromodal-slide" id="modal-delete-{{ $p->id }}" aria-hidden="true">
      <div
        class="modal__overlay fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 transition-opacity duration-300"
        tabindex="-1" data-micromodal-close>
        <div
          class="modal__container bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 sm:p-10 shadow-2xl w-full max-w-sm text-center relative transform transition-all duration-300 scale-95"
          role="dialog" aria-modal="true">
          <div
            class="w-20 h-20 bg-rose-100 dark:bg-rose-900/30 text-rose-600 rounded-3xl flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-trash-alt text-3xl"></i>
          </div>
          <h2 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tight mb-2">Hapus Sesi?</h2>
          <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Data presensi mahasiswa pada pertemuan
            ke-{{ $p->pertemuan_ke }} akan <strong class="text-rose-600">terhapus permanen</strong>.</p>
          <form action="{{ route('dosen.pertemuan.destroy', $p->id) }}" method="POST" class="w-full">
            @csrf @method('DELETE')
            <button type="submit"
              class="w-full py-4 bg-rose-600 text-white rounded-2xl font-black shadow-xl shadow-rose-500/30 transform transition-all hover:-translate-y-0.5 uppercase text-xs tracking-widest">Ya,
              Hapus Permanen</button>
          </form>
        </div>
      </div>
    </div>
  @endforeach
  <div class="modal micromodal-slide" id="modal-add-pertemuan" aria-hidden="true">
    <div class="modal__overlay fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4"
      tabindex="-1" data-micromodal-close>
      <div
        class="modal__container bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 sm:p-10 shadow-2xl w-full max-w-md relative"
        role="dialog" aria-modal="true">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Set Sesi Baru</h2>
          <button type="button" data-micromodal-close class="text-slate-400 hover:text-red-500"><i
              class="fas fa-times"></i></button>
        </div>
        <form action="{{ route('dosen.kelas.pertemuan.store', $kelas->id) }}" method="POST" class="space-y-4">
          @csrf
          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Sesi Ke-</label>
              <input type="number" name="pertemuan_ke" value="{{ $kelas->pertemuans->count() + 1 }}"
                class="w-full px-5 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-bold outline-none focus:border-primary-500"
                required>
            </div>
            <div class="space-y-1">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal</label>
              <input type="date" name="tanggal" value="{{ date('Y-m-d') }}"
                class="w-full px-5 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-bold outline-none focus:border-primary-500"
                required>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Jam Mulai</label>
              <input type="time" name="jam_mulai"
                class="w-full px-5 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-bold focus:border-primary-500"
                required>
            </div>
            <div class="space-y-1">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Jam Selesai</label>
              <input type="time" name="jam_selesai"
                class="w-full px-5 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-bold focus:border-primary-500"
                required>
            </div>
          </div>
          <div class="space-y-1">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Lokasi Presensi</label>
            <select name="lokasi_id"
              class="w-full px-5 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-bold focus:border-primary-500">
              @foreach($lokasis as $lok)
                <option value="{{ $lok->id }}">{{ $lok->nama }} ({{ $lok->radius_meter }}m)</option>
              @endforeach
            </select>
          </div>
          <div class="space-y-1">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Materi Bahasan</label>
            <textarea name="materi" rows="2"
              class="w-full px-5 py-3 rounded-2xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-bold focus:border-primary-500"
              placeholder="Input topik pertemuan..." required></textarea>
          </div>
          <button type="submit"
            class="w-full py-4 bg-primary-600 text-white rounded-2xl font-black shadow-xl shadow-primary-500/30 transform transition-all hover:-translate-y-0.5 mt-2">KONFIRMASI
            & SIMPAN SESI</button>
        </form>
      </div>
    </div>
  </div>
@endsection