<?php

use App\Models\Attendance;
use App\Models\ClubSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

function concurrentAdmin(): User
{
    return User::factory()->create(['role' => 'admin', 'status' => 'active']);
}

function concurrentStudent(): User
{
    return User::factory()->create(['role' => 'siswa', 'status' => 'active']);
}

function concurrentOpenSession(string $code, int $daysAhead): ClubSession
{
    return ClubSession::factory()->create([
        'scheduled_at' => now()->addDays($daysAhead),
        'opened_at' => now(),
        'closed_at' => null,
        'attendance_code' => Crypt::encryptString($code),
        'attendance_code_hash' => Hash::make($code),
        'attendance_code_expires_at' => now()->addMinute(),
    ]);
}

it('admin dapat memilih sesi terbuka lain untuk dipantau via ?session=', function () {
    $admin = concurrentAdmin();
    $defaultActive = concurrentOpenSession('111111', 3);
    $chosen = concurrentOpenSession('222222', 1);

    $this->actingAs($admin)
        ->get(route('admin.attendance', ['session' => $chosen->id]))
        ->assertOk()
        ->assertSee($chosen->title)
        ->assertSee('Sesi: '.$chosen->title)
        ->assertSee('Pantau Sesi')
        ->assertSee('222222')
        ->assertDontSee('111111');
});

it('admin tetap memantau sesi terbuka terbaru saat ?session= tidak diberikan', function () {
    $admin = concurrentAdmin();
    concurrentOpenSession('111111', 1);
    $latest = concurrentOpenSession('222222', 3);

    $this->actingAs($admin)
        ->get(route('admin.attendance'))
        ->assertOk()
        ->assertSee('Sesi: '.$latest->title)
        ->assertSee('222222')
        ->assertDontSee('111111');
});

it('admin jatuh ke sesi terbuka default saat ?session= tidak valid atau tertutup', function ($case) {
    $admin = concurrentAdmin();
    $defaultActive = concurrentOpenSession('111111', 3);
    $closed = ClubSession::factory()->create(['opened_at' => now(), 'closed_at' => now()]);

    $value = match ($case) {
        'closed' => $closed->id,
        'abc' => 'abc',
        default => 999999,
    };

    $this->actingAs($admin)
        ->get(route('admin.attendance', ['session' => $value]))
        ->assertOk()
        ->assertSee('Sesi: '.$defaultActive->title)
        ->assertSee('111111');
})->with(['nonexistent', 'closed', 'abc']);

it('halaman presensi siswa menampilkan pilihan sesi saat ada lebih dari satu sesi terbuka', function () {
    $student = concurrentStudent();
    $first = concurrentOpenSession('111111', 2);
    $second = concurrentOpenSession('222222', 1);

    $html = $this->actingAs($student)
        ->get(route('student.attendance'))
        ->assertOk()
        ->getContent();

    expect($html)
        ->toContain('Pilih Sesi Presensi')
        ->toContain($first->title)
        ->toContain($second->title);
});

it('halaman presensi siswa tanpa dua sesi terbuka tidak menampilkan pemilih', function () {
    $student = concurrentStudent();

    $html = $this->actingAs($student)
        ->get(route('student.attendance'))
        ->assertOk()
        ->getContent();

    expect($html)
        ->not->toContain('Pilih Sesi Presensi');
});

it('siswa dapat presensi ke masing-masing sesi terbuka dengan kodenya sendiri', function () {
    $student = concurrentStudent();
    $first = concurrentOpenSession('111111', 2);
    $second = concurrentOpenSession('222222', 1);

    $this->actingAs($student)
        ->post(route('student.attendance.store', $first), ['code' => '111111'])
        ->assertRedirect()
        ->assertSessionHas('status');

    $this->actingAs($student)
        ->post(route('student.attendance.store', $second), ['code' => '222222'])
        ->assertRedirect()
        ->assertSessionHas('status');

    expect(Attendance::where('club_session_id', $first->id)->where('user_id', $student->id)->exists())->toBeTrue()
        ->and(Attendance::where('club_session_id', $second->id)->where('user_id', $student->id)->exists())->toBeTrue();
});

it('kode satu sesi tidak bisa dipakai untuk sesi terbuka lainnya', function () {
    $student = concurrentStudent();
    $first = concurrentOpenSession('111111', 2);
    $second = concurrentOpenSession('222222', 1);

    $this->actingAs($student)
        ->post(route('student.attendance.store', $second), ['code' => '111111'])
        ->assertSessionHasErrors('code');

    expect(Attendance::where('club_session_id', $second->id)->where('user_id', $student->id)->exists())->toBeFalse();
});
