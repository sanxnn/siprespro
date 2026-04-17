<div class="pt-4 pb-2 px-2 mt-2">
  <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] px-2"
    x-show="sidebarOpen || !sidebarCollapse" x-transition.opacity>Lecturer Panel</span>
  <div x-show="sidebarCollapse && !sidebarOpen" class="border-t border-slate-200 dark:border-slate-700 mx-2 mt-2"></div>
</div>

<div x-data="{ open: {{ request()->routeIs('dosen.kelas.*', 'dosen.jadwal.*', 'dosen.mahasiswa.*') ? 'true' : 'false' }} }">
  <button @click="open = !open"
    class="w-full group flex items-center justify-between gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dosen.kelas.*', 'dosen.jadwal.*', 'dosen.mahasiswa.*') ? 'bg-primary-50 dark:bg-primary-900/10 text-primary-600 dark:text-primary-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-primary-600' }}">
    <div class="flex items-center gap-3">
      <i class="fas fa-chalkboard-user w-5 text-center shrink-0"></i>
      <span class="whitespace-nowrap transition-opacity duration-200 text-left" x-show="sidebarOpen || !sidebarCollapse">Kelas Saya</span>
    </div>
    <i class="fas fa-chevron-down text-[10px] transition-transform duration-200" x-show="sidebarOpen || !sidebarCollapse" :class="open ? 'rotate-180' : ''"></i>
  </button>

  <div x-show="open && (sidebarOpen || !sidebarCollapse)" x-collapse>
    <div class="ml-4 pl-4 border-l-2 border-slate-200 dark:border-slate-700 space-y-1 py-2 text-left">
      <a href="{{ route('dosen.kelas.index') }}"
        class="flex items-center px-4 py-2 text-sm rounded-lg {{ request()->routeIs('dosen.kelas.*') ? 'text-primary-600 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-primary-600' }}">
        <i class="fas fa-layer-group w-4"></i><span class="ml-2">Daftar Kelas</span>
      </a>
      <a href="{{ route('dosen.jadwal.index') }}"
        class="flex items-center px-4 py-2 text-sm rounded-lg {{ request()->routeIs('dosen.jadwal.*') ? 'text-primary-600 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-primary-600' }}">
        <i class="fas fa-calendar-alt w-4"></i><span class="ml-2">Atur Jadwal</span>
      </a>
      <a href="{{ route('dosen.mahasiswa.index') }}"
        class="flex items-center px-4 py-2 text-sm rounded-lg {{ request()->routeIs('dosen.mahasiswa.*') ? 'text-primary-600 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-primary-600' }}">
        <i class="fas fa-users w-4"></i><span class="ml-2">Peserta Kelas</span>
      </a>
    </div>
  </div>
</div>

<div x-data="{ open: {{ request()->routeIs('dosen.pertemuan.*', 'dosen.presensi.*') ? 'true' : 'false' }} }">
  <button @click="open = !open"
    class="w-full group flex items-center justify-between gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dosen.pertemuan.*', 'dosen.presensi.*') ? 'bg-primary-50 dark:bg-primary-900/10 text-primary-600 dark:text-primary-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-primary-600' }}">
    <div class="flex items-center gap-3">
      <i class="fas fa-clipboard-check w-5 text-center shrink-0"></i>
      <span class="whitespace-nowrap transition-opacity duration-200 text-left" x-show="sidebarOpen || !sidebarCollapse">Presensi</span>
    </div>
    <i class="fas fa-chevron-down text-[10px] transition-transform duration-200" x-show="sidebarOpen || !sidebarCollapse" :class="open ? 'rotate-180' : ''"></i>
  </button>

  <div x-show="open && (sidebarOpen || !sidebarCollapse)" x-collapse>
    <div class="ml-4 pl-4 border-l-2 border-slate-200 dark:border-slate-700 space-y-1 py-2 text-left">
      <a href="{{ route('dosen.pertemuan.index') }}"
        class="flex items-center px-4 py-2 text-sm rounded-lg {{ request()->routeIs('dosen.pertemuan.*') ? 'text-primary-600 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-primary-600' }}">
        <i class="fas fa-door-open w-4"></i><span class="ml-2">Kelola Pertemuan</span>
      </a>
      <a href="{{ route('dosen.presensi.index') }}"
        class="flex items-center px-4 py-2 text-sm rounded-lg {{ request()->routeIs('dosen.presensi.*') ? 'text-primary-600 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-primary-600' }}">
        <i class="fas fa-list-check w-4"></i><span class="ml-2">Data Kehadiran</span>
      </a>
    </div>
  </div>
</div>

<div x-data="{ open: {{ request()->routeIs('dosen.rekap.*') ? 'true' : 'false' }} }">
  <button @click="open = !open"
    class="w-full group flex items-center justify-between gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dosen.rekap.*') ? 'bg-primary-50 dark:bg-primary-900/10 text-primary-600 dark:text-primary-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-primary-600' }}">
    <div class="flex items-center gap-3">
      <i class="fas fa-chart-column w-5 text-center shrink-0"></i>
      <span class="whitespace-nowrap transition-opacity duration-200 text-left" x-show="sidebarOpen || !sidebarCollapse">Laporan</span>
    </div>
    <i class="fas fa-chevron-down text-[10px] transition-transform duration-200" x-show="sidebarOpen || !sidebarCollapse" :class="open ? 'rotate-180' : ''"></i>
  </button>

  <div x-show="open && (sidebarOpen || !sidebarCollapse)" x-collapse>
    <div class="ml-4 pl-4 border-l-2 border-slate-200 dark:border-slate-700 space-y-1 py-2 text-left">
      <a href="#"
        class="flex items-center px-4 py-2 text-sm rounded-lg text-slate-500 dark:text-slate-400 hover:text-primary-600">
        <i class="fas fa-file-export w-4"></i><span class="ml-2">Rekap Kehadiran</span>
      </a>
      <a href="#"
        class="flex items-center px-4 py-2 text-sm rounded-lg text-slate-500 dark:text-slate-400 hover:text-primary-600">
        <i class="fas fa-chart-line w-4"></i><span class="ml-2">Statistik Kelas</span>
      </a>
    </div>
  </div>
</div>