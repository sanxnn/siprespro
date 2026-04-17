<div class="pt-4 pb-2 px-2 mt-2">
  <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] px-2"
    x-show="sidebarOpen || !sidebarCollapse" x-transition.opacity>Architect Panel</span>
  <div x-show="sidebarCollapse && !sidebarOpen" class="border-t border-slate-200 dark:border-slate-700 mx-2 mt-2"></div>
</div>

<div x-data="{ open: {{ request()->routeIs('admin.users.*', 'admin.dosen.*', 'admin.mahasiswa.*') ? 'true' : 'false' }} }">
  <button @click="open = !open"
    class="w-full group flex items-center justify-between gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.users.*', 'admin.dosen.*', 'admin.mahasiswa.*') ? 'bg-primary-50 dark:bg-primary-900/10 text-primary-600 dark:text-primary-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-primary-600' }}">
    <div class="flex items-center gap-3">
      <i class="fas fa-users-gear w-5 text-center shrink-0"></i>
      <span class="whitespace-nowrap transition-opacity duration-200 text-left" x-show="sidebarOpen || !sidebarCollapse">Manajemen User</span>
    </div>
    <i class="fas fa-chevron-down text-[10px] transition-transform duration-200" x-show="sidebarOpen || !sidebarCollapse" :class="open ? 'rotate-180' : ''"></i>
  </button>

  <div x-show="open && (sidebarOpen || !sidebarCollapse)" x-collapse>
    <div class="ml-4 pl-4 border-l-2 border-slate-200 dark:border-slate-700 space-y-1 py-2">
      <a href="{{ route('admin.users.index') }}"
        class="flex items-center px-4 py-2 text-sm rounded-lg {{ request()->routeIs('admin.users.*') ? 'text-primary-600 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-primary-600' }}">
        <i class="fas fa-user-shield w-4"></i><span class="ml-2">Akun Sistem</span>
      </a>
      <a href="{{ route('admin.dosen.index') }}"
        class="flex items-center px-4 py-2 text-sm rounded-lg {{ request()->routeIs('admin.dosen.*') ? 'text-primary-600 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-primary-600' }}">
        <i class="fas fa-user-tie w-4"></i><span class="ml-2">Data Dosen</span>
      </a>
      <a href="{{ route('admin.mahasiswa.index') }}"
        class="flex items-center px-4 py-2 text-sm rounded-lg {{ request()->routeIs('admin.mahasiswa.*') ? 'text-primary-600 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-primary-600' }}">
        <i class="fas fa-user-graduate w-4"></i><span class="ml-2">Data Mahasiswa</span>
      </a>
    </div>
  </div>
</div>

<div x-data="{ open: {{ request()->routeIs('admin.semester.*', 'admin.golongan.*', 'admin.mata-kuliah.*', 'admin.ruang.*', 'admin.lokasi.*') ? 'true' : 'false' }} }">
  <button @click="open = !open"
    class="w-full group flex items-center justify-between gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.semester.*', 'admin.golongan.*', 'admin.mata-kuliah.*', 'admin.ruang.*', 'admin.lokasi.*') ? 'bg-primary-50 dark:bg-primary-900/10 text-primary-600 dark:text-primary-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-primary-600' }}">
    <div class="flex items-center gap-3">
      <i class="fas fa-database w-5 text-center shrink-0"></i>
      <span class="whitespace-nowrap transition-opacity duration-200 text-left" x-show="sidebarOpen || !sidebarCollapse">Master Akademik</span>
    </div>
    <i class="fas fa-chevron-down text-[10px] transition-transform duration-200" x-show="sidebarOpen || !sidebarCollapse" :class="open ? 'rotate-180' : ''"></i>
  </button>

  <div x-show="open && (sidebarOpen || !sidebarCollapse)" x-collapse>
    <div class="ml-4 pl-4 border-l-2 border-slate-200 dark:border-slate-700 space-y-1 py-2">
      <a href="{{ route('admin.semester.index') }}" class="flex items-center px-4 py-2 text-sm rounded-lg {{ request()->routeIs('admin.semester.*') ? 'text-primary-600 font-bold' : 'text-slate-500 dark:text-slate-400' }}">
        <i class="fas fa-calendar-alt w-4"></i> <span class="ml-2">Semester</span>
      </a>
      <a href="{{ route('admin.golongan.index') }}" class="flex items-center px-4 py-2 text-sm rounded-lg {{ request()->routeIs('admin.golongan.*') ? 'text-primary-600 font-bold' : 'text-slate-500 dark:text-slate-400' }}">
        <i class="fas fa-layer-group w-4"></i> <span class="ml-2">Golongan</span>
      </a>
      <a href="{{ route('admin.mata-kuliah.index') }}" class="flex items-center px-4 py-2 text-sm rounded-lg {{ request()->routeIs('admin.mata-kuliah.*') ? 'text-primary-600 font-bold' : 'text-slate-500 dark:text-slate-400' }}">
        <i class="fas fa-book w-4"></i> <span class="ml-2">Mata Kuliah</span>
      </a>
      <a href="{{ route('admin.ruang.index') }}" class="flex items-center px-4 py-2 text-sm rounded-lg {{ request()->routeIs('admin.ruang.*') ? 'text-primary-600 font-bold' : 'text-slate-500 dark:text-slate-400' }}">
        <i class="fas fa-door-open w-4"></i> <span class="ml-2">Data Ruang</span>
      </a>
      <a href="{{ route('admin.lokasi.index') }}" class="flex items-center px-4 py-2 text-sm rounded-lg {{ request()->routeIs('admin.lokasi.*') ? 'text-primary-600 font-bold' : 'text-slate-500 dark:text-slate-400' }}">
        <i class="fas fa-map-location-dot w-4"></i> <span class="ml-2">Lokasi Presensi</span>
      </a>
    </div>
  </div>
</div>

<div x-data="{ open: {{ request()->routeIs('admin.kelas-perkuliahan.*') ? 'true' : 'false' }} }">
  <button @click="open = !open"
    class="w-full group flex items-center justify-between gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.kelas-perkuliahan.*') ? 'bg-primary-50 dark:bg-primary-900/10 text-primary-600 dark:text-primary-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-primary-600' }}">
    <div class="flex items-center gap-3">
      <i class="fas fa-chalkboard-user w-5 text-center shrink-0"></i>
      <span class="whitespace-nowrap transition-opacity duration-200 text-left" x-show="sidebarOpen || !sidebarCollapse">Manajemen Kelas</span>
    </div>
    <i class="fas fa-chevron-down text-[10px] transition-transform duration-200" x-show="sidebarOpen || !sidebarCollapse" :class="open ? 'rotate-180' : ''"></i>
  </button>

  <div x-show="open && (sidebarOpen || !sidebarCollapse)" x-collapse>
    <div class="ml-4 pl-4 border-l-2 border-slate-200 dark:border-slate-700 space-y-1 py-2">
      <a href="{{ route('admin.kelas-perkuliahan.index') }}" class="flex items-center px-4 py-2 text-sm rounded-lg {{ request()->routeIs('admin.kelas-perkuliahan.*') ? 'text-primary-600 font-bold' : 'text-slate-500 dark:text-slate-400' }}">
        <i class="fas fa-school-flag w-4"></i> <span class="ml-2">Assign Kelas</span>
      </a>
    </div>
  </div>
</div>

<div x-data="{ open: {{ request()->routeIs('admin.presensi.*', 'admin.rekap.*') ? 'true' : 'false' }} }">
  <button @click="open = !open"
    class="w-full group flex items-center justify-between gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.presensi.*', 'admin.rekap.*') ? 'bg-primary-50 dark:bg-primary-900/10 text-primary-600 dark:text-primary-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-primary-600' }}">
    <div class="flex items-center gap-3">
      <i class="fas fa-chart-line w-5 text-center shrink-0"></i>
      <span class="whitespace-nowrap transition-opacity duration-200 text-left" x-show="sidebarOpen || !sidebarCollapse">Monitoring</span>
    </div>
    <i class="fas fa-chevron-down text-[10px] transition-transform duration-200" x-show="sidebarOpen || !sidebarCollapse" :class="open ? 'rotate-180' : ''"></i>
  </button>

  <div x-show="open && (sidebarOpen || !sidebarCollapse)" x-collapse>
    <div class="ml-4 pl-4 border-l-2 border-slate-200 dark:border-slate-700 space-y-1 py-2">
      <a href="{{ route('admin.presensi.index') }}" class="flex items-center px-4 py-2 text-sm rounded-lg {{ request()->routeIs('admin.presensi.*') ? 'text-primary-600 font-bold' : 'text-slate-500 dark:text-slate-400' }}">
        <i class="fas fa-list-check w-4"></i> <span class="ml-2">Log Kehadiran</span>
      </a>
      <a href="#" class="flex items-center px-4 py-2 text-sm rounded-lg text-slate-500 dark:text-slate-400">
        <i class="fas fa-file-invoice w-4"></i> <span class="ml-2">Rekap Global</span>
      </a>
    </div>
  </div>
</div>