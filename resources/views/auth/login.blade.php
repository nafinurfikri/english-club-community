@extends('layouts.app')

@section('title', 'Login - English Club Community')

@section('content')
    <div class="mx-auto w-full max-w-md py-4 sm:py-10">
        <div class="mb-6 text-center">
            <span class="text-sm font-semibold text-blue-600">EC Dwiguna</span>
            <h1 class="mt-2 text-2xl sm:text-3xl font-bold text-gray-900">Login Student</h1>
            <p class="mt-2 text-sm text-gray-500">Masuk untuk mengakses dashboard English Club.</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-8">
            @csrf

            <div class="space-y-5">
                <div>
                    <label for="email" class="mb-1.5 block text-sm font-semibold text-gray-700">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                           placeholder="nama@email.com"
                           class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>

                <div>
                    <label for="password" class="mb-1.5 block text-sm font-semibold text-gray-700">Password</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password"
                           placeholder="Masukkan password"
                           class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input name="remember" type="checkbox" value="1" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    Ingat saya
                </label>
            </div>

            <button type="submit" class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2">
                <i class="bi bi-box-arrow-in-right"></i>
                Masuk ke Dashboard
            </button>

            <p class="mt-5 text-center text-xs text-gray-500">
                Belum memiliki akun?
                <a href="{{ route('student.register') }}" class="font-semibold text-blue-600 hover:text-blue-700">Daftar sebagai student</a>.
            </p>
        </form>
    </div>
@endsection
