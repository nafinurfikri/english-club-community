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

    <div class="flex items-center gap-2 mb-6 text-blue-600">
        <img src="{{ asset('images/ec.png') }}" alt="Logo English Club" class="w-10 h-10 object-contain" onerror="this.src='https://ui-avatars.com/api/?name=EC&background=3b82f6&color=fff'">
        <div>
            <h2 class="text-lg font-bold leading-tight">EC Dwiguna</h2>
            <span class="text-xs text-gray-500">Community Portal</span>
        </div>
    </div>

    <nav class="flex flex-col gap-1" 
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

        <!-- GUEST SECTION -->
        <div class="px-3 pt-2 pb-1 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
            Public / Guest
        </div>

        <a href="{{ route('home') }}" 
            @mouseenter="setHover('home')"
            @mouseleave="clearHover()"
            :class="(hoveredItem === 'home' || ({{ request()->routeIs('home') ? 'true' : 'false' }} && hoveredItem === null)) 
                    ? 'bg-blue-500 text-white' 
                    : 'text-gray-700 hover:bg-gray-200'"
            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition">
            <i class="bi bi-house text-base"></i>
            <span>Home</span>
        </a>

        <a href="{{ route('about') }}" 
            @mouseenter="setHover('about')"
            @mouseleave="clearHover()"
            :class="(hoveredItem === 'about' || ({{ request()->routeIs('about') ? 'true' : 'false' }} && hoveredItem === null)) 
                    ? 'bg-blue-500 text-white' 
                    : 'text-gray-700 hover:bg-gray-200'"
            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition">
            <i class="bi bi-info-circle text-base"></i>
            <span>About</span>
        </a>

        <a href="{{ route('announcement') }}" 
            @mouseenter="setHover('announcement')"
            @mouseleave="clearHover()"
            :class="(hoveredItem === 'announcement' || ({{ request()->routeIs('announcement') ? 'true' : 'false' }} && hoveredItem === null)) 
                    ? 'bg-blue-500 text-white' 
                    : 'text-gray-700 hover:bg-gray-200'"
            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition">
            <i class="bi bi-bell text-base"></i>
            <span>Announcement</span>
        </a>

        <a href="{{ route('gallery') }}" 
            @mouseenter="setHover('gallery')"
            @mouseleave="clearHover()"
            :class="(hoveredItem === 'gallery' || ({{ request()->routeIs('gallery') ? 'true' : 'false' }} && hoveredItem === null)) 
                    ? 'bg-blue-500 text-white' 
                    : 'text-gray-700 hover:bg-gray-200'"
            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition">
            <i class="bi bi-image text-base"></i>
            <span>Gallery</span>
        </a>

        <!-- ADMIN SECTION -->
        <div class="px-3 pt-4 pb-1 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
            Admin Panel
        </div>

        <a href="{{ route('admin.dashboard') }}" 
            @mouseenter="setHover('admin_dashboard')"
            @mouseleave="clearHover()"
            :class="(hoveredItem === 'admin_dashboard' || ({{ request()->routeIs('admin.dashboard') ? 'true' : 'false' }} && hoveredItem === null)) 
                    ? 'bg-blue-500 text-white' 
                    : 'text-gray-700 hover:bg-gray-200'"
            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition">
            <i class="bi bi-speedometer2 text-base"></i>
            <span>Overview Admin</span>
        </a>

        <a href="{{ route('admin.attendance') }}" 
            @mouseenter="setHover('admin_attendance')"
            @mouseleave="clearHover()"
            :class="(hoveredItem === 'admin_attendance' || ({{ request()->routeIs('admin.attendance') ? 'true' : 'false' }} && hoveredItem === null)) 
                    ? 'bg-blue-500 text-white' 
                    : 'text-gray-700 hover:bg-gray-200'"
            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition">
            <i class="bi bi-qr-code-scan text-base"></i>
            <span>Kehadiran & OTP</span>
        </a>

        <a href="{{ route('admin.students') }}" 
            @mouseenter="setHover('admin_students')"
            @mouseleave="clearHover()"
            :class="(hoveredItem === 'admin_students' || ({{ request()->routeIs('admin.students') ? 'true' : 'false' }} && hoveredItem === null)) 
                    ? 'bg-blue-500 text-white' 
                    : 'text-gray-700 hover:bg-gray-200'"
            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition">
            <i class="bi bi-people text-base"></i>
            <span>Data Siswa</span>
        </a>

        <a href="{{ route('admin.grades') }}" 
            @mouseenter="setHover('admin_grades')"
            @mouseleave="clearHover()"
            :class="(hoveredItem === 'admin_grades' || ({{ request()->routeIs('admin.grades') ? 'true' : 'false' }} && hoveredItem === null)) 
                    ? 'bg-blue-500 text-white' 
                    : 'text-gray-700 hover:bg-gray-200'"
            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition">
            <i class="bi bi-journal-bookmark text-base"></i>
            <span>Data Nilai</span>
        </a>

        <a href="{{ route('admin.announcements') }}" 
            @mouseenter="setHover('admin_announcements')"
            @mouseleave="clearHover()"
            :class="(hoveredItem === 'admin_announcements' || ({{ request()->routeIs('admin.announcements') ? 'true' : 'false' }} && hoveredItem === null)) 
                    ? 'bg-blue-500 text-white' 
                    : 'text-gray-700 hover:bg-gray-200'"
            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition">
            <i class="bi bi-megaphone text-base"></i>
            <span>Kelola Pengumuman</span>
        </a>

        <a href="{{ route('admin.gallery') }}" 
            @mouseenter="setHover('admin_gallery')"
            @mouseleave="clearHover()"
            :class="(hoveredItem === 'admin_gallery' || ({{ request()->routeIs('admin.gallery') ? 'true' : 'false' }} && hoveredItem === null)) 
                    ? 'bg-blue-500 text-white' 
                    : 'text-gray-700 hover:bg-gray-200'"
            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition">
            <i class="bi bi-images text-base"></i>
            <span>Kelola Galeri</span>
        </a>

    </nav>
</aside>