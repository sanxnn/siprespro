@extends('layouts.app')
@section('title', 'Detail Presensi • SIPRESPRO')
@section('content')
  <div class=" mx-auto p-4 space-y-6">
    <div class="flex items-center justify-between">
      <a href="{{ route('mahasiswa.dashboard') }}"
        class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-primary-600 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
      </a>
      <span
        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
        <span class="w-1.5 h-1.5 mr-1.5 bg-green-500 rounded-full animate-pulse"></span> Absen Dibuka
      </span>
    </div>
    <div
      class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm p-6 space-y-4">
      <div>
        <span class="text-[11px] font-bold text-primary-600 uppercase tracking-wider block">Konfirmasi Kelas</span>
        <h2 class="text-xl font-bold text-slate-800 dark:text-white mt-0.5">{{ $pertemuan->nama_mk }}</h2>
        <p class="text-xs text-slate-400 mt-0.5">Kode MK: {{ $pertemuan->kode_mk }}</p>
      </div>
      <div class="grid grid-cols-2 gap-4 border-t border-b border-slate-100 dark:border-slate-700 py-4 text-xs">
        <div>
          <span class="block text-slate-400">Dosen Pengampu</span>
          <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $pertemuan->nama_dosen }}</span>
        </div>
        <div>
          <span class="block text-slate-400">Pertemuan Ke</span>
          <span class="font-bold text-slate-700 dark:text-slate-300 text-sm">#{{ $pertemuan->pertemuan_ke }}</span>
        </div>
        <div>
          <span class="block text-slate-400">Jam Operasional Absen</span>
          <span class="font-semibold text-slate-700 dark:text-slate-300 text-primary-600">
            {{ date('H:i', strtotime($pertemuan->jam_mulai)) }} - {{ date('H:i', strtotime($pertemuan->jam_selesai)) }}
            WIB
          </span>
        </div>
        <div>
          <span class="block text-slate-400">Lokasi / Ruang</span>
          <span class="font-semibold text-slate-700 dark:text-slate-300"><i
              class="fas fa-map-marker-alt text-rose-500 mr-1"></i> {{ $pertemuan->nama_lokasi }}</span>
        </div>
      </div>
      <form action="{{ route('mahasiswa.presensi.simpan', $pertemuan->id) }}" method="POST" id="formPresensi"
        class="space-y-4 pt-2">
        @csrf
        <input type="hidden" name="latitude" id="latInput">
        <input type="hidden" name="longitude" id="lngInput">
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Pilih Status
            Kehadiran</label>
          <div class="grid grid-cols-3 gap-3">
            <label
              class="relative flex flex-col p-4 border rounded-xl cursor-pointer transition-all focus-within:ring-2 focus-within:ring-primary-500 border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/30">
              <input type="radio" name="status" value="hadir" checked class="sr-only peer">
              <div class="flex flex-col items-center gap-1 text-center peer-checked:text-green-600">
                <i class="fas fa-fingerprint text-xl text-slate-400 peer-checked:text-green-500"></i>
                <span class="text-xs font-bold mt-1">Hadir</span>
              </div>
              <div
                class="absolute inset-0 border-2 border-transparent peer-checked:border-green-500 rounded-xl pointer-events-none">
              </div>
            </label>
            <label
              class="relative flex flex-col p-4 border rounded-xl cursor-pointer transition-all focus-within:ring-2 focus-within:ring-primary-500 border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/30">
              <input type="radio" name="status" value="sakit" class="sr-only peer">
              <div class="flex flex-col items-center gap-1 text-center peer-checked:text-blue-600">
                <i class="fas fa-medkit text-xl text-slate-400 peer-checked:text-blue-500"></i>
                <span class="text-xs font-bold mt-1">Sakit</span>
              </div>
              <div
                class="absolute inset-0 border-2 border-transparent peer-checked:border-blue-500 rounded-xl pointer-events-none">
              </div>
            </label>
            <label
              class="relative flex flex-col p-4 border rounded-xl cursor-pointer transition-all focus-within:ring-2 focus-within:ring-primary-500 border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/30">

              <input type="radio" name="status" value="izin" class="sr-only peer">
              <div class="flex flex-col items-center gap-1 text-center peer-checked:text-amber-600">
                <i class="fas fa-envelope text-xl text-slate-400 peer-checked:text-amber-500"></i>
                <span class="text-xs font-bold mt-1">Izin</span>
              </div>
              <div
                class="absolute inset-0 border-2 border-transparent peer-checked:border-amber-500 rounded-xl pointer-events-none">
              </div>
            </label>
          </div>
        </div>

        <div id="alert-sakit-izin" class="hidden transition-all duration-300 transform scale-95 origin-top mt-2">
          <div
            class="p-5 rounded-2xl bg-amber-50 dark:bg-rose-950/20 border-2 border-amber-400 dark:border-rose-500/50 shadow-xs">
            <div class="flex items-start gap-3.5">
              <div
                class="w-10 h-10 shrink-0 bg-amber-500 text-white dark:bg-rose-600 rounded-xl flex items-center justify-center animate-pulse shadow-md shadow-amber-500/20">
                <i class="fas fa-exclamation-triangle text-lg"></i>
              </div>

              <div class="space-y-1 text-left">
                <h4 class="text-xs sm:text-sm font-black text-amber-800 dark:text-rose-400 uppercase tracking-wider">
                  Perhatian: Prosedur Validasi Presensi
                </h4>
                <p class="text-[11px] sm:text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-medium">
                  Anda memilih status <span
                    class="text-rose-600 dark:text-rose-400 font-bold underline uppercase">Sakit/Izin</span>. Validasi lokasi GPS
                  dinonaktifkan untuk pengajuan ini. Namun, Anda <span class="text-slate-900 dark:text-white font-bold">wajib menyerahkan dokumen/surat keterangan fisik yang sah</span> kepada Dosen Pengampu.
                </p>

                <div
                  class="mt-3 pt-2.5 border-t border-amber-200 dark:border-rose-900/40 space-y-2 text-[10px] sm:text-[11px] text-amber-800 dark:text-rose-200">
                  <p class="flex items-start gap-1.5 font-bold text-rose-700 dark:text-rose-400">
                    <i class="fas fa-clock mt-0.5"></i> 1. Batas Waktu: Dokumen fisik WAJIB diterima oleh Dosen Pengampu maksimal H+1 dari waktu jadwal perkuliahan ini.
                  </p>
                  <p class="flex items-start gap-1.5">
                    <i class="fas fa-id-card mt-0.5 text-amber-600 dark:text-rose-400"></i> 2. Kelengkapan: Surat keterangan harus mencantumkan data diri lengkap mahasiswa yang bersangkutan secara jelas dan dapat dipertanggungjawabkan keabsahannya.
                  </p>
                  <p class="flex items-start gap-1.5">
                    <i class="fas fa-hands-helping mt-0.5 text-amber-600 dark:text-rose-400"></i> 3. Penyerahan: Untuk mempermudah, penyerahan dokumen fisik kepada Dosen Pengampu dapat diwakilkan oleh siapa saja.
                  </p>
                  <p class="flex items-start gap-1.5 font-black text-rose-700 dark:text-rose-400">
                    <i class="fas fa-gavel mt-0.5"></i> 4. Konsekuensi Tegas: Kegagalan menyerahkan dokumen fisik pada batas waktu yang ditentukan atau penemuan indikasi pemalsuan akan mengakibatkan status kehadiran Anda diubah menjadi ALFA secara permanen.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div id="gps-status"
          class="bg-slate-50 dark:bg-slate-900 rounded-xl p-3 border border-slate-100 dark:border-slate-800 text-center flex items-center justify-center gap-2 text-xs text-slate-500">
          <i class="fas fa-sync fa-spin text-primary-500" id="gps-icon"></i>
          <span id="gps-text">Sedang mengunci koordinat GPS kamu...</span>
        </div>
        <button type="submit" id="btnSubmit" disabled
          class="w-full flex items-center justify-center py-3 px-4 rounded-xl text-xs font-bold text-white bg-slate-400 cursor-not-allowed transition-all shadow-md">
          <i class="fas fa-check mr-2"></i> Kirim Kehadiran
        </button>
      </form>
    </div>
  </div>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const gpsStatusText = document.getElementById('gps-text');
      const gpsIcon = document.getElementById('gps-icon');
      const gpsBox = document.getElementById('gps-status');
      const btnSubmit = document.getElementById('btnSubmit');
      const latInput = document.getElementById('latInput');
      const lngInput = document.getElementById('lngInput');

      const statusInputs = document.querySelectorAll('input[name="status"]');
      const alertBox = document.getElementById('alert-sakit-izin');

      statusInputs.forEach(input => {
        input.addEventListener('change', function () {
          if (this.value === 'sakit' || this.value === 'izin') {
            alertBox.classList.remove('hidden');
            setTimeout(() => {
              alertBox.classList.remove('scale-95');
              alertBox.classList.add('scale-100');
            }, 20);
          } else {
            alertBox.classList.add('hidden');
            alertBox.classList.remove('scale-100');
            alertBox.classList.add('scale-95');
          }
        });
      });

      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          function (position) {
            latInput.value = position.coords.latitude;
            lngInput.value = position.coords.longitude;
            gpsBox.classList.remove('bg-slate-50', 'text-slate-500');
            gpsBox.classList.add('bg-green-50', 'text-green-700', 'border-green-100');
            gpsIcon.className = "fas fa-map-marked-alt text-green-500";
            gpsStatusText.innerText = "Lokasi berhasil dikunci! Silakan submit presensi.";
            btnSubmit.disabled = false;
            btnSubmit.className = "w-full flex items-center justify-center py-3 px-4 rounded-xl text-xs font-bold text-white bg-primary-600 hover:bg-primary-700 transition-all shadow-md shadow-primary-100 dark:shadow-none";
          },
          function (error) {
            gpsBox.classList.remove('bg-slate-50', 'text-slate-500');
            gpsBox.classList.add('bg-rose-50', 'text-rose-700', 'border-rose-100');
            gpsIcon.className = "fas fa-exclamation-triangle text-rose-500";
            if (error.code === error.PERMISSION_DENIED) {
              gpsStatusText.innerText = "Akses GPS ditolak! Izinkan lokasi di browser untuk absen.";
            } else {
              gpsStatusText.innerText = "Gagal mendapatkan lokasi. Coba refresh halaman.";
            }
          }
        );
      } else {
        gpsStatusText.innerText = "Browser kamu tidak mendukung deteksi lokasi (GPS).";
        gpsIcon.className = "fas fa-times-circle text-rose-500";
      }
    });
  </script>
@endsection