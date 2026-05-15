@extends('layouts.app')

@section('title', 'Akun & Informasi Profil • SIPRESPRO')

@section('content')
  <div class="max-w-5xl mx-auto space-y-6 text-left">
      <div>
          <h1 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Pengaturan Akun</h1>
          <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Kelola data akademik, informasi personal, dan verifikasi keamanan sistem.</p>
      </div>

      <form action="{{ route('profile.update') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          @csrf @method('PUT')

          {{-- LEFT SIDE: MINI PROFILE CARD --}}
          <div class="space-y-4">
              <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl p-6 text-center shadow-xs">
                @if($user->role === 'mahasiswa' && $user->mahasiswa)
                   <div class="w-24 h-24 bg-primary-500 text-white rounded-3xl flex items-center justify-center font-black text-4xl mx-auto shadow-xl shadow-primary-500/20 rotate-3 group hover:rotate-0 transition-transform duration-300">
                      {{ strtoupper(substr($user->mahasiswa->nama, 0, 1)) }}
                   </div>
                   <h3 class="font-black text-slate-800 dark:text-white mt-5 text-lg leading-tight truncate">{{ $user->mahasiswa->nama }}</h3>
                @elseif($user->role === 'dosen' && $user->dosen)
                   <div class="w-24 h-24 bg-primary-500 text-white rounded-3xl flex items-center justify-center font-black text-4xl mx-auto shadow-xl shadow-primary-500/20 rotate-3 group hover:rotate-0 transition-transform duration-300">
                      {{ strtoupper(substr($user->dosen->nama, 0, 1)) }}
                   </div>
                   <h3 class="font-black text-slate-800 dark:text-white mt-5 text-lg leading-tight truncate">{{ $user->dosen->nama }}</h3>
                @else
                   <div class="w-24 h-24 bg-slate-600 text-white rounded-3xl flex items-center justify-center font-black text-4xl mx-auto shadow-xl shadow-slate-500/20 rotate-3 group hover:rotate-0 transition-transform duration-300">
                      A
                   </div>
                   <h3 class="font-black text-slate-800 dark:text-white mt-5 text-lg leading-tight truncate">Admin</h3>
                @endif
                  
                  <p class="text-xs text-slate-400 font-mono mt-1">{{ $user->email }}</p>

                  <div class="mt-4 flex justify-center">
                      <span class="px-4 py-1 bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest rounded-full">
                          {{ $user->role }}
                      </span>
                  </div>

                  {{-- Metadata Academic Info --}}
                  <div class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-700 text-left space-y-3 text-xs">
                      @if($user->role === 'mahasiswa' && $user->mahasiswa)
                        <div class="flex justify-between">
                            <span class="text-slate-400 font-medium">Golongan</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 uppercase">{{ $user->mahasiswa->golongan->nama ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400 font-medium">Status Semester</span>
                            <span class="font-bold text-primary-500 uppercase">{{ $user->mahasiswa->semester->nama ?? 'Aktif' }}</span>
                        </div>
                      @endif
                      @if($user->role === 'dosen' && $user->dosen)
                        <div class="flex justify-between">
                            <span class="text-slate-400 font-medium">Jabatan</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200">{{ $user->dosen->jabatan }}</span>
                        </div>
                      @endif
                  </div>
              </div>
          </div>

          {{-- RIGHT SIDE: FORMS SEGMENTATION --}}
          <div class="lg:col-span-2 space-y-6">
            @if($user->role === 'mahasiswa' && $user->mahasiswa)
              <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl p-6 sm:p-8 space-y-4 shadow-xs">
                  <h4 class="font-black text-xs text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-700/50 pb-2 flex items-center gap-2">
                      <i class="fas fa-graduation-cap text-primary-500"></i> Kredensial Akademik
                  </h4>
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">                      
                      <div class="sm:col-span-2">
                          <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Nama Lengkap Sesuai SK</label>
                          <input type="text" name="name" value="{{ old('nama', $user->mahasiswa->nama) }}" required
                              class="w-full px-4 py-2.5 text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 outline-none focus:ring-2 focus:ring-primary-500/50 transition-all">
                      </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">NIM (Dikunci oleh Sistem Kampus)</label>
                            <input type="text" value="{{ $user->mahasiswa->nim }}" readonly
                                class="w-full px-4 py-2.5 text-xs font-mono font-bold rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-900/50 text-slate-400 dark:text-slate-500 cursor-not-allowed outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Tahun Angkatan</label>
                            <input type="number" name="angkatan" value="{{ old('angkatan', $user->mahasiswa->angkatan) }}" required
                                class="w-full px-4 py-2.5 text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 outline-none focus:ring-2 focus:ring-primary-500/50">
                        </div>
                    </div>
              </div>
            @endif

            @if($user->role === 'dosen' && $user->dosen)
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl p-6 sm:p-8 space-y-4 shadow-xs">
                  <h4 class="font-black text-xs text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-700/50 pb-2 flex items-center gap-2">
                      <i class="fas fa-graduation-cap text-primary-500"></i> Kredensial Akademik
                  </h4>
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                      <div class="sm:col-span-2">
                          <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Nama Lengkap Sesuai SK</label>
                          <input type="text" name="name" value="{{ old('nama', $user->dosen->nama) }}" required
                              class="w-full px-4 py-2.5 text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 outline-none focus:ring-2 focus:ring-primary-500/50 transition-all">
                      </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">NIP (Dikunci oleh Sistem Kampus)</label>
                            <input type="text" value="{{ $user->dosen->nip }}" readonly
                                class="w-full px-4 py-2.5 text-xs font-mono font-bold rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-900/50 text-slate-400 dark:text-slate-500 cursor-not-allowed outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">NIDN (Dikunci oleh Sistem Kampus)</label>
                            <input type="text" value="{{ $user->dosen->nidn }}" readonly
                                class="w-full px-4 py-2.5 text-xs font-mono font-bold rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-900/50 text-slate-400 dark:text-slate-500 cursor-not-allowed outline-none">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Fungsional Jabatan</label>
                            <input type="text" name="jabatan" value="{{ old('jabatan', $user->dosen->jabatan) }}" required
                                class="w-full px-4 py-2.5 text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 outline-none focus:ring-2 focus:ring-primary-500/50">
                        </div>
                    </div>
              </div>
            @endif

              {{-- BLOCK 2: DATA PERSONAL & KONTAK --}}
              <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl p-6 sm:p-8 space-y-4 shadow-xs">
                  <h4 class="font-black text-xs text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-slate-700/50 pb-2 flex items-center gap-2">
                      <i class="fas fa-id-card text-emerald-500"></i> Informasi Data Privat & Kontak
                  </h4>
                  @php 
                    $detail = $user->role === 'mahasiswa' ? $user->mahasiswa : ($user->role === 'dosen' ? $user->dosen : null);
                  @endphp
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                      <div>
                          <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Email SSO (Dikunci oleh Kampus)</label>
                          <input type="email" value="{{ $user->email }}" readonly
                              class="w-full px-4 py-2.5 text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-900/50 text-slate-400 dark:text-slate-500 cursor-not-allowed outline-none">
                      </div>

                      @if($detail)
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Nomor Handphone (WhatsApp)</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $detail->no_hp) }}" required
                                class="w-full px-4 py-2.5 text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 outline-none focus:ring-2 focus:ring-primary-500/50">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">NIK (No Induk Kependudukan)</label>
                            <input type="text" name="nik" value="{{ old('nik', $detail->nik) }}" required
                                class="w-full px-4 py-2.5 text-xs font-mono font-bold rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 outline-none focus:ring-2 focus:ring-primary-500/50">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $detail->tanggal_lahir) }}" required
                                class="w-full px-4 py-2.5 text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 outline-none focus:ring-2 focus:ring-primary-500/50">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Alamat Tempat Tinggal KTP</label>
                            <textarea name="alamat" rows="3" required
                                class="w-full px-4 py-2.5 text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 outline-none focus:ring-2 focus:ring-primary-500/50">{{ old('alamat', $detail->alamat) }}</textarea>
                        </div>
                      @endif
                  </div>
              </div>

              {{-- BLOCK 3: VALIDASI KEAMANAN & PASSWORD --}}
              <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl p-6 sm:p-8 space-y-4 shadow-xs">
                  <div class="border-b border-slate-100 dark:border-slate-700/50 pb-2">
                      <h4 class="font-black text-xs text-slate-400 uppercase tracking-widest flex items-center gap-2">
                          <i class="fas fa-shield-alt text-amber-500"></i> Gerbang Kunci Keamanan
                      </h4>
                      <p class="text-[10px] text-slate-400 italic mt-0.5">Biarkan kolom di bawah ini kosong jika Anda tidak berencana mengganti password masuk.</p>
                  </div>

                  <div class="space-y-4">
                      <div>
                          <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Verifikasi Password Sekarang</label>
                          <input type="password" name="current_password" placeholder="••••••••"
                              class="w-full px-4 py-2.5 text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 outline-none focus:ring-2 focus:ring-primary-500/50">
                          @error('current_password') <span class="text-[10px] text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                      </div>

                      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                          <div>
                              <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Sandi Password Baru</label>
                              <input type="password" name="password" placeholder="••••••••"
                                  class="w-full px-4 py-2.5 text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 outline-none focus:ring-2 focus:ring-primary-500/50">
                          </div>
                          <div>
                              <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Konfirmasi Sandi Baru</label>
                              <input type="password" name="password_confirmation" placeholder="••••••••"
                                  class="w-full px-4 py-2.5 text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 outline-none focus:ring-2 focus:ring-primary-500/50">
                          </div>
                      </div>
                  </div>
              </div>

              {{-- ACTION BUTTON --}}
              <div class="flex justify-end">
                  <button type="submit" class="px-8 py-3 bg-primary-600 hover:bg-primary-700 text-white font-black text-xs uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-primary-500/10 hover:-translate-y-0.5">
                      Eksekusi Simpan Data
                  </button>
              </div>
          </div>
      </form>
  </div>
@endsection