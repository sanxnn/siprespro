@extends('layouts.app')

@section('title', 'Data Presensi • SIPRESPRO')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 text-left">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Log Presensi Mahasiswa</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Monitoring kehadiran real-time berdasarkan koordinat GPS</p>
            </div>

            <form action="{{ route('admin.presensi.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                {{-- Filter Pertemuan yang Diperjelas Namanya --}}
                <select name="pertemuan_id" onchange="this.form.submit()"
                    class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold outline-none focus:ring-2 focus:ring-primary-500/50 transition-all">
                    <option value="">Semua Pertemuan</option>
                    @foreach($pertemuans as $p)
                        <option value="{{ $p->id }}" {{ request('pertemuan_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->kelasPerkuliahan->mataKuliah->nama }} - Kelas {{ $p->kelasPerkuliahan->nama_kelas }} (P-{{ $p->pertemuan_ke }})
                        </option>
                    @endforeach
                </select>

                {{-- Filter Status --}}
                <select name="status" onchange="this.form.submit()"
                    class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-bold outline-none focus:ring-2 focus:ring-primary-500/50 transition-all">
                    <option value="">Semua Status</option>
                    <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>HADIR</option>
                    <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>IZIN</option>
                    <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>SAKIT</option>
                    <option value="alpha" {{ request('status') == 'alpha' ? 'selected' : '' }}>ALPHA (Pilih Pertemuan Dulu)</option>
                </select>

                @if(request()->anyFilled(['pertemuan_id', 'status']))
                    <a href="{{ route('admin.presensi.index') }}" class="text-xs font-bold text-rose-500 hover:underline">Reset</a>
                @endif
            </form>
        </div>

        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm transition-colors duration-300">
            <!-- DESKTOP VIEW TABLE (Hanya nampil di layar laptop md: ke atas) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 whitespace-nowrap">Mahasiswa</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center whitespace-nowrap">Waktu Presensi</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center whitespace-nowrap">Status</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 whitespace-nowrap">Koordinat GPS</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-left">
                        @forelse($presensis as $presensi)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-all group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-900 flex items-center justify-center font-bold text-slate-500 dark:text-slate-400 text-xs border border-slate-200 dark:border-slate-700">
                                            {{ substr($presensi->mahasiswa->nama, 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-800 dark:text-slate-100">{{ $presensi->mahasiswa->nama }}</span>
                                            <span class="text-[10px] text-slate-400 font-mono">{{ $presensi->mahasiswa->nim }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($presensi->waktu_presensi)
                                        <div class="flex flex-col items-center">
                                            <span class="text-xs font-bold text-slate-700 dark:text-slate-200">{{ date('H:i', strtotime($presensi->waktu_presensi)) }}</span>
                                            <span class="text-[9px] text-slate-400 uppercase font-black">{{ date('d M Y', strtotime($presensi->waktu_presensi)) }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-300 dark:text-slate-600 italic font-medium">-</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @php
                                        $colors = [
                                            'hadir' => 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800',
                                            'izin' => 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800',
                                            'sakit' => 'bg-blue-50 text-blue-600 border-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800',
                                            'alpha' => 'bg-rose-50 text-rose-600 border-rose-100 dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase border {{ $colors[strtolower($presensi->status)] ?? 'bg-slate-50 text-slate-600' }}">
                                        {{ $presensi->status }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    @if($presensi->latitude && $presensi->longitude)
                                        <a href="https://www.google.com/maps?q={{ $presensi->latitude }},{{ $presensi->longitude }}" target="_blank" class="flex items-center gap-2 hover:text-primary-500 transition-colors">
                                            <i class="fas fa-location-dot text-red-500 text-xs"></i>
                                            <span class="text-[10px] font-mono text-slate-500 dark:text-slate-400 truncate max-w-[120px]">
                                                {{ $presensi->latitude }}, {{ $presensi->longitude }}
                                            </span>
                                        </a>
                                    @else
                                        <span class="text-[10px] text-slate-300 dark:text-slate-600 italic">Tidak Ada GPS</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        {{-- Tombol Edit mengoper data via dataset HTML5 jancok --}}
                                        <button type="button" 
                                            class="btn-edit-presensi p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors"
                                            data-id="{{ $presensi->id }}"
                                            data-nama="{{ $presensi->mahasiswa->nama }}"
                                            data-status="{{ strtolower($presensi->status) }}"
                                            data-mhs-id="{{ $presensi->mahasiswa_id }}"
                                            data-pertemuan-id="{{ $presensi->pertemuan_id }}">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                        
                                        @if(!str_contains($presensi->id, 'alpha-'))
                                            <button type="button" 
                                                class="btn-delete-presensi p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors"
                                                data-id="{{ $presensi->id }}"
                                                data-nama="{{ $presensi->mahasiswa->nama }}">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center text-slate-400 italic">Data presensi tidak ditemukan!</td>
                            </tr>
                        @endempty
                    </tbody>
                </table>
            </div>

            <!-- MOBILE STACKED CARD VIEW (Khusus Layar HP / md:hidden) -->
            <div class="block md:hidden p-4 space-y-3">
                @forelse($presensis as $presensi)
                    <div class="bg-slate-50 dark:bg-slate-900/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-800/80 shadow-xs space-y-3">
                        <div class="flex justify-between items-start gap-2">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 shrink-0 rounded-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center font-bold text-slate-600 dark:text-slate-300 text-sm border border-slate-300 dark:border-slate-700">
                                    {{ substr($presensi->mahasiswa->nama, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-black text-slate-800 dark:text-white text-sm leading-tight truncate">{{ $presensi->mahasiswa->nama }}</h4>
                                    <p class="text-[10px] font-mono text-slate-400 truncate mt-0.5 font-bold uppercase tracking-wider">{{ $presensi->mahasiswa->nim }}</p>
                                </div>
                            </div>
                            <div class="shrink-0 flex flex-col items-end gap-1.5">
                                @php
                                    $colors = [
                                        'hadir' => 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800',
                                        'izin' => 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800',
                                        'sakit' => 'bg-blue-50 text-blue-600 border-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800',
                                        'alpha' => 'bg-rose-50 text-rose-600 border-rose-100 dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-black uppercase border {{ $colors[strtolower($presensi->status)] ?? 'bg-slate-50 text-slate-600' }}">
                                    {{ $presensi->status }}
                                </span>
                            </div>
                        </div>

                        <div class="text-[11px] text-slate-500 dark:text-slate-400 space-y-2 font-medium">
                            <div class="flex justify-between items-center bg-white dark:bg-slate-800 p-2.5 rounded-xl border border-slate-100 dark:border-slate-700">
                                <span class="font-bold text-slate-700 dark:text-slate-300"><i class="fas fa-clock text-blue-500 mr-1.5"></i> Waktu:</span>
                                @if($presensi->waktu_presensi)
                                    <span class="font-semibold text-slate-800 dark:text-slate-200">{{ date('H:i', strtotime($presensi->waktu_presensi)) }} <span class="text-[9px] uppercase font-black text-slate-400 ml-1">{{ date('d M Y', strtotime($presensi->waktu_presensi)) }}</span></span>
                                @else
                                    <span class="text-xs text-slate-300 dark:text-slate-600 italic font-medium">-</span>
                                @endif
                            </div>

                            <div class="flex flex-col gap-1.5 bg-white dark:bg-slate-800 p-2.5 rounded-xl border border-slate-100 dark:border-slate-700">
                                <span class="font-bold text-slate-700 dark:text-slate-300"><i class="fas fa-satellite text-indigo-500 mr-1.5"></i> GPS Coord:</span>
                                @if($presensi->latitude && $presensi->longitude)
                                    <a href="https://www.google.com/maps?q={{ $presensi->latitude }},{{ $presensi->longitude }}" target="_blank" class="flex justify-between items-center bg-slate-50 dark:bg-slate-900 px-2 py-1.5 rounded-lg border dark:border-slate-700 hover:border-indigo-300 transition-colors">
                                        <code class="text-[10px] font-mono text-slate-600 dark:text-slate-300 truncate">
                                            {{ $presensi->latitude }}, {{ $presensi->longitude }}
                                        </code>
                                        <i class="fas fa-external-link-alt text-[9px] text-indigo-400"></i>
                                    </a>
                                @else
                                    <span class="text-[10px] text-slate-300 dark:text-slate-600 italic">Tidak Ada GPS</span>
                                @endif
                            </div>
                        </div>

                        <div class="pt-2 border-t border-slate-200/50 dark:border-slate-700/50 flex justify-end gap-1.5">
                            <button type="button" 
                                class="btn-edit-presensi px-3 py-1.5 bg-blue-50 text-blue-600 border border-blue-100 rounded-xl text-[10px] font-black uppercase tracking-wider flex items-center gap-1"
                                data-id="{{ $presensi->id }}"
                                data-nama="{{ $presensi->mahasiswa->nama }}"
                                data-status="{{ strtolower($presensi->status) }}"
                                data-mhs-id="{{ $presensi->mahasiswa_id }}"
                                data-pertemuan-id="{{ $presensi->pertemuan_id }}">
                                <i class="fas fa-edit"></i> Koreksi
                            </button>
                            
                            @if(!str_contains($presensi->id, 'alpha-'))
                                <button type="button" 
                                    class="btn-delete-presensi px-3 py-1.5 bg-red-50 text-red-600 border border-red-100 rounded-xl text-[10px] font-black uppercase tracking-wider flex items-center gap-1"
                                    data-id="{{ $presensi->id }}"
                                    data-nama="{{ $presensi->mahasiswa->nama }}">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 italic">Data presensi tidak ditemukan!</div>
                @endforelse
            </div>
            @if($presensis->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
                    {{ $presensis->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- MASTER MODAL EDIT (HANYA SATU BOX UNTUK SEMUA BARIS DATA COK) --}}
    {{-- ========================================== --}}
    <div class="modal" id="modal-edit-master" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
            <div class="modal__container w-full max-w-sm bg-white dark:bg-slate-800 rounded-4xl p-8 shadow-2xl" role="dialog" @click.stop>
                <header class="mb-6 text-center">
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">Koreksi Presensi</h2>
                    <p id="modal-edit-nama" class="text-[10px] text-slate-400 uppercase font-black tracking-widest mt-1"></p>
                </header>

                <form id="form-edit-master" action="" method="POST">
                    @csrf @method('PUT')
                    
                    {{-- Input flag tambahan jika datanya aslinya Alpha Dummy --}}
                    <input type="hidden" name="mahasiswa_id" id="input-mhs-id">
                    <input type="hidden" name="pertemuan_id" id="input-pertemuan-id">

                    <div class="space-y-4 text-left">
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Pilih Status Baru</label>
                        <select name="status" id="modal-edit-status-select"
                            class="w-full px-4 py-3 rounded-xl border dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 outline-none focus:ring-2 focus:ring-blue-500/50 font-bold text-xs">
                            <option value="hadir">HADIR</option>
                            <option value="izin">IZIN</option>
                            <option value="sakit">SAKIT</option>
                            <option value="alpha">ALPHA</option>
                        </select>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <button type="button" data-micromodal-close class="flex-1 py-3 bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 rounded-xl font-bold transition-all text-xs">Batal</button>
                        <button type="submit" class="flex-1 py-3 bg-blue-600 text-white rounded-xl font-bold shadow-lg shadow-blue-200 dark:shadow-none text-xs">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- MASTER MODAL DELETE --}}
    {{-- ========================================== --}}
    <div class="modal" id="modal-delete-master" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
            <div class="modal__container w-full max-w-sm bg-white dark:bg-slate-800 rounded-4xl p-8 shadow-2xl" role="dialog" @click.stop>
                <div class="text-center">
                    <div class="w-16 h-16 bg-rose-50 dark:bg-rose-900/30 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-trash-alt text-2xl"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">Hapus Data?</h2>
                    <p class="text-sm text-slate-500 mt-2">Data presensi <strong id="modal-delete-nama" class="text-slate-800 dark:text-white"></strong> akan dihapus permanen.</p>
                </div>

                <form id="form-delete-master" action="" method="POST" class="mt-8 flex gap-3">
                    @csrf @method('DELETE')
                    <button type="button" data-micromodal-close class="flex-1 py-3 bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 rounded-xl font-bold text-xs">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-rose-600 text-white rounded-xl font-bold text-xs">Hapus</button>
                </form>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT INJECTOR MODAL --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Logic Handler untuk Modal Edit
            const editButtons = document.querySelectorAll('.btn-edit-presensi');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');
                    const status = this.getAttribute('data-status');
                    const mhsId = this.getAttribute('data-mhs-id');
                    const pertemuanId = this.getAttribute('data-pertemuan-id');

                    // Set teks nama & opsi terpilih
                    document.getElementById('modal-edit-nama').innerText = nama;
                    document.getElementById('modal-edit-status-select').value = status;
                    document.getElementById('input-mhs-id').value = mhsId;
                    document.getElementById('input-pertemuan-id').value = pertemuanId;

                    // Set URL Action Form Dinamis
                    const form = document.getElementById('form-edit-master');
                    form.action = `/admin/presensi/${id}`;

                    // Tampilkan modal pake MicroModal
                    MicroModal.show('modal-edit-master');
                });
            });

            // Logic Handler untuk Modal Delete
            const deleteButtons = document.querySelectorAll('.btn-delete-presensi');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');

                    document.getElementById('modal-delete-nama').innerText = nama;

                    const form = document.getElementById('form-delete-master');
                    form.action = `/admin/presensi/${id}`;

                    MicroModal.show('modal-delete-master');
                });
            });
        });
    </script>
@endsection