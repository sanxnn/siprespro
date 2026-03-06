<!DOCTYPE html>
<html lang="id" class="light">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="@yield('meta_description', 'Sistem Informasi Absensi - Prodi BTP Polije')">
  <title>@yield('title', 'siprespro')</title>

  <!-- Tailwind CSS (CDN) -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Tailwind Config -->
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          fontFamily: {
            sans: ['Poppins', 'sans-serif'],
          },
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

  <style>
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes pulseSlow {

      0%,
      100% {
        opacity: 0.5;
      }

      50% {
        opacity: 0.8;
      }
    }

    .animate-fade-in {
      animation: fadeIn 0.4s ease-out;
    }

    .animate-slide-down {
      animation: slideDown 0.3s ease-out;
    }

    .animate-pulse-slow {
      animation: pulseSlow 4s ease-in-out infinite;
    }
  </style>
</head>

<body
  class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 font-sans min-h-screen flex items-center justify-center p-4 transition-colors duration-200">

  <!-- Theme Toggle -->
  <button id="themeToggle" aria-label="Toggle dark mode"
    class="fixed top-4 right-4 w-11 h-11 rounded-full border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 flex items-center justify-center hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm z-50">
    <i class="fas fa-moon dark:hidden"></i>
    <i class="fas fa-sun hidden dark:block text-amber-400"></i>
  </button>

  <!-- Login Container -->
  <div
    class="w-full max-w-4xl bg-white dark:bg-slate-800 rounded-3xl shadow-xl overflow-hidden grid grid-cols-1 lg:grid-cols-2 animate-fade-in">

    <!-- Brand Section (Desktop Only) -->
    <div class="hidden lg:flex flex-col justify-center p-10 text-white relative overflow-hidden"
      style="background: linear-gradient(135deg, #10b981, #059669);">
      <!-- Animated Background -->
      <div
        class="absolute inset-0 bg-[radial-gradient(circle_at_50%_120%,rgba(255,255,255,0.15),transparent_70%)] animate-pulse-slow">
      </div>

      <div class="relative z-10">
        <div class="flex items-center gap-3 mb-6">
          <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
            <i class="fas fa-leaf text-xl"></i>
          </div>
          <span class="font-bold text-xl tracking-tight">siprespro</span>
        </div>

        <h2 class="text-3xl font-bold mb-4">Sistem Informasi Absensi</h2>

        <p class="text-white/90 leading-relaxed mb-8">
          Jurusan Produksi Pertanian<br>
          <strong class="text-white">Prodi Budidaya Tanaman Perkebunan</strong><br>
          Politeknik Negeri Jember
        </p>

        <ul class="space-y-3">
          <li class="flex items-center gap-3 text-sm">
            <span class="w-5 h-5 bg-white/20 rounded-full flex items-center justify-center text-xs">✓</span>
            Absensi Berbasis GPS & QR Code
          </li>
          <li class="flex items-center gap-3 text-sm">
            <span class="w-5 h-5 bg-white/20 rounded-full flex items-center justify-center text-xs">✓</span>
            Monitoring Kehadiran Praktikum
          </li>
          <li class="flex items-center gap-3 text-sm">
            <span class="w-5 h-5 bg-white/20 rounded-full flex items-center justify-center text-xs">✓</span>
            Rekap Otomatis per Semester
          </li>
        </ul>
      </div>
    </div>

    <!-- Form Section -->
    <div class="p-8 lg:p-10 flex flex-col justify-center">
      @yield('content')
    </div>

  </div>

  <!-- Dark Mode Script -->
  <script>
    // Check saved theme or system preference
    if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark');
    }

    // Toggle function
    document.getElementById('themeToggle').addEventListener('click', () => {
      document.documentElement.classList.toggle('dark');
      localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
    });
  </script>

  @yield('scripts')
</body>

</html>