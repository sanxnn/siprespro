@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page_title', 'Profil Saya')

@section('content')

<div class="space-y-6">

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-100 text-emerald-700 font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-gradient-to-r from-indigo-600 to-blue-500 rounded-3xl p-6 text-white shadow-sm">
        <h1 class="text-2xl font-bold">Profil Mahasiswa</h1>
        <p class="text-sm opacity-90 mt-2">
            Kelola informasi akun dan data diri Anda.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Card Profil --}}
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">

            <div class="flex flex-col items-center text-center">

                <div class="w-24 h-24 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-3xl font-bold">
                    {{ strtoupper(substr($mahasiswa->nama, 0, 1)) }}
                </div>

                <h2 class="mt-4 text-xl font-bold text-slate-800 dark:text-white">
                    {{ $mahasiswa->nama }}
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    {{ $user->email }}
                </p>

                <div class="mt-5 w-full space-y-3 text-sm">

                    <div class="flex justify-between">
                        <span class="text-slate-500">NIM</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-200">
                            {{ $mahasiswa->nim ?? '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-slate-500">Golongan</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-200">
                            {{ $mahasiswa->golongan->nama ?? '-' }}
                        </span>
                    </div>

                </div>

            </div>
        </div>

        {{-- Form --}}
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">

            <form action="{{ route('mahasiswa.profil.update') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        Nama Lengkap
                    </label>

                    <input type="text"
                        name="nama"
                        value="{{ old('nama', $mahasiswa->nama) }}"
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white px-4 py-3 focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        Email
                    </label>

                    <input type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white px-4 py-3 focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        Nomor HP
                    </label>

                    <input type="text"
                        name="nomor_hp"
                        value="{{ old('nomor_hp', $mahasiswa->nomor_hp) }}"
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white px-4 py-3 focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        Alamat
                    </label>

                    <textarea
                        name="alamat"
                        rows="4"
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white px-4 py-3 focus:ring-2 focus:ring-indigo-500">{{ old('alamat', $mahasiswa->alamat) }}</textarea>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="px-6 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold transition">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>

@endsection