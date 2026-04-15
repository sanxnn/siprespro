<!-- Section Header -->
<div class="pt-4 pb-2 px-2 mt-2">
  <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider px-2"
    x-show="sidebarOpen || !sidebarCollapse" x-transition.opacity.duration.200ms>Admin Panel</span>
  <div x-show="sidebarCollapse && !sidebarOpen" class="border-t border-slate-200 dark:border-slate-700 mx-2 mt-2">
  </div>
</div>

<!-- 📊 Users & Role (Dropdown) -->
<div>
  <button @click="setActiveMenu('users')"
    class="w-full group flex items-center justify-between gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-primary-600 dark:hover:text-primary-400">
    <div class="flex items-center gap-3">
      <i class="fas fa-users-gear w-5 text-center shrink-0"></i>
      <span class="whitespace-nowrap transition-opacity duration-200 text-left" x-show="sidebarOpen || !sidebarCollapse"
        x-transition.opacity.duration.200ms>User & Access</span>
    </div>
    <i class="fas fa-chevron-down text-xs transition-transform duration-200" x-show="sidebarOpen || !sidebarCollapse"
      :class="activeMenu === 'users' ? 'rotate-180' : ''"></i>
  </button>

  <!-- Submenu -->
  <div x-show="activeMenu === 'users' && (sidebarOpen || !sidebarCollapse)" x-collapse class="overflow-hidden">
    <div class="ml-4 pl-4 border-l-2 border-slate-200 dark:border-slate-700 space-y-1 py-2">
      <a href="{{ route('admin.users.index') }}"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-users w-5 text-center shrink-0"></i>
        <span class="ml-2">Semua Users</span>
      </a>
      <a href="{{ route('admin.dosen.index') }}"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-user-plus w-5 text-center shrink-0"></i>
        <span class="ml-2">Data Dosen</span>
      </a>
      <a href="{{ route('admin.mahasiswa.index') }}"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-user-tag w-5 text-center shrink-0"></i>
        <span class="ml-2">Data Mahasiswa</span>
      </a>
    </div>
  </div>
</div>

<!-- 🎓 Manajemen Akademik (Dropdown) -->
<div>
  <button @click="setActiveMenu('akademik')"
    class="w-full group flex items-center justify-between gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-primary-600 dark:hover:text-primary-400">
    <div class="flex items-center gap-3">
      <i class="fas fa-graduation-cap w-5 text-center shrink-0"></i>
      <span class="whitespace-nowrap transition-opacity duration-200 text-left" x-show="sidebarOpen || !sidebarCollapse"
        x-transition.opacity.duration.200ms>Akademik</span>
    </div>
    <i class="fas fa-chevron-down text-xs transition-transform duration-200" x-show="sidebarOpen || !sidebarCollapse"
      :class="activeMenu === 'akademik' ? 'rotate-180' : ''"></i>
  </button>

  <!-- Submenu -->
  <div x-show="activeMenu === 'akademik' && (sidebarOpen || !sidebarCollapse)" x-collapse class="overflow-hidden">
    <div class="ml-4 pl-4 border-l-2 border-slate-200 dark:border-slate-700 space-y-1 py-2">
      <a href="{{ route('admin.semester.index') }}"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-calendar-days w-5 text-center shrink-0"></i>
        <span class="ml-2">Semester</span>
      </a>
      <a href="{{ route('admin.golongan.index') }}"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-tags w-5 text-center shrink-0"></i>
        <span class="ml-2">Golongan</span>
      </a>
      <a href="{{ route('admin.mata-kuliah.index') }}"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-book w-5 text-center shrink-0"></i>
        <span class="ml-2">Mata Kuliah</span>
      </a>
    </div>
  </div>
</div>

<!-- 🏫 Kelas & Jadwal (Dropdown) -->
<div>
  <button @click="setActiveMenu('kelas')"
    class="w-full group flex items-center justify-between gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-primary-600 dark:hover:text-primary-400">
    <div class="flex items-center gap-3">
      <i class="fas fa-school w-5 text-center shrink-0"></i>
      <span class="whitespace-nowrap transition-opacity duration-200 text-left" x-show="sidebarOpen || !sidebarCollapse"
        x-transition.opacity.duration.200ms>Kelas & Jadwal</span>
    </div>
    <i class="fas fa-chevron-down text-xs transition-transform duration-200" x-show="sidebarOpen || !sidebarCollapse"
      :class="activeMenu === 'kelas' ? 'rotate-180' : ''"></i>
  </button>

  <!-- Submenu -->
  <div x-show="activeMenu === 'kelas' && (sidebarOpen || !sidebarCollapse)" x-collapse class="overflow-hidden">
    <div class="ml-4 pl-4 border-l-2 border-slate-200 dark:border-slate-700 space-y-1 py-2">
      <a href="#"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-chalkboard w-5 text-center shrink-0"></i>
        <span class="ml-2">Kelas Perkuliahan</span>
      </a>
      <a href="#"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-calendar-week w-5 text-center shrink-0"></i>
        <span class="ml-2">Jadwal Kuliah</span>
      </a>
      <a href="#"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-door-open w-5 text-center shrink-0"></i>
        <span class="ml-2">Data Ruang</span>
      </a>
    </div>
  </div>
</div>

<!-- 📝 Presensi (Dropdown) -->
<div>
  <button @click="setActiveMenu('presensi')"
    class="w-full group flex items-center justify-between gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-primary-600 dark:hover:text-primary-400">
    <div class="flex items-center gap-3">
      <i class="fas fa-clipboard-check w-5 text-center shrink-0"></i>
      <span class="whitespace-nowrap transition-opacity duration-200 text-left" x-show="sidebarOpen || !sidebarCollapse"
        x-transition.opacity.duration.200ms>Presensi</span>
    </div>
    <i class="fas fa-chevron-down text-xs transition-transform duration-200" x-show="sidebarOpen || !sidebarCollapse"
      :class="activeMenu === 'presensi' ? 'rotate-180' : ''"></i>
  </button>

  <!-- Submenu -->
  <div x-show="activeMenu === 'presensi' && (sidebarOpen || !sidebarCollapse)" x-collapse class="overflow-hidden">
    <div class="ml-4 pl-4 border-l-2 border-slate-200 dark:border-slate-700 space-y-1 py-2">
      <a href="#"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-calendar-plus w-5 text-center shrink-0"></i>
        <span class="ml-2">Kelola Pertemuan</span>
      </a>
      <a href="#"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-clipboard-list w-5 text-center shrink-0"></i>
        <span class="ml-2">Data Presensi</span>
      </a>
      <a href="#"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-pen-to-square w-5 text-center shrink-0"></i>
        <span class="ml-2">Koreksi Presensi</span>
      </a>
      <a href="#"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-location-dot w-5 text-center shrink-0"></i>
        <span class="ml-2">Lokasi Presensi</span>
      </a>
    </div>
  </div>
</div>

<!-- 📈 Laporan (Dropdown) -->
<div>
  <button @click="setActiveMenu('laporan')"
    class="w-full group flex items-center justify-between gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-primary-600 dark:hover:text-primary-400">
    <div class="flex items-center gap-3">
      <i class="fas fa-chart-column w-5 text-center shrink-0"></i>
      <span class="whitespace-nowrap transition-opacity duration-200 text-left" x-show="sidebarOpen || !sidebarCollapse"
        x-transition.opacity.duration.200ms>Laporan</span>
    </div>
    <i class="fas fa-chevron-down text-xs transition-transform duration-200" x-show="sidebarOpen || !sidebarCollapse"
      :class="activeMenu === 'laporan' ? 'rotate-180' : ''"></i>
  </button>

  <!-- Submenu -->
  <div x-show="activeMenu === 'laporan' && (sidebarOpen || !sidebarCollapse)" x-collapse class="overflow-hidden">
    <div class="ml-4 pl-4 border-l-2 border-slate-200 dark:border-slate-700 space-y-1 py-2">
      <a href="#"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-clipboard-check w-5 text-center shrink-0"></i>
        <span class="ml-2">Rekap Kehadiran</span>
      </a>
      <a href="#"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-chalkboard-user w-5 text-center shrink-0"></i>
        <span class="ml-2">Rekap Dosen</span>
      </a>
      <a href="#"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-file-export w-5 text-center shrink-0"></i>
        <span class="ml-2">Export Data</span>
      </a>
    </div>
  </div>
</div>

<!-- ⚙️ Settings (Dropdown) -->
<div>
  <button @click="setActiveMenu('settings')"
    class="w-full group flex items-center justify-between gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-primary-600 dark:hover:text-primary-400">
    <div class="flex items-center gap-3">
      <i class="fas fa-gear w-5 text-center shrink-0"></i>
      <span class="whitespace-nowrap transition-opacity duration-200 text-left" x-show="sidebarOpen || !sidebarCollapse"
        x-transition.opacity.duration.200ms>Settings</span>
    </div>
    <i class="fas fa-chevron-down text-xs transition-transform duration-200" x-show="sidebarOpen || !sidebarCollapse"
      :class="activeMenu === 'settings' ? 'rotate-180' : ''"></i>
  </button>

  <!-- Submenu -->
  <div x-show="activeMenu === 'settings' && (sidebarOpen || !sidebarCollapse)" x-collapse class="overflow-hidden">
    <div class="ml-4 pl-4 border-l-2 border-slate-200 dark:border-slate-700 space-y-1 py-2">
      <a href="#"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-user-circle w-5 text-center shrink-0"></i>
        <span class="ml-2">Profil Admin</span>
      </a>
      <a href="#"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-lock w-5 text-center shrink-0"></i>
        <span class="ml-2">Ganti Password</span>
      </a>
      <a href="#"
        class="block px-4 py-2 text-sm rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400">
        <i class="fas fa-database w-5 text-center shrink-0"></i>
        <span class="ml-2">Backup Database</span>
      </a>
    </div>
  </div>
</div>