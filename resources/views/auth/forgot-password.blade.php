@extends('auth.layouts.app')

@section('title', 'Lupa Password | siprespro')

@section('content')

  <!-- Form Header -->
  <div class="text-center mb-8">
    <div class="inline-flex items-center justify-center w-14 h-14 bg-amber-100 dark:bg-amber-900/30 rounded-2xl text-amber-600 dark:text-amber-400 mb-4">
      <i class="fas fa-key text-xl"></i>
    </div>
    <h2 class="text-2xl font-semibold mb-2">Lupa Password?</h2>
    <p class="text-slate-500 dark:text-slate-400">Masukkan email Polije Anda, kami akan kirimkan link reset</p>
  </div>

  <!-- Alerts -->
  @if (session('status'))
    <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-xl mb-6 flex items-center gap-2 animate-slide-down">
      <i class="fas fa-check-circle"></i>
      <span>{{ session('status') }}</span>
    </div>
  @endif

  @if ($errors->any())
    <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 px-4 py-3 rounded-xl mb-6 flex items-center gap-2 animate-slide-down">
      <i class="fas fa-exclamation-circle"></i>
      <span>{{ $errors->first() }}</span>
    </div>
  @endif

  <!-- Forgot Password Form -->
  <form method="POST" action="{{ route('password.email') }}" id="resetForm" class="space-y-5">
    @csrf

    <!-- Email -->
    <div>
      <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Email Polije</label>
      <div class="relative">
        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
          <i class="fas fa-envelope"></i>
        </span>
        <input type="email" name="email" 
               class="w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all text-slate-800 dark:text-slate-100 placeholder-slate-400"
               placeholder="nama@polije.ac.id" 
               value="{{ old('email') }}" 
               required autofocus autocomplete="email">
      </div>
    </div>

    <!-- Submit Button -->
    <button type="submit" id="submitBtn" 
            class="w-full bg-primary-500 hover:bg-primary-600 text-white font-medium py-3 px-4 rounded-xl transition-all flex items-center justify-center gap-2 shadow-lg shadow-primary-500/25 hover:shadow-primary-500/40 active:scale-[0.98]">
      <i class="fas fa-paper-plane"></i>
      <span>Kirim Link Reset</span>
    </button>
  </form>

  <!-- Footer -->
  <div class="text-center mt-8 pt-6 border-t border-slate-200 dark:border-slate-700">
    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm text-primary-600 dark:text-primary-400 hover:underline font-medium">
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

@push('scripts')
<script>
  // Loading State on Submit
  document.getElementById('resetForm')?.addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.classList.add('opacity-75', 'cursor-not-allowed');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
  });
</script>
@endpush