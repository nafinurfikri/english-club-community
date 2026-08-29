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

function namedStudent(string $name): User
{
    return User::factory()->create(['name' => $name, 'role' => 'siswa', 'status' => 'active']);
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
    $hadirStudent = namedStudent('Budi Hadir');
    $izinStudent = namedStudent('Siti Izin');
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

it('halaman presensi admin aman saat ada siswa tanpa presensi', function () {
    $admin = adminUser();
    $student = namedStudent('Andi Aktif');
    $session = ClubSession::factory()->create(['opened_at' => now(), 'closed_at' => null]);
    $student2 = namedStudent('Rina Aktif');

    Attendance::create([
        'club_session_id' => $session->id,
        'user_id' => $student->id,
        'attended_at' => now(),
        'status' => Attendance::STATUS_HADIR,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.attendance'))
        ->assertOk()
        ->assertSee($student->name)
        ->assertSee($student2->name);
});

it('admin dapat membuat sesi baru yang langsung aktif dengan kode OTP', function () {
    $admin = adminUser();

    $this->actingAs($admin)
        ->post(route('admin.sessions.store'), [
            'title' => 'Speaking Practice',
            'description' => 'Latihan berbicara',
            'scheduled_at' => now()->addDay(),
        ])
        ->assertRedirect()
        ->assertSessionHas('attendance_code');

    $session = ClubSession::latest('id')->first();
    expect($session->title)->toBe('Speaking Practice')
        ->and($session->isOpen())->toBeTrue()
        ->and($session->attendance_code_hash)->not->toBeNull();
});

it('admin dapat membuka dan menutup sesi presensi', function () {
    $admin = adminUser();
    $session = ClubSession::factory()->create(['opened_at' => null, 'closed_at' => null]);

    $this->actingAs($admin)
        ->patch(route('admin.sessions.open', $session))
        ->assertRedirect();

    expect($session->fresh()->isOpen())->toBeTrue();

    $this->actingAs($admin)
        ->patch(route('admin.sessions.close', $session))
        ->assertRedirect();

    expect($session->fresh()->isOpen())->toBeFalse();
});

it('admin dapat me-generate ulang kode OTP untuk sesi terbuka', function () {
    $admin = adminUser();
    $session = ClubSession::factory()->create(['opened_at' => now(), 'closed_at' => null]);

    $this->actingAs($admin)
        ->patch(route('admin.sessions.code', $session))
        ->assertRedirect()
        ->assertSessionHas('attendance_code')
        ->assertSessionHas('status');

    $this->assertNotSame($session->attendance_code_hash, $session->fresh()->attendance_code_hash);
});
