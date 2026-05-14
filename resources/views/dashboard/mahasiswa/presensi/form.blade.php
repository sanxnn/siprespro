@extends('layouts.app')

@section('title', 'Isi Presensi')
@section('page_title', 'Isi Presensi')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<style>
    #modalMap {
        height: 380px;
        width: 100%;
        border-radius: 20px;
        overflow: hidden;
        z-index: 1;
    }
</style>

<div class="space-y-6">

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-100 text-emerald-700 font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-red-100 text-red-700 font-semibold">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-gradient-to-r from-emerald-600 to-teal-500 rounded-3xl p-6 text-white shadow-sm">
        <h1 class="text-2xl font-bold">Presensi Hari Ini</h1>
        <p class="text-sm opacity-90 mt-2">
            Pilih kelas yang sedang dibuka, lalu konfirmasi lokasi Anda sebelum presensi.
        </p>
    </div>

    <div class="grid grid-cols-1 gap-5">
        @forelse($pertemuans as $pertemuan)
            @php
                $jadwal = $pertemuan->kelasPerkuliahan?->jadwals?->first();
                $lokasi = $jadwal?->lokasi;
                $presensiSaya = $pertemuan->presensis->first();
                $sudahPresensi = $presensiSaya !== null;

                $sekarang = now()->format('H:i:s');
                $belumMulai = $pertemuan->presensi_mulai && $sekarang < $pertemuan->presensi_mulai;
                $sudahSelesai = $pertemuan->presensi_selesai && $sekarang > $pertemuan->presensi_selesai;
            @endphp

            <div class="rounded-3xl p-6 border shadow-sm transition
                {{ ($sudahPresensi || $sudahSelesai)
                    ? 'bg-slate-100 dark:bg-slate-800/60 border-slate-200 dark:border-slate-700 opacity-75'
                    : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 hover:shadow-md' }}">

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

                    <div class="space-y-4">
                        <div>
                            @if($sudahPresensi)
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-200 text-slate-600 text-xs font-bold">
                                    SUDAH PRESENSI
                                </span>
                            @elseif($belumMulai)
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                                    BELUM DIBUKA
                                </span>
                            @elseif($sudahSelesai)
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">
                                    PRESENSI BERAKHIR
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                    PRESENSI DIBUKA
                                </span>
                            @endif

                            <h2 class="text-xl font-bold text-slate-800 dark:text-white mt-3">
                                {{ $pertemuan->kelasPerkuliahan?->mataKuliah?->nama ?? '-' }}
                            </h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-slate-500">
                            <p>
                                <i class="fas fa-user-tie mr-2"></i>
                                {{ $pertemuan->kelasPerkuliahan?->dosen?->nama ?? '-' }}
                            </p>

                            <p>
                                <i class="fas fa-calendar mr-2"></i>
                                {{ \Carbon\Carbon::parse($pertemuan->tanggal)->format('d M Y') }}
                            </p>

                            <p>
                                <i class="fas fa-layer-group mr-2"></i>
                                Pertemuan ke-{{ $pertemuan->pertemuan_ke ?? '-' }}
                            </p>

                            <p>
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                {{ $lokasi->nama ?? 'Lokasi belum diatur' }}
                            </p>

                            <p>
                                <i class="fas fa-clock mr-2"></i>
                                Presensi:
                                {{ $pertemuan->presensi_mulai ? substr($pertemuan->presensi_mulai, 0, 5) : '-' }}
                                -
                                {{ $pertemuan->presensi_selesai ? substr($pertemuan->presensi_selesai, 0, 5) : '-' }}
                            </p>
                        </div>

                        @if($lokasi)
                            <div class="inline-flex px-4 py-2 rounded-xl bg-slate-50 dark:bg-slate-700/40 text-sm text-slate-600 dark:text-slate-300">
                                Radius presensi:
                                <strong class="ml-1">{{ $lokasi->radius_meter }} meter</strong>
                            </div>
                        @endif

                        @if($sudahPresensi)
                            <div class="text-sm text-slate-500">
                                Waktu presensi:
                                <strong>
                                    {{ $presensiSaya->waktu_presensi ? \Carbon\Carbon::parse($presensiSaya->waktu_presensi)->format('d M Y H:i') : '-' }}
                                </strong>
                            </div>
                        @endif
                    </div>

                    <div class="lg:w-64">
                        @if($sudahPresensi)
                            <button disabled
                                class="w-full px-5 py-3 rounded-2xl bg-slate-300 text-slate-600 font-bold cursor-not-allowed">
                                <i class="fas fa-check-circle mr-2"></i>
                                Sudah Presensi
                            </button>
                        @elseif($belumMulai)
                            <button disabled
                                class="w-full px-5 py-3 rounded-2xl bg-blue-100 text-blue-700 font-bold cursor-not-allowed">
                                <i class="fas fa-clock mr-2"></i>
                                Mulai {{ substr($pertemuan->presensi_mulai, 0, 5) }}
                            </button>
                        @elseif($sudahSelesai)
                            <button disabled
                                class="w-full px-5 py-3 rounded-2xl bg-red-100 text-red-700 font-bold cursor-not-allowed">
                                <i class="fas fa-lock mr-2"></i>
                                Presensi Berakhir
                            </button>
                        @elseif(!$lokasi)
                            <button disabled
                                class="w-full px-5 py-3 rounded-2xl bg-slate-300 text-slate-600 font-bold cursor-not-allowed">
                                Lokasi Belum Diatur
                            </button>
                        @else
                            <button type="button"
                                onclick="openPresensiModal(
                                    {{ $pertemuan->id }},
                                    {{ $lokasi->latitude }},
                                    {{ $lokasi->longitude }},
                                    {{ $lokasi->radius_meter }},
                                    '{{ addslashes($lokasi->nama) }}',
                                    '{{ addslashes($pertemuan->kelasPerkuliahan?->mataKuliah?->nama ?? '-') }}'
                                )"
                                class="w-full px-5 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold transition">
                                <i class="fas fa-location-dot mr-2"></i>
                                Presensi Sekarang
                            </button>
                        @endif
                    </div>

                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                <p class="text-slate-500">Tidak ada presensi yang dibuka hari ini.</p>
            </div>
        @endforelse
    </div>
</div>

<div id="presensiModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-slate-900/60" onclick="closePresensiModal()"></div>

    <div class="relative max-w-4xl mx-auto mt-10 bg-white dark:bg-slate-800 rounded-3xl shadow-xl overflow-hidden">
        <div class="p-6 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
            <div>
                <h2 id="modalTitle" class="text-xl font-bold text-slate-800 dark:text-white">
                    Konfirmasi Presensi
                </h2>
                <p id="modalSubtitle" class="text-sm text-slate-500 mt-1">
                    Mengambil lokasi Anda...
                </p>
            </div>

            <button type="button" onclick="closePresensiModal()"
                class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="p-6 space-y-5">
            <div id="modalMap"></div>

            <div id="radiusInfo" class="p-4 rounded-2xl bg-slate-100 text-slate-600 text-sm">
                Menunggu lokasi...
            </div>

            <form action="{{ route('mahasiswa.presensi.store') }}" method="POST">
                @csrf
                <input type="hidden" name="pertemuan_id" id="modalPertemuanId">
                <input type="hidden" name="latitude" id="modalLatitude">
                <input type="hidden" name="longitude" id="modalLongitude">

                <button type="submit" id="confirmButton" disabled
                    class="w-full px-5 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold transition disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-check-circle mr-2"></i>
                    Konfirmasi Presensi
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    let map = null;
    let userMarker = null;
    let kelasMarker = null;
    let radiusCircle = null;

    let currentLokasi = {
        lat: null,
        lng: null,
        radius: null,
        nama: null
    };

    function openPresensiModal(pertemuanId, lokasiLat, lokasiLng, radius, lokasiNama, mataKuliah) {
        document.getElementById('presensiModal').classList.remove('hidden');
        document.getElementById('modalPertemuanId').value = pertemuanId;
        document.getElementById('modalTitle').innerText = mataKuliah;
        document.getElementById('modalSubtitle').innerText = 'Memeriksa lokasi Anda...';
        document.getElementById('radiusInfo').innerText = 'Mengambil lokasi perangkat...';
        document.getElementById('confirmButton').disabled = true;

        currentLokasi = {
            lat: lokasiLat,
            lng: lokasiLng,
            radius: radius,
            nama: lokasiNama
        };

        setTimeout(() => {
            initModalMap();
            getUserLocation();
        }, 300);
    }

    function closePresensiModal() {
        document.getElementById('presensiModal').classList.add('hidden');
    }

    function initModalMap() {
        if (!map) {
            map = L.map('modalMap').setView([currentLokasi.lat, currentLokasi.lng], 17);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
        }

        map.setView([currentLokasi.lat, currentLokasi.lng], 17);

        if (kelasMarker) map.removeLayer(kelasMarker);
        if (radiusCircle) map.removeLayer(radiusCircle);
        if (userMarker) {
            map.removeLayer(userMarker);
            userMarker = null;
        }

        kelasMarker = L.marker([currentLokasi.lat, currentLokasi.lng])
            .addTo(map)
            .bindPopup(currentLokasi.nama);

        radiusCircle = L.circle([currentLokasi.lat, currentLokasi.lng], {
            radius: currentLokasi.radius
        }).addTo(map);

        map.fitBounds(radiusCircle.getBounds());

        setTimeout(() => {
            map.invalidateSize();
        }, 300);
    }

    function getUserLocation() {
        const radiusInfo = document.getElementById('radiusInfo');
        const confirmButton = document.getElementById('confirmButton');

        if (!navigator.geolocation) {
            radiusInfo.className = 'p-4 rounded-2xl bg-red-100 text-red-700 text-sm';
            radiusInfo.innerText = 'Browser Anda tidak mendukung fitur lokasi.';
            confirmButton.disabled = true;
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function(position) {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;

                document.getElementById('modalLatitude').value = userLat;
                document.getElementById('modalLongitude').value = userLng;

                userMarker = L.marker([userLat, userLng])
                    .addTo(map)
                    .bindPopup('Lokasi Anda');

                const jarak = hitungJarak(
                    userLat,
                    userLng,
                    currentLokasi.lat,
                    currentLokasi.lng
                );

                const masukRadius = jarak <= currentLokasi.radius;

                if (masukRadius) {
                    radiusInfo.className = 'p-4 rounded-2xl bg-emerald-100 text-emerald-700 text-sm font-semibold';
                    radiusInfo.innerText = `Lokasi valid. Jarak Anda sekitar ${Math.round(jarak)} meter dari titik presensi.`;
                    confirmButton.disabled = false;
                } else {
                    radiusInfo.className = 'p-4 rounded-2xl bg-red-100 text-red-700 text-sm font-semibold';
                    radiusInfo.innerText = `Anda berada di luar radius. Jarak Anda sekitar ${Math.round(jarak)} meter dari titik presensi.`;
                    confirmButton.disabled = true;
                }

                const bounds = L.latLngBounds([
                    [currentLokasi.lat, currentLokasi.lng],
                    [userLat, userLng]
                ]);

                map.fitBounds(bounds, { padding: [50, 50] });
            },
            function() {
                radiusInfo.className = 'p-4 rounded-2xl bg-red-100 text-red-700 text-sm';
                radiusInfo.innerText = 'Gagal mengambil lokasi. Pastikan izin lokasi browser diaktifkan.';
                confirmButton.disabled = true;
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    }

    function hitungJarak(lat1, lon1, lat2, lon2) {
        const R = 6371000;
        const toRad = value => value * Math.PI / 180;

        const dLat = toRad(lat2 - lat1);
        const dLon = toRad(lon2 - lon1);

        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);

        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        return R * c;
    }
</script>
@endsection