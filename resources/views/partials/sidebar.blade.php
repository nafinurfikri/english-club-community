@php
    $activeClass = 'bg-blue-500 text-white hover:bg-gray-700';
    $normalClass = 'text-gray-900 hover:bg-blue-500 hover:text-white';
@endphp

{{-- Overlay gelap saat sidebar terbuka di HP --}}
<div 
    x-show="sidebarOpen" 
    @click="sidebarOpen = false"
    x-transition.opacity
    class="fixed inset-0 bg-black/40 z-30 lg:hidden"
    style="display: none;">
</div>

<aside 
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="w-64 p-4 border-r border-gray-200 bg-gray-100 h-screen overflow-y-auto
           fixed lg:sticky top-0 z-40 transition-transform duration-300">

    <button @click="sidebarOpen = false" class="lg:hidden mb-4 text-gray-500">
        <i class="bi bi-x-lg text-xl"></i>
    </button>

    <div class="flex items-center gap-2 mb-6 text-blue-500">
        <img src="{{ asset('images/ec.png') }}" alt="Logo English Club" class="w-12 h-12">
        <h2 class="text-xl font-bold mb-2">EC Dwiguna</h2>
    </div>

    <nav class="flex flex-col gap-2" 
         x-data="{ 
             hoveredItem: null, 
             leaveTimeout: null,
             setHover(item) {
                 clearTimeout(this.leaveTimeout);
                 this.hoveredItem = item;
             },
             clearHover() {
                 this.leaveTimeout = setTimeout(() => {
                     this.hoveredItem = null;
                 }, 100);
             }
         }">

        <a href="{{ route('home') }}" 
            @mouseenter="setHover('home')"
            @mouseleave="clearHover()"
            :class="(hoveredItem === 'home' || ({{ request()->routeIs('home') ? 'true' : 'false' }} && hoveredItem === null)) 
                    ? 'bg-blue-500 text-white' 
                    : 'text-gray-900'"
            class="flex items-center gap-2 px-3 py-2 rounded transition">
            <i class="bi bi-house"></i>
            <span>Home</span>
        </a>

        <a href="{{ route('about') }}" 
            @mouseenter="setHover('about')"
            @mouseleave="clearHover()"
            :class="(hoveredItem === 'about' || ({{ request()->routeIs('about') ? 'true' : 'false' }} && hoveredItem === null)) 
                    ? 'bg-blue-500 text-white' 
                    : 'text-gray-900'"
            class="flex items-center gap-2 px-3 py-2 rounded transition">
            <i class="bi bi-info-circle"></i>
            <span>About</span>
        </a>

        <a href="{{ route('announcement') }}" 
            @mouseenter="setHover('announcement')"
            @mouseleave="clearHover()"
            :class="(hoveredItem === 'announcement' || ({{ request()->routeIs('announcement') ? 'true' : 'false' }} && hoveredItem === null)) 
                    ? 'bg-blue-500 text-white' 
                    : 'text-gray-900'"
            class="flex items-center gap-2 px-3 py-2 rounded transition">
            <i class="bi bi-bell"></i>
            <span>Announcement</span>
        </a>

        <a href="{{ route('gallery') }}" 
            @mouseenter="setHover('gallery')"
            @mouseleave="clearHover()"
            :class="(hoveredItem === 'gallery' || ({{ request()->routeIs('gallery') ? 'true' : 'false' }} && hoveredItem === null)) 
                    ? 'bg-blue-500 text-white' 
                    : 'text-gray-900'"
            class="flex items-center gap-2 px-3 py-2 rounded transition">
            <i class="bi bi-image"></i>
            <span>Gallery</span>
        </a>

    </nav>
</aside>