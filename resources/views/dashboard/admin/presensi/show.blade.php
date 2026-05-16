@extends('layouts.app')
@section('title', 'Detail Presensi • SIPRESPRO')
@section('content')
    <div class="space-y-6 mx-auto p-2 sm:p-4 text-left">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 text-left mb-6">
            <div>
                <a href="{{ route('admin.presensi.index') }}" class="inline-flex items-center text-xs font-bold text-slate-500 hover:text-primary-600 transition-colors mb-2">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Rekap
                </a>
                <h1 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Detail Presensi Mahasiswa</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 font-medium mt-1">
                    {{ $pertemuan->kelasPerkuliahan->mataKuliah->nama }} - Kelas {{ $pertemuan->kelasPerkuliahan->nama_kelas }} (Pertemuan {{ $pertemuan->pertemuan_ke }})
                </p>
            </div>
            
            <div class="bg-white dark:bg-slate-800 px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-4">
                <div class="text-center">
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-0.5">Hadir</span>
                    <span class="text-sm font-bold text-emerald-600">{{ $data->where('status', 'hadir')->count() }}</span>
                </div>
                <div class="w-px h-8 bg-slate-200 dark:bg-slate-700"></div>
                <div class="text-center">
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-0.5">Sakit</span>
                    <span class="text-sm font-bold text-blue-600">{{ $data->where('status', 'sakit')->count() }}</span>
                </div>
                <div class="w-px h-8 bg-slate-200 dark:bg-slate-700"></div>
                <div class="text-center">
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-0.5">Izin</span>
                    <span class="text-sm font-bold text-amber-600">{{ $data->where('status', 'izin')->count() }}</span>
                </div>
                <div class="w-px h-8 bg-slate-200 dark:bg-slate-700"></div>
                <div class="text-center">
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-0.5">Alfa</span>
                    <span class="text-sm font-bold text-rose-600">{{ $data->where('status', 'alfa')->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Table Data -->
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl overflow-hidden shadow-xs transition-colors duration-300">
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 whitespace-nowrap">Mahasiswa</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 text-center whitespace-nowrap">Waktu Presensi</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 text-center whitespace-nowrap">Status</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 whitespace-nowrap">Koordinat GPS</th>
                            <th class="px-6 py-4 text-right text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-left">
                        @forelse($data as $presensi)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-all group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-900 flex items-center justify-center font-black text-slate-500 dark:text-slate-400 text-xs border border-slate-200 dark:border-slate-700">
                                            {{ substr($presensi->mahasiswa->nama, 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-black text-slate-800 dark:text-slate-100">{{ $presensi->mahasiswa->nama }}</span>
                                            <span class="text-[10px] text-slate-400 font-mono font-bold">{{ $presensi->mahasiswa->nim }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($presensi->waktu_presensi)
                                        <div class="flex flex-col items-center">
                                            <span class="text-xs font-black text-slate-700 dark:text-slate-200">{{ date('H:i', strtotime($presensi->waktu_presensi)) }}</span>
                                            <span class="text-[9px] text-slate-400 uppercase font-bold">{{ date('d M Y', strtotime($presensi->waktu_presensi)) }}</span>
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
                                            'alfa' => 'bg-rose-50 text-rose-600 border-rose-100 dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800',
                                            'alpha' => 'bg-rose-50 text-rose-600 border-rose-100 dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase border {{ $colors[strtolower($presensi->status)] ?? 'bg-slate-50 text-slate-600' }}">
                                        {{ $presensi->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($presensi->latitude && $presensi->longitude)
                                        <a href="https://www.google.com/maps?q={{ $presensi->latitude }},{{ $presensi->longitude }}" target="_blank" class="flex items-center gap-2 hover:text-primary-600 transition-colors">
                                            <i class="fas fa-location-dot text-rose-500 text-xs"></i>
                                            <span class="text-[10px] font-mono font-bold text-slate-500 dark:text-slate-400 truncate max-w-[120px]">
                                                {{ $presensi->latitude }}, {{ $presensi->longitude }}
                                            </span>
                                        </a>
                                    @else
                                        <span class="text-[10px] text-slate-300 dark:text-slate-600 font-medium italic">Tidak Ada GPS</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button type="button" 
                                            class="btn-edit-presensi w-8 h-8 flex items-center justify-center text-blue-600 bg-white border border-slate-200 dark:border-slate-700 dark:bg-slate-800 hover:bg-blue-50 hover:border-blue-200 dark:hover:bg-blue-900/30 rounded-xl transition-all shadow-xs"
                                            data-id="{{ $presensi->id }}"
                                            data-nama="{{ $presensi->mahasiswa->nama }}"
                                            data-status="{{ strtolower($presensi->status) }}"
                                            data-mhs-id="{{ $presensi->mahasiswa_id }}"
                                            data-pertemuan-id="{{ $presensi->pertemuan_id }}">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                        @if(!str_contains($presensi->id, 'alpha-'))
                                            <button type="button" 
                                                class="btn-delete-presensi w-8 h-8 flex items-center justify-center text-rose-600 bg-white border border-slate-200 dark:border-slate-700 dark:bg-slate-800 hover:bg-rose-50 hover:border-rose-200 dark:hover:bg-rose-900/30 rounded-xl transition-all shadow-xs"
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
                                <td colspan="5" class="px-6 py-20 text-center text-slate-400 font-medium italic">Data mahasiswa tidak ditemukan!</td>
                            </tr>
                        @endempty
                    </tbody>
                </table>
            </div>

            <!-- Mobile View -->
            <div class="block md:hidden p-4 space-y-3">
                @forelse($data as $presensi)
                    <div class="bg-slate-50 dark:bg-slate-900/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-800/80 shadow-xs space-y-3">
                        <div class="flex justify-between items-start gap-2">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 shrink-0 rounded-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center font-black text-slate-600 dark:text-slate-300 text-sm border border-slate-300 dark:border-slate-700">
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
                                        'alfa' => 'bg-rose-50 text-rose-600 border-rose-100 dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800',
                                        'alpha' => 'bg-rose-50 text-rose-600 border-rose-100 dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-black uppercase border {{ $colors[strtolower($presensi->status)] ?? 'bg-slate-50 text-slate-600' }}">
                                    {{ $presensi->status }}
                                </span>
                            </div>
                        </div>
                        <div class="text-[11px] text-slate-500 dark:text-slate-400 space-y-2 font-medium">
                            <div class="flex justify-between items-center bg-white dark:bg-slate-800 p-2.5 rounded-xl border border-slate-100 dark:border-slate-700 shadow-xs">
                                <span class="font-bold text-slate-700 dark:text-slate-300"><i class="fas fa-clock text-primary-500 mr-1.5"></i> Waktu:</span>
                                @if($presensi->waktu_presensi)
                                    <span class="font-black text-slate-800 dark:text-slate-200">{{ date('H:i', strtotime($presensi->waktu_presensi)) }} <span class="text-[9px] uppercase font-bold text-slate-400 ml-1">{{ date('d M Y', strtotime($presensi->waktu_presensi)) }}</span></span>
                                @else
                                    <span class="text-[10px] text-slate-300 dark:text-slate-600 italic font-medium">-</span>
                                @endif
                            </div>
                            <div class="flex flex-col gap-1.5 bg-white dark:bg-slate-800 p-2.5 rounded-xl border border-slate-100 dark:border-slate-700 shadow-xs">
                                <span class="font-bold text-slate-700 dark:text-slate-300"><i class="fas fa-location-dot text-rose-500 mr-1.5"></i> GPS Coord:</span>
                                @if($presensi->latitude && $presensi->longitude)
                                    <a href="https://www.google.com/maps?q={{ $presensi->latitude }},{{ $presensi->longitude }}" target="_blank" class="flex justify-between items-center bg-slate-50 dark:bg-slate-900 px-2 py-1.5 rounded-lg border border-slate-100 dark:border-slate-700 hover:border-primary-300 transition-colors">
                                        <code class="text-[10px] font-mono font-bold text-slate-600 dark:text-slate-300 truncate">
                                            {{ $presensi->latitude }}, {{ $presensi->longitude }}
                                        </code>
                                        <i class="fas fa-external-link-alt text-[9px] text-primary-400"></i>
                                    </a>
                                @else
                                    <span class="text-[10px] text-slate-300 dark:text-slate-600 italic font-medium">Tidak Ada Data Lokasi</span>
                                @endif
                            </div>
                        </div>
                        <div class="pt-2 border-t border-slate-200/50 dark:border-slate-700/50 flex justify-end gap-1.5">
                            <button type="button" 
                                class="btn-edit-presensi px-3 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800 rounded-xl text-[10px] font-black uppercase tracking-wider flex items-center gap-1 shadow-xs"
                                data-id="{{ $presensi->id }}"
                                data-nama="{{ $presensi->mahasiswa->nama }}"
                                data-status="{{ strtolower($presensi->status) }}"
                                data-mhs-id="{{ $presensi->mahasiswa_id }}"
                                data-pertemuan-id="{{ $presensi->pertemuan_id }}">
                                <i class="fas fa-edit"></i> Koreksi
                            </button>
                            @if(!str_contains($presensi->id, 'alpha-'))
                                <button type="button" 
                                    class="btn-delete-presensi px-3 py-1.5 bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 border border-rose-100 dark:border-rose-800 rounded-xl text-[10px] font-black uppercase tracking-wider flex items-center gap-1 shadow-xs"
                                    data-id="{{ $presensi->id }}"
                                    data-nama="{{ $presensi->mahasiswa->nama }}">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 italic">Data mahasiswa tidak ditemukan!</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modals (Copied from presensi.blade.php for interaction) -->
    <div class="modal" id="modal-edit-master" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
            <div class="modal__container w-full max-w-sm bg-white dark:bg-slate-800 rounded-4xl p-8 shadow-2xl" role="dialog" @click.stop>
                <header class="mb-6 text-center">
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">Koreksi Presensi</h2>
                    <p id="modal-edit-nama" class="text-[10px] text-slate-400 uppercase font-black tracking-widest mt-1"></p>
                </header>
                <form id="form-edit-master" action="" method="POST">
                    @csrf @method('PUT')
                    <input type="hidden" name="mahasiswa_id" id="input-mhs-id">
                    <input type="hidden" name="pertemuan_id" id="input-pertemuan-id">
                    <div class="space-y-4 text-left">
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Pilih Status Baru</label>
                        <select name="status" id="modal-edit-status-select"
                            class="w-full px-4 py-3 rounded-xl border dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 outline-none focus:ring-2 focus:ring-primary-500/50 font-bold text-xs">
                            <option value="hadir">HADIR</option>
                            <option value="izin">IZIN</option>
                            <option value="sakit">SAKIT</option>
                            <option value="alpha">ALFA</option>
                        </select>
                    </div>
                    <div class="mt-8 flex gap-3">
                        <button type="button" data-micromodal-close class="flex-1 py-3 bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 rounded-xl font-bold transition-all text-xs">Batal</button>
                        <button type="submit" class="flex-1 py-3 bg-primary-600 text-white rounded-xl font-bold shadow-lg shadow-primary-200 dark:shadow-none text-xs">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.btn-edit-presensi');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');
                    let status = this.getAttribute('data-status');
                    if (status === 'alfa') status = 'alpha'; 
                    const mhsId = this.getAttribute('data-mhs-id');
                    const pertemuanId = this.getAttribute('data-pertemuan-id');
                    
                    document.getElementById('modal-edit-nama').innerText = nama;
                    document.getElementById('modal-edit-status-select').value = status;
                    document.getElementById('input-mhs-id').value = mhsId;
                    document.getElementById('input-pertemuan-id').value = pertemuanId;
                    
                    const form = document.getElementById('form-edit-master');
                    form.action = `/admin/presensi/${id}`;
                    
                    MicroModal.show('modal-edit-master');
                });
            });

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
