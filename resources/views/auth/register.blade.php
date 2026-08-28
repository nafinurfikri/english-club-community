@extends('layouts.app')

@section('title', 'Daftar Student - English Club Community')

@section('content')
    <div class="mx-auto w-full max-w-2xl py-4 sm:py-8">
        <div class="mb-6 text-center">
            <span class="text-sm font-semibold text-blue-600">EC Dwiguna</span>
            <h1 class="mt-2 text-2xl sm:text-3xl font-bold text-gray-900">Daftar sebagai Student</h1>
            <p class="mt-2 text-sm text-gray-500">Buat akun untuk mengakses dashboard dan kegiatan English Club.</p>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700" role="alert">
                <p class="font-semibold">Pendaftaran belum berhasil.</p>
                <ul class="mt-1 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('student.register.store') }}" class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-8">
            @csrf

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="name" class="mb-1.5 block text-sm font-semibold text-gray-700">Nama Lengkap</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name"
                           placeholder="Masukkan nama lengkap"
                           class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>

                <div class="sm:col-span-2">
                    <label for="email" class="mb-1.5 block text-sm font-semibold text-gray-700">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email"
                           placeholder="nama@email.com"
                           class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>

                <div>
                    <label for="password" class="mb-1.5 block text-sm font-semibold text-gray-700">Password</label>
                    <input id="password" name="password" type="password" required minlength="8" autocomplete="new-password"
                           placeholder="Minimal 8 karakter"
                           class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>

                <div>
                    <label for="password_confirmation" class="mb-1.5 block text-sm font-semibold text-gray-700">Konfirmasi Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" autocomplete="new-password"
                           placeholder="Ulangi password"
                           class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
            </div>

            <button type="submit" class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2">
                <i class="bi bi-person-plus-fill"></i>
                Buat Akun Student
            </button>

            <p class="mt-5 text-center text-xs text-gray-500">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700">Masuk di sini</a>.
            </p>
        </form>
    </div>
@endsection
