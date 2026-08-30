<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'English Club Community')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/ec.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body class="bg-gray-50 overflow-x-hidden">

    @include('partials.toast')

    <div class="flex min-h-screen" @if (request()->routeIs('admin.*') || (request()->routeIs('student.*') && !request()->routeIs('student.register')) || request()->routeIs('home', 'about', 'announcement', 'gallery', 'student.register', 'login')) x-data="{ sidebarOpen: false }" @endif>

        @if (request()->routeIs('admin.*') || (request()->routeIs('student.*') && !request()->routeIs('student.register')))
        @include('partials.sidebar')
        @elseif (request()->routeIs('home', 'about', 'announcement', 'gallery', 'student.register', 'login'))
        @include('partials.sidebar', ['drawerOnly' => true])
        @endif

        <div class="flex-1 flex flex-col min-w-0">

            @hasSection('header')
                @yield('header')
            @else
                @include('partials.header')
            @endif

            <main class="{{ request()->routeIs('home', 'about', 'announcement', 'gallery', 'student.register', 'login') ? 'w-full max-w-7xl mx-auto px-3 sm:px-8 lg:px-12 pt-[68px] sm:pt-[76px] pb-4 sm:pb-6' : 'w-full min-w-0 p-3 sm:p-6' }}">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>