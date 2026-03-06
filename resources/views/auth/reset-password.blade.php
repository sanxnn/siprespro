@extends('auth.layouts.app')

@section('title', 'Reset Password | siprespro')

@section('content')

  <!-- Form Header -->
  <div class="text-center mb-8">
    <div
      class="inline-flex items-center justify-center w-14 h-14 bg-primary-100 dark:bg-primary-900/30 rounded-2xl text-primary-600 dark:text-primary-400 mb-4">
      <i class="fas fa-lock text-xl"></i>
    </div>
    <h2 class="text-2xl font-semibold mb-2">Buat Password Baru</h2>
    <p class="text-slate-500 dark:text-slate-400">Masukkan password baru untuk akun Anda</p>
  </div>

  <!-- Alerts -->
  @if (session('status'))
    <div
      class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-xl mb-6 flex items-center gap-2 animate-slide-down">
      <i class="fas fa-check-circle"></i>
      <span>{{ session('status') }}</span>
    </div>
  @endif

  @if ($errors->any())
    <div
      class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 px-4 py-3 rounded-xl mb-6 flex items-center gap-2 animate-slide-down">
      <i class="fas fa-exclamation-circle"></i>
      <span>{{ $errors->first() }}</span>
    </div>
  @endif

  <!-- Reset Password Form -->
  <form method="POST" action="{{ route('password.update') }}" id="resetForm" class="space-y-5">
    @csrf

    <!-- Hidden Fields -->
    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ $email }}">

    <!-- Email (Read Only) -->
    <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Email</label>
      <div class="relative">
        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
          <i class="fas fa-envelope"></i>
        </span>
        <input type="email"
          class="w-full pl-11 pr-4 py-3 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-500 dark:text-slate-400 cursor-not-allowed"
          value="{{ $email }}" readonly>
      </div>
    </div>

    <!-- Password -->
    <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Password Baru</label>
      <div class="relative">
        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
          <i class="fas fa-lock"></i>
        </span>
        <input type="password" name="password" id="password"
          class="w-full pl-11 pr-12 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all text-slate-800 dark:text-slate-100 placeholder-slate-400"
          placeholder="Minimal 8 karakter" required autocomplete="new-password">
        <button type="button" onclick="togglePassword('password', 'toggleIcon1')"
          class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
          <i class="fas fa-eye" id="toggleIcon1"></i>
        </button>
      </div>
      @error('password')
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
      @enderror
    </div>

    <!-- Confirm Password -->
    <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Konfirmasi Password</label>
      <div class="relative">
        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
          <i class="fas fa-lock"></i>
        </span>
        <input type="password" name="password_confirmation" id="passwordConfirmation"
          class="w-full pl-11 pr-12 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all text-slate-800 dark:text-slate-100 placeholder-slate-400"
          placeholder="Ulangi password baru" required autocomplete="new-password">
        <button type="button" onclick="togglePassword('passwordConfirmation', 'toggleIcon2')"
          class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
          <i class="fas fa-eye" id="toggleIcon2"></i>
        </button>
      </div>
    </div>

    <!-- Password Strength Hint -->
    <div class="text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-700/30 rounded-lg p-3">
      <i class="fas fa-info-circle me-1"></i>
      Password harus minimal 8 karakter, kombinasi huruf dan angka.
    </div>

    <!-- Submit Button -->
    <button type="submit" id="submitBtn"
      class="w-full bg-primary-500 hover:bg-primary-600 text-white font-medium py-3 px-4 rounded-xl transition-all flex items-center justify-center gap-2 shadow-lg shadow-primary-500/25 hover:shadow-primary-500/40 active:scale-[0.98]">
      <i class="fas fa-save"></i>
      <span>Simpan Password Baru</span>
    </button>
  </form>

  <!-- Footer -->
  <div class="text-center mt-8 pt-6 border-t border-slate-200 dark:border-slate-700">
    <a href="{{ route('login') }}"
      class="inline-flex items-center gap-2 text-sm text-primary-600 dark:text-primary-400 hover:underline font-medium">
      <i class="fas fa-arrow-left"></i>
      <span>Kembali ke login</span>
    </a>
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
    function togglePassword(inputId, iconId) {
      const input = document.getElementById(inputId);
      const icon = document.getElementById(iconId);
      if (input && icon) {
        if (input.type === 'password') {
          input.type = 'text';
          icon.className = 'fas fa-eye-slash';
        } else {
          input.type = 'password';
          icon.className = 'fas fa-eye';
        }
      }
    }

    // Loading State on Submit
    document.getElementById('resetForm')?.addEventListener('submit', function () {
      const btn = document.getElementById('submitBtn');
      btn.classList.add('opacity-75', 'cursor-not-allowed');
      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    });
  </script>
@endsection