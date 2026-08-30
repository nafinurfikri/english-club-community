@extends('layouts.app')

@section('title', $subject['title'].' - Student English Club')

@section('header')
    <header class="bg-white shadow-sm border-b border-gray-100 px-3 sm:px-6 py-3 sm:py-4 flex items-center justify-between gap-3">
        <div class="min-w-0">
            <a href="{{ route('student.subjects') }}" class="text-sm text-blue-600 hover:text-blue-700 inline-flex items-center gap-1">
                <i class="bi bi-arrow-left"></i> Kembali ke Mata Pelajaran
            </a>
            <h1 class="mt-1 text-lg sm:text-xl font-bold text-gray-900 leading-tight truncate">{{ $subject['title'] }}</h1>
        </div>
        <span class="bg-emerald-100 text-emerald-700 text-xs font-semibold px-3 py-1.5 rounded-full whitespace-nowrap shrink-0">Active Academic</span>
    </header>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto">
        <!-- Subject Info -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm mb-5">
            <div class="flex items-center gap-3">
                <div class="h-14 w-14 rounded-xl overflow-hidden bg-gray-200 shrink-0">
                    <img src="{{ $subject['img'] }}" alt="{{ $subject['title'] }}" class="h-full w-full object-cover">
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-900">{{ $subject['title'] }}</p>
                    <p class="text-xs text-gray-500">{{ $subject['level'] }} &middot; {{ $subject['teacher'] }}</p>
                </div>
            </div>
            <p class="mt-3 text-sm text-gray-600">{{ $subject['desc'] }}</p>
        </div>

        @if (! $attended)
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 text-sm text-amber-700">
                <p class="font-semibold">Materi terkunci.</p>
                <p class="mt-1">Untuk mengakses materi mata pelajaran ini, selesaikan presensi pada sesi mata pelajaran ini terlebih dahulu.</p>
            </div>
        @endif

        @php($allMaterials = $subjectMaterials->concat($sessionMaterials))
        @if ($allMaterials->isEmpty())
            <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-10 text-center">
                <i class="bi bi-journal-x text-4xl text-gray-300"></i>
                <p class="mt-3 text-sm text-gray-500">Belum ada materi untuk mata pelajaran ini.</p>
            </div>
        @else
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                    <span class="text-sm font-bold text-gray-900">Materi Pembelajaran</span>
                    <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">{{ $allMaterials->count() }} Materi</span>
                </div>
                <ul class="divide-y divide-gray-100">
                    @foreach ($allMaterials as $material)
                        @php($allowed = auth()->user()->can('view', $material))
                        <li class="px-4 py-3 flex items-center justify-between gap-3">
                            <div class="min-w-0 flex items-center gap-3">
                                <span class="shrink-0 h-9 w-9 rounded-lg {{ $material->type === 'url' ? 'bg-blue-50 text-blue-600' : 'bg-emerald-50 text-emerald-600' }} flex items-center justify-center">
                                    <i class="bi {{ $material->type === 'url' ? 'bi-link-45deg' : 'bi-file-earmark-text' }}"></i>
                                </span>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-gray-900">{{ $material->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $material->type === 'url' ? 'Link' : 'File' }}</p>
                                </div>
                            </div>
                            @if ($allowed)
                                <a href="{{ route('student.materials.show', $material) }}"
                                   class="shrink-0 flex items-center gap-1 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 text-xs font-semibold">
                                    <i class="bi bi-box-arrow-up-right"></i> Buka
                                </a>
                            @else
                                <span class="shrink-0 flex items-center gap-1 rounded-lg bg-gray-100 text-gray-400 px-3 py-1.5 text-xs font-semibold cursor-not-allowed">
                                    <i class="bi bi-lock-fill"></i> Terkunci
                                </span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endsection
