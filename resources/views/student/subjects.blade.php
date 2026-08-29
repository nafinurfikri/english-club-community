@extends('layouts.app')

@section('title', 'Mata Pelajaran - Student English Club')

@section('header')
    <header class="bg-white shadow-sm border-b border-gray-100 px-3 sm:px-6 py-3 sm:py-4 flex items-center justify-between gap-3"
            x-data="{ search: '' }">
        <div class="min-w-0">
            <h1 class="text-lg sm:text-xl font-bold text-gray-900 leading-tight">Kurikulum &amp; Mata Pelajaran</h1>
            <p class="text-xs sm:text-sm text-gray-500">View details of your current enrollments and available learning modules.</p>
        </div>

        <div class="flex items-center gap-2 sm:gap-4 shrink-0">
            <div class="relative hidden md:block w-40 lg:w-56">
                <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" x-model="search" placeholder="Search"
                       class="w-full pl-10 pr-4 py-2 rounded-full bg-gray-100 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300"
                       @input="$dispatch('subject-search', search)">
            </div>

            <button class="relative text-gray-500 hover:text-gray-700 transition">
                <i class="bi bi-bell text-xl"></i>
                <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
            </button>

            <span class="hidden sm:block w-px h-6 bg-gray-200"></span>

            <span class="bg-emerald-100 text-emerald-700 text-xs font-semibold px-3 py-1.5 rounded-full whitespace-nowrap">Active Academic</span>
        </div>
    </header>
@endsection

@section('content')

    <div x-data="{
            levels: 'all',
            search: '',
            subjects: @js($subjects->values()),
            get levelOptions() {
                return [...new Set(this.subjects.map(s => s.level))];
            },
            get filtered() {
                return this.subjects.filter(s =>
                    (this.levels === 'all' || s.level === this.levels) &&
                    (this.search === '' || s.title.toLowerCase().includes(this.search.toLowerCase()))
                );
            }
        }"
        @subject-search.window="search = $event.detail">

        <!-- Filter Bar -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-4 mb-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <select x-model="levels"
                    class="w-full sm:w-64 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 font-medium focus:outline-none focus:ring-2 focus:ring-blue-300">
                <option value="all">All Levels &amp; Programs</option>
                <template x-for="lvl in levelOptions" :key="lvl">
                    <option :value="lvl" x-text="lvl"></option>
                </template>
            </select>

            <span class="text-sm font-semibold text-blue-600" x-text="filtered.length + ' Available Modules'"></span>
        </div>

        <!-- Subjects Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">

            <template x-for="s in filtered" :key="s.id">
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden flex flex-col">
                    <!-- Card Image -->
                    <div class="relative h-36 bg-gray-200">
                        <img :src="s.img" :alt="s.title" class="w-full h-full object-cover" loading="lazy">
                        <span class="absolute top-3 left-3 bg-black/60 backdrop-blur-sm text-white text-xs px-2.5 py-1 rounded-md font-semibold"
                              x-text="s.level"></span>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 flex flex-col flex-1">
                        <h3 class="text-lg font-bold text-gray-900" x-text="s.title"></h3>
                        <p class="text-xs text-gray-500 mt-1.5 flex items-center gap-1.5">
                            <i class="bi bi-person text-sm"></i>
                            <span x-text="s.teacher"></span>
                        </p>
                        <p class="text-sm text-gray-500 mt-3 truncate" x-text="s.desc"></p>

                        <!-- Card Footer -->
                        <div class="mt-auto pt-4 flex items-center justify-end gap-2">
                            <button class="bg-blue-50 text-blue-600 hover:bg-blue-100 px-4 py-2 rounded-lg text-xs font-semibold transition-colors duration-200">
                                Details
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Empty State -->
            <div x-show="filtered.length === 0" class="col-span-full bg-white border border-dashed border-gray-300 rounded-2xl p-10 text-center">
                <i class="bi bi-inbox text-3xl text-gray-300"></i>
                <p class="text-sm text-gray-500 mt-2">Tidak ada mata pelajaran yang cocok dengan filter.</p>
            </div>
        </div>
    </div>

@endsection