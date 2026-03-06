<!DOCTYPE html>
<html lang="id" class="light">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Dashboard | siprespro')</title>

  <!-- Tailwind CSS (CDN) -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Tailwind Config -->
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          fontFamily: { sans: ['Poppins', 'sans-serif'] },
          colors: {
            primary: {
              50: '#ecfdf5', 100: '#d1fae5', 200: '#a7f3d0', 300: '#6ee7b7',
              400: '#34d399', 500: '#10b981', 600: '#059669', 700: '#047857',
              800: '#065f46', 900: '#064e3b',
            }
          }
        }
      }
    }
  </script>

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

  <!-- Google Fonts: Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Alpine.js -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>

  <!-- MicroModal.js -->
  <script src="https://unpkg.com/micromodal/dist/micromodal.min.js"></script>

  <!-- Custom Styles -->
  <style>
    [x-cloak] {
      display: none !important;
    }

    body {
      font-family: 'Poppins', sans-serif;
    }

    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
      width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
      background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 3px;
    }

    .dark .custom-scrollbar::-webkit-scrollbar-thumb {
      background: #475569;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }

    /* Smooth Transitions */
    .sidebar-transition {
      transition: width 0.3s ease, padding 0.3s ease;
    }

    .content-transition {
      transition: margin-left 0.3s ease;
    }

    .fade-in {
      animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(8px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 1000;
      align-items: center;
      justify-content: center;
    }

    .modal.is-open {
      display: flex;
    }

    /* Overlay */
    .modal__overlay {
      position: absolute;
      inset: 0;
      opacity: 0;
    }

    /* Container default state */
    .modal__container {
      position: relative;
      background: white;
      border-radius: 16px;
      padding: 24px;
      max-width: 400px;
      width: 90%;
      z-index: 1;

      opacity: 0;
      transform: scale(0.95) translateY(-10px);
    }

    .dark .modal__container {
      background: #1e293b;
    }

    /* OPEN */
    .modal.is-open .modal__overlay {
      animation: overlayFadeIn 0.35s ease forwards;
    }

    .modal.is-open .modal__container {
      animation: modalSlideIn 0.35s ease forwards;
    }

    /* CLOSE */
    .modal[aria-hidden="true"] .modal__overlay {
      animation: overlayFadeOut 0.35s ease forwards;
    }

    .modal[aria-hidden="true"] .modal__container {
      animation: modalSlideOut 0.35s ease forwards;
    }

    /* KEYFRAMES */
    @keyframes overlayFadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    @keyframes overlayFadeOut {
      from {
        opacity: 1;
      }

      to {
        opacity: 0;
      }
    }

    @keyframes modalSlideIn {
      from {
        opacity: 0;
        transform: scale(0.95) translateY(-10px);
      }

      to {
        opacity: 1;
        transform: scale(1) translateY(0);
      }
    }

    @keyframes modalSlideOut {
      from {
        opacity: 1;
        transform: scale(1) translateY(0);
      }

      to {
        opacity: 0;
        transform: scale(0.95) translateY(-10px);
      }
    }
  </style>

  @yield('styles')
</head>

<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 font-sans antialiased"
  x-data="{ sidebarOpen:false, sidebarCollapse:false, darkMode:false }" x-init="() => {

  MicroModal.init()

  const saved = localStorage.getItem('theme')
  const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches
  darkMode = saved === 'dark' || (!saved && systemDark)

  if(darkMode){
    document.documentElement.classList.add('dark')
  }

  $watch('darkMode', value=>{
    document.documentElement.classList.toggle('dark', value)
    localStorage.setItem('theme', value ? 'dark' : 'light')
  })

}">

  <div class="flex min-h-screen" id="wrapper">

    <!-- Sidebar -->
    @include('layouts.sidebar')

    <div x-cloak class="fixed inset-0 bg-black/40 z-30 lg:hidden" x-show="sidebarOpen" x-transition.opacity
      @click="sidebarOpen = false">
    </div>

    <!-- Content Wrapper -->
    <div class="flex-1 flex flex-col lg:ml-64 transition-all duration-300"
      :class="sidebarCollapse ? 'lg:ml-20' : 'lg:ml-64'" id="page-top">

      <!-- Topbar -->
      @include('layouts.topbar')

      <!-- Main Content -->
      <main class="flex-1 p-6 fade-in">

        <!-- Page Header -->
        @hasSection('page_title')
          <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            <div>
              <h1 class="text-2xl font-bold tracking-tight">@yield('page_title')</h1>
              @hasSection('page_subtitle')
                <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">@yield('page_subtitle')</p>
              @endif
            </div>
            @hasSection('page_actions')
              <div>@yield('page_actions')</div>
            @endif
          </div>
        @endif

        <!-- Alerts -->
        @if (session('success'))
          <div
            class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-700 text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-xl mb-4 flex items-center gap-2 fade-in"
            x-data="{ show: true }" x-show="show" x-transition>
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="ml-auto opacity-60 hover:opacity-100" @click="show = false">
              <i class="fas fa-times text-sm"></i>
            </button>
          </div>
        @endif

        @if ($errors->any())
          <div
            class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-300 px-4 py-3 rounded-xl mb-4 flex items-center gap-2 fade-in"
            x-data="{ show: true }" x-show="show" x-transition>
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ $errors->first() }}</span>
            <button type="button" class="ml-auto opacity-60 hover:opacity-100" @click="show = false">
              <i class="fas fa-times text-sm"></i>
            </button>
          </div>
        @endif

        <!-- Content Yield -->
        @yield('content')

      </main>

      <!-- Footer -->
      @include('layouts.footer')

    </div>
  </div>

  <!-- Scroll to Top -->
  <button x-data="{ 
    show: false,
    init() {
      window.addEventListener('scroll', () => {
        this.show = window.scrollY > 150;
      });
    },
    scrollToTop() {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  }" x-show="show" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-4" @click="scrollToTop()"
    class="fixed bottom-5 right-5 w-11 h-11 bg-primary-500 hover:bg-primary-600 text-white rounded-xl flex items-center justify-center shadow-lg hover:shadow-xl transition-all z-50 cursor-pointer"
    aria-label="Scroll to top">
    <i class="fas fa-arrow-up text-sm"></i>
  </button>

  <!-- Logout Modal (MicroModal dengan Animasi) -->
  @include('layouts.modal.logout')

  <script>
    MicroModal.init({
      openClass: 'is-open',
      disableScroll: true,
      awaitOpenAnimation: true,
      awaitCloseAnimation: true
    });
  </script>

  @yield('scripts')
</body>

</html>