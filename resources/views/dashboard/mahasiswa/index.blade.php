@extends('layouts.app')

@section('content')
  <div class="space-y-6 p-4 mx-auto">

    <!-- 🌟 WELCOME BANNER & PROFILE QUICK VIEW (SaaS Style) -->
    <div
      class="relative overflow-hidden bg-gradient-to-r from-primary-600 to-indigo-700 text-white rounded-2xl p-6 shadow-lg">
      <div class="absolute right-0 bottom-0 opacity-10 translate-x-10 translate-y-10">
        <i class="fas fa-graduation-cap text-9xl"></i>
      </div>
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
        <div>
          <span
            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-500/30 text-primary-100 border border-primary-400/20 uppercase tracking-wider mb-2">
            Panel Mahasiswa
          </span>
          <h1 class="text-2xl md:text-3xl font-bold tracking-tight">Selamat Datang, {{ $mahasiswa->nama }}!</h1>
          <p class="text-primary-100/80 text-sm mt-1">Pantau kehadiran dan lakukan presensi kelas dengan cepat dan
            disiplin.</p>
        </div>

        <!-- Metadata Grid (Info Login, Gol, Smester) -->
        <div
          class="grid grid-cols-2 sm:grid-cols-3 gap-3 bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/10 text-xs">
          <div>
            <span class="block text-white/60">NIM</span>
            <span class="font-semibold text-sm">{{ $mahasiswa->nim }}</span>
          </div>
          <div>
            <span class="block text-white/60">Golongan</span>
            <span class="font-semibold text-sm">{{ $mahasiswa->nama_golongan }}</span>
          </div>
          <div>
            <span class="block text-white/60">Semester</span>
            <span class="font-semibold text-sm text-yellow-300">{{ $mahasiswa->nama_semester }}</span>
          </div>
          <div
            class="col-span-2 sm:col-span-3 border-t border-white/10 pt-2 mt-1 flex justify-between items-center text-[11px] text-white/80">
            <span><i class="fas fa-user-shield mr-1"></i> Angkatan: <span
                class="text-green-300 font-medium">{{ $mahasiswa->angkatan }}</span></span>
            <span><i class="fas fa-clock mr-1"></i> {{ date('d M Y') }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- 📊 SAAS STATS CARDS GRID -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
      <!-- Card Persentase -->
      <div
        class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-100 dark:border-slate-700/50 shadow-sm flex items-center justify-between col-span-2 lg:col-span-1">
        <div>
          <span class="text-xs text-slate-400 dark:text-slate-500 font-medium uppercase">Total Kehadiran</span>
          <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mt-1">{{ $persentaseKehadiran }}%</h3>
          <span class="text-[11px] text-slate-400">Dari {{ $totalPertemuan }} pertemuan terpantau</span>
        </div>
        <div class="p-3 bg-primary-50 dark:bg-primary-950/40 text-primary-600 dark:text-primary-400 rounded-lg">
          <i class="fas fa-chart-line text-xl"></i>
        </div>
      </div>

      <!-- Card Hadir -->
      <div
        class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-100 dark:border-slate-700/50 shadow-sm flex items-center justify-between">
        <div>
          <span class="text-xs text-slate-400 dark:text-slate-500 font-medium uppercase">Hadir</span>
          <h3 class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">{{ $kehadiran->hadir ?? 0 }}</h3>
          <span class="text-[11px] text-slate-400">Pertemuan</span>
        </div>
        <div class="p-3 bg-green-50 dark:bg-green-950/40 text-green-600 dark:text-green-400 rounded-lg">
          <i class="fas fa-check-circle text-xl"></i>
        </div>
      </div>

      <!-- Card Sakit -->
      <div
        class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-100 dark:border-slate-700/50 shadow-sm flex items-center justify-between">
        <div>
          <span class="text-xs text-slate-400 dark:text-slate-500 font-medium uppercase">Sakit</span>
          <h3 class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">{{ $kehadiran->sakit ?? 0 }}</h3>
          <span class="text-[11px] text-slate-400">Surat/Bukti Medis</span>
        </div>
        <div class="p-3 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 rounded-lg">
          <i class="fas fa-medkit text-xl"></i>
        </div>
      </div>

      <!-- Card Izin -->
      <div
        class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-100 dark:border-slate-700/50 shadow-sm flex items-center justify-between">
        <div>
          <span class="text-xs text-slate-400 dark:text-slate-500 font-medium uppercase">Izin</span>
          <h3 class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-1">{{ $kehadiran->izin ?? 0 }}</h3>
          <span class="text-[11px] text-slate-400">Keperluan Sah</span>
        </div>
        <div class="p-3 bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 rounded-lg">
          <i class="fas fa-envelope text-xl"></i>
        </div>
      </div>

      <!-- Card Alpha -->
      <div
        class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-100 dark:border-slate-700/50 shadow-sm flex items-center justify-between">
        <div>
          <span class="text-xs text-slate-400 dark:text-slate-500 font-medium uppercase">Alpha</span>
          <h3 class="text-2xl font-bold text-rose-600 dark:text-rose-400 mt-1">{{ $kehadiran->alpha ?? 0 }}</h3>
          <span class="text-[11px] text-rose-500 font-medium">Batas Maksimal Terbuka</span>
        </div>
        <div class="p-3 bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 rounded-lg">
          <i class="fas fa-times-circle text-xl"></i>
        </div>
      </div>
    </div>

    <!-- 🗓️ JADWAL KULIAH HARI INI & ACTION ABSEN -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

      <!-- Kolom Kiri & Tengah: Jadwal Hari Ini -->
      <div class="lg:col-span-2 space-y-4">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">Jadwal Kuliah Hari Ini</h2>
            <p class="text-xs text-slate-400">Daftar kelas perkuliahan aktif yang terjadwal</p>
          </div>
          <span
            class="text-xs px-2.5 py-1 font-semibold rounded-md bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
            {{ count($jadwalHariIni) }} Kelas
          </span>
        </div>

        @if($jadwalHariIni->isEmpty())
          <div
            class="bg-white dark:bg-slate-800 rounded-xl p-8 border border-dashed border-slate-200 dark:border-slate-700 text-center">
            <div
              class="w-16 h-16 bg-slate-50 dark:bg-slate-700/30 rounded-full flex items-center justify-center mx-auto text-slate-400 mb-3">
              <i class="fas fa-calendar-check text-2xl"></i>
            </div>
            <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300">Tidak Ada Jadwal Hari Ini</h4>
            <p class="text-xs text-slate-400 mt-1">Santai dulu, atau manfaatkan waktu untuk mengecek riwayat tugas.</p>
          </div>
        @else
          <div class="space-y-3">
            @foreach($jadwalHariIni as $jadwal)
              <div
                class="bg-white dark:bg-slate-800 rounded-xl p-4 border border-slate-100 dark:border-slate-700/50 shadow-sm hover:shadow-md transition-shadow duration-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                <!-- Detail Kiri -->
                <div class="flex items-start gap-3">
                  <div
                    class="p-3 rounded-xl bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 shrink-0 text-center min-w-[70px]">
                    <span
                      class="block text-xs font-bold uppercase tracking-tight text-primary-600 dark:text-primary-400">Jam</span>
                    <span class="block text-xs font-semibold mt-0.5">{{ date('H:i', strtotime($jadwal->jam_mulai)) }}</span>
                  </div>
                  <div>
                    <div class="flex items-center gap-2">
                      <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100">{{ $jadwal->nama_mk }} </h4>
                      <span class="text-slate-400">•</span>
                      <p class="text-[11px] text-primary-600 font-semibold mt-0.5"> Pertemuan Ke-{{ $jadwal->pertemuan_ke }}
                      </p>
                    </div>
                    <p class="text-xs text-slate-400 mt-0.5"><i class="fas fa-user-tie mr-1 text-[10px]"></i>
                      {{ $jadwal->nama_dosen }}</p>
                    <div class="flex items-center gap-3 mt-2 text-[11px]">
                      <span class="text-slate-500 dark:text-slate-400 font-medium"><i
                          class="fas fa-door-open mr-1 text-slate-400"></i> Ruang {{ $jadwal->ruangan }}</span>
                      <span class="text-slate-400">•</span>
                      <span class="text-slate-500 dark:text-slate-400 font-medium"><i
                          class="fas fa-layer-group mr-1 text-slate-400"></i> {{ $jadwal->sks }} SKS</span>
                    </div>
                  </div>
                </div>

                <!-- Tombol / Status Aksi Kanan -->
                <div
                  class="sm:text-right flex items-center sm:flex-col justify-between sm:justify-center gap-2 border-t sm:border-t-0 pt-3 sm:pt-0 border-slate-100 dark:border-slate-700">
                  <span class="block text-[10px] text-slate-400 uppercase font-semibold sm:hidden">Status Presensi</span>

                  @if($jadwal->status_absen_mhs)
                    <!-- Jika Sudah Absen -->
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                                                    @if($jadwal->status_absen_mhs == 'Hadir') bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400
                                                    @elseif(in_array($jadwal->status_absen_mhs, ['Sakit', 'Izin'])) bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-400
                                                    @else @endif">
                      <i class="fas fa-check-circle mr-1 text-[10px]"></i> {{ $jadwal->status_absen_mhs }}
                    </span>
                  @else
                    <!-- Jika Belum Absen -->
                    @if($jadwal->status_buka_absen == 'dibuka')
                      <!-- Absen Dibuka Dosen (Sesuai ENUM database lu) -->
                      <a href="{{ route('mahasiswa.presensi.show', $jadwal->pertemuan_id) }}"
                        class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold shadow-sm shadow-primary-200 dark:shadow-none transition-colors duration-150">
                        <i class="fas fa-fingerprint mr-1.5 text-sm"></i> Isi Presensi
                      </a>
                    @else
                      <!-- Absen Belum Dibuka / Ditutup -->
                      <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-500">
                        <i class="fas fa-lock mr-1 text-[10px]"></i> Belum Dibuka
                      </span>
                    @endif
                  @endif
                </div>

              </div>
            @endforeach
          </div>
        @endif
      </div>

      <!-- Kolom Kanan: Riwayat Presensi Terakhir -->
      <div class="space-y-4">
        <div>
          <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">Aktivitas Terakhir</h2>
          <p class="text-xs text-slate-400">Riwayat pengisian presensi kamu belakangan ini</p>
        </div>

        <div
          class="bg-white dark:bg-slate-800 rounded-xl p-4 border border-slate-100 dark:border-slate-700/50 shadow-sm space-y-3">
          @if($riwayatTerakhir->isEmpty())
            <p class="text-xs text-slate-400 text-center py-4">Belum ada riwayat aktivitas presensi.</p>
          @else
            <div class="relative pl-4 border-l-2 border-slate-100 dark:border-slate-700 space-y-4">
              @foreach($riwayatTerakhir as $riwayat)
                <div class="relative">

                  @if($riwayat->status == 'hadir')
                    <span
                      class="absolute -left-[21px] mt-1 w-2.5 h-2.5 rounded-full ring-4 ring-white dark:ring-slate-800 bg-green-500"></span>
                    <div>
                      <h5 class="text-xs font-bold text-slate-700 dark:text-slate-300 line-clamp-1">{{ $riwayat->nama_mk }}</h5>
                      <div class="flex items-center justify-between text-[11px] text-slate-400 mt-0.5">
                        <span>{{ date('d M Y', strtotime($riwayat->tanggal)) }}</span>
                        <span class="font-semibold text-green-600 dark:text-green-400">{{ ucwords($riwayat->status) }}</span>
                      </div>
                    </div>

                  @elseif(in_array($riwayat->status, ['sakit', 'izin']))
                    <span
                      class="absolute -left-[21px] mt-1 w-2.5 h-2.5 rounded-full ring-4 ring-white dark:ring-slate-800 bg-amber-500"></span>
                    <div>
                      <h5 class="text-xs font-bold text-slate-700 dark:text-slate-300 line-clamp-1">{{ $riwayat->nama_mk }}</h5>
                      <div class="flex items-center justify-between text-[11px] text-slate-400 mt-0.5">
                        <span>{{ date('d M Y', strtotime($riwayat->tanggal)) }}</span>
                        <span class="font-semibold text-amber-600 dark:text-amber-400">{{ ucwords($riwayat->status) }}</span>
                      </div>
                    </div>

                  @else
                    <span
                      class="absolute -left-[21px] mt-1 w-2.5 h-2.5 rounded-full ring-4 ring-white dark:ring-slate-800 bg-rose-500"></span>
                    <div>
                      <h5 class="text-xs font-bold text-slate-700 dark:text-slate-300 line-clamp-1">{{ $riwayat->nama_mk }}</h5>
                      <div class="flex items-center justify-between text-[11px] text-slate-400 mt-0.5">
                        <span>{{ date('d M Y', strtotime($riwayat->tanggal)) }}</span>
                        <span class="font-semibold text-rose-600 dark:text-rose-400">{{ ucwords($riwayat->status) }}</span>
                      </div>
                    </div>
                  @endif

                </div>
              @endforeach
            </div>
          @endif

          <div class="border-t border-slate-100 dark:border-slate-700 pt-3 mt-2">
            <a href="{{ route('mahasiswa.presensi.riwayat') }}"
              class="block text-center text-xs font-semibold text-primary-600 dark:text-primary-400 hover:underline">
              Lihat Semua Riwayat <i class="fas fa-arrow-right ml-1 text-[10px]"></i>
            </a>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection