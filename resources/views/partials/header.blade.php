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

        <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition shrink-0">
            <i class="bi bi-person-fill"></i>
            Login
        </a>
    </div>
</header>
@else
<header class="bg-white shadow px-3 sm:px-6 py-3 sm:py-4 flex items-center justify-between gap-3">

    @if (request()->routeIs('admin.*', 'student.*'))
    <button @click="sidebarOpen = true" class="lg:hidden text-gray-600">
        <i class="bi bi-list text-2xl"></i>
    </button>
    @endif

    {{-- Search bar --}}
    <div class="relative flex-1 min-w-0 max-w-sm">
        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        <input 
            type="text" 
            placeholder="Search records..." 
            class="w-full pl-10 pr-4 py-2.5 rounded-full bg-gray-100 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300">
    </div>

    <a href="{{ route('login') }}">
        <div class="flex items-center gap-2">
            <p class="text-sm font-medium text-gray-900 hidden sm:block">Login</p>
            <div class="w-9 h-9 rounded-full bg-blue-500 flex items-center justify-center text-white">
                <i class="bi bi-person-fill"></i>
            </div>
        </div>
    </a>
</header>
@endif