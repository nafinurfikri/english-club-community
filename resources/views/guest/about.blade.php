@extends('layouts.app')

@section('title', 'Tentang - English Club Community')

@section('content')

    <section class="bg-blue-50 py-14 px-6 rounded-2xl mb-6 text-center">
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3">Tentang English Club</h1>
        <p class="text-gray-600 leading-relaxed max-w-2xl mx-auto">
            English Club Dwiguna adalah wadah bagi siswa-siswi yang memiliki minat dan semangat dalam mengembangkan kemampuan berbahasa Inggris. Berdiri sejak beberapa tahun lalu, komunitas ini telah menjadi rumah bagi ratusan siswa untuk berlatih speaking, berkompetisi, dan membangun kepercayaan diri lewat berbagai kegiatan rutin.
        </p>
    </section>

    <section class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Pembina Ekskul</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-blue-50 rounded-lg p-4 flex items-center justify-center">
                <img src="{{ asset('images/pembina.jpg') }}" alt="Pembina English Club" class="w-full max-w-55 aspect-3/4 object-cover rounded-lg">
            </div>
            <div class="sm:col-span-2 bg-white border border-gray-200 rounded-lg p-6 flex flex-col justify-center">
                <h3 class="text-lg font-bold text-gray-900">Miss Windy</h3>
                <p class="text-sm text-blue-600 mb-2">Guru Bahasa Inggris</p>
                <p class="text-sm text-gray-600 leading-relaxed">
                    Berpengalaman membimbing siswa mengasah kemampuan speaking, pronunciation, dan public speaking. Mendorong setiap anggota untuk berani tampil dan percaya diri berkomunikasi dalam bahasa Inggris, baik di kelas maupun di ajang perlombaan.
                </p>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Jadwal Kegiatan</h2>
        <div class="bg-blue-500 rounded-2xl p-6 sm:p-8 flex flex-col sm:flex-row items-center gap-6">

            <div class="bg-white rounded-xl w-20 h-20 flex flex-col items-center justify-center shrink-0">
                <span class="text-xs font-semibold text-blue-500 uppercase">Setiap</span>
                <span class="text-lg font-bold text-gray-900">Kamis</span>
            </div>

            <div class="flex-1 text-center sm:text-left">
                <p class="text-white font-bold text-lg mb-1">Latihan Rutin English Club</p>
                <p class="text-blue-100 text-sm">Gabungan seluruh divisi, latihan speaking dan persiapan lomba</p>
            </div>

            <div class="flex items-center gap-4 text-white text-sm shrink-0">
                <div class="flex items-center gap-2">
                    <i class="bi bi-clock text-lg"></i>
                    <span>16.00 - 17.00</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="bi bi-geo-alt text-lg"></i>
                    <span>Ruang Kelas</span>
                </div>
            </div>

        </div>
    </section>

    <section class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Divisi Kami</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-blue-50 rounded-lg p-5 text-center">
                <i class="bi bi-book-half text-blue-600 text-3xl mb-3"></i>
                <p class="text-sm font-semibold text-gray-900">Story Telling</p>
            </div>
            <div class="bg-blue-50 rounded-lg p-5 text-center">
                <i class="bi bi-chat-square-quote text-blue-600 text-3xl mb-3"></i>
                <p class="text-sm font-semibold text-gray-900">Debate</p>
            </div>
            <div class="bg-blue-50 rounded-lg p-5 text-center">
                <i class="bi bi-mic text-blue-600 text-3xl mb-3"></i>
                <p class="text-sm font-semibold text-gray-900">Speech</p>
            </div>
            <div class="bg-blue-50 rounded-lg p-5 text-center">
                <i class="bi bi-broadcast text-blue-600 text-3xl mb-3"></i>
                <p class="text-sm font-semibold text-gray-900">News Anchor</p>
            </div>
        </div>
    </section>

    {{-- Dokumentasi --}}
    <section class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Dokumentasi Kegiatan</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <img src="{{ asset('images/dokumentasi-1.jpg') }}" alt="Dokumentasi kegiatan 1" class="w-full h-32 object-cover rounded-lg">
            <img src="{{ asset('images/dokumentasi-2.jpg') }}" alt="Dokumentasi kegiatan 2" class="w-full h-32 object-cover rounded-lg">
            <img src="{{ asset('images/dokumentasi-3.jpg') }}" alt="Dokumentasi kegiatan 3" class="w-full h-32 object-cover rounded-lg">
            <img src="{{ asset('images/dokumentasi-4.jpg') }}" alt="Dokumentasi kegiatan 4" class="w-full h-32 object-cover rounded-lg">
        </div>
    </section>

@endsection