<!-- Logout Modal (MicroModal dengan Animasi) -->
<div class="modal" id="modal-logout" aria-hidden="true">
  <div class="modal__overlay" tabindex="-1" data-micromodal-close></div>
  <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-logout-title">
    <div class="flex items-center gap-3 mb-4">
      <div
        class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center text-red-600 dark:text-red-400 shrink-0">
        <i class="fas fa-sign-out-alt text-xl"></i>
      </div>
      <div>
        <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100" id="modal-logout-title">
          Konfirmasi Logout
        </h3>
        <p class="text-sm text-slate-500 dark:text-slate-400">Sistem Presensi Polije</p>
      </div>
      <button
        class="ml-auto text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700"
        data-micromodal-close aria-label="Close modal">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <div class="mb-6">
      <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
        Apakah Anda yakin ingin keluar dari sistem <strong class="text-primary-500">siprespro</strong>?
      </p>
      <div class="mt-4 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl">
        <p class="text-xs text-amber-800 dark:text-amber-300 flex items-start gap-2">
          <i class="fas fa-info-circle mt-0.5 shrink-0"></i>
          <span>Anda perlu login kembali untuk mengakses dashboard.</span>
        </p>
      </div>
    </div>
    <div class="flex gap-3">
      <button
        class="flex-1 px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all text-sm font-medium focus:outline-none focus:ring-2 focus:ring-slate-400"
        data-micromodal-close>
        Batal
      </button>
      <form method="POST" action="{{ route('logout') }}" class="flex-1">
        @csrf
        <button type="submit"
          class="w-full px-4 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white transition-all text-sm font-medium shadow-lg shadow-red-500/25 hover:shadow-red-500/40 focus:outline-none focus:ring-2 focus:ring-red-400 active:scale-[0.98]">
          <i class="fas fa-sign-out-alt me-2"></i> Ya, Logout
        </button>
      </form>
    </div>
  </div>
</div>