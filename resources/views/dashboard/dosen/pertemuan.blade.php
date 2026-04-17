@extends('layouts.app')

@section('title', 'Kelola Pertemuan • SIPRESPRO')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 text-left">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Kelola Pertemuan</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Buka atau tutup sesi presensi perkuliahan</p>
        </div>
        <button @click="MicroModal.show('modal-create-pertemuan')"
            class="flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold transition-all shadow-lg shadow-indigo-200 dark:shadow-none">
            <i class="fas fa-calendar-plus"></i>
            <span>Buat Pertemuan</span>
        </button>
    </div>

    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm transition-colors duration-300">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Kelas & Matkul</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center">Pertemuan Ke</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Tanggal & Materi</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-left">
                    @forelse($pertemuans as $p)
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-all group">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-800 dark:text-slate-100 leading-tight">{{ $p->kelasPerkuliahan->mataKuliah->nama }}</span>
                                <span class="text-[10px] font-bold text-primary-600 uppercase tracking-widest mt-1">Kelas {{ $p->kelasPerkuliahan->nama_kelas }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 font-black text-xs border border-slate-200 dark:border-slate-700">
                                {{ $p->pertemuan_ke }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-200"><i class="fas fa-calendar-day mr-1.5 text-slate-400"></i> {{ date('d M Y', strtotime($p->tanggal)) }}</span>
                                <span class="text-[10px] text-slate-400 italic truncate max-w-[200px]">{{ $p->materi }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[9px] font-black uppercase border {{ $p->status == 'dibuka' ? 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800' : 'bg-rose-50 text-rose-600 border-rose-100 dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800' }}">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $p->status == 'dibuka' ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></span>
                                {{ $p->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button @click="MicroModal.show('modal-edit-{{ $p->id }}')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white transition-all border border-blue-100 dark:border-blue-800"><i class="fas fa-edit text-xs"></i></button>
                                <button @click="MicroModal.show('modal-delete-{{ $p->id }}')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white transition-all border border-red-100 dark:border-red-800"><i class="fas fa-trash text-xs"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center text-slate-400 italic font-medium">Belum ada pertemuan yang dibuat jancok!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal" id="modal-create-pertemuan" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
        <div class="modal__container w-full max-w-lg bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 shadow-2xl border-none dark:border dark:border-slate-700" role="dialog" @click.stop>
            <header class="flex justify-between items-center mb-6 pb-4 border-b dark:border-slate-700/50">
                <div>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">Buka Pertemuan</h2>
                    <p class="text-[11px] text-indigo-600 dark:text-indigo-400 uppercase font-black tracking-widest mt-1">Sesi Presensi Mahasiswa</p>
                </div>
                <button class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors" data-micromodal-close><i class="fas fa-times"></i></button>
            </header>

            <form action="{{ route('admin.pertemuan.store') }}" method="POST" class="space-y-4 text-left">
                @csrf
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest text-left">Pilih Kelas</label>
                    <select name="kelas_perkuliahan_id" required class="w-full px-4 py-3 rounded-xl border dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/50 outline-none">
                        @foreach($kelases as $k) <option value="{{ $k->id }}">[{{ $k->mataKuliah->kode_mk }}] {{ $k->mataKuliah->nama }} (Kelas {{ $k->nama_kelas }})</option> @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4 text-left">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Pertemuan Ke-</label>
                        <input type="number" name="pertemuan_ke" required min="1" max="16" placeholder="1" class="w-full px-4 py-3 rounded-xl border dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-3 rounded-xl border dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest text-left">Pokok Bahasan / Materi</label>
                    <textarea name="materi" required rows="2" placeholder="Contoh: Instalasi Laravel & Setup Environment" class="w-full px-4 py-3 rounded-xl border dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 outline-none resize-none focus:ring-2 focus:ring-indigo-500/50"></textarea>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest text-left">Status Awal</label>
                    <select name="status" class="w-full px-4 py-3 rounded-xl border dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 outline-none">
                        <option value="dibuka">Buka Presensi Sekarang</option>
                        <option value="ditutup">Simpan sebagai Draft (Tutup)</option>
                    </select>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" data-micromodal-close class="flex-1 py-3 bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 rounded-xl font-bold transition-all">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-200 dark:shadow-none transition-all">Simpan Pertemuan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($pertemuans as $p)
  <div class="modal" id="modal-edit-{{ $p->id }}" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
      <div class="modal__container w-full max-w-lg bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 shadow-2xl border-none dark:border dark:border-slate-700" role="dialog" @click.stop>
        <header class="flex justify-between items-center mb-6 pb-4 border-b dark:border-slate-700/50 text-left">
          <div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-white">Update Pertemuan</h2>
            <p class="text-[10px] text-blue-500 font-black uppercase tracking-widest mt-0.5">Edit Sesi Pertemuan Ke-{{ $p->pertemuan_ke }}</p>
          </div>
          <button class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors" data-micromodal-close><i class="fas fa-times"></i></button>
        </header>

        <form action="{{ route('admin.pertemuan.update', $p->id) }}" method="POST" class="space-y-4 text-left">
          @csrf @method('PUT')
          <div>
            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Pokok Bahasan</label>
            <textarea name="materi" required rows="2" class="w-full px-4 py-3 rounded-xl border dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 outline-none resize-none focus:ring-2 focus:ring-blue-500/50">{{ $p->materi }}</textarea>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $p->tanggal }}" required class="w-full px-4 py-3 rounded-xl border dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 outline-none">
            </div>
            <div>
              <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Status Presensi</label>
              <select name="status" class="w-full px-4 py-3 rounded-xl border dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 outline-none focus:ring-2 focus:ring-blue-500/50">
                <option value="dibuka" {{ $p->status == 'dibuka' ? 'selected' : '' }}>Dibuka (Mahasiswa Bisa Absen)</option>
                <option value="ditutup" {{ $p->status == 'ditutup' ? 'selected' : '' }}>Ditutup (Presensi Berakhir)</option>
              </select>
            </div>
          </div>
          <div class="mt-8 flex gap-3">
            <button type="button" data-micromodal-close class="flex-1 py-3 bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 rounded-xl font-bold transition-all">Batal</button>
            <button type="submit" class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-200 dark:shadow-none transition-all">Update Sesi</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal" id="modal-delete-{{ $p->id }}" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
      <div class="modal__container w-full max-w-sm text-center bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 shadow-2xl border-none dark:border dark:border-slate-700" role="dialog" @click.stop>
        <div class="w-20 h-20 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-red-50 dark:border-red-900/10">
          <i class="fas fa-calendar-xmark text-3xl"></i>
        </div>
        <h2 class="text-xl text-slate-800 dark:text-white mb-2 font-bold">Hapus Pertemuan?</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 leading-relaxed">
          Semua data presensi pada pertemuan ke-{{ $p->pertemuan_ke }} kelas {{ $p->kelasPerkuliahan->nama_kelas }} akan ikut terhapus jancok!
        </p>
        <form action="{{ route('admin.pertemuan.destroy', $p->id) }}" method="POST" class="flex gap-3">
          @csrf @method('DELETE')
          <button type="button" data-micromodal-close class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 rounded-xl font-bold transition-all">Batal</button>
          <button type="submit" class="flex-1 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold shadow-lg shadow-red-200">Ya, Hapus</button>
        </form>
      </div>
    </div>
  </div>
@endforeach

@endsection