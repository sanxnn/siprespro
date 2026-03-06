@extends('layouts.app')

@section('title', 'Admin Dashboard | siprespro')

@section('content')

  <!-- 🔔 Alert Banner (Opsional) -->
  @if(true) {{-- Nanti: session('info') --}}
    <div
      class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 mb-6 flex items-start gap-3">
      <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mt-0.5"></i>
      <div>
        <p class="text-sm font-medium text-blue-800 dark:text-blue-200">Semester Genap 2024/2025 sedang aktif</p>
        <p class="text-xs text-blue-600 dark:text-blue-300 mt-1">Periode: 1 Feb 2025 - 30 Jun 2025</p>
      </div>
    </div>
  @endif

  <!-- 📈 STATS CARDS -->
  <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

    <!-- Total Mahasiswa -->
    <div
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 hover:shadow-lg transition-shadow">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Total Mahasiswa</p>
          <p class="text-2xl font-bold mt-1">1,248</p>
          <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1 flex items-center gap-1">
            <i class="fas fa-arrow-up text-[10px]"></i> +12% dari bulan lalu
          </p>
        </div>
        <div
          class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center text-emerald-600 dark:text-emerald-400">
          <i class="fas fa-users"></i>
        </div>
      </div>
    </div>

    <!-- Total Dosen -->
    <div
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 hover:shadow-lg transition-shadow">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Total Dosen</p>
          <p class="text-2xl font-bold mt-1">86</p>
          <p class="text-xs text-slate-400 mt-1">Stabil</p>
        </div>
        <div
          class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center text-primary-600 dark:text-primary-400">
          <i class="fas fa-chalkboard-teacher"></i>
        </div>
      </div>
    </div>

    <!-- Presensi Hari Ini -->
    <div
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 hover:shadow-lg transition-shadow">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Presensi Hari Ini</p>
          <p class="text-2xl font-bold mt-1">342</p>
          <p class="text-xs text-amber-600 dark:text-amber-400 mt-1 flex items-center gap-1">
            <i class="fas fa-clock text-[10px]"></i> Update real-time
          </p>
        </div>
        <div
          class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center text-amber-600 dark:text-amber-400">
          <i class="fas fa-clipboard-check"></i>
        </div>
      </div>
    </div>

    <!-- Tingkat Kehadiran -->
    <div
      class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 hover:shadow-lg transition-shadow">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Kehadiran Hari Ini</p>
          <p class="text-2xl font-bold mt-1">94.2%</p>
          <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-1.5 mt-2">
            <div class="bg-primary-500 h-1.5 rounded-full" style="width: 94.2%"></div>
          </div>
        </div>
        <div
          class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center text-blue-600 dark:text-blue-400">
          <i class="fas fa-chart-pie"></i>
        </div>
      </div>
    </div>

  </div>

  <!-- 🗓️ SECONDARY STATS (Opsional Row) -->
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 text-center">
      <p class="text-xs text-slate-500 dark:text-slate-400">Mata Kuliah</p>
      <p class="text-xl font-bold mt-1">42</p>
    </div>
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 text-center">
      <p class="text-xs text-slate-500 dark:text-slate-400">Kelas Aktif</p>
      <p class="text-xl font-bold mt-1">28</p>
    </div>
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 text-center">
      <p class="text-xs text-slate-500 dark:text-slate-400">Golongan</p>
      <p class="text-xl font-bold mt-1">12</p>
    </div>
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4 text-center">
      <p class="text-xs text-slate-500 dark:text-slate-400">Lokasi Presensi</p>
      <p class="text-xl font-bold mt-1">8</p>
    </div>
  </div>

  <!-- 📊 CONTENT GRID -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- LEFT COLUMN: Recent Activities + Chart -->
    <div class="lg:col-span-2 space-y-6">

      <!-- 📋 Recent Presensi Activities -->
      <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden">
        <div class="p-5 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
          <h3 class="font-semibold text-lg">Aktivitas Presensi Terbaru</h3>
          <button class="text-primary-500 hover:text-primary-600 text-sm font-medium transition-colors">Lihat
            Semua</button>
        </div>
        <div class="divide-y divide-slate-200 dark:divide-slate-700 max-h-[380px] overflow-y-auto custom-scrollbar">

          <!-- Static Item 1 -->
          <div
            class="p-4 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
            <div class="flex items-center gap-3">
              <div
                class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center text-primary-600 dark:text-primary-400 font-semibold text-sm">
                A
              </div>
              <div>
                <p class="font-medium text-sm">Ahmad Fauzi</p>
                <p class="text-xs text-slate-500">220411100123 • IF-3A • PBO</p>
              </div>
            </div>
            <div class="text-right">
              <span
                class="px-2.5 py-1 rounded-full text-[10px] font-semibold uppercase tracking-wide bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400">
                hadir
              </span>
              <p class="text-[10px] text-slate-400 mt-1">2 menit lalu</p>
            </div>
          </div>

          <!-- Static Item 2 -->
          <div
            class="p-4 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
            <div class="flex items-center gap-3">
              <div
                class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center text-primary-600 dark:text-primary-400 font-semibold text-sm">
                S
              </div>
              <div>
                <p class="font-medium text-sm">Siti Nurhaliza</p>
                <p class="text-xs text-slate-500">220411100145 • IF-3B • Basis Data</p>
              </div>
            </div>
            <div class="text-right">
              <span
                class="px-2.5 py-1 rounded-full text-[10px] font-semibold uppercase tracking-wide bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400">
                izin
              </span>
              <p class="text-[10px] text-slate-400 mt-1">15 menit lalu</p>
            </div>
          </div>

          <!-- Static Item 3 -->
          <div
            class="p-4 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
            <div class="flex items-center gap-3">
              <div
                class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center text-primary-600 dark:text-primary-400 font-semibold text-sm">
                R
              </div>
              <div>
                <p class="font-medium text-sm">Rizky Pratama</p>
                <p class="text-xs text-slate-500">220411100167 • IF-3A • PBO</p>
              </div>
            </div>
            <div class="text-right">
              <span
                class="px-2.5 py-1 rounded-full text-[10px] font-semibold uppercase tracking-wide bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400">
                sakit
              </span>
              <p class="text-[10px] text-slate-400 mt-1">32 menit lalu</p>
            </div>
          </div>

          <!-- Static Item 4 -->
          <div
            class="p-4 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
            <div class="flex items-center gap-3">
              <div
                class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center text-primary-600 dark:text-primary-400 font-semibold text-sm">
                D
              </div>
              <div>
                <p class="font-medium text-sm">Dewi Lestari</p>
                <p class="text-xs text-slate-500">220411100189 • IF-3C • Jaringan</p>
              </div>
            </div>
            <div class="text-right">
              <span
                class="px-2.5 py-1 rounded-full text-[10px] font-semibold uppercase tracking-wide bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400">
                hadir
              </span>
              <p class="text-[10px] text-slate-400 mt-1">1 jam lalu</p>
            </div>
          </div>

          <!-- Static Item 5 -->
          <div
            class="p-4 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
            <div class="flex items-center gap-3">
              <div
                class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center text-primary-600 dark:text-primary-400 font-semibold text-sm">
                M
              </div>
              <div>
                <p class="font-medium text-sm">Muhammad Hafiz</p>
                <p class="text-xs text-slate-500">220411100201 • IF-3B • Basis Data</p>
              </div>
            </div>
            <div class="text-right">
              <span
                class="px-2.5 py-1 rounded-full text-[10px] font-semibold uppercase tracking-wide bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                alpha
              </span>
              <p class="text-[10px] text-slate-400 mt-1">2 jam lalu</p>
            </div>
          </div>

        </div>
      </div>

      <!-- 📈 Attendance Chart Placeholder -->
      <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5">
        <div class="flex justify-between items-center mb-4">
          <h3 class="font-semibold text-lg">Tren Kehadiran 7 Hari Terakhir</h3>
          <select
            class="text-xs border border-slate-200 dark:border-slate-600 rounded-lg px-2 py-1 bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
            <option>Minggu Ini</option>
            <option>Bulan Ini</option>
            <option>Semester Ini</option>
          </select>
        </div>
        <!-- Placeholder Chart - Nanti ganti dengan Chart.js / ApexCharts -->
        <div class="h-48 bg-slate-50 dark:bg-slate-700/30 rounded-xl flex items-end justify-between px-4 pb-4 gap-2">
          <div class="w-full bg-primary-200 dark:bg-primary-800 rounded-t" style="height: 70%"></div>
          <div class="w-full bg-primary-200 dark:bg-primary-800 rounded-t" style="height: 85%"></div>
          <div class="w-full bg-primary-200 dark:bg-primary-800 rounded-t" style="height: 60%"></div>
          <div class="w-full bg-primary-200 dark:bg-primary-800 rounded-t" style="height: 90%"></div>
          <div class="w-full bg-primary-200 dark:bg-primary-800 rounded-t" style="height: 75%"></div>
          <div class="w-full bg-primary-200 dark:bg-primary-800 rounded-t" style="height: 95%"></div>
          <div class="w-full bg-primary-200 dark:bg-primary-800 rounded-t" style="height: 88%"></div>
        </div>
        <div class="flex justify-between mt-2 text-[10px] text-slate-400 px-2">
          <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span>
        </div>
      </div>

    </div>

    <!-- RIGHT COLUMN: Schedule + Quick Actions -->
    <div class="space-y-6">

      <!-- 🗓️ Jadwal Hari Ini (Jumat) -->
      <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden">
        <div class="p-5 border-b border-slate-200 dark:border-slate-700">
          <h3 class="font-semibold text-lg">Jadwal Hari Ini</h3>
          <p class="text-xs text-slate-500 dark:text-slate-400">Jumat, 14 Maret 2025</p>
        </div>
        <div class="divide-y divide-slate-200 dark:divide-slate-700 max-h-80 overflow-y-auto custom-scrollbar">

          <!-- Schedule Item 1 -->
          <div class="p-4 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
            <div class="flex items-start justify-between">
              <div>
                <p class="font-medium text-sm">Pemrograman Berbasis Objek</p>
                <p class="text-xs text-slate-500 mt-0.5">IF-3A • R.304 • 08:00 - 10:30</p>
                <p class="text-xs text-primary-600 dark:text-primary-400 mt-1 flex items-center gap-1">
                  <i class="fas fa-map-marker-alt text-[10px]"></i> Gedung D, Lantai 3
                </p>
              </div>
              <span
                class="px-2 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-[10px] font-semibold rounded-full">
                Berlangsung
              </span>
            </div>
          </div>

          <!-- Schedule Item 2 -->
          <div class="p-4 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
            <div class="flex items-start justify-between">
              <div>
                <p class="font-medium text-sm">Basis Data Lanjut</p>
                <p class="text-xs text-slate-500 mt-0.5">IF-3B • R.205 • 10:45 - 13:15</p>
                <p class="text-xs text-primary-600 dark:text-primary-400 mt-1 flex items-center gap-1">
                  <i class="fas fa-map-marker-alt text-[10px]"></i> Gedung C, Lantai 2
                </p>
              </div>
              <span
                class="px-2 py-1 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-[10px] font-semibold rounded-full">
                Berikutnya
              </span>
            </div>
          </div>

          <!-- Schedule Item 3 -->
          <div class="p-4 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
            <div class="flex items-start justify-between">
              <div>
                <p class="font-medium text-sm">Jaringan Komputer</p>
                <p class="text-xs text-slate-500 mt-0.5">IF-3C • Lab.2 • 13:30 - 16:00</p>
                <p class="text-xs text-primary-600 dark:text-primary-400 mt-1 flex items-center gap-1">
                  <i class="fas fa-map-marker-alt text-[10px]"></i> Gedung E, Lab Komputer
                </p>
              </div>
              <span
                class="px-2 py-1 bg-slate-100 dark:bg-slate-700 text-slate-400 text-[10px] font-semibold rounded-full">
                13:30
              </span>
            </div>
          </div>

        </div>
        <div class="p-3 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30">
          <a href="#" class="text-xs text-primary-600 dark:text-primary-400 hover:underline font-medium">Lihat Semua
            Jadwal →</a>
        </div>
      </div>

      <!-- ⚡ Quick Actions -->
      <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl">
        <div class="p-5 border-b border-slate-200 dark:border-slate-700">
          <h3 class="font-semibold text-lg">Aksi Cepat</h3>
        </div>
        <div class="p-4 space-y-2">
          <a href="#"
            class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
            <div
              class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center text-primary-600 group-hover:bg-primary-500 group-hover:text-white transition-colors">
              <i class="fas fa-users text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
              <p class="font-medium text-sm truncate">Kelola Users</p>
              <p class="text-xs text-slate-500 truncate">Tambah, edit, hapus users</p>
            </div>
            <i class="fas fa-chevron-right text-xs text-slate-400 group-hover:text-primary-500 transition-colors"></i>
          </a>

          <a href="#"
            class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
            <div
              class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center text-primary-600 group-hover:bg-primary-500 group-hover:text-white transition-colors">
              <i class="fas fa-calendar text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
              <p class="font-medium text-sm truncate">Kelola Semester</p>
              <p class="text-xs text-slate-500 truncate">Atur tahun ajaran</p>
            </div>
            <i class="fas fa-chevron-right text-xs text-slate-400 group-hover:text-primary-500 transition-colors"></i>
          </a>

          <a href="#"
            class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
            <div
              class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center text-primary-600 group-hover:bg-primary-500 group-hover:text-white transition-colors">
              <i class="fas fa-book text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
              <p class="font-medium text-sm truncate">Kelola Mata Kuliah</p>
              <p class="text-xs text-slate-500 truncate">Input data MK</p>
            </div>
            <i class="fas fa-chevron-right text-xs text-slate-400 group-hover:text-primary-500 transition-colors"></i>
          </a>

          <a href="#"
            class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
            <div
              class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center text-primary-600 group-hover:bg-primary-500 group-hover:text-white transition-colors">
              <i class="fas fa-chart-bar text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
              <p class="font-medium text-sm truncate">Laporan Presensi</p>
              <p class="text-xs text-slate-500 truncate">Export data per periode</p>
            </div>
            <i class="fas fa-chevron-right text-xs text-slate-400 group-hover:text-primary-500 transition-colors"></i>
          </a>
        </div>
      </div>

      <!-- 🎓 Info Semester Aktif -->
      <div class="bg-primary-600 dark:bg-primary-800 rounded-2xl p-5 text-white">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-xs opacity-80 uppercase tracking-wide">Semester Aktif</p>
            <p class="text-xl font-bold mt-1">Genap 2024/2025</p>
            <p class="text-sm opacity-90 mt-2">1 Feb 2025 - 30 Jun 2025</p>
            <div class="flex gap-2 mt-4">
              <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-medium">12 Golongan</span>
              <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-medium">28 Kelas</span>
            </div>
          </div>
          <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
            <i class="fas fa-graduation-cap text-xl"></i>
          </div>
        </div>
      </div>

    </div>

  </div>

@endsection