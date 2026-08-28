@extends('layouts.app')

@section('title', 'Presensi & OTP Generator - Admin English Club')

@section('content')

    <!-- Header Section -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Presensi Siswa & OTP Generator</h1>
            <p class="text-xs sm:text-sm text-gray-500">Kelola sesi kehadiran siswa dan generate kode OTP presensi di kelas.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-xs font-semibold">
                Sesi: Routine Practice #16
            </span>
        </div>
    </div>

    <!-- Generator OTP & Quick Info Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6"
         x-data="{
             otpCode: '849201',
             duration: 30,
             timeLeft: 1800,
             copied: false,
             isRevoked: false,
             generateOtp() {
                 this.otpCode = Math.floor(100000 + Math.random() * 900000).toString();
                 this.timeLeft = this.duration * 60;
                 this.isRevoked = false;
             },
             copyOtp() {
                 if (this.isRevoked) return;
                 navigator.clipboard.writeText(this.otpCode);
                 this.copied = true;
                 setTimeout(() => this.copied = false, 2000);
             },
             revokeOtp() {
                 this.otpCode = 'EXPIRED';
                 this.isRevoked = true;
                 this.timeLeft = 0;
             }
         }">
        
        <!-- Live OTP Card Generator -->
        <div class="lg:col-span-1 bg-linear-to-br from-blue-600 to-indigo-700 text-white rounded-2xl p-6 shadow-md flex flex-col justify-between">
            <div>
                <div class="flex justify-between items-center mb-4">
                    <span :class="isRevoked ? 'bg-rose-500/30 text-rose-200' : 'bg-white/20 text-white'" class="text-xs font-semibold px-2.5 py-1 rounded-full backdrop-blur-md" x-text="isRevoked ? 'OTP Revoked / Expired' : 'OTP Generator Live'"></span>
                    <span class="text-xs text-blue-200 flex items-center gap-1">
                        <i class="bi bi-clock-history"></i> <span x-text="isRevoked ? 'Tidak Berlaku' : 'Berlaku 30 Menit'"></span>
                    </span>
                </div>

                <div class="text-center py-4">
                    <p class="text-xs text-blue-100 mb-1">Kode Presensi Hari Ini</p>
                    <div :class="isRevoked ? 'text-rose-300 line-through opacity-80' : 'text-white'" 
                         class="text-4xl sm:text-5xl font-mono font-extrabold tracking-widest bg-white/10 border border-white/20 py-3 rounded-xl shadow-inner backdrop-blur-md"
                         x-text="otpCode">
                    </div>
                </div>
            </div>

            <div class="space-y-2 mt-4">
                <button @click="generateOtp()" 
                        class="w-full bg-white text-blue-700 hover:bg-blue-50 font-bold py-2.5 rounded-xl text-sm transition shadow flex items-center justify-center gap-2 cursor-pointer">
                    <i class="bi bi-arrow-repeat text-base"></i> Generate Kode Baru
                </button>
                <div class="grid grid-cols-2 gap-2">
                    <button @click="copyOtp()" 
                            :disabled="isRevoked"
                            class="w-full bg-blue-800/60 hover:bg-blue-800 disabled:opacity-50 disabled:cursor-not-allowed text-white font-semibold py-2 rounded-xl text-xs transition border border-white/10 flex items-center justify-center gap-1 cursor-pointer">
                        <i :class="copied ? 'bi bi-check2' : 'bi bi-clipboard'"></i> 
                        <span x-text="copied ? 'Tersalin!' : 'Salin OTP'"></span>
                    </button>
                    <button @click="revokeOtp()" 
                            :disabled="isRevoked"
                            class="w-full bg-rose-500/80 hover:bg-rose-600 disabled:opacity-50 disabled:cursor-not-allowed text-white font-semibold py-2 rounded-xl text-xs transition border border-white/10 flex items-center justify-center gap-1 cursor-pointer">
                        <i class="bi bi-x-circle-fill"></i> 
                        <span x-text="isRevoked ? 'Revoked' : 'Revoke OTP'"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Attendance Stats Overview -->
        <div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-900 mb-4">Rekap Kehadiran Realtime Sesi Ini</h3>
                
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                    <div class="p-3 bg-gray-50 border border-gray-100 rounded-xl text-center">
                        <span class="text-xs text-gray-500 font-medium block mb-1">Total Siswa</span>
                        <span class="text-xl font-bold text-gray-900">48</span>
                    </div>
                    <div class="p-3 bg-emerald-50 border border-emerald-100 rounded-xl text-center">
                        <span class="text-xs text-emerald-600 font-medium block mb-1">Hadir (OTP)</span>
                        <span class="text-xl font-bold text-emerald-700">42</span>
                    </div>
                    <div class="p-3 bg-amber-50 border border-amber-100 rounded-xl text-center">
                        <span class="text-xs text-amber-600 font-medium block mb-1">Izin</span>
                        <span class="text-xl font-bold text-amber-700">4</span>
                    </div>
                    <div class="p-3 bg-rose-50 border border-rose-100 rounded-xl text-center">
                        <span class="text-xs text-rose-600 font-medium block mb-1">Alpha</span>
                        <span class="text-xl font-bold text-rose-700">2</span>
                    </div>
                </div>

                <p class="text-xs text-gray-500">
                    Siswa yang telah memasukkan OTP otomatis terverifikasi dengan status <strong class="text-emerald-600">Hadir</strong>. Admin dapat mengubah status manual jika diperlukan.
                </p>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100 text-xs text-gray-400">
                <span>Tanggal Sesi: 28 Agustus 2026</span>
                <span>Waktu Sesi: 16.00 - 17.30 WIB</span>
            </div>
        </div>

    </div>

    <!-- Table Kehadiran Siswa -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden"
         x-data="{
             searchQuery: '',
             students: [
                 { nis: '202410012', name: 'Kelvin Muaezin Lubis', divisi: 'Speech', status: 'Hadir', time: '16.05 WIB' },
                 { nis: '202410015', name: 'Riekosta Febrianto', divisi: 'Debate', status: 'Hadir', time: '16.08 WIB' },
                 { nis: '202410018', name: 'Nafi Nur Fikri', divisi: 'Story Telling', status: 'Izin', time: '-' },
                 { nis: '202410022', name: 'Anindya Eka Pratiwi', divisi: 'News Anchor', status: 'Hadir', time: '16.02 WIB' }
             ]
         }">
        
        <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <h2 class="text-base font-bold text-gray-900">Daftar Kehadiran Siswa</h2>
            <div class="w-full sm:w-64">
                <input type="text" 
                       x-model="searchQuery"
                       placeholder="Cari nama atau NIS..." 
                       class="w-full px-3 py-1.5 bg-gray-50 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs text-gray-700 uppercase font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3">NIS</th>
                        <th class="px-4 py-3">Nama Siswa</th>
                        <th class="px-4 py-3">Divisi</th>
                        <th class="px-4 py-3">Waktu Masuk</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-for="(s, index) in students" :key="index">
                        <tr class="hover:bg-gray-50/80">
                            <td class="px-4 py-3 font-mono text-xs text-gray-500" x-text="s.nis"></td>
                            <td class="px-4 py-3 font-semibold text-gray-900" x-text="s.name"></td>
                            <td class="px-4 py-3 text-xs" x-text="s.divisi"></td>
                            <td class="px-4 py-3 text-xs text-gray-400" x-text="s.time"></td>
                            <td class="px-4 py-3">
                                <span :class="{
                                    'bg-emerald-100 text-emerald-700': s.status === 'Hadir',
                                    'bg-amber-100 text-amber-700': s.status === 'Izin',
                                    'bg-rose-100 text-rose-700': s.status === 'Alpha'
                                }" class="px-2.5 py-1 text-xs font-semibold rounded-full" x-text="s.status"></span>
                            </td>
                            <td class="px-4 py-3 text-right space-x-1">
                                <button @click="s.status = 'Hadir'; s.time = '16.10 WIB'" class="px-2 py-1 text-xs bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-md">Hadir</button>
                                <button @click="s.status = 'Izin'" class="px-2 py-1 text-xs bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-md">Izin</button>
                                <button @click="s.status = 'Alpha'" class="px-2 py-1 text-xs bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-md">Alpha</button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

@endsection
