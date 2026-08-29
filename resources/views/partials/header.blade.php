@if (request()->routeIs('home', 'about', 'announcement', 'gallery', 'student.register', 'login'))
<header class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-3 sm:px-8 lg:px-12 py-3 sm:py-4 flex items-center justify-between gap-2 sm:gap-6">
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-blue-600 shrink-0">
            <img src="{{ asset('images/ec.png') }}" alt="Logo English Club" class="w-10 h-10 object-contain" onerror="this.src='https://ui-avatars.com/api/?name=EC&background=3b82f6&color=fff'">
            <span class="font-bold text-gray-900 hidden sm:block">EC Dwiguna</span>
        </a>

        <nav class="min-w-0 flex-1 flex justify-center items-center gap-1 sm:gap-3 text-sm font-medium overflow-x-auto">
            <a href="{{ route('home') }}" class="px-2 sm:px-3 py-2 rounded-lg whitespace-nowrap {{ request()->routeIs('home') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">Home</a>
            <a href="{{ route('about') }}" class="px-2 sm:px-3 py-2 rounded-lg whitespace-nowrap {{ request()->routeIs('about') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">About</a>
            <a href="{{ route('announcement') }}" class="px-2 sm:px-3 py-2 rounded-lg whitespace-nowrap {{ request()->routeIs('announcement') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">Announcement</a>
            <a href="{{ route('gallery') }}" class="px-2 sm:px-3 py-2 rounded-lg whitespace-nowrap {{ request()->routeIs('gallery') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">Gallery</a>
        </nav>

        @auth
        <div class="relative shrink-0" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center gap-2 hover:opacity-90 transition">
                <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-bold uppercase shrink-0">
                    {{ str(auth()->user()->name)->substr(0, 2) }}
                </div>
                <span class="hidden sm:block text-sm font-semibold text-gray-900 max-w-[8rem] truncate">{{ auth()->user()->name }}</span>
                <i class="bi bi-chevron-down text-xs text-gray-500"></i>
            </button>
            <div x-show="open" @click.away="open = false"
                 x-transition
                 class="absolute right-0 top-full mt-2 w-48 bg-white border border-gray-200 rounded-xl shadow-lg py-1.5 z-50">
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('student.dashboard') }}"
                   class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                @if (auth()->user()->role !== 'admin')
                <a href="{{ route('student.profile') }}"
                   class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    <i class="bi bi-person"></i> Profile
                </a>
                @endif
                <div class="my-1 border-t border-gray-100"></div>
                <form method="POST" action="{{ route('logout') }}" class="px-0">
                    @csrf
                    <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-sm text-rose-600 hover:bg-rose-50">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>
        @else
        <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition shrink-0">
            <i class="bi bi-person-fill"></i>
            Login
        </a>
        @endauth
    </div>
</header>
@else
<header class="bg-white shadow px-3 sm:px-6 py-3 sm:py-4 flex items-center justify-between gap-3">

    @if (request()->routeIs('admin.*', 'student.*'))
    <button @click="sidebarOpen = true" class="lg:hidden text-gray-600">
        <i class="bi bi-list text-2xl"></i>
    </button>
    @endif

    @if (auth()->user()?->isAdmin())
    {{-- Search bar (admin global search) --}}
    <form method="GET" action="{{ route('admin.search') }}" class="relative flex-1 min-w-0 max-w-sm">
        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        <input
            type="search"
            name="q"
            value="{{ request('q') }}"
            placeholder="Search records..."
            class="w-full pl-10 pr-4 py-2.5 rounded-full bg-gray-100 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300">
    </form>
    @endif

    <div class="flex items-center gap-2 shrink-0">
        <p class="text-sm font-medium text-gray-900 hidden sm:block max-w-[10rem] truncate">{{ auth()->user()?->name ?? 'Login' }}</p>
        <div class="w-9 h-9 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-bold uppercase">
            {{ str(auth()->user()?->name ?? '?')->substr(0, 2) }}
        </div>
    </div>
</header>
@endif