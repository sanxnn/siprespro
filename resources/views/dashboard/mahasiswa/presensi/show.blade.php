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
        <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 mt-0.5">{{ $pertemuan->nama_mk }}</h2>
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
              <input type="radio" name="status" value=izin" class="sr-only peer">
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
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          function (position) {
            // Berhasil ambil koordinat
            latInput.value = position.coords.latitude;
            lngInput.value = position.coords.longitude;
            // Update UI status GPS sukses
            gpsBox.classList.remove('bg-slate-50', 'text-slate-500');
            gpsBox.classList.add('bg-green-50', 'text-green-700', 'border-green-100');
            gpsIcon.className = "fas fa-map-marked-alt text-green-500";
            gpsStatusText.innerText = "Lokasi berhasil dikunci! Silakan submit presensi.";
            // Aktifkan tombol submit
            btnSubmit.disabled = false;
            btnSubmit.className = "w-full flex items-center justify-center py-3 px-4 rounded-xl text-xs font-bold text-white bg-primary-600 hover:bg-primary-700 transition-all shadow-md shadow-primary-100 dark:shadow-none";
          },
          function (error) {
            // Gagal ambil koordinat (GPS dimatikan/ditolak user)
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