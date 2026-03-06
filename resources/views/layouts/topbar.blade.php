<!-- Topbar -->
<header
  class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm border-b border-slate-200 dark:border-slate-700 px-6 py-3.5 flex justify-between items-center sticky top-0 z-30">

  <!-- Left: Page Title (Mobile) -->
  <div class="flex items-center gap-3 lg:hidden">
    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden">
      <i class="fas fa-bars"></i>
    </button>

    <!-- DESKTOP COLLAPSE -->
    <button class="hidden lg:block text-xl" @click="sidebarCollapse = !sidebarCollapse">
      <i class="fas fa-bars"></i>
    </button>
    <h2 class="text-base font-semibold truncate max-w-[180px]">@yield('page_title', 'Dashboard')</h2>
  </div>


  <!-- Right: Actions -->
  <div class="flex items-center gap-2 ml-auto">

    <!-- Dark Mode Toggle -->
    <button @click="darkMode = !darkMode"
      class="w-10 h-10 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center justify-center hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors text-slate-600 dark:text-slate-300">
      <i class="fas fa-moon" x-show="!darkMode" x-transition></i>
      <i class="fas fa-sun text-amber-400" x-show="darkMode" x-transition x-cloak></i>
    </button>

    <!-- Notifications -->
    <button
      class="w-10 h-10 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center justify-center hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors text-slate-600 dark:text-slate-300 relative">
      <i class="fas fa-bell"></i>
      <span
        class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white dark:border-slate-800"></span>
    </button>

    <!-- User Dropdown (Alpine.js) -->
    <div class="relative" x-data="{ open: false }">
      <button @click="open = !open" @click.outside="open = false"
        class="flex items-center gap-3 px-2 py-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
        <div class="text-right hidden sm:block">
          <div class="text-sm font-medium text-slate-800 dark:text-slate-100">{{ auth()->user()->email }}</div>
          <div class="text-xs text-slate-500 capitalize">{{ auth()->user()->role }}</div>
        </div>
        <div
          class="w-9 h-9 bg-primary-500 rounded-lg flex items-center justify-center text-white font-semibold shrink-0 shadow-md">
          {{ strtoupper(substr(auth()->user()->email, 0, 1)) }}
        </div>
      </button>

      <!-- Dropdown Menu -->
      <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95" @click.away="open = false"
        class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-xl py-2 z-50"
        x-cloak>
        <a href="#"
          class=" px-4 py-2.5 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors flex items-center gap-2">
          <i class="fas fa-user text-sm"></i> Profil
        </a>
        <a href="#"
          class=" px-4 py-2.5 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors flex items-center gap-2">
          <i class="fas fa-cog text-sm"></i> Pengaturan
        </a>
        <hr class="border-slate-200 dark:border-slate-700 my-1">
        <a href="#" @click.prevent="open = false; MicroModal.show('modal-logout')"
          class=" px-4 py-2.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors flex items-center gap-2 cursor-pointer">
          <i class="fas fa-sign-out-alt text-sm"></i> Logout
        </a>
      </div>
    </div>

  </div>
</header>