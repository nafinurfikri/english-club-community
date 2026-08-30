@extends('layouts.app')

@section('title', 'Dashboard - Student English Club')

@section('header')
    <header class="bg-white shadow-sm border-b border-gray-100 px-3 sm:px-6 py-3 sm:py-4 flex items-center justify-between gap-3">
        <button @click="sidebarOpen = true" aria-label="Menu"
                class="lg:hidden inline-flex items-center justify-center w-11 h-11 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 transition shrink-0">
            <i class="bi bi-list text-2xl"></i>
        </button>

        <div class="min-w-0">
            <h1 class="text-lg sm:text-xl font-bold text-gray-900 leading-tight">Welcome Back, {{ str(auth()->user()?->name ?? 'Student')->explode(' ')->first() }}!</h1>
            <p class="text-xs sm:text-sm text-gray-500">Ready to level up your English skills today?</p>
        </div>

        <div class="flex items-center gap-2 sm:gap-4 shrink-0">
            <button class="relative text-gray-500 hover:text-gray-700 transition">
                <i class="bi bi-bell text-xl"></i>
                <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
            </button>

            <span class="hidden sm:block w-px h-6 bg-gray-200"></span>

            <span class="bg-emerald-100 text-emerald-700 text-xs font-semibold px-3 py-1.5 rounded-full whitespace-nowrap">Active Academic</span>
        </div>
    </header>
@endsection

@section('content')

    <!-- Hero Banner -->
    <div class="bg-blue-600 rounded-2xl p-6 sm:p-8 text-white mb-6 shadow-sm flex flex-col lg:flex-row justify-between lg:items-center items-start gap-5">
        <div class="min-w-0">
            <span class="inline-block bg-white/15 border border-white/30 text-xs px-2.5 py-0.5 rounded-full font-semibold mb-3">Academic Term: 2026 - Semester 1</span>
            <h2 class="text-2xl sm:text-3xl font-bold mb-2">English Proficiency Excellence Map</h2>
            <p class="text-blue-100 text-sm max-w-md">Keep track of your active learning plans, attendance status, and recent feedback directly from our portal.</p>
        </div>
        <a href="#" class="bg-white text-blue-600 hover:bg-blue-50 font-semibold px-4 py-2.5 rounded-xl text-sm transition shadow-sm flex items-center gap-2 shrink-0">
            Check Schedule
        </a>
    </div>

    <!-- Overview Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm flex items-start justify-between gap-3">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Sesi</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $attendanceCount }} Presensi</h3>
                <span class="text-xs text-gray-400 font-medium mt-1 block">Kehadiran tercatat</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shrink-0">
                <i class="bi bi-calendar-check-fill"></i>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm flex items-start justify-between gap-3">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Tingkat Kehadiran</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $attendancePercentage }}%</h3>
                <span class="text-xs text-gray-400 font-medium mt-1 block">Dari seluruh sesi</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shrink-0">
                <i class="bi bi-percent"></i>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm flex items-start justify-between gap-3">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Mata Pelajaran</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $sessions->count() }} Sesi</h3>
                <span class="text-xs text-gray-400 font-medium mt-1 block">Akan datang</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shrink-0">
                <i class="bi bi-book-fill"></i>
            </div>
        </div>
    </div>

    <!-- Upcoming Sessions -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">

        <!-- Quick Access -->
        <div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
            <h3 class="text-base font-bold text-gray-900 mb-4">Akses Cepat</h3>
            <div class="space-y-3">
                <a href="{{ route('student.subjects') }}" class="block rounded-xl border border-gray-100 px-4 py-3.5 hover:bg-blue-50 transition">
                    <p class="text-sm font-semibold text-gray-900">Mata Pelajaran & Materi</p>
                    <p class="text-xs text-gray-500 mt-0.5">Lihat materi setelah presensi</p>
                </a>
                <a href="{{ route('student.attendance') }}" class="block rounded-xl border border-gray-100 px-4 py-3.5 hover:bg-blue-50 transition">
                    <p class="text-sm font-semibold text-gray-900">Presensi Kehadiran</p>
                    <p class="text-xs text-gray-500 mt-0.5">Masukkan kode OTP sesi hari ini</p>
                </a>
            </div>
        </div>

        <!-- Upcoming Schedule (real data) -->
        <div class="lg:col-span-3 bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-gray-900">Jadwal Sesi Akan Datang</h3>
            </div>

            @if ($sessions->isEmpty())
                <p class="text-sm text-gray-400 text-center py-6">Belum ada sesi terjadwal.</p>
            @else
                <div class="space-y-3">
                    @foreach ($sessions as $session)
                        <div class="border border-gray-100 rounded-xl px-4 py-3.5">
                            <p class="text-sm font-semibold text-gray-900">{{ $session->title }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $session->scheduled_at?->format('l, d M Y · H:i') ?? 'Belum dijadwalkan' }}
                            </p>
                            @if ($session->subject)
                                <span class="text-xs font-semibold text-blue-600">{{ $session->subject->name }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

@endsection
