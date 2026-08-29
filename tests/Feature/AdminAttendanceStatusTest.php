<?php

use App\Models\Attendance;
use App\Models\ClubSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function adminUser(): User
{
    return User::factory()->create(['role' => 'admin', 'status' => 'active']);
}

function activeStudent(): User
{
    return User::factory()->create(['role' => 'siswa', 'status' => 'active']);
}

it('admin dapat mengubah status presensi siswa menjadi izin', function () {
    $admin = adminUser();
    $student = activeStudent();
    $session = ClubSession::factory()->create(['opened_at' => now(), 'closed_at' => null]);

    $this->actingAs($admin)
        ->patch(route('admin.attendance.status'), [
            'club_session_id' => $session->id,
            'user_id' => $student->id,
            'status' => Attendance::STATUS_IZIN,
        ])
        ->assertRedirect();

    expect(
        Attendance::where('club_session_id', $session->id)->where('user_id', $student->id)->first()
    )->status->toBe(Attendance::STATUS_IZIN);
});

it('admin dapat mengubah status presensi menjadi alpha', function () {
    $admin = adminUser();
    $student = activeStudent();
    $session = ClubSession::factory()->create(['opened_at' => now(), 'closed_at' => null]);

    $this->actingAs($admin)
        ->patch(route('admin.attendance.status'), [
            'club_session_id' => $session->id,
            'user_id' => $student->id,
            'status' => Attendance::STATUS_ALPHA,
        ])
        ->assertRedirect();

    expect(
        Attendance::where('club_session_id', $session->id)->where('user_id', $student->id)->first()
    )->status->toBe(Attendance::STATUS_ALPHA);
});

it('status presensi menerima nilai yang valid saja', function () {
    $admin = adminUser();
    $student = activeStudent();
    $session = ClubSession::factory()->create(['opened_at' => now(), 'closed_at' => null]);

    $this->actingAs($admin)
        ->patch(route('admin.attendance.status'), [
            'club_session_id' => $session->id,
            'user_id' => $student->id,
            'status' => 'sakit',
        ])
        ->assertSessionHasErrors('status');
});

it('hanya admin yang dapat mengubah status presensi', function () {
    $student = activeStudent();
    $otherStudent = activeStudent();
    $session = ClubSession::factory()->create(['opened_at' => now(), 'closed_at' => null]);

    $this->actingAs($otherStudent)
        ->patch(route('admin.attendance.status'), [
            'club_session_id' => $session->id,
            'user_id' => $student->id,
            'status' => Attendance::STATUS_IZIN,
        ])
        ->assertForbidden();
});

it('halaman presensi admin menampilkan semua siswa aktif beserta statusnya', function () {
    $admin = adminUser();
    $hadirStudent = activeStudent();
    $izinStudent = activeStudent();
    $session = ClubSession::factory()->create(['opened_at' => now(), 'closed_at' => null]);

    Attendance::create([
        'club_session_id' => $session->id,
        'user_id' => $hadirStudent->id,
        'attended_at' => now(),
        'status' => Attendance::STATUS_HADIR,
    ]);
    Attendance::create([
        'club_session_id' => $session->id,
        'user_id' => $izinStudent->id,
        'attended_at' => null,
        'status' => Attendance::STATUS_IZIN,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.attendance'))
        ->assertOk()
        ->assertSee($hadirStudent->name)
        ->assertSee($izinStudent->name)
        ->assertSee('Hadir')
        ->assertSee('Izin');
});
