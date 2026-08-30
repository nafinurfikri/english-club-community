<?php

use App\Models\Attendance;
use App\Models\ClubSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function historyAdmin(): User
{
    return User::factory()->create(['role' => 'admin', 'status' => 'active']);
}

function historyStudent(string $name): User
{
    return User::factory()->create(['name' => $name, 'role' => 'siswa', 'status' => 'active']);
}

it('halaman riwayat menampilkan sesi pada minggu berjalan saja', function () {
    $admin = historyAdmin();

    ClubSession::factory()->create(['scheduled_at' => now()->startOfWeek()->addDays(1), 'title' => 'Sesi Minggu Ini']);
    ClubSession::factory()->create(['scheduled_at' => now()->startOfWeek()->subWeek()->addDays(1), 'title' => 'Sesi Minggu Lalu']);

    $this->actingAs($admin)
        ->get(route('admin.attendance.history'))
        ->assertOk()
        ->assertSee('Sesi Minggu Ini')
        ->assertDontSee('Sesi Minggu Lalu');
});

it('parameter week menampilkan sesi pada minggu yang diminta', function () {
    $admin = historyAdmin();
    $prevWeek = now()->startOfWeek()->subWeek()->format('o-\WW');

    ClubSession::factory()->create(['scheduled_at' => now()->startOfWeek()->addDays(1), 'title' => 'Sesi Minggu Ini']);
    ClubSession::factory()->create(['scheduled_at' => now()->subWeek()->startOfWeek()->addDays(1), 'title' => 'Sesi Minggu Lalu']);

    $this->actingAs($admin)
        ->get(route('admin.attendance.history', ['week' => $prevWeek]))
        ->assertOk()
        ->assertSee('Sesi Minggu Lalu')
        ->assertDontSee('Sesi Minggu Ini');
});

it('parameter week yang tidak valid memakai minggu berjalan', function () {
    $admin = historyAdmin();

    ClubSession::factory()->create(['scheduled_at' => now()->startOfWeek()->addDays(1), 'title' => 'Sesi Minggu Ini']);

    $this->actingAs($admin)
        ->get(route('admin.attendance.history', ['week' => 'abc']))
        ->assertOk()
        ->assertSee('Sesi Minggu Ini');
});

it('halaman riwayat menyediakan dropdown berisi minggu berjalan dan minggu dari sesi', function () {
    $admin = historyAdmin();
    $lastWeekMonday = now()->startOfWeek()->subWeek();

    ClubSession::factory()->create(['scheduled_at' => $lastWeekMonday->copy()->addDays(1)]);

    $html = $this->actingAs($admin)
        ->get(route('admin.attendance.history'))
        ->assertOk()
        ->getContent();

    $currentWeek = now()->startOfWeek()->format('o-\WW');

    expect(str_contains($html, 'name="week"'))->toBeTrue()
        ->and(str_contains($html, 'value="'.$lastWeekMonday->format('o-\WW').'"'))->toBeTrue()
        ->and(str_contains($html, 'value="'.$currentWeek.'"'))->toBeTrue()
        ->and(str_contains($html, 'selected'))->toBeTrue();
});

it('halaman riwayat menampilkan pesan kosong saat tidak ada sesi pada minggu tersebut', function () {
    $admin = historyAdmin();
    $prevWeek = now()->startOfWeek()->subWeek()->format('o-\WW');

    $this->actingAs($admin)
        ->get(route('admin.attendance.history', ['week' => $prevWeek]))
        ->assertOk()
        ->assertSee('Belum ada sesi pada minggu ini.');
});

it('halaman roster sesi menampilkan siswa beserta status kehadirannya', function () {
    $admin = historyAdmin();
    $hadirStudent = historyStudent('Budi Hadir');
    $izinStudent = historyStudent('Siti Izin');
    $tanpaCatatan = historyStudent('Rina Alpha');
    $session = ClubSession::factory()->create(['scheduled_at' => now()->startOfWeek()->addDays(1)]);

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
        ->get(route('admin.attendance.history.session', $session))
        ->assertOk()
        ->assertSee('Budi Hadir')
        ->assertSee('Siti Izin')
        ->assertSee('Rina Alpha')
        ->assertSee('Hadir')
        ->assertSee('Izin')
        ->assertSee('WIB')
        ->getContent();

    preg_match('/text-xl font-bold text-gray-900">(\d+)<\/span>/', $html, $total);
    preg_match('/text-xl font-bold text-emerald-700">(\d+)<\/span>/', $html, $hadir);
    preg_match('/text-xl font-bold text-amber-700">(\d+)<\/span>/', $html, $izin);
    preg_match('/text-xl font-bold text-rose-700">(\d+)<\/span>/', $html, $alpha);

    expect((int) ($total[1] ?? 0))->toBe(3)
        ->and((int) ($hadir[1] ?? 0))->toBe(1)
        ->and((int) ($izin[1] ?? 0))->toBe(1)
        ->and((int) ($alpha[1] ?? 0))->toBe(1)
        ->and(str_contains($html, 'bg-rose-100 text-rose-700">Alpha</span>'))->toBeTrue();
});

it('roster sesi menyediakan tombol aksi untuk mengubah status kehadiran', function () {
    $admin = historyAdmin();
    $student = historyStudent('Budi Hadir');
    $session = ClubSession::factory()->create(['scheduled_at' => now()->startOfWeek()->addDays(1)]);

    Attendance::create([
        'club_session_id' => $session->id,
        'user_id' => $student->id,
        'attended_at' => now(),
        'status' => Attendance::STATUS_HADIR,
    ]);

    $html = $this->actingAs($admin)
        ->get(route('admin.attendance.history.session', $session))
        ->assertOk()
        ->assertSee('Aksi Status')
        ->getContent();

    expect(substr_count($html, route('admin.attendance.status')))->toBe(3);
});

it('admin dapat mengubah status kehadiran dari halaman roster sesi', function () {
    $admin = historyAdmin();
    $student = historyStudent('Rina Alpha');
    $session = ClubSession::factory()->create(['scheduled_at' => now()->startOfWeek()->addDays(1)]);

    $this->actingAs($admin)
        ->from(route('admin.attendance.history.session', $session))
        ->patch(route('admin.attendance.status'), [
            'club_session_id' => $session->id,
            'user_id' => $student->id,
            'status' => Attendance::STATUS_IZIN,
        ])
        ->assertRedirect(route('admin.attendance.history.session', $session));

    expect(
        Attendance::where('club_session_id', $session->id)->where('user_id', $student->id)->first()
    )->status->toBe(Attendance::STATUS_IZIN);
});

it('halaman riwayat kehadiran hanya dapat diakses oleh admin', function () {
    $student = historyStudent('Andi Siswa');
    $session = ClubSession::factory()->create(['scheduled_at' => now()->startOfWeek()->addDays(1)]);

    $this->actingAs($student)
        ->get(route('admin.attendance.history'))
        ->assertForbidden();

    $this->actingAs($student)
        ->get(route('admin.attendance.history.session', $session))
        ->assertForbidden();
});
