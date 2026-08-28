@extends('layouts.app')

@section('title', 'Admin Overview - English Club Community')

@section('content')

    <!-- Admin Banner Header -->
    <div class="bg-blue-600 rounded-2xl p-6 sm:p-8 text-white mb-6 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="bg-blue-500/50 border border-blue-400/50 text-xs px-2.5 py-0.5 rounded-full font-semibold">Admin Panel</span>
                <span class="text-xs text-blue-200">Kamis, 28 Agustus 2026</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold">Administrator Dashboard</h1>
            <p class="text-blue-100 text-sm mt-1">Kelola data siswa, absensi OTP, pengumuman, nilai, dan galeri kegiatan.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.attendance') }}" class="bg-white text-blue-600 hover:bg-blue-50 font-semibold px-4 py-2.5 rounded-xl text-sm transition shadow-sm flex items-center gap-2">
                <i class="bi bi-qr-code-scan"></i> Generate OTP Absen
            </a>
        </div>
    </div>

    <!-- Overview Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Siswa Active</p>
                <h3 class="text-2xl font-bold text-gray-900">48 Siswa</h3>
                <span class="text-xs text-emerald-600 font-medium flex items-center gap-1 mt-1">
                    <i class="bi bi-arrow-up-short"></i> +4 anggota baru bulan ini
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Kehadiran Hari Ini</p>
                <h3 class="text-2xl font-bold text-gray-900">42 / 48</h3>
                <span class="text-xs text-emerald-600 font-medium flex items-center gap-1 mt-1">
                    <i class="bi bi-check-circle"></i> 87.5% tingkat kehadiran
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                <i class="bi bi-[calendar-check-fill]"></i>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Pengumuman Published</p>
                <h3 class="text-2xl font-bold text-gray-900">12 Post</h3>
                <span class="text-xs text-gray-400 font-medium flex items-center gap-1 mt-1">
                    Terakhir 2 hari lalu
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
                <i class="bi bi-megaphone-fill"></i>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Foto Galeri</p>
                <h3 class="text-2xl font-bold text-gray-900">36 Foto</h3>
                <span class="text-xs text-blue-600 font-medium flex items-center gap-1 mt-1">
                    4 Album Divisi
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shrink-0">
                <i class="bi bi-images"></i>
            </div>
        </div>
    </div>

    <!-- Quick Access Menu & Recent Activity Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        
        <!-- Left Column: Quick Admin Shortcut Cards -->
        <div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <h2 class="text-base font-bold text-gray-900 mb-4">Modul Manajemen Admin</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                
                <a href="{{ route('admin.attendance') }}" class="p-4 border border-gray-100 rounded-xl bg-blue-50/50 hover:bg-blue-50 transition block group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-500 text-white flex items-center justify-center text-lg group-hover:scale-105 transition">
                            <i class="bi bi-qr-code-scan"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-sm group-hover:text-blue-600">Presensi & OTP</h3>
                            <p class="text-xs text-gray-500">Generate kode OTP & rekap absen</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.students') }}" class="p-4 border border-gray-100 rounded-xl bg-emerald-50/50 hover:bg-emerald-50 transition block group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-emerald-500 text-white flex items-center justify-center text-lg group-hover:scale-105 transition">
                            <i class="bi bi-people"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-sm group-hover:text-emerald-600">Data Anggota Siswa</h3>
                            <p class="text-xs text-gray-500">Kelola NIS, Divisi & data siswa</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.grades') }}" class="p-4 border border-gray-100 rounded-xl bg-purple-50/50 hover:bg-purple-50 transition block group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-purple-500 text-white flex items-center justify-center text-lg group-hover:scale-105 transition">
                            <i class="bi bi-journal-bookmark"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-sm group-hover:text-purple-600">Data Nilai Siswa</h3>
                            <p class="text-xs text-gray-500">Input nilai evaluasi divisi</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.announcements') }}" class="p-4 border border-gray-100 rounded-xl bg-amber-50/50 hover:bg-amber-50 transition block group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-500 text-white flex items-center justify-center text-lg group-hover:scale-105 transition">
                            <i class="bi bi-megaphone"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-sm group-hover:text-amber-600">Kelola Pengumuman</h3>
                            <p class="text-xs text-gray-500">Publish info kegiatan ke guest & siswa</p>
                        </div>
                    </div>
                </a>

            </div>
        </div>

        <!-- Right Column: Sesi OTP Aktif / Status Sesi -->
        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between"
             x-data="{
                 activeCode: '849201',
                 timeLeft: 180,
                 isGenerating: false,
                 generateNew() {
                     this.isGenerating = true;
                     setTimeout(() => {
                         this.activeCode = Math.floor(100000 + Math.random() * 900000).toString();
                         this.timeLeft = 300;
                         this.isGenerating = false;
                     }, 500);
                 }
             }">
            <div>
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-bold text-gray-900 text-sm">Status Kode OTP Sesi Aktif</h3>
                    <span class="px-2 py-0.5 text-[10px] font-semibold bg-emerald-100 text-emerald-700 rounded-full flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span> Live
                    </span>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-xl p-5 text-center my-4">
                    <span class="text-xs text-blue-600 font-semibold block mb-1">Kode OTP Sesi Hari Ini</span>
                    <div class="text-3xl font-mono font-bold tracking-widest text-gray-900 my-2" x-text="activeCode"></div>
                    <p class="text-xs text-gray-500">Gunakan kode ini untuk absensi siswa di kelas</p>
                </div>
            </div>

            <button @click="generateNew()" 
                    :disabled="isGenerating"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-xl text-sm transition flex items-center justify-center gap-2">
                <i class="bi bi-arrow-repeat text-base" :class="isGenerating ? 'animate-spin' : ''"></i>
                <span x-text="isGenerating ? 'Generating...' : 'Generate OTP Baru'"></span>
            </button>
        </div>

    </div>

@endsection
