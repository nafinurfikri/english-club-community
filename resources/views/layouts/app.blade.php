<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'English Club Community')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body class="bg-gray-50">

    <div class="flex" x-data="{ sidebarOpen: false }">
        
        @include('partials.sidebar')

        <div class="flex-1 flex flex-col min-w-0">

            @hasSection('header')
                @yield('header')
            @else
                @include('partials.header')
            @endif

            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>