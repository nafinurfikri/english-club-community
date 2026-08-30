<div 
    x-show="sidebarOpen" 
    @click="sidebarOpen = false"
    x-transition.opacity
    class="fixed inset-0 bg-black/40 z-30 lg:hidden"
    style="display: none;">
</div>

<aside 
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="w-64 max-w-[85vw] p-4 border-r border-gray-200 bg-gray-100 h-screen overflow-y-auto flex flex-col
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

    <nav class="flex flex-col flex-1 gap-1"
         x-data="{ 
             hoveredItem: null,
             activeItem: null, 
             leaveTimeout: null,
             setHover(item) {
                 clearTimeout(this.leaveTimeout);
                 this.hoveredItem = item;
             },
             clearHover() {
                 this.leaveTimeout = setTimeout(() => {
                     this.hoveredItem = null;
                 }, 100);
            },
            setActive(item) {
                this.activeItem = item;
            }
                }">

        @if (request()->routeIs('home', 'about', 'announcement', 'gallery'))
        <!-- GUEST SECTION -->
        <div class="px-3 pt-2 pb-1 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
            Public / Guest
        </div>

        <a href="{{ route('home') }}" 
            @click="setActive('home')"
            @mouseenter="setHover('home')"
            @mouseleave="clearHover()"
            :class="(hoveredItem === 'home' || activeItem === 'home' || ({{ request()->routeIs('home') ? 'true' : 'false' }} && hoveredItem === null && activeItem === null)) 
                    ? 'bg-blue-500 text-white' 
                    : 'text-gray-700 hover:bg-gray-200'"
            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-300">
            <i class="bi bi-house text-base"></i>
            <span>Home</span>
        </a>

        <a href="{{ route('about') }}" 
            @click="setActive('about')"
            @mouseenter="setHover('about')"
            @mouseleave="clearHover()"
            :class="(hoveredItem === 'about' || activeItem === 'about' || ({{ request()->routeIs('about') ? 'true' : 'false' }} && hoveredItem === null && activeItem === null)) 
                    ? 'bg-blue-500 text-white' 
                    : 'text-gray-700 hover:bg-gray-200'"
            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-300">
            <i class="bi bi-info-circle text-base"></i>
            <span>About</span>
        </a>

        <a href="{{ route('announcement') }}" 
            @click="setActive('announcement')"
            @mouseenter="setHover('announcement')"
            @mouseleave="clearHover()"
            :class="(hoveredItem === 'announcement' || activeItem === 'announcement' || ({{ request()->routeIs('announcement') ? 'true' : 'false' }} && hoveredItem === null && activeItem === null)) 
                    ? 'bg-blue-500 text-white' 
                    : 'text-gray-700 hover:bg-gray-200'"
            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-300">
            <i class="bi bi-bell text-base"></i>
            <span>Announcement</span>
        </a>

        <a href="{{ route('gallery') }}" 
            @click="setActive('gallery')"
            @mouseenter="setHover('gallery')"
            @mouseleave="clearHover()"
            :class="(hoveredItem === 'gallery' || activeItem === 'gallery' || ({{ request()->routeIs('gallery') ? 'true' : 'false' }} && hoveredItem === null && activeItem === null)) 
                    ? 'bg-blue-500 text-white' 
                    : 'text-gray-700 hover:bg-gray-200'"
            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-300">
            <i class="bi bi-image text-base"></i>
            <span>Gallery</span>
        </a>
        @endif

        @if (request()->routeIs('admin.*'))
        <!-- ADMIN SECTION -->
        <div class="px-3 pt-4 pb-1 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
            Admin Panel
        </div>

        <a href="{{ route('admin.dashboard') }}" 
            @click="setActive('admin_dashboard')"
            @mouseenter="setHover('admin_dashboard')"
            @mouseleave="clearHover()"
            :class="(hoveredItem === 'admin_dashboard' || activeItem === 'admin_dashboard' || ({{ request()->routeIs('admin.dashboard') ? 'true' : 'false' }} && hoveredItem === null && activeItem === null)) 
                    ? 'bg-blue-500 text-white' 
                    : 'text-gray-700 hover:bg-gray-200'"
            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-300">
            <i class="bi bi-speedometer2 text-base"></i>
            <span>Overview Admin</span>
        </a>

        <a href="{{ route('admin.attendance') }}" 
            @click="setActive('admin_attendance')"
            @mouseenter="setHover('admin_attendance')"
            @mouseleave="clearHover()"
            :class="(hoveredItem === 'admin_attendance' || activeItem === 'admin_attendance' || ({{ request()->routeIs('admin.attendance') ? 'true' : 'false' }} && hoveredItem === null && activeItem === null)) 
                    ? 'bg-blue-500 text-white' 
                    : 'text-gray-700 hover:bg-gray-200'"
            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-300">
            <i class="bi bi-qr-code-scan text-base"></i>
            <span>Kehadiran & OTP</span>
        </a>

        <a href="{{ route('admin.attendance.history') }}" 
            @click="setActive('admin_attendance_history')"
            @mouseenter="setHover('admin_attendance_history')"
            @mouseleave="clearHover()"
            :class="(hoveredItem === 'admin_attendance_history' || activeItem === 'admin_attendance_history' || ({{ request()->routeIs('admin.attendance.history*') ? 'true' : 'false' }} && hoveredItem === null && activeItem === null)) 
                    ? 'bg-blue-500 text-white' 
                    : 'text-gray-700 hover:bg-gray-200'"
            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-300">
            <i class="bi bi-calendar-check text-base"></i>
            <span>Riwayat Kehadiran</span>
        </a>

        <a href="{{ route('admin.students') }}" 
            @click="setActive('admin_students')"
            @mouseenter="setHover('admin_students')"
            @mouseleave="clearHover()"
            :class="(hoveredItem === 'admin_students' || activeItem === 'admin_students' || ({{ request()->routeIs('admin.students') ? 'true' : 'false' }} && hoveredItem === null && activeItem === null)) 
                    ? 'bg-blue-500 text-white' 
                    : 'text-gray-700 hover:bg-gray-200'"
            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-300">
            <i class="bi bi-people text-base"></i>
            <span>Data Siswa</span>
        </a>

        <a href="{{ route('admin.subjects') }}" 
            @click="setActive('admin_subjects')"
            @mouseenter="setHover('admin_subjects')"
            @mouseleave="clearHover()"
            :class="(hoveredItem === 'admin_subjects' || activeItem === 'admin_subjects' || ({{ request()->routeIs('admin.subjects') ? 'true' : 'false' }} && hoveredItem === null && activeItem === null)) 
                    ? 'bg-blue-500 text-white' 
                    : 'text-gray-700 hover:bg-gray-200'"
            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-300">
            <i class="bi bi-book text-base"></i>
            <span>Data Mata Pelajaran</span>
        </a>

        <a href="{{ route('admin.announcements') }}" 
            @click="setActive('admin_announcements')"
            @mouseenter="setHover('admin_announcements')"
            @mouseleave="clearHover()"
            :class="(hoveredItem === 'admin_announcements' || activeItem === 'admin_announcements' || ({{ request()->routeIs('admin.announcements') ? 'true' : 'false' }} && hoveredItem === null && activeItem === null)) 
                    ? 'bg-blue-500 text-white' 
                    : 'text-gray-700 hover:bg-gray-200'"
            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-300">
            <i class="bi bi-megaphone text-base"></i>
            <span>Kelola Pengumuman</span>
        </a>

        <a href="{{ route('admin.gallery') }}" 
            @click="setActive('admin_gallery')"
            @mouseenter="setHover('admin_gallery')"
            @mouseleave="clearHover()"
            :class="(hoveredItem === 'admin_gallery' || activeItem === 'admin_gallery' || ({{ request()->routeIs('admin.gallery') ? 'true' : 'false' }} && hoveredItem === null && activeItem === null)) 
                    ? 'bg-blue-500 text-white' 
                    : 'text-gray-700 hover:bg-gray-200'"
            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-300">
            <i class="bi bi-images text-base"></i>
            <span>Kelola Galeri</span>
        </a>

        @endif

        @if (request()->routeIs('student.*'))
    <!-- STUDENT SECTION -->
    <div class="px-3 pt-4 pb-1 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
        Student Menu
    </div>

    <a href="{{ route('student.dashboard') }}"
        @click="setActive('student_dashboard')"
            @mouseenter="setHover('student_dashboard')"
        @mouseleave="clearHover()"
        :class="(hoveredItem === 'student_dashboard' || activeItem === 'student_dashboard' || ({{ request()->routeIs('student.dashboard') ? 'true' : 'false' }} && hoveredItem === null && activeItem === null))
                ? 'bg-blue-500 text-white'
                : 'text-gray-700 hover:bg-gray-200'"
        class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-300">
        <i class="bi bi-grid-fill text-base"></i>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('student.subjects') }}"
        @click="setActive('student_subjects')"
        @mouseenter="setHover('student_subjects')"
        @mouseleave="clearHover()"
        :class="(hoveredItem === 'student_subjects' || activeItem === 'student_subjects' || ({{ request()->routeIs('student.subjects') ? 'true' : 'false' }} && hoveredItem === null && activeItem === null))
                ? 'bg-blue-500 text-white'
                : 'text-gray-700 hover:bg-gray-200'"
        class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-300">
        <i class="bi bi-book text-base"></i>
        <span>Mata Pelajaran</span>
    </a>

    <a href="{{ route('student.attendance') }}"
        @click="setActive('student_attendance')"
        @mouseenter="setHover('student_attendance')"
        @mouseleave="clearHover()"
        :class="(hoveredItem === 'student_attendance' || activeItem === 'student_attendance' || ({{ request()->routeIs('student.attendance') ? 'true' : 'false' }} && hoveredItem === null && activeItem === null))
                ? 'bg-blue-500 text-white'
                : 'text-gray-700 hover:bg-gray-200'"
        class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-300">
        <i class="bi bi-calendar-check text-base"></i>
        <span>Presensi</span>
    </a>

    <a href="{{ route('student.announcements') }}"
        @click="setActive('student_announcements')"
            @mouseenter="setHover('student_announcements')"
        @mouseleave="clearHover()"
        :class="(hoveredItem === 'student_announcements' || activeItem === 'student_announcements' || ({{ request()->routeIs('student.announcements') ? 'true' : 'false' }} && hoveredItem === null && activeItem === null)) ? 'bg-blue-500 text-white' : 'text-gray-700 hover:bg-gray-200'"
        class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-300">
        <i class="bi bi-bell text-base"></i>
        <span>Pengumuman</span>
    </a>

    <a href="{{ route('student.profile') }}"
        @click="setActive('student_profile')"
            @mouseenter="setHover('student_profile')"
        @mouseleave="clearHover()"
        :class="(hoveredItem === 'student_profile' || activeItem === 'student_profile' || ({{ request()->routeIs('student.profile') ? 'true' : 'false' }} && hoveredItem === null && activeItem === null)) ? 'bg-blue-500 text-white' : 'text-gray-700 hover:bg-gray-200'"
        class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-300">
        <i class="bi bi-person text-base"></i>
        <span>Profile &amp; Stats</span>
    </a>
        @endif

    </nav>

    @if (request()->routeIs('admin.*') || request()->routeIs('student.*'))
    <!-- User Menu Card -->
    <div class="relative mt-auto pt-6" x-data="{ open: false }">
        <div x-show="open" @click.away="open = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-2"
             class="absolute bottom-full left-0 right-0 mb-0.5 bg-white border border-gray-200 rounded-xl shadow-xl py-1.5 overflow-hidden origin-bottom">
            <a href="{{ request()->routeIs('admin.*') ? route('admin.dashboard') : route('student.dashboard') }}"
               class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                <i class="bi bi-speedometer2 text-blue-600"></i> Dashboard
            </a>
            @if (request()->routeIs('student.*'))
            <a href="{{ route('student.profile') }}"
               class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                <i class="bi bi-person text-blue-600"></i> Profile
            </a>
            @endif
            <div class="my-1 border-t border-gray-100"></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left flex items-center gap-2.5 px-4 py-2 text-sm text-rose-600 hover:bg-rose-50">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
        <button @click="open = !open"
                :class="open ? 'bg-gray-200 border-gray-300' : 'bg-gray-200/50 border-gray-200'"
                class="w-full border rounded-xl p-3 flex items-center gap-3 hover:bg-gray-200 transition text-left cursor-pointer">
            <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-bold shrink-0 uppercase shadow-sm">
                {{ str(auth()->user()?->name ?? 'U')->substr(0, 2) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-bold text-gray-800 truncate">{{ auth()->user()?->name ?? 'User' }}</p>
                <span class="text-xs text-gray-500">{{ request()->routeIs('admin.*') ? 'Admin Panel' : 'Student ID: '.auth()->user()?->id }}</span>
            </div>
            <i class="bi bi-chevron-up text-xs text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
        </button>
    </div>
    @endif
</aside>