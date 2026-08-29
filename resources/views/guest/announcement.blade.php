@extends('layouts.app')

@section('title', 'Pengumuman - English Club Community')

@section('content')

    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Pengumuman</h1>
        <p class="text-gray-500 text-sm mt-1">Informasi dan pengumuman terbaru seputar kegiatan sekolah.</p>
    </div>

    <div class="flex flex-col gap-4">
        @forelse ($announcements ?? [] as $announcement)
        <div class="bg-white rounded-lg shadow p-5">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded">{{ ucfirst($announcement->type) }}</span>
                    <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded">Published</span>
                </div>
                <span class="text-xs text-gray-400">{{ $announcement->published_at?->format('d M Y') }}</span>
            </div>

            <h2 class="text-lg font-semibold text-gray-900 mb-2">{{ $announcement->title }}</h2>
            <p class="text-sm text-gray-600 mb-3">{{ $announcement->body }}</p>
        </div>
        @empty
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-8 text-center">
            <i class="bi bi-megaphone text-3xl text-gray-300"></i>
            <p class="mt-2 text-sm text-gray-500">Belum ada pengumuman yang dipublikasikan.</p>
        </div>
        @endforelse

        @if (!isset($announcements))
        <div class="bg-white rounded-lg shadow p-5">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded">Akademik</span>
                    <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded">Published</span>
                </div>
                <span class="text-xs text-gray-400">Today, Jan 24, 2024</span>
            </div>

            <h2 class="text-lg font-semibold text-gray-900 mb-2">Jadwal Ujian Akhir Semester Ganjil TA 2023/2024</h2>
            <p class="text-sm text-gray-600 mb-3">
                Diberitahukan kepada seluruh siswa EC Dwiguna bahwa pelaksanaan Ujian Akhir Semester Ganjil akan dimulai pada tanggal 5 Februari s/d 12 Februari 2024. Harap persiapkan administrasi dan kartu ujian masing-masing.
            </p>

            <a href="#" class="text-sm font-medium text-blue-600 hover:underline">
                Read Full Announcement &rarr;
            </a>
        </div>

        <div class="bg-white rounded-lg shadow p-5">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-medium text-yellow-600 bg-yellow-50 px-2 py-1 rounded">Kegiatan</span>
                    <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded">Published</span>
                </div>
                <span class="text-xs text-gray-400">Jan 18, 2024</span>
            </div>

            <h2 class="text-lg font-semibold text-gray-900 mb-2">Workshop Public Speaking & Personal Branding</h2>
            <p class="text-sm text-gray-600 mb-3">
                Ikuti workshop interaktif bersama narasumber nasional bertema "Speak with Impact". Wajib untuk kelas XI dan XII. Pendaftaran dibuka sampai 28 Januari melalui Student Hub masing-masing.
            </p>

            <a href="#" class="text-sm font-medium text-blue-600 hover:underline">
                Read Full Announcement &rarr;
            </a>
        </div>

        <div class="bg-white rounded-lg shadow p-5">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-medium text-yellow-600 bg-yellow-50 px-2 py-1 rounded">Kegiatan</span>
                    <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded">Published</span>
                </div>
                <span class="text-xs text-gray-400">Jan 18, 2024</span>
            </div>

            <h2 class="text-lg font-semibold text-gray-900 mb-2">Workshop Public Speaking & Personal Branding</h2>
            <p class="text-sm text-gray-600 mb-3">
                Ikuti workshop interaktif bersama narasumber nasional bertema "Speak with Impact". Wajib untuk kelas XI dan XII. Pendaftaran dibuka sampai 28 Januari melalui Student Hub masing-masing.
            </p>

            <a href="#" class="text-sm font-medium text-blue-600 hover:underline">
                Read Full Announcement &rarr;
            </a>
        </div>

        <div class="bg-white rounded-lg shadow p-5">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-medium text-yellow-600 bg-yellow-50 px-2 py-1 rounded">Kegiatan</span>
                    <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded">Published</span>
                </div>
                <span class="text-xs text-gray-400">Jan 18, 2024</span>
            </div>

            <h2 class="text-lg font-semibold text-gray-900 mb-2">Workshop Public Speaking & Personal Branding</h2>
            <p class="text-sm text-gray-600 mb-3">
                Ikuti workshop interaktif bersama narasumber nasional bertema "Speak with Impact". Wajib untuk kelas XI dan XII. Pendaftaran dibuka sampai 28 Januari melalui Student Hub masing-masing.
            </p>

            <a href="#" class="text-sm font-medium text-blue-600 hover:underline">
                Read Full Announcement &rarr;
            </a>
        </div>
        @endif

    </div>

@endsection