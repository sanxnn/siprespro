<!-- Section Header -->
<div class="pt-4 pb-2 px-2 mt-2">
  <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] px-2"
    x-show="sidebarOpen || !sidebarCollapse" x-transition.opacity>Menu Mahasiswa</span>
  <div x-show="sidebarCollapse && !sidebarOpen" class="border-t border-slate-200 dark:border-slate-700 mx-2 mt-2"></div>
</div>

<!-- 📝 Presensi Saya -->
<div x-data="{ open: {{ request()->is('mahasiswa/presensi*') ? 'true' : 'false' }} }">
  <button @click="open = !open"
    class="w-full group flex items-center justify-between gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->is('mahasiswa/presensi*') ? 'bg-primary-50 dark:bg-primary-900/10 text-primary-600 dark:text-primary-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-primary-600' }}">
    <div class="flex items-center gap-3">
      <i class="fas fa-clipboard-check w-5 text-center shrink-0"></i>
      <span class="whitespace-nowrap transition-opacity duration-200 text-left"
        x-show="sidebarOpen || !sidebarCollapse">Presensi Saya</span>
    </div>
    <i class="fas fa-chevron-down text-[10px] transition-transform duration-200"
      x-show="sidebarOpen || !sidebarCollapse" :class="open ? 'rotate-180' : ''"></i>
  </button>

  <div x-show="open && (sidebarOpen || !sidebarCollapse)" x-collapse>
    <div class="ml-4 pl-4 border-l-2 border-slate-200 dark:border-slate-700 space-y-1 py-2 text-left">

      <!-- Isi Presensi Hari Ini -->
      <a href="{{ route('mahasiswa.presensi.index') }}"
        class="flex items-center px-4 py-2 text-sm rounded-lg {{ request()->routeIs('mahasiswa.presensi.index') ? 'text-primary-600 font-bold bg-primary-50/50 dark:bg-primary-900/10' : 'text-slate-500 dark:text-slate-400 hover:text-primary-600' }}">
        <i class="fas fa-check-circle w-4"></i>
        <span class="ml-2">Isi Presensi</span>
      </a>

      <!-- Riwayat Kehadiran Global -->
      <a href="{{ route('mahasiswa.presensi.riwayat') }}"
        class="flex items-center px-4 py-2 text-sm rounded-lg {{ request()->routeIs('mahasiswa.presensi.riwayat') ? 'text-primary-600 font-bold bg-primary-50/50 dark:bg-primary-900/10' : 'text-slate-500 dark:text-slate-400 hover:text-primary-600' }}">
        <i class="fas fa-history w-4"></i>
        <span class="ml-2">Riwayat Kehadiran</span>
      </a>

      <!-- ⚡ QUICK ACCESS: Otomatis muncul jika mhs berada di dalam Detail Log Matkul -->
      @if(request()->route('kelas_id'))
        <div class="pt-2 pb-1 text-[10px] font-black text-slate-400 uppercase tracking-widest italic pl-4">Quick Access
        </div>

        <a href="{{ route('mahasiswa.presensi.detail-riwayat', request()->route('kelas_id')) }}"
          class="flex items-center px-4 py-2 text-sm rounded-lg transition-all {{ request()->routeIs('mahasiswa.presensi.detail-riwayat') ? 'text-primary-600 font-bold bg-primary-50/50 dark:bg-primary-900/10' : 'text-slate-500 dark:text-slate-400 hover:text-primary-600' }}">
          <div class="relative flex items-center justify-center">
            <i class="fas fa-circle-dot w-4 text-[8px] text-primary-500"></i>
          </div>
          <span class="ml-2">Detail Log Matkul</span>
        </a>
      @endif

    </div>
  </div>
</div>