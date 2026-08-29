@extends('layouts.app')

@section('title', 'Beranda - English Club Community')

@section('content')

    @php
        $hero = $sections?->get('hero')?->published_content ?? [];
        $about = $sections?->get('about')?->published_content ?? [];
        $cta = $sections?->get('cta')?->published_content ?? [];
    @endphp

    <section class="bg-blue-50 rounded-2xl px-6 py-16 text-center mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
            {{ $hero['title'] ?? 'Belajar Bahasa Inggris Bareng Komunitas' }}
        </h1>
        <p class="text-gray-600 max-w-xl mx-auto mb-6">
            {{ $hero['subtitle'] ?? 'Gabung dengan English Club Community, tempat latihan speaking, sharing, dan kegiatan seru bareng teman sebaya.' }}
        </p>
        <div class="flex justify-center gap-3">
            <a href="{{ route('student.register') }}" class="px-5 py-2.5 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600 transition">
                Gabung Sekarang
            </a>
            <a href="{{ route('about') }}" class="px-5 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition">
                Pelajari Lebih Lanjut
            </a>
        </div>
    </section>

    <section class="mb-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ $about['title'] ?? 'Apa itu English Club?' }}</h2>
                <p class="text-gray-600 leading-relaxed">{{ $about['body'] ?? 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Cumque, repellat eligendi eum voluptatibus accusantium suscipit molestias veritatis iusto quaerat aliquam dolorum adipisci velit, inventore reprehenderit laudantium minima harum ipsam quam ea explicabo possimus facere. Amet dolor nulla obcaecati magnam voluptatem?' }}</p>
            </div>
            <div>
                <img src="{{ asset('images/tentang-ec.jpg') }}" alt="Kegiatan English Club" class="w-full h-64 object-cover rounded-xl">
            </div>
        </div>
    </section>

    <section class="bg-blue-500 py-10 px-6 rounded-2xl mb-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">

            <div class="flex items-center gap-4">
                <i class="bi bi-book text-white text-4xl"></i>
                <div>
                    <p class="text-2xl sm:text-3xl font-bold text-white">120+</p>
                    <p class="text-xs sm:text-sm text-blue-100 uppercase tracking-wide">Anggota Aktif</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <i class="bi-diagram-3 text-white text-4xl"></i>
                <div>
                    <p class="text-2xl sm:text-3xl font-bold text-white">4</p>
                    <p class="text-xs sm:text-sm text-blue-100 uppercase tracking-wide">Divisi</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <i class="bi bi-calendar-event text-white text-4xl"></i>
                <div>
                    <p class="text-2xl sm:text-3xl font-bold text-white">4</p>
                    <p class="text-xs sm:text-sm text-blue-100 uppercase tracking-wide">Kegiatan/Bulan</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <i class="bi bi-award text-white text-4xl"></i>
                <div>
                    <p class="text-2xl sm:text-3xl font-bold text-white">15+</p>
                    <p class="text-xs sm:text-sm text-blue-100 uppercase tracking-wide">Kompetisi Diikuti</p>
                </div>
            </div>

        </div>
    </section>

    <section class="text-center mb-8 px-6">
        <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-12 max-w-2xl mx-auto">
            Divisi
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 text-left sm:text-center">

            <div>
                <i class="bi bi-book-half text-6xl text-gray-900 mb-4 block"></i>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Story Telling</h3>
                <p class="text-sm text-gray-500">
                    Di divisi ini kamu akan belajar bercerita dengan bahasa Inggris, membangun intonasi, ekspresi, dan kepercayaan diri di depan audiens.
                </p>
            </div>

            <div>
                <i class="bi bi-chat-square-quote text-6xl text-gray-900 mb-4 block"></i>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Debate</h3>
                <p class="text-sm text-gray-500">
                    Di divisi ini kamu akan belajar berargumen secara logis, berpikir kritis, serta menyampaikan pendapat dalam bahasa Inggris.
                </p>
            </div>

            <div>
                <i class="bi bi-mic text-6xl text-gray-900 mb-4 block"></i>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Speech</h3>
                <p class="text-sm text-gray-500">
                    Di divisi ini kamu akan belajar public speaking, menyusun naskah pidato, serta menyampaikannya dengan percaya diri.
                </p>
            </div>

            <div>
                <i class="bi bi-broadcast text-6xl text-gray-900 mb-4 block"></i>
                <h3 class="text-lg font-bold text-gray-900 mb-2">News Anchor</h3>
                <p class="text-sm text-gray-500">
                    Di divisi ini kamu akan belajar membawakan berita, teknik pengucapan yang jelas, serta gaya penyiaran profesional.
                </p>
            </div>

        </div>

    </section>
    <section class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Kata Mereka</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-3">"Skill speaking aku naik banget setelah ikut EC Dwiguna."</p>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-medium">KL</div>
                    <p class="text-sm font-medium text-gray-900">Kelvin Muaezin Lubis</p>
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-3">"Kegiatannya seru, banyak teman baru juga."</p>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-pink-500 flex items-center justify-center text-white text-xs font-medium">AP</div>
                    <p class="text-sm font-medium text-gray-900">Anindya Eka Pratiwi</p>
                </div>
            </div>
        </div>
    </section>

    <section class="mb-8" x-data="{ openFaq: null }">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">FAQ</h2>
        <div class="max-w-2xl mx-auto flex flex-col divide-y divide-gray-200 border-t border-b border-gray-200">

            <div class="py-4">
                <button @click="openFaq === 1 ? openFaq = null : openFaq = 1" class="flex items-center justify-between w-full text-left">
                    <span class="text-sm font-medium text-gray-900">Bagaimana cara bergabung?</span>
                    <i class="bi bi-chevron-down text-gray-400 transition" :class="openFaq === 1 ? 'rotate-180' : ''"></i>
                </button>
                <p x-show="openFaq === 1" x-collapse class="text-sm text-gray-600 mt-2">
                    Kamu bisa daftar lewat halaman Sign Up di bagian bawah halaman ini, atau hubungi admin lewat kontak yang tersedia.
                </p>
            </div>

            <div class="py-4">
                <button @click="openFaq === 2 ? openFaq = null : openFaq = 2" class="flex items-center justify-between w-full text-left">
                    <span class="text-sm font-medium text-gray-900">Apakah ada biaya pendaftaran?</span>
                    <i class="bi bi-chevron-down text-gray-400 transition" :class="openFaq === 2 ? 'rotate-180' : ''"></i>
                </button>
                <p x-show="openFaq === 2" x-collapse class="text-sm text-gray-600 mt-2">
                    Tidak ada biaya pendaftaran, komunitas ini gratis untuk seluruh siswa.
                </p>
            </div>

            <div class="py-4">
                <button @click="openFaq === 3 ? openFaq = null : openFaq = 3" class="flex items-center justify-between w-full text-left">
                    <span class="text-sm font-medium text-gray-900">Kapan jadwal kegiatan rutin?</span>
                    <i class="bi bi-chevron-down text-gray-400 transition" :class="openFaq === 3 ? 'rotate-180' : ''"></i>
                </button>
                <p x-show="openFaq === 3" x-collapse class="text-sm text-gray-600 mt-2">
                    Kegiatan rutin diadakan setiap hari Kamis sore, cek halaman About untuk info detail.
                </p>
            </div>

        </div>
    </section>
    <section class="bg-blue-50 rounded-2xl px-6 py-12 text-center">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $cta['title'] ?? 'Yuk, Gabung Sekarang!' }}</h2>
        <p class="text-gray-900 text-sm mb-6">{{ $cta['body'] ?? 'Jadi bagian dari EC Dwiguna dan asah kemampuan bahasa Inggrismu bareng komunitas.' }}</p>
        <div class="flex justify-center gap-3">
            <a href="{{ route('student.register') }}" class="px-5 py-2.5 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-50 hover:text-blue-500 hover:border transition">
                Sign Up
            </a>
            <a href="{{ route('login') }}" class="px-5 py-2.5 border border-gray-500 text-gray-900 rounded-lg text-sm font-medium hover:bg-gray-800 hover:text-white transition">
                Sign In
            </a>
        </div>
    </section>
@endsection