@extends('layouts.app')

@section('title', 'Riwayat Kehadiran - Admin English Club')

@section('content')

    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Riwayat Kehadiran</h1>
            <p class="text-xs sm:text-sm text-gray-500">Daftar sesi beserta rekap kehadiran siswa per minggu.</p>
        </div>
        <a href="{{ route('admin.attendance') }}" class="flex items-center gap-1 rounded-lg border border-gray-200 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
            <i class="bi bi-keyboard text-xs"></i> Presensi & OTP
        </a>
    </div>

    <!-- Week Filter -->
    <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-4 sm:p-5 shadow-sm">
        <form method="GET" action="{{ route('admin.attendance.history') }}" class="flex flex-col sm:flex-row sm:items-end gap-3">
            <div class="flex-1 min-w-0">
                <label for="week" class="block text-xs font-semibold text-gray-500 mb-2">Minggu Pertemuan</label>
                <select name="week" id="week" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 focus:ring-2 focus:ring-blue-300">
                    @foreach ($availableWeeks as $option)
                        <option value="{{ $option['key'] }}" @selected($option['key'] === $currentWeek)>
                            {{ $option['current'] ? 'Minggu Ini · ' : '' }}{{ $option['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="rounded-xl bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                Tampilkan
            </button>
        </form>
        <p class="mt-3 text-xs text-gray-500">Menampilkan: <strong class="text-gray-700">{{ $weekLabel }}</strong></p>
    </div>

    <!-- Session List -->
    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs text-gray-700 uppercase font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3">Sesi</th>
                        <th class="px-4 py-3">Jadwal</th>
                        <th class="px-4 py-3 text-center">Hadir</th>
                        <th class="px-4 py-3 text-center">Izin</th>
                        <th class="px-4 py-3 text-center">Alpha</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($sessions as $session)
                        <tr class="hover:bg-gray-50/80">
                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-900">{{ $session->title }}</p>
                                <p class="text-xs text-gray-400">{{ $session->subject?->name ?? 'Tanpa mapel' }}</p>
                            </td>
                            <td class="px-4 py-3 text-xs">{{ $session->scheduled_at?->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">{{ $session->hadir_count }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700">{{ $session->izin_count }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-rose-100 text-rose-700">{{ max(0, $totalStudents - $session->hadir_count - $session->izin_count) }}</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.attendance.history.session', $session) }}"
                                    class="inline-flex items-center gap-1 text-xs font-semibold bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg px-2.5 py-1.5">
                                    <i class="bi bi-people text-sm"></i> Lihat Roster
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-xs text-gray-400">Belum ada sesi pada minggu ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection