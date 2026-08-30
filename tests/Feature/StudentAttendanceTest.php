<?php

use App\Models\ClubSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('renders the student attendance page', function () {
    $this->actingAs(User::factory()->create(['status' => 'active']))
        ->get(route('student.attendance'))
        ->assertOk()
        ->assertSee('Presensi Kehadiran')
        ->assertSee('Daily Attendance')
        ->assertSee('Verify Attendance');
});

it('menyediakan waktu kedaluwarsa OTP untuk sesi terbuka dengan kode aktif', function () {
    $open = ClubSession::factory()->create([
        'opened_at' => now(),
        'closed_at' => null,
        'attendance_code' => Crypt::encryptString('123456'),
        'attendance_code_hash' => Hash::make('123456'),
        'attendance_code_expires_at' => now()->addMinutes(ClubSession::OTP_LIFETIME_MINUTES),
    ]);

    $this->actingAs(User::factory()->create(['status' => 'active']))
        ->get(route('student.attendance'))
        ->assertOk()
        ->assertSee(data_get($open->attendance_code_expires_at->toIso8601String(), 0, $open->attendance_code_expires_at->toIso8601String()), false);
});

it('tidak menampilkan waktu kedaluwarsa saat kode sesi sudah kedaluwarsa', function () {
    ClubSession::factory()->create([
        'opened_at' => now()->subMinutes(5),
        'closed_at' => null,
        'attendance_code' => Crypt::encryptString('123456'),
        'attendance_code_hash' => Hash::make('123456'),
        'attendance_code_expires_at' => now()->subMinutes(2),
    ]);

    $this->actingAs(User::factory()->create(['status' => 'active']))
        ->get(route('student.attendance'))
        ->assertOk()
        ->assertSee('Tidak ada kode aktif');
});
