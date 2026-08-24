<header class="bg-white shadow px-4 sm:px-6 py-4 flex items-center justify-between gap-4">

    <button @click="sidebarOpen = true" class="lg:hidden text-gray-600">
        <i class="bi bi-list text-2xl"></i>
    </button>

    {{-- Search bar --}}
    <div class="relative flex-1 max-w-sm">
        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        <input 
            type="text" 
            placeholder="Search records..." 
            class="w-full pl-10 pr-4 py-2.5 rounded-full bg-gray-100 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300">
    </div>

    <a href="#">
        <div class="flex items-center gap-2">
            <p class="text-sm font-medium text-gray-900 hidden sm:block">Login</p>
            <div class="w-9 h-9 rounded-full bg-blue-500 flex items-center justify-center text-white">
                <i class="bi bi-person-fill"></i>
            </div>
        </div>
    </a>
</header>