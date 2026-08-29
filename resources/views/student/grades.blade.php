@extends('layouts.app')

@section('title', 'Nilai Saya - Student English Club')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Nilai Saya</h1>
        <p class="text-sm text-gray-500 mt-1">Lihat perkembangan nilai yang sudah dipublikasikan.</p>
    </div>

    <div class="space-y-4">
        @forelse ($grades as $grade)
            <article class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <h2 class="font-bold text-gray-900">{{ $grade->category?->name ?? 'Penilaian' }}</h2>
                        <p class="text-xs text-gray-500 mt-1">{{ $grade->created_at?->format('d M Y') }}</p>
                    </div>
                    <span class="text-2xl font-bold text-blue-600">{{ $grade->score }}</span>
                </div>
                @if ($grade->notes)
                    <p class="text-sm text-gray-600 mt-4 border-t border-gray-100 pt-3">{{ $grade->notes }}</p>
                @endif
            </article>
        @empty
            <div class="bg-white border border-dashed border-gray-300 rounded-2xl p-8 text-center">
                <i class="bi bi-award text-3xl text-gray-300"></i>
                <p class="text-sm text-gray-500 mt-2">Belum ada nilai yang dipublikasikan.</p>
            </div>
        @endforelse
    </div>
@endsection