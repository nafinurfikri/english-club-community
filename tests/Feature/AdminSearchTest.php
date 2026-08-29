<?php

use App\Models\Announcement;
use App\Models\ClubSession;
use App\Models\Material;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function adminSearchAdmin(): User
{
    return User::factory()->create(['role' => 'admin', 'status' => 'active']);
}

it('admin dapat mencari siswa berdasarkan nama', function () {
    $admin = adminSearchAdmin();
    User::factory()->create(['name' => 'Budi Santoso', 'role' => 'siswa', 'status' => 'active']);
    User::factory()->create(['name' => 'Andi Wijaya', 'role' => 'siswa', 'status' => 'active']);

    $this->actingAs($admin)
        ->get(route('admin.search', ['q' => 'Budi']))
        ->assertSuccessful()
        ->assertSee('Budi Santoso')
        ->assertDontSee('Andi Wijaya');
});

it('admin dapat mencari mata pelajaran berdasarkan nama atau guru', function () {
    $admin = adminSearchAdmin();
    Subject::create(['name' => 'Bahasa Inggris', 'level' => 'Dasar', 'teacher' => 'Miss Windy', 'description' => 'Deskripsi']);
    Subject::create(['name' => 'Matematika', 'level' => 'Dasar', 'teacher' => 'Mr Joko', 'description' => 'Deskripsi']);

    $this->actingAs($admin)
        ->get(route('admin.search', ['q' => 'Windy']))
        ->assertSuccessful()
        ->assertSee('Bahasa Inggris')
        ->assertDontSee('Matematika');
});

it('admin dapat mencari pengumuman berdasarkan judul', function () {
    $admin = adminSearchAdmin();
    Announcement::create(['title' => 'Pendaftaran Gelombang 2', 'slug' => 'pendaftaran-2', 'body' => 'Isi pengumuman']);
    Announcement::create(['title' => 'Penutupan', 'slug' => 'penutupan', 'body' => 'Isi pengumuman lain']);

    $this->actingAs($admin)
        ->get(route('admin.search', ['q' => 'Pendaftaran']))
        ->assertSuccessful()
        ->assertSee('Pendaftaran Gelombang 2')
        ->assertDontSee('Penutupan');
});

it('admin dapat mencari materi pembelajaran', function () {
    $admin = adminSearchAdmin();
    $session = ClubSession::create(['title' => 'Sesi 1', 'scheduled_at' => now()]);
    Material::create(['club_session_id' => $session->id, 'title' => 'Modul Grammar', 'type' => 'pdf', 'is_published' => true]);
    Material::create(['club_session_id' => $session->id, 'title' => 'Modul Vocabulary', 'type' => 'pdf', 'is_published' => true]);

    $this->actingAs($admin)
        ->get(route('admin.search', ['q' => 'Grammar']))
        ->assertSuccessful()
        ->assertSee('Modul Grammar')
        ->assertDontSee('Modul Vocabulary');
});

it('menampilkan pesan saat tidak ada hasil yang cocok', function () {
    $admin = adminSearchAdmin();

    $this->actingAs($admin)
        ->get(route('admin.search', ['q' => 'tidak ada data ini']))
        ->assertSuccessful()
        ->assertSee('Tidak ditemukan hasil');
});

it('hanya admin yang dapat mengakses pencarian', function () {
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);

    $this->actingAs($student)
        ->get(route('admin.search', ['q' => 'Budi']))
        ->assertForbidden();
});
