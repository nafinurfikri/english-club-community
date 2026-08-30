@extends('layouts.app')

@section('title', 'Presensi & OTP Generator - Admin English Club')

@section('content')

    @php($activeSession = $activeSession ?? null)
    @php($sessionMaterials = $sessions->map(fn ($session) => [
        'id' => $session->id,
        'title' => $session->title,
        'materials' => $session->materials->map(fn ($material) => [
            'id' => $material->id,
            'title' => $material->title,
            'is_published' => (bool) $material->is_published,
        ])->values(),
    ])->values())

    <!-- Manajemen Sesi & Generate OTP -->
    <div class="mb-6 grid grid-cols-1 lg:grid-cols-2 gap-4"
         x-data="{
             showMaterials: false,
             materialSessionId: null,
             materialType: 'file',
             sessionMaterials: @js($sessionMaterials),
             get activeMaterialSession() {
                 return this.sessionMaterials.find(s => s.id === this.materialSessionId) || null;
             }
         }">
        <!-- Buat Sesi Baru -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <h2 class="text-base font-bold text-gray-900 mb-1">Buat Sesi Baru</h2>
            <p class="text-xs text-gray-500 mb-4">Buat sesi pertemuan lalu buka presensi untuk mendapatkan kode OTP baru.</p>
            <form method="POST" action="{{ route('admin.sessions.store') }}" class="grid grid-cols-1 gap-3">
                @csrf
                <input name="title" required placeholder="Judul sesi, mis. Speaking Practice" class="rounded-xl border border-gray-300 px-3 py-2 text-sm">
                <select name="subject_id" required class="rounded-xl border border-gray-300 px-3 py-2 text-sm">
                    <option value="">Pilih Mata Pelajaran</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
                <input name="scheduled_at" type="datetime-local" class="rounded-xl border border-gray-300 px-3 py-2 text-sm">
                <textarea name="description" rows="2" placeholder="Deskripsi (opsional)" class="rounded-xl border border-gray-300 px-3 py-2 text-sm"></textarea>
                <button class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                    <i class="bi bi-plus-circle-fill"></i> Buat Sesi & OTP
                </button>
            </form>
        </div>

        <!-- Daftar Sesi -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <h2 class="text-base font-bold text-gray-900 mb-4">Daftar Sesi Presensi</h2>
            @forelse ($sessions as $session)
                <div class="flex items-center justify-between gap-3 border border-gray-100 rounded-xl px-4 py-3 mb-2">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $session->title }}</p>
                        <span class="text-xs text-gray-500">{{ $session->scheduled_at?->format('d M Y H:i') ?? 'Belum dijadwalkan' }}</span>
                    </div>
                    <div class="flex flex-wrap items-center justify-end gap-1.5">
                        <button type="button"
                                @click="materialSessionId = {{ $session->id }}; showMaterials = true"
                                class="text-xs font-semibold bg-gray-50 text-gray-700 hover:bg-gray-100 rounded-lg px-2.5 py-1.5">
                            <i class="bi bi-folder2-open"></i> Materi
                        </button>
                        @if ($session->isOpen())
                            @if ($session->id !== $activeSession?->id)
                                <a href="{{ route('admin.attendance', ['session' => $session->id]) }}" class="text-xs font-semibold bg-gray-50 text-gray-700 hover:bg-gray-100 rounded-lg px-2.5 py-1.5">Pantau</a>
                                <form method="POST" action="{{ route('admin.sessions.code', $session) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="text-xs font-semibold bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg px-2.5 py-1.5">Generate OTP</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.sessions.close', $session) }}">
                                @csrf
                                @method('PATCH')
                                <button class="text-xs font-semibold bg-rose-50 text-rose-700 hover:bg-rose-100 rounded-lg px-2.5 py-1.5">Tutup</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.sessions.open', $session) }}">
                                @csrf
                                @method('PATCH')
                                <button class="text-xs font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-lg px-2.5 py-1.5">Buka Presensi</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-xs text-gray-400 text-center py-6">Belum ada sesi. Buat sesi baru untuk mulai presensi.</p>
            @endforelse
        </div>

        <!-- Modal Kelola Materi Sesi -->
        <div x-show="showMaterials" class="lg:col-span-2"
             x-transition.opacity
             style="display: none;">
            <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                <div @click.away="showMaterials = false" class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl space-y-4 max-h-[85vh] overflow-y-auto">
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900" x-text="'Materi Sesi: ' + (activeMaterialSession ? activeMaterialSession.title : '')"></h3>
                        <button @click="showMaterials = false" class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
                    </div>

                    <form method="POST" :action="activeMaterialSession ? '{{ url('/admin/sessions') }}/' + activeMaterialSession.id + '/materials' : '#'"
                          enctype="multipart/form-data" class="space-y-3 rounded-xl border border-gray-200 p-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Judul Materi</label>
                            <input name="title" type="text" required placeholder="Judul materi" class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Tipe</label>
                                <select name="type" required x-model="materialType" class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                                    <option value="file">File</option>
                                    <option value="url">URL</option>
                                </select>
                            </div>
                            <div class="flex items-end pb-1">
                                <label class="flex items-center gap-2 text-xs text-gray-600"><input name="published" type="checkbox" value="1" checked> Publikasikan</label>
                            </div>
                        </div>
                        <div x-show="materialType === 'file'">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">File</label>
                            <input name="file" type="file" class="w-full px-3 py-2 border rounded-xl text-sm">
                        </div>
                        <div x-show="materialType === 'url'">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">URL</label>
                            <input name="url" type="url" placeholder="https://..." class="w-full px-3 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                        <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                            <i class="bi bi-plus-circle-fill"></i> Tambah Materi
                        </button>
                    </form>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-bold text-gray-900">Daftar Materi</span>
                            <span class="text-xs text-gray-400" x-text="(activeMaterialSession ? activeMaterialSession.materials.length : 0) + ' materi'"></span>
                        </div>
                        <template x-if="activeMaterialSession && activeMaterialSession.materials.length">
                            <ul class="space-y-2">
                                <template x-for="m in activeMaterialSession.materials" :key="m.id">
                                    <li class="flex items-center justify-between gap-3 rounded-xl border border-gray-100 bg-gray-50 px-3 py-2">
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-semibold text-gray-900" x-text="m.title"></p>
                                            <span class="text-xs" :class="m.is_published ? 'text-emerald-600' : 'text-gray-400'" x-text="m.is_published ? 'Published' : 'Draft'"></span>
                                        </div>
                                        <div class="flex shrink-0 items-center gap-2">
                                            <form method="POST" :action="'{{ url('/admin/session-materials') }}/' + m.id" x-show="!m.is_published">
                                                @csrf
                                                @method('PATCH')
                                                <button class="text-emerald-600 hover:text-emerald-800 text-xs font-semibold"><i class="bi bi-check-circle"></i> Publish</button>
                                            </form>
                                            <form method="POST" :action="'{{ url('/admin/session-materials') }}/' + m.id" onsubmit="return confirm('Hapus materi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-rose-600 hover:text-rose-800 text-xs font-semibold"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </template>
                        <template x-if="activeMaterialSession && !activeMaterialSession.materials.length">
                            <p class="text-sm text-gray-400 text-center py-4">Belum ada materi untuk sesi ini.</p>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($activeSession)
        <form id="regenerate-code-form" method="POST" action="{{ route('admin.sessions.code', $activeSession) }}" class="hidden">
            @csrf
            @method('PATCH')
        </form>
        <form id="revoke-code-form" method="POST" action="{{ route('admin.sessions.revoke-code', $activeSession) }}" class="hidden">
            @csrf
            @method('PATCH')
        </form>
    @endif

    <!-- Header Section -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Presensi Siswa & OTP Generator</h1>
            <p class="text-xs sm:text-sm text-gray-500">Kelola sesi kehadiran siswa dan generate kode OTP presensi di kelas.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-xs font-semibold">
                Sesi: {{ $activeSession?->title ?? 'Belum ada sesi aktif' }}
            </span>
            @if (($openSessions ?? collect())->count() > 1)
                <select onchange="window.location.href = this.value"
                        class="px-2.5 py-1 border border-gray-200 rounded-lg text-xs font-semibold text-gray-700 bg-white"
                        aria-label="Pantau sesi">
                    <option value="" disabled>Pantau Sesi...</option>
                    @foreach ($openSessions as $openSession)
                        <option value="{{ route('admin.attendance', ['session' => $openSession->id]) }}"
                                @selected($openSession->id === $activeSession?->id)>
                            {{ $openSession->title }} · {{ $openSession->subject?->name }} · {{ $openSession->scheduled_at?->format('H:i') ?? '—' }}
                        </option>
                    @endforeach
                </select>
            @endif
        </div>
    </div>

    <!-- Generator OTP & Quick Info Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6"
         x-data="{
             otpCode: @js($otpCode),
             copied: false,
             hasCode: @js(($activeSession?->attendance_code_hash) !== null),
             isRevoked: @js(($activeSession?->attendance_code_hash) === null),
             otpUrl: @js($activeSession ? route('admin.sessions.otp', $activeSession) : null),
             expiresAt: @js($activeSession?->attendance_code_expires_at?->toIso8601String()),
             seconds: 0,
             refreshing: false,
             pollTimer: null,
             tickTimer: null,
             init() {
                 if (this.otpUrl) {
                     this.fetchOtp();
                     this.pollTimer = setInterval(() => this.fetchOtp(), 5000);
                 }
                 this.tickTimer = setInterval(() => this.tick(), 1000);
                 this.tick();
             },
             async fetchOtp() {
                 if (!this.otpUrl) return;
                 try {
                     const res = await fetch(this.otpUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                     if (!res.ok) return;
                     const data = await res.json();
                     this.hasCode = !!data.code;
                     this.isRevoked = !data.is_open || !data.code;
                     if (data.code) {
                         if (data.code !== this.otpCode) this.copied = false;
                         this.otpCode = data.code;
                         this.expiresAt = data.expires_at;
                     } else {
                         this.otpCode = '';
                         this.expiresAt = null;
                         this.seconds = 0;
                     }
                 } catch (e) {
                     // poll berikutnya akan mencoba lagi
                 }
             },
             tick() {
                 if (!this.expiresAt) { this.seconds = 0; return; }
                 const diff = Math.max(0, Math.round((new Date(this.expiresAt).getTime() - Date.now()) / 1000));
                 this.seconds = diff;
                 if (diff <= 0 && this.hasCode && !this.refreshing) {
                     this.refreshing = true;
                     this.fetchOtp().finally(() => { this.refreshing = false; });
                 }
             },
             get timerText() {
                 const s = this.seconds;
                 const m = String(Math.floor(s / 60)).padStart(2, '0');
                 const ss = String(s % 60).padStart(2, '0');
                 return m + ':' + ss;
             },
             get displayCode() {
                 if (this.isRevoked || !this.hasCode || !this.otpCode) return 'Tidak tersedia';
                 return this.otpCode;
             },
             generateOtp() {
                 document.getElementById('regenerate-code-form')?.submit();
             },
             copyOtp() {
                 if (this.isRevoked || !this.hasCode) return;
                 navigator.clipboard.writeText(this.otpCode);
                 this.copied = true;
                 setTimeout(() => this.copied = false, 2000);
             },
             revokeOtp() {
                 if (this.isRevoked) return;
                 this.otpCode = '';
                 this.isRevoked = true;
                 this.hasCode = false;
                 this.seconds = 0;
                 document.getElementById('revoke-code-form')?.submit();
             }
         }">
        
        <!-- Live OTP Card Generator -->
        <div class="lg:col-span-1 bg-linear-to-br from-blue-600 to-indigo-700 text-white rounded-2xl p-6 shadow-md flex flex-col justify-between">
            <div>
                <div class="flex justify-between items-center mb-4">
                    <span :class="isRevoked ? 'bg-rose-500/30 text-rose-200' : 'bg-white/20 text-white'" class="text-xs font-semibold px-2.5 py-1 rounded-full backdrop-blur-md" x-text="isRevoked ? 'OTP Revoked / Expired' : 'OTP Generator Live'"></span>
                    <span class="text-xs text-blue-200 flex items-center gap-1">
                        <i class="bi bi-clock-history"></i> <span x-text="isRevoked || !hasCode ? 'Tidak Berlaku' : timerText"></span>
                    </span>
                </div>

                <div class="text-center py-4">
                    <p class="text-xs text-blue-100 mb-1">Kode Presensi Hari Ini</p>
                    <div :class="isRevoked ? 'text-rose-300 line-through opacity-80' : 'text-white'"
                         class="text-4xl sm:text-5xl font-mono font-extrabold tracking-widest bg-white/10 border border-white/20 py-3 rounded-xl shadow-inner backdrop-blur-md"
                         x-text="displayCode">
                    </div>
                </div>
            </div>

            <div class="space-y-2 mt-4">
                <button @click="generateOtp()" 
                            :disabled="!otpUrl"
                            class="w-full bg-white text-blue-700 hover:bg-blue-50 font-bold py-2.5 rounded-xl text-sm transition shadow flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                    <i class="bi bi-arrow-repeat text-base"></i> Generate Kode Baru
                </button>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <button @click="copyOtp()" 
                            :disabled="isRevoked"
                            class="w-full bg-blue-800/60 hover:bg-blue-800 disabled:opacity-50 disabled:cursor-not-allowed text-white font-semibold py-2 rounded-xl text-xs transition border border-white/10 flex items-center justify-center gap-1 cursor-pointer">
                        <i :class="copied ? 'bi bi-check2' : 'bi bi-clipboard'"></i> 
                        <span x-text="copied ? 'Tersalin!' : 'Salin OTP'"></span>
                    </button>
                    <button @click="revokeOtp()" 
                            :disabled="isRevoked"
                            class="w-full bg-rose-500/80 hover:bg-rose-600 disabled:opacity-50 disabled:cursor-not-allowed text-white font-semibold py-2 rounded-xl text-xs transition border border-white/10 flex items-center justify-center gap-1 cursor-pointer">
                        <i class="bi bi-x-circle-fill"></i> 
                        <span x-text="isRevoked ? 'Revoked' : 'Revoke OTP'"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Attendance Stats Overview -->
        <div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-900 mb-4">Rekap Kehadiran Realtime Sesi Ini</h3>
                
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                    <div class="p-3 bg-gray-50 border border-gray-100 rounded-xl text-center">
                        <span class="text-xs text-gray-500 font-medium block mb-1">Total Siswa</span>
                        <span class="text-xl font-bold text-gray-900">{{ $totalStudents }}</span>
                    </div>
                    <div class="p-3 bg-emerald-50 border border-emerald-100 rounded-xl text-center">
                        <span class="text-xs text-emerald-600 font-medium block mb-1">Hadir (OTP)</span>
                        <span class="text-xl font-bold text-emerald-700">{{ $hadirCount }}</span>
                    </div>
                    <div class="p-3 bg-amber-50 border border-amber-100 rounded-xl text-center">
                        <span class="text-xs text-amber-600 font-medium block mb-1">Izin</span>
                        <span class="text-xl font-bold text-amber-700">{{ $izinCount }}</span>
                    </div>
                    <div class="p-3 bg-rose-50 border border-rose-100 rounded-xl text-center">
                        <span class="text-xs text-rose-600 font-medium block mb-1">Alpha</span>
                        <span class="text-xl font-bold text-rose-700">{{ $alphaCount }}</span>
                    </div>
                </div>

                <p class="text-xs text-gray-500">
                    Siswa yang telah memasukkan OTP otomatis terverifikasi dengan status <strong class="text-emerald-600">Hadir</strong>. Admin dapat mengubah status manual jika diperlukan.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 pt-4 border-t border-gray-100 text-xs text-gray-400">
                <span>Jadwal: {{ $activeSession?->scheduled_at?->format('d M Y H:i') ?? 'Belum dijadwalkan' }}</span>
                <span>Status: {{ $activeSession ? ($activeSession->isOpen() ? 'Terbuka' : 'Tertutup') : 'Belum ada sesi' }}</span>
            </div>
        </div>

    </div>

    <!-- Table Kehadiran Siswa -->
    @if ($activeSession)
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden"
         x-data="{
             searchQuery: '',
             students: @js($attendanceRows->values()),
             get filteredStudents() {
                 const q = this.searchQuery.trim().toLowerCase();
                 if (!q) return this.students;
                 return this.students.filter(s =>
                     (s.name || '').toLowerCase().includes(q) ||
                     (s.email || '').toLowerCase().includes(q)
                 );
             }
         }">
        
        <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <h2 class="text-base font-bold text-gray-900">Daftar Kehadiran Siswa</h2>
            <div class="w-full sm:w-64">
                <input type="text" 
                       x-model="searchQuery"
                       placeholder="Cari nama atau NIS..." 
                       class="w-full px-3 py-1.5 bg-gray-50 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs text-gray-700 uppercase font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3">Nama Siswa</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Waktu Masuk</th>
                        <th class="px-4 py-3">Status</th>
                        @if ($activeSession)
                            <th class="px-4 py-3 text-right">Aksi Status</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
<template x-if="students.length === 0">
                    <tr>
                        <td colspan="{{ $activeSession ? 5 : 4 }}" class="px-4 py-6 text-center text-xs text-gray-400">Belum ada siswa aktif.</td>
                    </tr>
                </template>
                <template x-if="students.length > 0 && filteredStudents.length === 0">
                    <tr>
                        <td colspan="{{ $activeSession ? 5 : 4 }}" class="px-4 py-6 text-center text-xs text-gray-400">Tidak ada siswa yang cocok dengan pencarian.</td>
                    </tr>
                </template>
                <template x-for="(s, index) in filteredStudents" :key="index">
                        <tr class="hover:bg-gray-50/80">
                            <td class="px-4 py-3 font-semibold text-gray-900" x-text="s.name"></td>
                            <td class="px-4 py-3 text-xs" x-text="s.email"></td>
                            <td class="px-4 py-3 text-xs text-gray-400" x-text="s.time"></td>
                            <td class="px-4 py-3">
                                <span
                                      :class="{
                                          'bg-emerald-100 text-emerald-700': s.status === 'Hadir',
                                          'bg-amber-100 text-amber-700': s.status === 'Izin',
                                          'bg-rose-100 text-rose-700': s.status === 'Alpha'
                                      }" class="px-2.5 py-1 text-xs font-semibold rounded-full" x-text="s.status"></span>
                            </td>
                            @if ($activeSession)
                                <td class="px-4 py-3 text-right">
                                    <div class="inline-flex items-center gap-1 rounded-lg border border-gray-200 p-0.5">
                                        @foreach (['hadir' => 'Hadir', 'izin' => 'Izin', 'alpha' => 'Alpha'] as $code => $label)
                                            <form method="POST" action="{{ route('admin.attendance.status') }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="club_session_id" value="{{ $activeSession->id }}">
                                                <input type="hidden" name="user_id" :value="s.id">
                                                <input type="hidden" name="status" value="{{ $code }}">
                                                <button class="text-[11px] font-medium px-1.5 py-1 rounded-md text-gray-600 hover:bg-blue-50 hover:text-blue-700">
                                                    {{ $label }}
                                                </button>
                                            </form>
                                        @endforeach
                                    </div>
                                </td>
                            @endif
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
    @endif

@endsection
