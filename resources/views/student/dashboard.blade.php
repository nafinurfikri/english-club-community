@extends('layouts.app')

@section('title', 'Dashboard - Student English Club')

@section('header')
    <header class="bg-white shadow-sm border-b border-gray-100 px-4 sm:px-6 py-4 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-lg sm:text-xl font-bold text-gray-900 leading-tight">Welcome Back, Budi!</h1>
            <p class="text-xs sm:text-sm text-gray-500">Ready to level up your English skills today?</p>
        </div>

        <div class="flex items-center gap-3 sm:gap-4">
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
        <div>
            <span class="inline-block bg-white/15 border border-white/30 text-xs px-2.5 py-0.5 rounded-full font-semibold mb-3">Academic Term: 2026 - Semester 1</span>
            <h2 class="text-2xl sm:text-3xl font-bold mb-2">English Proficiency Excellence Map</h2>
            <p class="text-blue-100 text-sm max-w-md">Keep track of your active learning plans, attendance status, and recent feedback directly from our portal.</p>
        </div>
        <a href="#" class="bg-white text-blue-600 hover:bg-blue-50 font-semibold px-4 py-2.5 rounded-xl text-sm transition shadow-sm flex items-center gap-2 shrink-0">
            Check Schedule
        </a>
    </div>

    <!-- Overview Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm flex items-start justify-between gap-3">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Mapel Aktif</p>
                <h3 class="text-2xl font-bold text-gray-900">6 Subjects</h3>
                <span class="text-xs text-gray-400 font-medium mt-1 block">In Progress</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shrink-0">
                <i class="bi bi-book-fill"></i>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm flex items-start justify-between gap-3">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Kehadiran Bulan Ini</p>
                <h3 class="text-2xl font-bold text-gray-900">94.8%</h3>
                <span class="text-xs text-gray-400 font-medium mt-1 block">18 of 19 Sessions</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shrink-0">
                <i class="bi bi-calendar-check-fill"></i>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm flex items-start justify-between gap-3">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Rata-rata Nilai</p>
                <h3 class="text-2xl font-bold text-gray-900">3.85 GPA</h3>
                <span class="text-xs text-gray-400 font-medium mt-1 block">Grade Average: A-</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shrink-0">
                <i class="bi bi-award-fill"></i>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm flex items-start justify-between gap-3">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Pengumuman Baru</p>
                <h3 class="text-2xl font-bold text-gray-900">3 Unread</h3>
                <span class="text-xs text-gray-400 font-medium mt-1 block">Important updates</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg shrink-0">
                <i class="bi bi-bell-fill"></i>
            </div>
        </div>
    </div>

    <!-- Recent Activities & Upcoming Schedule -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">

        <!-- Recent Activities -->
        <div class="lg:col-span-3 bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
            <h3 class="text-base font-bold text-gray-900 mb-4">Recent Activities</h3>

            <div class="space-y-3">
                <div class="bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Submitted Assignment 2</p>
                        <p class="text-xs text-gray-500 mt-0.5">Advanced Grammar &bull; 2 hours ago</p>
                    </div>
                    <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 text-xs font-semibold rounded-full whitespace-nowrap">On Time</span>
                </div>

                <div class="bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Attended Live Session</p>
                        <p class="text-xs text-gray-500 mt-0.5">Conversational Practice &bull; Yesterday</p>
                    </div>
                    <span class="bg-blue-100 text-blue-700 px-2.5 py-1 text-xs font-semibold rounded-full whitespace-nowrap">Attended</span>
                </div>

                <div class="bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Grades Released: Midterm Test</p>
                        <p class="text-xs text-gray-500 mt-0.5">Listening Comprehension &bull; 3 days ago</p>
                    </div>
                    <span class="bg-amber-100 text-amber-700 px-2.5 py-1 text-xs font-semibold rounded-full whitespace-nowrap">A</span>
                </div>
            </div>
        </div>

        <!-- Upcoming Schedule -->
        <div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-gray-900">Upcoming Schedule</h3>
                <a href="#" class="text-xs font-semibold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                    View Full <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="space-y-3">
                <div class="border border-gray-100 rounded-xl px-4 py-3.5">
                    <p class="text-sm font-semibold text-gray-900">Advanced Grammar</p>
                    <p class="text-xs text-gray-500 mt-0.5">Today, 10:00 AM - 12:00 PM</p>
                    <div class="flex items-center justify-between gap-2 mt-2.5">
                        <span class="text-xs text-gray-500 flex items-center gap-1.5">
                            <i class="bi bi-person"></i> Dr. Elizabeth Stone
                        </span>
                        <span class="text-xs font-semibold text-blue-600">Room 302 / Zoom</span>
                    </div>
                </div>

                <div class="border border-gray-100 rounded-xl px-4 py-3.5">
                    <p class="text-sm font-semibold text-gray-900">Conversational Practice</p>
                    <p class="text-xs text-gray-500 mt-0.5">Tomorrow, 02:00 PM - 04:00 PM</p>
                    <div class="flex items-center justify-between gap-2 mt-2.5">
                        <span class="text-xs text-gray-500 flex items-center gap-1.5">
                            <i class="bi bi-person"></i> James Mitchell, M.A.
                        </span>
                        <span class="text-xs font-semibold text-blue-600">Auditorium B</span>
                    </div>
                </div>

                <div class="border border-gray-100 rounded-xl px-4 py-3.5">
                    <p class="text-sm font-semibold text-gray-900">Listening Comprehension</p>
                    <p class="text-xs text-gray-500 mt-0.5">Friday, 09:00 AM - 11:00 AM</p>
                    <div class="flex items-center justify-between gap-2 mt-2.5">
                        <span class="text-xs text-gray-500 flex items-center gap-1.5">
                            <i class="bi bi-person"></i> Sarah Connor, B.E.d
                        </span>
                        <span class="text-xs font-semibold text-blue-600">Lab English 1</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
