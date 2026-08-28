@extends('layouts.app')

@section('title', 'Mata Pelajaran - Student English Club')

@section('header')
    <header class="bg-white shadow-sm border-b border-gray-100 px-4 sm:px-6 py-4 flex items-center justify-between gap-4"
            x-data="{ search: '' }">
        <div>
            <h1 class="text-lg sm:text-xl font-bold text-gray-900 leading-tight">Kurikulum &amp; Mata Pelajaran</h1>
            <p class="text-xs sm:text-sm text-gray-500">View details of your current enrollments and available learning modules.</p>
        </div>

        <div class="flex items-center gap-3 sm:gap-4">
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
            subjects: [
                { title: 'Advanced Grammar', level: 'Level 3 - Int', teacher: 'Dr. Elizabeth Stone', desc: 'Comprehensive review of complex grammatical structures and advanced usage.', students: 28, img: 'https://picsum.photos/seed/ec-grammar/640/300' },
                { title: 'Conversational Practice', level: 'Level 2 - Basic', teacher: 'James Mitchell, M.A.', desc: 'Interactive discussion sessions aimed at enhancing everyday fluency.', students: 32, img: 'https://picsum.photos/seed/ec-conversation/640/300' },
                { title: 'Listening Comprehension', level: 'Level 1 - Beg', teacher: 'Sarah Connor, B.E.d', desc: 'Designed to build auditory skills focused on daily conversations.', students: 20, img: 'https://picsum.photos/seed/ec-listening/640/300' },
                { title: 'Public Speaking', level: 'Level 4 - Adv', teacher: 'Prof. Arthur Pendelton', desc: 'Developing rhetorical speaking capacities, body language, and stage presence.', students: 15, img: 'https://picsum.photos/seed/ec-speaking/640/300' },
                { title: 'Business English', level: 'Professional Class', teacher: 'William Sterling, MBA', desc: 'Instruction focused on corporate presentations and email etiquette.', students: 25, img: 'https://picsum.photos/seed/ec-business/640/300' },
                { title: 'TOEFL Preparation', level: 'Special Prep', teacher: 'Dr. Amanda Ross', desc: 'Rigorous diagnostic testing and skill builder tasks for the TOEFL exam.', students: 40, img: 'https://picsum.photos/seed/ec-toefl/640/300' }
            ],
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
                <option value="Level 1 - Beg">Level 1 - Beg</option>
                <option value="Level 2 - Basic">Level 2 - Basic</option>
                <option value="Level 3 - Int">Level 3 - Int</option>
                <option value="Level 4 - Adv">Level 4 - Adv</option>
                <option value="Professional Class">Professional Class</option>
                <option value="Special Prep">Special Prep</option>
            </select>

            <span class="text-sm font-semibold text-blue-600" x-text="filtered.length + ' Available Modules'"></span>
        </div>

        <!-- Subjects Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">

            <template x-for="s in filtered" :key="s.title">
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
                        <div class="mt-auto pt-4 flex items-center justify-between gap-2">
                            <span class="text-xs font-semibold text-blue-600 flex items-center gap-1.5">
                                <i class="bi bi-people text-sm"></i>
                                <span x-text="s.students + ' Active Students'"></span>
                            </span>
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
