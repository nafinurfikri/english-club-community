<?php

use App\Models\Attendance;
use App\Models\ClubSession;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

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

it('rekap kehadiran realtime menghitung hadir, izin, dan alpha dengan benar', function () {
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

    $html = $this->actingAs($admin)
        ->get(route('admin.attendance'))
        ->assertOk()
        ->getContent();

    preg_match('/text-xl font-bold text-gray-900">(\d+)<\/span>/', $html, $total);
    preg_match('/text-xl font-bold text-emerald-700">(\d+)<\/span>/', $html, $hadir);
    preg_match('/text-xl font-bold text-amber-700">(\d+)<\/span>/', $html, $izin);
    preg_match('/text-xl font-bold text-rose-700">(\d+)<\/span>/', $html, $alpha);

    expect((int) ($total[1] ?? 0))->toBe(2)
        ->and((int) ($hadir[1] ?? 0))->toBe(1)
        ->and((int) ($izin[1] ?? 0))->toBe(1)
        ->and((int) ($alpha[1] ?? 0))->toBe(0);
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

it('tabel daftar kehadiran hanya muncul saat ada sesi aktif', function () {
    $admin = adminUser();
    activeStudent();

    $this->actingAs($admin)
        ->get(route('admin.attendance'))
        ->assertOk()
        ->assertDontSee('Daftar Kehadiran Siswa');

    ClubSession::factory()->create(['opened_at' => now(), 'closed_at' => null]);

    $this->actingAs($admin)
        ->get(route('admin.attendance'))
        ->assertOk()
        ->assertSee('Daftar Kehadiran Siswa');
});

it('admin dapat membuat sesi baru yang langsung aktif dengan kode OTP', function () {
    $admin = adminUser();
    $subject = Subject::create(['name' => 'Speaking', 'level' => 'Level 2 - Basic', 'teacher' => 'Miss Windy', 'description' => 'Deskripsi']);

    $this->actingAs($admin)
        ->post(route('admin.sessions.store'), [
            'title' => 'Speaking Practice',
            'subject_id' => $subject->id,
            'description' => 'Latihan berbicara',
            'scheduled_at' => now()->addDay(),
        ])
        ->assertRedirect()
        ->assertSessionHas('attendance_code');

    $session = ClubSession::latest('id')->first();
    expect($session->title)->toBe('Speaking Practice')
        ->and($session->isOpen())->toBeTrue()
        ->and($session->attendance_code_hash)->not->toBeNull()
        ->and($session->attendance_code_expires_at)->not->toBeNull();
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

it('daftar sesi tidak menampilkan tombol generate OTP duplikat untuk sesi aktif', function () {
    $admin = adminUser();
    $session = ClubSession::factory()->create(['opened_at' => now(), 'closed_at' => null]);

    $html = $this->actingAs($admin)
        ->get(route('admin.attendance'))
        ->assertOk()
        ->assertSee('Generate Kode Baru')
        ->getContent();

    expect(substr_count($html, route('admin.sessions.code', $session)))->toBe(1);
});

it('daftar sesi tetap menampilkan tombol generate OTP untuk sesi terbuka non-aktif', function () {
    $admin = adminUser();
    $active = ClubSession::factory()->create(['scheduled_at' => now()->addDays(2), 'opened_at' => now(), 'closed_at' => null]);
    $other = ClubSession::factory()->create(['scheduled_at' => now()->addDays(1), 'opened_at' => now(), 'closed_at' => null]);

    $html = $this->actingAs($admin)
        ->get(route('admin.attendance'))
        ->assertOk()
        ->getContent();

    expect(substr_count($html, route('admin.sessions.code', $active)))->toBe(1)
        ->and(substr_count($html, route('admin.sessions.code', $other)))->toBe(1);
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

it('admin dapat mencabut kode OTP sehingga tidak bisa dipakai presensi', function () {
    $admin = adminUser();
    $student = activeStudent();
    $code = '123456';
    $session = ClubSession::factory()->create([
        'opened_at' => now(),
        'closed_at' => null,
        'attendance_code_hash' => Hash::make($code),
    ]);

    $this->actingAs($admin)
        ->patch(route('admin.sessions.revoke-code', $session))
        ->assertRedirect()
        ->assertSessionHas('status');

    $session = $session->fresh();
    expect($session->attendance_code_hash)->toBeNull()
        ->and($session->attendance_code)->toBeNull()
        ->and($session->attendance_code_expires_at)->toBeNull();

    $this->actingAs($student)
        ->post(route('student.attendance.store', $session), ['code' => $code])
        ->assertSessionHasErrors('code');
});

it('admin dapat melihat kode OTP aktif melalui endpoint otp', function () {
    $admin = adminUser();
    $code = '654321';
    $session = ClubSession::factory()->create([
        'opened_at' => now(),
        'closed_at' => null,
        'attendance_code' => Crypt::encryptString($code),
        'attendance_code_hash' => Hash::make($code),
        'attendance_code_expires_at' => now()->addMinute(),
    ]);

    $this->actingAs($admin)
        ->getJson(route('admin.sessions.otp', $session))
        ->assertOk()
        ->assertJsonPath('code', $code)
        ->assertJsonPath('is_open', true);
});

it('OTP otomatis di-rotate saat sudah kedaluwarsa', function () {
    $admin = adminUser();
    $oldCode = '111111';
    $session = ClubSession::factory()->create([
        'opened_at' => now(),
        'closed_at' => null,
        'attendance_code' => Crypt::encryptString($oldCode),
        'attendance_code_hash' => Hash::make($oldCode),
        'attendance_code_expires_at' => now()->subMinute(),
    ]);
    $oldHash = $session->attendance_code_hash;

    $response = $this->actingAs($admin)
        ->getJson(route('admin.sessions.otp', $session))
        ->assertOk()
        ->assertJsonPath('is_open', true)
        ->assertJsonPath('code', fn ($code) => is_numeric($code) && strlen((string) $code) === 6);

    $newCode = $response->json('code');
    $fresh = $session->fresh();

    expect($newCode)->not->toBe($oldCode)
        ->and($fresh->attendance_code_hash)->not->toBe($oldHash)
        ->and(Crypt::decryptString($fresh->attendance_code))->toBe($newCode)
        ->and($fresh->attendance_code_expires_at)->not->toBeNull();
});

it('siswa tidak bisa presensi dengan kode yang sudah kedaluwarsa', function () {
    $student = activeStudent();
    $code = '222222';
    $session = ClubSession::factory()->create([
        'opened_at' => now(),
        'closed_at' => null,
        'attendance_code' => Crypt::encryptString($code),
        'attendance_code_hash' => Hash::make($code),
        'attendance_code_expires_at' => now()->subSecond(),
    ]);

    $this->actingAs($student)
        ->post(route('student.attendance.store', $session), ['code' => $code])
        ->assertSessionHasErrors('code');

    expect(Attendance::where('club_session_id', $session->id)->where('user_id', $student->id)->exists())->toBeFalse();
});

it('halaman presensi admin menampilkan kode OTP aktif saat reload', function () {
    $admin = adminUser();
    $code = '987654';
    $session = ClubSession::factory()->create([
        'opened_at' => now(),
        'closed_at' => null,
        'attendance_code' => Crypt::encryptString($code),
        'attendance_code_hash' => Hash::make($code),
        'attendance_code_expires_at' => now()->addMinute(),
    ]);

    $this->actingAs($admin)
        ->get(route('admin.attendance'))
        ->assertOk()
        ->assertSee($code);
});

it('sesi lama tanpa kode tersimpan di-rotate untuk kode yang bisa ditampilkan', function () {
    $admin = adminUser();
    $oldCode = '333333';
    $session = ClubSession::factory()->create([
        'opened_at' => now(),
        'closed_at' => null,
        'attendance_code' => null,
        'attendance_code_hash' => Hash::make($oldCode),
        'attendance_code_expires_at' => null,
    ]);

    $response = $this->actingAs($admin)
        ->getJson(route('admin.sessions.otp', $session))
        ->assertOk()
        ->assertJsonPath('is_open', true)
        ->assertJsonPath('code', fn ($code) => is_numeric($code) && strlen((string) $code) === 6);

    expect($response->json('code'))->not->toBe($oldCode)
        ->and($session->fresh()->attendance_code)->not->toBeNull();
});

it('endpoint otp tidak menyalakan kode untuk sesi yang sudah di-revoke', function () {
    $admin = adminUser();
    $session = ClubSession::factory()->create([
        'opened_at' => now(),
        'closed_at' => null,
        'attendance_code' => null,
        'attendance_code_hash' => null,
        'attendance_code_expires_at' => null,
    ]);

    $this->actingAs($admin)
        ->getJson(route('admin.sessions.otp', $session))
        ->assertOk()
        ->assertJsonPath('code', null)
        ->assertJsonPath('is_open', true);
});
