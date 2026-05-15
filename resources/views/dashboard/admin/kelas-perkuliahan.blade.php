@extends('layouts.app')

@section('title', 'Kelas Perkuliahan • SIPRESPRO')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Manajemen Kelas</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Pengaturan relasi matkul, dosen pengampu,
                    dan lokasi ruang</p>
            </div>
            <button type="button" @click="MicroModal.show('modal-create-kelas')"
                class="flex items-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold transition-all shadow-lg shadow-primary-200 dark:shadow-none">
                <i class="fas fa-plus-circle"></i>
                <span>Buka Kelas Baru</span>
            </button>
        </div>

        <div
            class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm transition-colors duration-300">
            <!-- DESKTOP VIEW TABLE (Hanya nampil di layar laptop md: ke atas) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th
                                class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                Informasi Mata Kuliah & Kelas</th>
                            <th
                                class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                Golongan</th>
                            <th
                                class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                Dosen Pengampu</th>
                            <th
                                class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center whitespace-nowrap">
                                Ruang & Tipe</th>
                            <th
                                class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($kelases as $kelas)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-all group">
                                <td class="px-6 py-4 min-w-[280px]">
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="w-10 h-10 shrink-0 rounded-xl bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center text-amber-600 dark:text-amber-400 font-bold border border-amber-200 dark:border-amber-800">
                                            <i class="fas fa-book"></i>
                                        </div>
                                        <div class="space-y-1">
                                            <p class="font-bold text-slate-800 dark:text-slate-100 leading-tight">
                                                {{ $kelas->mataKuliah->nama }}
                                            </p>
                                            <p class="text-[10px] text-slate-400 font-mono font-bold uppercase tracking-wider">
                                                {{ $kelas->mataKuliah->kode_mk }}
                                            </p>
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-md bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-300 text-[10px] font-bold border border-indigo-100 dark:border-indigo-800">{{ $kelas->nama_kelas }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($kelas->golongans as $gol)
                                            <span
                                                class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-[10px] font-bold border border-slate-200 dark:border-slate-600">
                                                {{ $gol->nama }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $kelas->dosen->nama }}</span>
                                        <span class="text-[10px] text-slate-400 italic">NIP:
                                            {{ $kelas->dosen->nip ?? '-' }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400"><i
                                                class="fas fa-door-closed mr-1"></i> {{ $kelas->ruang->nama }}</span>
                                        <span
                                            class="text-[9px] font-black uppercase px-2 py-0.5 rounded {{ $kelas->tipe_kelas == 'reguler' ? 'text-blue-600 bg-blue-50 border-blue-100' : 'text-purple-600 bg-purple-50 border-purple-100' }}">
                                            {{ $kelas->tipe_kelas }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button type="button" @click="MicroModal.show('modal-edit-{{ $kelas->id }}')"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 border border-blue-100"><i
                                                class="fas fa-edit text-xs"></i></button>
                                        <button type="button" @click="MicroModal.show('modal-delete-{{ $kelas->id }}')"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 border border-red-100"><i
                                                class="fas fa-trash text-xs"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center text-slate-400 italic font-medium">Data kelas
                                    masih kosong!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- MOBILE STACKED CARD VIEW (Khusus Layar HP / md:hidden) -->
            <div class="block md:hidden p-4 space-y-3">
                @forelse($kelases as $kelas)
                    <div class="bg-slate-50 dark:bg-slate-900/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-800/80 shadow-xs space-y-3">
                        <div class="flex justify-between items-start gap-2">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 shrink-0 rounded-xl bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center font-black text-amber-600 dark:text-amber-400 text-sm border border-amber-200 dark:border-amber-800">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-black text-slate-800 dark:text-white text-sm leading-tight truncate">{{ $kelas->mataKuliah->nama }}</h4>
                                    <div class="flex items-center gap-1 mt-0.5">
                                        <p class="text-[10px] font-mono text-slate-400 truncate font-bold uppercase tracking-wider">{{ $kelas->mataKuliah->kode_mk }}</p>
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-md bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-300 text-[9px] font-bold border border-indigo-100 dark:border-indigo-800">{{ $kelas->nama_kelas }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-1">
                            @foreach($kelas->golongans as $gol)
                                <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-[10px] font-bold border border-slate-200 dark:border-slate-600">
                                    {{ $gol->nama }}
                                </span>
                            @endforeach
                        </div>

                        <div class="text-[11px] text-slate-500 dark:text-slate-400 space-y-2 font-medium">
                            <div class="flex flex-col gap-1 bg-white dark:bg-slate-800 p-2.5 rounded-xl border border-slate-100 dark:border-slate-700">
                                <span class="font-bold text-slate-700 dark:text-slate-300"><i class="fas fa-user-tie text-blue-500 mr-1.5"></i> Dosen:</span>
                                <div>
                                    <p class="font-semibold text-slate-800 dark:text-slate-200 text-xs">{{ $kelas->dosen->nama }}</p>
                                    <p class="text-[10px] text-slate-400 italic">NIP: {{ $kelas->dosen->nip ?? '-' }}</p>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center bg-white dark:bg-slate-800 p-2.5 rounded-xl border border-slate-100 dark:border-slate-700">
                                <span class="font-bold text-slate-700 dark:text-slate-300"><i class="fas fa-door-closed text-emerald-500 mr-1.5"></i> Ruang & Tipe:</span>
                                <div class="flex items-center gap-1.5">
                                    <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ $kelas->ruang->nama }}</span>
                                    <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded {{ $kelas->tipe_kelas == 'reguler' ? 'text-blue-600 bg-blue-50 border-blue-100' : 'text-purple-600 bg-purple-50 border-purple-100' }}">
                                        {{ $kelas->tipe_kelas }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2 border-t border-slate-200/50 dark:border-slate-700/50 flex justify-end gap-1.5">
                            <button type="button" @click="MicroModal.show('modal-edit-{{ $kelas->id }}')"
                                class="px-3 py-1.5 bg-blue-50 text-blue-600 border border-blue-100 rounded-xl text-[10px] font-black uppercase tracking-wider flex items-center gap-1">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button type="button" @click="MicroModal.show('modal-delete-{{ $kelas->id }}')"
                                class="px-3 py-1.5 bg-red-50 text-red-600 border border-red-100 rounded-xl text-[10px] font-black uppercase tracking-wider flex items-center gap-1">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 italic font-medium">Data kelas masih kosong!</div>
                @endforelse
            </div>
        </div>
    </div>


    <div class="modal" id="modal-create-kelas" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close
            class="bg-slate-900/60 backdrop-blur-sm fixed inset-0 z-50 flex items-center justify-center">
            <div class="modal__container w-full max-w-4xl bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-2xl md:overflow-hidden"
                role="dialog" @click.stop>

                <header
                    class="px-10 py-3 bg-slate-50 dark:bg-slate-900/50 border-b dark:border-slate-700 flex justify-between items-center">
                    <div class="text-left">
                        <h2 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Buka Kelas Perkuliahan
                        </h2>
                        <p class="text-[10px] text-primary-600 font-bold uppercase tracking-widest italic">
                            Periode: {{ $semesterAktif->nama ?? '-' }} ({{ $semesterAktif->tahun_ajaran ?? '-' }})
                        </p>
                    </div>
                    <button type="button"
                        class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white dark:bg-slate-800 shadow-sm text-slate-400 hover:text-red-500 transition-all"
                        data-micromodal-close>
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </header>

                <form action="{{ route('admin.kelas-perkuliahan.store') }}" method="POST" class="p-10">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

                        <div class="lg:col-span-6 space-y-6">
                            <div class="text-left">
                                <label
                                    class="block text-[11px] font-black text-slate-400 mb-2 uppercase tracking-widest italic text-primary-600">
                                    Konfigurasi Target Golongan
                                </label>
                                <div id="info-aturan"
                                    class="bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-400 p-4 rounded-r-2xl mb-4 transition-all">
                                    <div class="flex items-start gap-3">
                                        <i class="fas fa-info-circle text-amber-500 mt-0.5"></i>
                                        <p id="teks-aturan"
                                            class="text-[11px] text-amber-700 dark:text-amber-300 leading-tight font-medium text-left">
                                            Tipe <b>Reguler</b> hanya diperbolehkan memilih 1 golongan saja.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="text-left">
                                <label
                                    class="block text-[11px] font-black text-slate-400 mb-2 uppercase tracking-widest">Nama
                                    Kelas / Label</label>
                                <input type="text" name="nama_kelas" required placeholder="Contoh: A, B, atau Gabungan"
                                    class="w-full px-5 py-4 rounded-2xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white focus:border-primary-500 outline-none transition-all font-bold">
                            </div>

                            <div class="text-left">
                                <label
                                    class="block text-[11px] font-black text-slate-400 mb-3 uppercase tracking-widest">Tipe
                                    Kelas</label>
                                <div class="grid grid-cols-2 gap-4">
                                    @foreach(['reguler', 'gabungan'] as $tipe)
                                        <label
                                            class="relative flex items-center justify-center p-4 rounded-2xl border-2 cursor-pointer transition-all border-slate-100 dark:border-slate-700 has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50 dark:has-[:checked]:bg-primary-900/20 group">
                                            <input type="radio" name="tipe_kelas" value="{{ $tipe }}" class="sr-only" {{ $tipe == 'reguler' ? 'checked' : '' }}>
                                            <span
                                                class="text-xs font-black uppercase text-slate-500 group-hover:text-primary-600 tracking-widest text-center">{{ $tipe }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="text-left">
                                <label
                                    class="block text-[11px] font-black text-slate-400 mb-2 uppercase tracking-widest">Mata
                                    Kuliah Aktif</label>
                                <select name="mata_kuliah_id" required
                                    class="w-full px-5 py-4 rounded-2xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-bold appearance-none outline-none focus:border-primary-500">
                                    @foreach($matkulFiltered as $m)
                                        <option value="{{ $m->id }}">[{{ $m->kode_mk }}] {{ $m->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4 text-left">
                                <div>
                                    <label
                                        class="block text-[11px] font-black text-slate-400 mb-2 uppercase tracking-widest">Dosen</label>
                                    <select name="dosen_id" required
                                        class="w-full px-4 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-semibold outline-none">
                                        @foreach($dosens as $d) <option value="{{ $d->id }}">{{ $d->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="block text-[11px] font-black text-slate-400 mb-2 uppercase tracking-widest">Ruangan</label>
                                    <select name="ruang_id" required
                                        class="w-full px-4 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-semibold outline-none">
                                        @foreach($ruangs as $r) <option value="{{ $r->id }}">{{ $r->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-6 flex flex-col text-left">
                            <label
                                class="block text-[11px] font-black text-slate-400 mb-4 uppercase tracking-widest text-primary-600">
                                Golongan Tersedia (Berdasarkan Mahasiswa)
                            </label>
                            <div class="flex-1 overflow-y-auto pr-3 max-h-[420px] space-y-4 custom-scrollbar">

                                @foreach($angkatanList as $ank)
                                    <div
                                        class="p-5 rounded-3xl bg-slate-50 dark:bg-slate-900/50 border-2 border-slate-100 dark:border-slate-700">
                                        <div class="flex justify-between items-center mb-4">
                                            <div>
                                                <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase">Angkatan
                                                    {{ $ank['tahun'] }}
                                                </h3>
                                                <p
                                                    class="text-[10px] text-primary-600 font-bold uppercase tracking-widest text-left">
                                                    Semester {{ $ank['semester_nama'] }}</p>
                                            </div>
                                            <span
                                                class="text-[10px] bg-slate-200 dark:bg-slate-700 px-2 py-1 rounded-lg font-bold text-slate-500">{{ $ank['data_golongan']->count() }}
                                                Golongan</span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2 text-left">
                                            @foreach($ank['data_golongan'] as $g)
                                                <label
                                                    class="flex items-center gap-3 p-3 bg-white dark:bg-slate-800 rounded-xl border border-transparent hover:border-primary-500 cursor-pointer group shadow-sm transition-all text-left">
                                                    <input type="checkbox" name="golongan_ids[]" value="{{ $g->id }}"
                                                        class="w-5 h-5 rounded-lg text-primary-600 focus:ring-primary-500 transition-all">
                                                    <span
                                                        class="text-xs font-black text-slate-600 dark:text-slate-400 group-hover:text-primary-600 uppercase">Gol.
                                                        {{ $g->nama }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>

                    <div class="mt-10 pt-8 border-t dark:border-slate-700 flex items-center justify-between">
                        <button type="button" data-micromodal-close
                            class="text-sm font-bold text-slate-400 hover:text-slate-600 uppercase tracking-widest italic">Batal</button>
                        <button type="submit"
                            class="px-10 py-4 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl font-black shadow-xl shadow-primary-500/30 transition-all transform hover:-translate-y-1 uppercase tracking-widest text-xs">
                            Konfirmasi & Buka Kelas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach($kelases as $kelas)
        <div class="modal" id="modal-edit-{{ $kelas->id }}" aria-hidden="true">
            <div class="modal__overlay" tabindex="-1" data-micromodal-close
                class="bg-slate-900/60 backdrop-blur-sm fixed inset-0 z-50 flex items-center justify-center">
                <div class="modal__container w-full max-w-4xl bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-2xl md:overflow-hidden"
                    role="dialog" @click.stop>
                    <header
                        class="px-10 py-3 bg-slate-50 dark:bg-slate-900/50 border-b dark:border-slate-700 flex justify-between items-center">
                        <div class="text-left">
                            <h2 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Buka Kelas Perkuliahan
                            </h2>
                            <p class="text-[10px] text-primary-600 font-bold uppercase tracking-widest italic">
                                Periode: {{ $semesterAktif->nama ?? '-' }} ({{ $semesterAktif->tahun_ajaran ?? '-' }})
                            </p>
                        </div>
                        <button type="button"
                            class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white dark:bg-slate-800 shadow-sm text-slate-400 hover:text-red-500 transition-all"
                            data-micromodal-close>
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </header>

                    <form action="{{ route('admin.kelas-perkuliahan.update', $kelas->id) }}" method="POST" class="p-10">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

                            <div class="lg:col-span-6 space-y-6">
                                <div class="text-left">
                                    <label
                                        class="block text-[11px] font-black text-slate-400 mb-2 uppercase tracking-widest italic text-primary-600">
                                        Konfigurasi Target Golongan
                                    </label>
                                    <div id="info-aturan"
                                        class="bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-400 p-4 rounded-r-2xl mb-4 transition-all">
                                        <div class="flex items-start gap-3">
                                            <i class="fas fa-info-circle text-amber-500 mt-0.5"></i>
                                            <p id="teks-aturan"
                                                class="text-[11px] text-amber-700 dark:text-amber-300 leading-tight font-medium text-left">
                                                Tipe <b>Reguler</b> hanya diperbolehkan memilih 1 golongan saja.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-left">
                                    <label
                                        class="block text-[11px] font-black text-slate-400 mb-2 uppercase tracking-widest">Nama
                                        Kelas / Label</label>
                                    <input type="text" name="nama_kelas" value="{{ $kelas->nama_kelas }}" required
                                        placeholder="Contoh: A, B, atau Gabungan"
                                        class="w-full px-5 py-4 rounded-2xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white focus:border-primary-500 outline-none transition-all font-bold">
                                </div>

                                <div class="text-left">
                                    <label
                                        class="block text-[11px] font-black text-slate-400 mb-3 uppercase tracking-widest text-left">Tipe
                                        Kelas</label>
                                    <div class="grid grid-cols-2 gap-4">
                                        @foreach(['reguler', 'gabungan'] as $tipe)
                                        <label class="relative flex items-center justify-center p-4 rounded-2xl border-2 cursor-pointer transition-all border-slate-100 dark:border-slate-700 has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50 dark:has-[:checked]:bg-primary-900/20 group">
                                            <input type="radio" name="tipe_kelas" value="{{ $tipe }}" class="sr-only tipe-kelas-edit"  {{ $kelas->tipe_kelas == $tipe ? 'checked' : '' }}>
                                            <span class="text-xs font-black uppercase text-slate-500 group-hover:text-primary-600 tracking-widest text-center">{{ $tipe }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="text-left">
                                    <label
                                        class="block text-[11px] font-black text-slate-400 mb-2 uppercase tracking-widest">Mata
                                        Kuliah Aktif</label>
                                    <select name="mata_kuliah_id" required
                                        class="w-full px-5 py-4 rounded-2xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-white focus:border-primary-500 outline-none transition-all font-bold">
                                        @foreach($matkulFiltered as $m)
                                            <option value="{{ $m->id }}" {{ $kelas->mata_kuliah_id == $m->id ? 'selected' : '' }}>
                                                [{{ $m->kode_mk }}] {{ $m->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="grid grid-cols-2 gap-4 text-left">
                                    <div>
                                        <label
                                            class="block text-[11px] font-black text-slate-400 mb-2 uppercase tracking-widest">Dosen</label>
                                        <select name="dosen_id" required
                                            class="w-full px-4 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-semibold outline-none">
                                            @foreach($dosens as $d)
                                                <option value="{{ $d->id }}" {{ $kelas->dosen_id == $d->id ? 'selected' : '' }}>
                                                    {{ $d->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[11px] font-black text-slate-400 mb-2 uppercase tracking-widest">Ruangan</label>
                                        <select name="ruang_id" required
                                            class="w-full px-4 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 font-semibold outline-none">
                                            @foreach($ruangs as $r)
                                                <option value="{{ $r->id }}" {{ $kelas->ruang_id == $r->id ? 'selected' : '' }}>
                                                    {{ $r->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="lg:col-span-6 flex flex-col text-left">
                                <label
                                    class="block text-[11px] font-black text-slate-400 mb-4 uppercase tracking-widest text-primary-600">
                                    Pilih Golongan Mahasiswa
                                </label>
                                <div class="flex-1 pr-3 space-y-4">
                                    @php
                                        $golonganTerpilih = $kelas->golongans->pluck('id')->toArray();
                                        $targetSemester = $kelas->mataKuliah->semester_id;
                                        $angkatanSesuai = collect($angkatanList)->firstWhere('semester_nama', $kelas->mataKuliah->semester->nama ?? null)
                                            ?? collect($angkatanList)->first();
                                    @endphp

                                    @if($angkatanSesuai)
                                        <div
                                            class="p-5 rounded-3xl bg-slate-50 dark:bg-slate-900/50 border-2 border-slate-100 dark:border-slate-700">
                                            <div class="mb-4">
                                                <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase">
                                                    Angkatan {{ $angkatanSesuai['tahun'] }}
                                                    <span class="text-primary-600 ml-2">(Semester
                                                        {{ $angkatanSesuai['semester_nama'] }})</span>
                                                </h3>
                                            </div>

                                            <div class="grid grid-cols-2 gap-2">
                                                @foreach($angkatanSesuai['data_golongan'] as $g)
                                                    @php $isCheck = in_array($g->id, $golonganTerpilih); @endphp

                                                    <label class="flex items-center gap-3 p-3 rounded-xl border-2 transition-all cursor-pointer 
                                                                                {{ $isCheck ? 'bg-primary-50 border-primary-500' : 'bg-white dark:bg-slate-800 border-transparent' }} 
                                                                                hover:border-primary-500">

                                                        <input type="checkbox" name="golongan_ids[]" value="{{ $g->id }}"
                                                            class="w-5 h-5 rounded-lg text-primary-600" {{ $isCheck ? 'checked' : '' }}>

                                                        <span
                                                            class="text-xs font-black uppercase {{ $isCheck ? 'text-primary-600' : 'text-slate-600' }}">
                                                            Gol. {{ $g->nama }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-xs italic text-slate-400">Gak ada data angkatan yang cocok.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 pt-8 border-t dark:border-slate-700 flex items-center justify-between">
                            <button type="button" data-micromodal-close
                                class="text-sm font-bold text-slate-400 hover:text-slate-600 uppercase tracking-widest italic">Batal</button>
                            <button type="submit"
                                class="px-10 py-4 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl font-black shadow-xl shadow-primary-500/30 transition-all transform hover:-translate-y-1 uppercase tracking-widest text-xs">
                                Konfirmasi & Buka Kelas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal" id="modal-delete-{{ $kelas->id }}" aria-hidden="true">
            <div class="modal__overlay" tabindex="-1" data-micromodal-close>
                <div class="modal__container w-full max-w-sm text-center bg-white dark:bg-slate-800 rounded-4xl p-8 shadow-2xl border-none dark:border dark:border-slate-700"
                    role="dialog" @click.stop>

                    <div
                        class="w-20 h-20 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-red-50 dark:border-red-900/10">
                        <i class="fas fa-exclamation-triangle text-3xl"></i>
                    </div>

                    <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Hapus Kelas?</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 leading-relaxed">
                        Menghapus kelas <b>{{ $kelas->nama_kelas }}</b> bakal bikin relasi golongan dan presensi mahasiswa ilang
                        semua. Yakin mau dihapus?
                    </p>

                    <form action="{{ route('admin.kelas-perkuliahan.destroy', $kelas->id) }}" method="POST" class="flex gap-3">
                        @csrf
                        @method('DELETE')
                        <button type="button" data-micromodal-close
                            class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl font-bold transition-all">Batal</button>
                        <button type="submit"
                            class="flex-1 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold shadow-lg shadow-red-200 dark:shadow-none transition-all">Ya,
                            Hapus!</button>
                    </form>

                </div>
            </div>
        </div>
    @endforeach
@endsection