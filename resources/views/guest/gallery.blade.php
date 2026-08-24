@extends('layouts.app')

@section('title', 'Gallery - English Club Community')

@section('content')

    {{-- Judul halaman --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Gallery</h1>
        <p class="text-gray-500 text-sm mt-1">Dokumentasi kegiatan, acara, dan momen kelas.</p>
    </div>

    {{-- Filter tab --}}
    <div class="flex items-center gap-2 mb-6" x-data="{ activeTab: 'all' }">
        <button 
            @click="activeTab = 'all'"
            :class="activeTab === 'all' ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 border border-gray-300'"
            class="px-4 py-1.5 rounded-full text-sm font-medium transition">
            All Activities
        </button>
        <button 
            @click="activeTab = 'events'"
            :class="activeTab === 'events' ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 border border-gray-300'"
            class="px-4 py-1.5 rounded-full text-sm font-medium transition">
            Events
        </button>
        <button 
            @click="activeTab = 'class'"
            :class="activeTab === 'class' ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 border border-gray-300'"
            class="px-4 py-1.5 rounded-full text-sm font-medium transition">
            Class Activities
        </button>
        <button 
            @click="activeTab = 'competitions'"
            :class="activeTab === 'competitions' ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 border border-gray-300'"
            class="px-4 py-1.5 rounded-full text-sm font-medium transition">
            Competitions
        </button>
    </div>

    {{-- Grid foto --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Contoh 1 kartu foto --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <img src="{{ asset('lomba.png') }}" alt="Lomba Debat Bahasa Inggris" class="w-full h-40 object-cover">
            <div class="p-3">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Competitions</span>
                    <span class="text-xs text-gray-400">Jan 22, 2024</span>
                </div>
                <p class="text-sm font-semibold text-gray-900">Lomba Debat Bahasa Inggris</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <img src="{{ asset('lomba.png') }}" alt="Lomba Debat Bahasa Inggris" class="w-full h-40 object-cover">
            <div class="p-3">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Competitions</span>
                    <span class="text-xs text-gray-400">Jan 22, 2024</span>
                </div>
                <p class="text-sm font-semibold text-gray-900">Lomba Debat Bahasa Inggris</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <img src="{{ asset('lomba.png') }}" alt="Lomba Debat Bahasa Inggris" class="w-full h-40 object-cover">
            <div class="p-3">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Competitions</span>
                    <span class="text-xs text-gray-400">Jan 22, 2024</span>
                </div>
                <p class="text-sm font-semibold text-gray-900">Lomba Debat Bahasa Inggris</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <img src="{{ asset('lomba.png') }}" alt="Lomba Debat Bahasa Inggris" class="w-full h-40 object-cover">
            <div class="p-3">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Competitions</span>
                    <span class="text-xs text-gray-400">Jan 22, 2024</span>
                </div>
                <p class="text-sm font-semibold text-gray-900">Lomba Debat Bahasa Inggris</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <img src="{{ asset('lomba.png') }}" alt="Lomba Debat Bahasa Inggris" class="w-full h-40 object-cover">
            <div class="p-3">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Competitions</span>
                    <span class="text-xs text-gray-400">Jan 22, 2024</span>
                </div>
                <p class="text-sm font-semibold text-gray-900">Lomba Debat Bahasa Inggris</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <img src="{{ asset('lomba.png') }}" alt="Lomba Debat Bahasa Inggris" class="w-full h-40 object-cover">
            <div class="p-3">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Competitions</span>
                    <span class="text-xs text-gray-400">Jan 22, 2024</span>
                </div>
                <p class="text-sm font-semibold text-gray-900">Lomba Debat Bahasa Inggris</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <img src="{{ asset('lomba.png') }}" alt="Lomba Debat Bahasa Inggris" class="w-full h-40 object-cover">
            <div class="p-3">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Competitions</span>
                    <span class="text-xs text-gray-400">Jan 22, 2024</span>
                </div>
                <p class="text-sm font-semibold text-gray-900">Lomba Debat Bahasa Inggris</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <img src="{{ asset('lomba.png') }}" alt="Lomba Debat Bahasa Inggris" class="w-full h-40 object-cover">
            <div class="p-3">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Competitions</span>
                    <span class="text-xs text-gray-400">Jan 22, 2024</span>
                </div>
                <p class="text-sm font-semibold text-gray-900">Lomba Debat Bahasa Inggris</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <img src="{{ asset('lomba.png') }}" alt="Lomba Debat Bahasa Inggris" class="w-full h-40 object-cover">
            <div class="p-3">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Competitions</span>
                    <span class="text-xs text-gray-400">Jan 22, 2024</span>
                </div>
                <p class="text-sm font-semibold text-gray-900">Lomba Debat Bahasa Inggris</p>
            </div>
        </div>
    </div>

@endsection