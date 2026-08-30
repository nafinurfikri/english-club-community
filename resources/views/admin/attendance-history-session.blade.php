@extends('layouts.app')

@section('title', 'Daftar Kehadiran - ' . $session->title)

@section('content')

    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $session->title }}</h1>
            <p class="text-xs sm:text-sm text-gray-500">
                {{ $session->subject?->name ?? 'Tanpa mapel' }} ·
                {{ $session->scheduled_at?->format('d M Y H:i') }}
            </p>
        </div>
        <a href="{{ route('admin.attendance.history') }}" class="flex items-center gap-1 rounded-lg border border-gray-200 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
            <i class="bi bi-chevron-left text-xs"></i> Kembali ke Riwayat
        </a>
    </div>

    <!-- Summary Stats -->
    <div class="mb-6 grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="p-3 bg-gray-50 border border-gray-100 rounded-xl text-center">
            <span class="text-xs text-gray-500 font-medium block mb-1">Total Siswa</span>
            <span class="text-xl font-bold text-gray-900">{{ $totalStudents }}</span>
        </div>
        <div class="p-3 bg-emerald-50 border border-emerald-100 rounded-xl text-center">
            <span class="text-xs text-emerald-600 font-medium block mb-1">Hadir</span>
            <span class="text-xl font-bold text-emerald-700">{{ $hadirCount }}</span>
        </div>
        <div class="p-3 bg-amber-50 border border-amber-100 rounded-xl text-center">
            <span class="text-xs text-amber-600 font-medium block mb-1">Izin</span>
            <span class="text-xl font-bold text-amber-700">{{ $izinCount }}</span>
        </div>
        <div class="p-3 bg-rose-50 border border-rose-100 rounded-xl text-center">
            <span class="text-xs text-rose-600 font-medium block mb-1">Alpha</span>
            <span class="text-xl font-bold text-rose-700">{{ $alphaCount }}</span>
        </div>
    </div>

    <!-- Roster -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <h2 class="text-base font-bold text-gray-900">Daftar Kehadiran Siswa</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs text-gray-700 uppercase font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3">Nama Siswa</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Waktu Masuk</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($rows as $row)
                        <tr class="hover:bg-gray-50/80">
                            <td class="px-4 py-3 font-semibold text-gray-900">{{ $row['name'] }}</td>
                            <td class="px-4 py-3 text-xs">{{ $row['email'] }}</td>
                            <td class="px-4 py-3 text-xs text-gray-400">{{ $row['time'] }}</td>
                            <td class="px-4 py-3">
                                @php($badgeClass = match ($row['status']) {
                                    'Hadir' => 'bg-emerald-100 text-emerald-700',
                                    'Izin' => 'bg-amber-100 text-amber-700',
                                    default => 'bg-rose-100 text-rose-700',
                                })
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $badgeClass }}">{{ $row['status'] }}</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1 rounded-lg border border-gray-200 p-0.5">
                                    @foreach (['hadir' => 'Hadir', 'izin' => 'Izin', 'alpha' => 'Alpha'] as $code => $label)
                                        <form method="POST" action="{{ route('admin.attendance.status') }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="club_session_id" value="{{ $session->id }}">
                                            <input type="hidden" name="user_id" value="{{ $row['id'] }}">
                                            <input type="hidden" name="status" value="{{ $code }}">
                                            <button class="text-[11px] font-medium px-1.5 py-1 rounded-md text-gray-600 hover:bg-blue-50 hover:text-blue-700">
                                                {{ $label }}
                                            </button>
                                        </form>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-xs text-gray-400">Belum ada data kehadiran untuk sesi ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection