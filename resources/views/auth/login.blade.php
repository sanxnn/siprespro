@extends('auth.layouts.app')

@section('title', 'Login | siprespro')

@section('content')

    <!-- Form Header -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-semibold mb-2">Selamat Datang</h2>
        <p class="text-slate-500 dark:text-slate-400">Silakan masuk untuk mengakses dashboard presensi</p>
    </div>

    <!-- Alerts -->
    @if (session('success'))
        <div
            class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-xl mb-6 flex items-center gap-2 animate-slide-down">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div
            class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 px-4 py-3 rounded-xl mb-6 flex items-center gap-2 animate-slide-down">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <!-- Login Form -->
    <form method="POST" action="{{ route('authenticate') }}" id="loginForm" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Email</label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                    <i class="fas fa-envelope"></i>
                </span>
                <input type="email" name="email"
                    class="w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all text-slate-800 dark:text-slate-100 placeholder-slate-400"
                    placeholder="nama@polije.ac.id" value="{{ old('email') }}" required autofocus autocomplete="email">
            </div>
        </div>

        <!-- Password -->
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Password</label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                    <i class="fas fa-lock"></i>
                </span>
                <input type="password" name="password"
                    class="w-full pl-11 pr-12 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all text-slate-800 dark:text-slate-100 placeholder-slate-400"
                    placeholder="••••••••" required autocomplete="current-password">
                <button type="button" onclick="togglePassword()"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                    <i class="fas fa-eye" id="toggleIcon"></i>
                </button>
            </div>
        </div>

        <!-- Remember & Forgot -->
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember"
                    class="w-4 h-4 rounded border-slate-300 text-primary-500 focus:ring-primary-500 cursor-pointer" {{ old('remember') ? 'checked' : '' }}>
                <span class="text-sm text-slate-600 dark:text-slate-400">Ingat saya</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                    class="text-sm text-primary-600 dark:text-primary-400 hover:underline font-medium">
                    Lupa password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit" id="submitBtn"
            class="w-full bg-primary-500 hover:bg-primary-600 text-white font-medium py-3 px-4 rounded-xl transition-all flex items-center justify-center gap-2 shadow-lg shadow-primary-500/25 hover:shadow-primary-500/40 active:scale-[0.98]">
            <i class="fas fa-sign-in-alt"></i>
            <span>Masuk</span>
        </button>
    </form>

    <!-- Footer -->
    <div class="text-center mt-8 pt-6 border-t border-slate-200 dark:border-slate-700">
        <span class="text-sm text-slate-500 dark:text-slate-400">Butuh bantuan? </span>
        <a href="#" class="text-sm text-primary-600 dark:text-primary-400 hover:underline font-medium">Hubungi IT Polije</a>
    </div>

    <!-- Copyright -->
    <div class="text-center mt-4">
        <small class="text-xs text-slate-400 dark:text-slate-500">
            &copy; {{ date('Y') }} siprespro. Politeknik Negeri Jember.
        </small>
    </div>

@endsection

@section('scripts')
    <script>
        // Toggle Password Visibility
        function togglePassword() {
            const input = document.querySelector('input[name="password"]');
            const icon = document.getElementById('toggleIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        // Loading State on Submit
        document.getElementById('loginForm')?.addEventListener('submit', function (e) {
            const btn = document.getElementById('submitBtn');
            btn.classList.add('opacity-75', 'cursor-not-allowed');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        });
    </script>
@endsection