<!DOCTYPE html>
<html lang="id" class="light">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Dashboard | siprespro')</title>

  <script src="https://cdn.tailwindcss.com"></script>

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

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
  <script src="https://unpkg.com/micromodal/dist/micromodal.min.js"></script>

  <style>
    [x-cloak] { display: none !important; }
    body { font-family: 'Poppins', sans-serif; }

    /* --- FIX MODAL JADI CENTER --- */
    .modal {
      display: none;
      z-index: 1000;
    }

    .modal.is-open {
      display: block;
    }

    .modal__overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(15, 23, 42, 0.75);
      backdrop-filter: blur(4px);
      display: flex; /* Pakai Flexbox */
      justify-content: center; /* Horisontal Center */
      align-items: center; /* Vertikal Center */
      z-index: 1000;
    }

    .modal__container {
      background-color: #ffffff;
      padding: 24px;
      border-radius: 20px;
      box-sizing: border-box;
      max-height: 90vh;
      overflow-y: auto;
      position: relative;
      /* Hapus max-width 400px yang tadi ngerusak layout */
    }

    .dark .modal__container {
      background-color: #1e293b;
      border: 1px solid #334155;
    }

    /* ANIMASI */
    @keyframes mmFadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes mmSlideIn { 
        from { transform: scale(0.95) translateY(10px); opacity: 0; } 
        to { transform: scale(1) translateY(0); opacity: 1; } 
    }

    .modal.is-open .modal__overlay { animation: mmFadeIn 0.3s ease-out; }
    .modal.is-open .modal__container { animation: mmSlideIn 0.3s ease-out; }

    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #475569; }
  </style>

  @yield('styles')
</head>

<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 antialiased"
  x-data="{ sidebarOpen:false, sidebarCollapse:false, darkMode:false }" 
  x-init="() => {
    MicroModal.init({
      openClass: 'is-open',
      disableScroll: true,
      awaitOpenAnimation: true,
      awaitCloseAnimation: true
    });

    const saved = localStorage.getItem('theme');
    darkMode = saved === 'dark' || (!saved && window.matchMedia('(prefers-color-scheme: dark)').matches);
    if(darkMode) document.documentElement.classList.add('dark');
    $watch('darkMode', v => {
      document.documentElement.classList.toggle('dark', v);
      localStorage.setItem('theme', v ? 'dark' : 'light');
    });
}">

  <div class="flex min-h-screen">
    @include('layouts.sidebar')

    <div x-cloak class="fixed inset-0 bg-black/40 z-30 lg:hidden" x-show="sidebarOpen" @click="sidebarOpen = false"></div>

    <div class="flex-1 flex flex-col lg:ml-64 transition-all duration-300" :class="sidebarCollapse ? 'lg:ml-20' : 'lg:ml-64'">
      @include('layouts.topbar')

      <main class="flex-1 p-6">
        @if (session('success'))
          <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 p-4 rounded-xl mb-4 flex items-center gap-2">
            <i class="fas fa-check-circle text-emerald-500"></i>
            <span>{{ session('success') }}</span>
          </div>
        @endif

        @if ($errors->any())
          <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 p-4 rounded-xl mb-4 flex items-center gap-2 text-red-600">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ $errors->first() }}</span>
          </div>
        @endif

        @yield('content')
      </main>

      @include('layouts.footer')
    </div>
  </div>

  @include('layouts.components.modal.logout')

  @yield('scripts')
</body>
</html>