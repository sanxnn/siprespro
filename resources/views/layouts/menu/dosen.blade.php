<!-- Section Header -->
<div class="pt-4 pb-2 px-2 mt-2">
  <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider px-2"
    x-show="sidebarOpen || !sidebarCollapse" x-transition.opacity.duration.200ms>Menu Dosen</span>
  <div x-show="sidebarCollapse && !sidebarOpen" class="border-t border-slate-200 dark:border-slate-700 mx-2 mt-2">
  </div>
</div>

<!-- 🏫 Kelas Saya -->
<div>
  <button @click="setActiveMenu('dosen_kelas')"
    class="w-full group flex items-center justify-between gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-primary-600 dark:hover:text-primary-400">
    <div class="flex items-center gap-3">
      <i class="fas fa-chalkboard-user w-5 text-center shrink-0"></i>
      <span class="whitespace-nowrap transition-opacity duration-200 text-left" x-show="sidebarOpen || !sidebarCollapse"
        x-transition.opacity.duration.200ms>Kelas Saya</span>
    </div>
    <i class="fas fa-chevron-down text-xs transition-transform duration-200" x-show="sidebarOpen || !sidebarCollapse"
      :class="activeMenu === 'dosen_kelas' ? 'rotate-180' : ''"></i>
  </button>

  <!-- Submenu -->
  <div x-show="activeMenu === 'dosen_kelas' && (sidebarOpen || !sidebarCollapse)" x-collapse class="overflow-hidden">
    <div class="ml-4 pl-4 border-l-2 border-slate-200 dark:border-slate-700 space-y-1 py-2">
      <a href="#"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-chalkboard w-5 text-center shrink-0"></i>
        <span class="ml-2">Semua Kelas</span>
      </a>
      <a href="#"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-calendar-week w-5 text-center shrink-0"></i>
        <span class="ml-2">Jadwal Mengajar</span>
      </a>
      <a href="#"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-users w-5 text-center shrink-0"></i>
        <span class="ml-2">Daftar Mahasiswa</span>
      </a>
    </div>
  </div>
</div>

<!-- 📝 Presensi -->
<div>
  <button @click="setActiveMenu('dosen_presensi')"
    class="w-full group flex items-center justify-between gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-primary-600 dark:hover:text-primary-400">
    <div class="flex items-center gap-3">
      <i class="fas fa-clipboard-check w-5 text-center shrink-0"></i>
      <span class="whitespace-nowrap transition-opacity duration-200 text-left" x-show="sidebarOpen || !sidebarCollapse"
        x-transition.opacity.duration.200ms>Presensi</span>
    </div>
    <i class="fas fa-chevron-down text-xs transition-transform duration-200" x-show="sidebarOpen || !sidebarCollapse"
      :class="activeMenu === 'dosen_presensi' ? 'rotate-180' : ''"></i>
  </button>

  <!-- Submenu -->
  <div x-show="activeMenu === 'dosen_presensi' && (sidebarOpen || !sidebarCollapse)" x-collapse class="overflow-hidden">
    <div class="ml-4 pl-4 border-l-2 border-slate-200 dark:border-slate-700 space-y-1 py-2">
      <a href="#"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-pen-to-square w-5 text-center shrink-0"></i>
        <span class="ml-2">Input Presensi</span>
      </a>
      <a href="#"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-history w-5 text-center shrink-0"></i>
        <span class="ml-2">Riwayat Presensi</span>
      </a>
      <a href="#"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-file-export w-5 text-center shrink-0"></i>
        <span class="ml-2">Export Laporan</span>
      </a>
    </div>
  </div>
</div>

<!-- 📈 Laporan -->
<div>
  <button @click="setActiveMenu('dosen_laporan')"
    class="w-full group flex items-center justify-between gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-primary-600 dark:hover:text-primary-400">
    <div class="flex items-center gap-3">
      <i class="fas fa-chart-column w-5 text-center shrink-0"></i>
      <span class="whitespace-nowrap transition-opacity duration-200 text-left" x-show="sidebarOpen || !sidebarCollapse"
        x-transition.opacity.duration.200ms>Laporan</span>
    </div>
    <i class="fas fa-chevron-down text-xs transition-transform duration-200" x-show="sidebarOpen || !sidebarCollapse"
      :class="activeMenu === 'dosen_laporan' ? 'rotate-180' : ''"></i>
  </button>

  <!-- Submenu -->
  <div x-show="activeMenu === 'dosen_laporan' && (sidebarOpen || !sidebarCollapse)" x-collapse class="overflow-hidden">
    <div class="ml-4 pl-4 border-l-2 border-slate-200 dark:border-slate-700 space-y-1 py-2">
      <a href="#"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-clipboard-list w-5 text-center shrink-0"></i>
        <span class="ml-2">Statistik Kehadiran</span>
      </a>
      <a href="#"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-chart-line w-5 text-center shrink-0"></i>
        <span class="ml-2">Performa Kelas</span>
      </a>
    </div>
  </div>
</div>