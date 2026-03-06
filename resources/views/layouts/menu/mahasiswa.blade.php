<!-- Section Header -->
<div class="pt-4 pb-2 px-2 mt-2">
  <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider px-2"
    x-show="sidebarOpen || !sidebarCollapse" x-transition.opacity.duration.200ms>Menu Mahasiswa</span>
  <div x-show="sidebarCollapse && !sidebarOpen" class="border-t border-slate-200 dark:border-slate-700 mx-2 mt-2">
  </div>
</div>

<!-- 📝 Presensi Saya -->
<div>
  <button @click="setActiveMenu('mhs_presensi')"
    class="w-full group flex items-center justify-between gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-primary-600 dark:hover:text-primary-400">
    <div class="flex items-center gap-3">
      <i class="fas fa-clipboard-check w-5 text-center shrink-0"></i>
      <span class="whitespace-nowrap transition-opacity duration-200 text-left" x-show="sidebarOpen || !sidebarCollapse"
        x-transition.opacity.duration.200ms>Presensi Saya</span>
    </div>
    <i class="fas fa-chevron-down text-xs transition-transform duration-200" x-show="sidebarOpen || !sidebarCollapse"
      :class="activeMenu === 'mhs_presensi' ? 'rotate-180' : ''"></i>
  </button>

  <!-- Submenu -->
  <div x-show="activeMenu === 'mhs_presensi' && (sidebarOpen || !sidebarCollapse)" x-collapse class="overflow-hidden">
    <div class="ml-4 pl-4 border-l-2 border-slate-200 dark:border-slate-700 space-y-1 py-2">
      <a href="#"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-check-circle w-5 text-center shrink-0"></i>
        <span class="ml-2">Isi Presensi</span>
      </a>
      <a href="#"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-history w-5 text-center shrink-0"></i>
        <span class="ml-2">Riwayat Kehadiran</span>
      </a>
      <a href="#"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-chart-pie w-5 text-center shrink-0"></i>
        <span class="ml-2">Statistik Saya</span>
      </a>
    </div>
  </div>
</div>

<!-- 📅 Jadwal Kuliah -->
<div>
  <button @click="setActiveMenu('mhs_jadwal')"
    class="w-full group flex items-center justify-between gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-primary-600 dark:hover:text-primary-400">
    <div class="flex items-center gap-3">
      <i class="fas fa-calendar-week w-5 text-center shrink-0"></i>
      <span class="whitespace-nowrap transition-opacity duration-200 text-left" x-show="sidebarOpen || !sidebarCollapse"
        x-transition.opacity.duration.200ms>Jadwal Kuliah</span>
    </div>
    <i class="fas fa-chevron-down text-xs transition-transform duration-200" x-show="sidebarOpen || !sidebarCollapse"
      :class="activeMenu === 'mhs_jadwal' ? 'rotate-180' : ''"></i>
  </button>

  <!-- Submenu -->
  <div x-show="activeMenu === 'mhs_jadwal' && (sidebarOpen || !sidebarCollapse)" x-collapse class="overflow-hidden">
    <div class="ml-4 pl-4 border-l-2 border-slate-200 dark:border-slate-700 space-y-1 py-2">
      <a href="#"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-calendar-day w-5 text-center shrink-0"></i>
        <span class="ml-2">Jadwal Minggu Ini</span>
      </a>
      <a href="#"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-calendar-alt w-5 text-center shrink-0"></i>
        <span class="ml-2">Jadwal Semester</span>
      </a>
    </div>
  </div>
</div>

<!-- 🎓 Akademik -->
<div>
  <button @click="setActiveMenu('mhs_akademik')"
    class="w-full group flex items-center justify-between gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-primary-600 dark:hover:text-primary-400">
    <div class="flex items-center gap-3">
      <i class="fas fa-graduation-cap w-5 text-center shrink-0"></i>
      <span class="whitespace-nowrap transition-opacity duration-200 text-left" x-show="sidebarOpen || !sidebarCollapse"
        x-transition.opacity.duration.200ms>Akademik</span>
    </div>
    <i class="fas fa-chevron-down text-xs transition-transform duration-200" x-show="sidebarOpen || !sidebarCollapse"
      :class="activeMenu === 'mhs_akademik' ? 'rotate-180' : ''"></i>
  </button>

  <!-- Submenu -->
  <div x-show="activeMenu === 'mhs_akademik' && (sidebarOpen || !sidebarCollapse)" x-collapse class="overflow-hidden">
    <div class="ml-4 pl-4 border-l-2 border-slate-200 dark:border-slate-700 space-y-1 py-2">
      <a href="#"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-user-circle w-5 text-center shrink-0"></i>
        <span class="ml-2">Profil Saya</span>
      </a>
      <a href="#"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-file-alt w-5 text-center shrink-0"></i>
        <span class="ml-2">KHS / Transkrip</span>
      </a>
    </div>
  </div>
</div>