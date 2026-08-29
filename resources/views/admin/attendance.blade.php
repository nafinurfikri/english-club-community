@extends('layouts.app')

@section('title', 'Presensi & OTP Generator - Admin English Club')

@section('content')

    @php($activeSession = $activeSession ?? null)

    <!-- Manajemen Sesi & Generate OTP -->
    <div class="mb-6 grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Buat Sesi Baru -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <h2 class="text-base font-bold text-gray-900 mb-1">Buat Sesi Baru</h2>
            <p class="text-xs text-gray-500 mb-4">Buat sesi pertemuan lalu buka presensi untuk mendapatkan kode OTP baru.</p>
            <form method="POST" action="{{ route('admin.sessions.store') }}" class="grid grid-cols-1 gap-3">
                @csrf
                <input name="title" required placeholder="Judul sesi, mis. Speaking Practice" class="rounded-xl border border-gray-300 px-3 py-2 text-sm">
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
                    <div class="flex items-center gap-1.5 shrink-0">
                        @if ($session->isOpen())
                            <form method="POST" action="{{ route('admin.sessions.code', $session) }}">
                                @csrf
                                @method('PATCH')
                                <button class="text-xs font-semibold bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg px-2.5 py-1.5">Generate OTP</button>
                            </form>
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
    </div>

    @if ($activeSession)
        <form id="regenerate-code-form" method="POST" action="{{ route('admin.sessions.code', $activeSession) }}" class="hidden">
            @csrf
            @method('PATCH')
        </form>
    @endif

    @if ($activeSession)
        <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <h2 class="text-base font-bold text-gray-900">Tambah Materi Sesi</h2>
            <form method="POST" action="{{ route('admin.materials.store', $activeSession) }}" enctype="multipart/form-data" class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                @csrf
                <input name="title" required placeholder="Judul materi" class="rounded-xl border border-gray-300 px-3 py-2 text-sm">
                <select name="type" required class="rounded-xl border border-gray-300 px-3 py-2 text-sm">
                    <option value="file">File</option>
                    <option value="url">URL</option>
                </select>
                <input name="file" type="file" class="rounded-xl border border-gray-300 px-3 py-2 text-xs">
                <input name="url" type="url" placeholder="https://..." class="rounded-xl border border-gray-300 px-3 py-2 text-sm">
                <label class="flex items-center gap-2 text-xs text-gray-600 sm:col-span-2"><input name="published" type="checkbox" value="1"> Publikasikan untuk student</label>
                <button class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 lg:col-span-1">Simpan Materi</button>
            </form>
        </div>
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
        </div>
    </div>

    <!-- Generator OTP & Quick Info Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6"
         x-data="{
             otpCode: @js(session('attendance_code', $activeSession ? 'Kode tersimpan' : 'Belum tersedia')),
             duration: 30,
             timeLeft: 1800,
             copied: false,
             isRevoked: false,
             generateOtp() {
                 document.getElementById('regenerate-code-form')?.submit();
             },
             copyOtp() {
                 if (this.isRevoked) return;
                 navigator.clipboard.writeText(this.otpCode);
                 this.copied = true;
                 setTimeout(() => this.copied = false, 2000);
             },
             revokeOtp() {
                 this.otpCode = 'EXPIRED';
                 this.isRevoked = true;
                 this.timeLeft = 0;
             }
         }">
        
        <!-- Live OTP Card Generator -->
        <div class="lg:col-span-1 bg-linear-to-br from-blue-600 to-indigo-700 text-white rounded-2xl p-6 shadow-md flex flex-col justify-between">
            <div>
                <div class="flex justify-between items-center mb-4">
                    <span :class="isRevoked ? 'bg-rose-500/30 text-rose-200' : 'bg-white/20 text-white'" class="text-xs font-semibold px-2.5 py-1 rounded-full backdrop-blur-md" x-text="isRevoked ? 'OTP Revoked / Expired' : 'OTP Generator Live'"></span>
                    <span class="text-xs text-blue-200 flex items-center gap-1">
                        <i class="bi bi-clock-history"></i> <span x-text="isRevoked ? 'Tidak Berlaku' : 'Berlaku 30 Menit'"></span>
                    </span>
                </div>

                <div class="text-center py-4">
                    <p class="text-xs text-blue-100 mb-1">Kode Presensi Hari Ini</p>
                    <div :class="isRevoked ? 'text-rose-300 line-through opacity-80' : 'text-white'" 
                         class="text-4xl sm:text-5xl font-mono font-extrabold tracking-widest bg-white/10 border border-white/20 py-3 rounded-xl shadow-inner backdrop-blur-md"
                         x-text="otpCode">
                    </div>
                </div>
            </div>

            <div class="space-y-2 mt-4">
                <button @click="generateOtp()" 
                        class="w-full bg-white text-blue-700 hover:bg-blue-50 font-bold py-2.5 rounded-xl text-sm transition shadow flex items-center justify-center gap-2 cursor-pointer">
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
                        <span class="text-xl font-bold text-emerald-700">{{ $activeSession?->attendances_count ?? 0 }}</span>
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
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden"
         x-data="{
             searchQuery: '',
             students: @js($attendanceRows->values())
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
                            <td colspan="5" class="px-4 py-6 text-center text-xs text-gray-400">Belum ada siswa aktif.</td>
                        </tr>
                    </template>
                    <template x-for="(s, index) in students" :key="index">
                        <tr class="hover:bg-gray-50/80">
                            <td class="px-4 py-3 font-semibold text-gray-900" x-text="s.name"></td>
                            <td class="px-4 py-3 text-xs" x-text="s.email"></td>
                            <td class="px-4 py-3 text-xs text-gray-400" x-text="s.time"></td>
                            <td class="px-4 py-3">
                                <span x-show="s.status"
                                      :class="{
                                          'bg-emerald-100 text-emerald-700': s.status === 'Hadir',
                                          'bg-amber-100 text-amber-700': s.status === 'Izin',
                                          'bg-rose-100 text-rose-700': s.status === 'Alpha'
                                      }" class="px-2.5 py-1 text-xs font-semibold rounded-full" x-text="s.status"></span>
                                <span x-show="!s.status" class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">Belum Tercatat</span>
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

@endsection
