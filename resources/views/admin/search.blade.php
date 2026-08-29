@extends('layouts.app')

@section('title', 'Hasil Pencarian - Admin English Club')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Hasil Pencarian</h1>
        <p class="text-xs sm:text-sm text-gray-500">
            @if ($query !== '')
                Menampilkan hasil untuk kata kunci <span class="font-semibold text-gray-700">"{{ $query }}"</span>.
            @else
                Masukkan kata kunci untuk mencari data.
            @endif
        </p>
    </div>

    <form method="GET" action="{{ route('admin.search') }}" class="mb-6 flex max-w-xl gap-2">
        <div class="relative flex-1">
            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" name="q" value="{{ $query }}"
                   placeholder="Cari siswa, mata pelajaran, pengumuman, materi..."
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-white border border-gray-200 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300">
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2.5 rounded-xl text-sm transition shrink-0">
            <i class="bi bi-search"></i> Cari
        </button>
    </form>

    @if ($query === '')
        <div class="rounded-xl border border-dashed border-gray-300 bg-white p-10 text-center">
            <i class="bi bi-search text-4xl text-gray-300"></i>
            <p class="mt-3 text-sm text-gray-500">Ketik kata kunci pada kolom pencarian untuk mulai mencari data.</p>
        </div>
    @else
        @php
            $groups = [
                'students' => ['label' => 'Siswa', 'icon' => 'bi-people', 'empty' => 'Tidak ada siswa yang cocok.'],
                'subjects' => ['label' => 'Mata Pelajaran', 'icon' => 'bi-book', 'empty' => 'Tidak ada mata pelajaran yang cocok.'],
                'announcements' => ['label' => 'Pengumuman', 'icon' => 'bi-megaphone', 'empty' => 'Tidak ada pengumuman yang cocok.'],
                'materials' => ['label' => 'Materi Pembelajaran', 'icon' => 'bi-file-earmark-text', 'empty' => 'Tidak ada materi yang cocok.'],
            ];
        @endphp

        @foreach ($groups as $key => $group)
            <section class="mb-6">
                <div class="mb-3 flex items-center gap-2">
                    <i class="bi {{ $group['icon'] }} text-blue-600"></i>
                    <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">{{ $group['label'] }}</h2>
                    <span class="text-xs text-gray-400">({{ $results[$key]->count() }})</span>
                </div>

                @if ($results[$key]->isEmpty())
                    <p class="text-sm text-gray-400">{{ $group['empty'] }}</p>
                @else
                    <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach ($results[$key] as $item)
                            <li>
                                @if ($key === 'students')
                                    <a href="{{ route('admin.students') }}"
                                       class="flex items-center justify-between gap-3 rounded-xl border border-gray-200 bg-white px-4 py-3 transition hover:border-blue-300 hover:shadow-sm">
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-semibold text-gray-900">{{ $item->name }}</p>
                                            <p class="truncate text-xs text-gray-500">{{ $item->email }}</p>
                                        </div>
                                        <span class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $item->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $item->status }}
                                        </span>
                                    </a>
                                @elseif ($key === 'subjects')
                                    <a href="{{ route('admin.subjects') }}"
                                       class="flex items-center gap-3 rounded-xl border border-gray-200 bg-white px-4 py-3 transition hover:border-blue-300 hover:shadow-sm">
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-semibold text-gray-900">{{ $item->name }}</p>
                                            <p class="truncate text-xs text-gray-500">{{ $item->level }} &middot; {{ $item->teacher }}</p>
                                        </div>
                                    </a>
                                @elseif ($key === 'announcements')
                                    <a href="{{ route('admin.announcements') }}"
                                       class="flex items-center gap-3 rounded-xl border border-gray-200 bg-white px-4 py-3 transition hover:border-blue-300 hover:shadow-sm">
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-semibold text-gray-900">{{ $item->title }}</p>
                                            <p class="truncate text-xs text-gray-500">{{ $item->published_at?->format('d M Y') ?? 'Belum dipublikasi' }}</p>
                                        </div>
                                    </a>
                                @else
                                    <a href="{{ route('admin.attendance') }}"
                                       class="flex items-center gap-3 rounded-xl border border-gray-200 bg-white px-4 py-3 transition hover:border-blue-300 hover:shadow-sm">
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-semibold text-gray-900">{{ $item->title }}</p>
                                            <p class="truncate text-xs text-gray-500">{{ $item->clubSession?->title }}</p>
                                        </div>
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        @endforeach

        @if (collect($results)->every(fn ($items) => $items->isEmpty()))
            <div class="rounded-xl border border-gray-200 bg-white p-10 text-center">
                <i class="bi bi-search-x text-4xl text-gray-300"></i>
                <p class="mt-3 text-sm font-semibold text-gray-700">Tidak ditemukan hasil untuk "{{ $query }}".</p>
                <p class="text-sm text-gray-500">Coba kata kunci lain atau periksa ejaan.</p>
            </div>
        @endif
    @endif
@endsection
