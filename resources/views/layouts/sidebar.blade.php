<aside x-data="{ 
    activeMenu: null,  /* Track dropdown mana yang aktif */
    setActiveMenu(menu) {
      this.activeMenu = this.activeMenu === menu ? null : menu;  /* Toggle */
    }
  }" x-cloak
  class="fixed top-0 left-0 h-full bg-white dark:bg-slate-800 border-r border-slate-200 dark:border-slate-700 z-40 flex flex-col transition-all duration-300 overflow-hidden"
  :class="[
    sidebarCollapse ? 'lg:w-20' : 'lg:w-64',
    sidebarOpen ? 'translate-x-0' : '-translate-x-full',
    'w-72 lg:translate-x-0'
  ]">

  <!-- Brand -->
  <a href="#"
    class="flex items-center gap-3 px-4 py-4 border-b border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
    <div
      class="w-12 h-12 bg-primary-500 rounded-xl flex items-center justify-center text-white shrink-0 shadow-lg shadow-primary-500/25">
      <i class="fas fa-leaf"></i>
    </div>
    <span class="font-bold text-lg whitespace-nowrap transition-opacity duration-200"
      x-show="sidebarOpen || !sidebarCollapse" x-transition.opacity.duration.200ms>siprespro</span>
  </a>

  <!-- Nav Menu -->
  <nav class="flex-1 overflow-y-auto p-3 space-y-1 custom-scrollbar">

    <!-- Dashboard -->
    <a href="{{ route(auth()->user()->getDashboardRoute()) }}"
      class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-slate-600 dark:text-slate-400 hover:bg-primary-50 dark:hover:bg-primary-900/20 hover:text-primary-600 dark:hover:text-primary-400">
      <i class="fas fa-home w-5 text-center shrink-0"></i>
      <span class="whitespace-nowrap transition-opacity duration-200" x-show="sidebarOpen || !sidebarCollapse"
        x-transition.opacity.duration.200ms>Dashboard</span>
    </a>

    <!-- ADMIN MENU -->
    @if(auth()->user()?->isAdmin())
      @include('layouts.menu.admin')
    @endif

    <!-- DOSEN MENU -->
    @if(auth()->user()?->isDosen())
      @include('layouts.menu.dosen')
    @endif

    <!-- MAHASISWA MENU -->
    @if(auth()->user()?->isMahasiswa())
      @include('layouts.menu.mahasiswa')
    @endif

  </nav>

  <!-- Collapse Toggle (Bottom) -->
  <div class="hidden md:flex p-3 border-t border-slate-200 dark:border-slate-700 overflow-hidden">
    <button @click="sidebarCollapse = !sidebarCollapse"
      class="w-full flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl text-slate-500 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-all duration-200 text-sm font-medium">
      <i class="fas" :class="sidebarCollapse ? 'fa-chevron-right' : 'fa-chevron-left'"></i>
      <span x-show="sidebarOpen || !sidebarCollapse" x-transition.opacity.duration.200ms class="whitespace-nowrap">
        Collapse
      </span>
    </button>
  </div>

</aside>