<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('menampilkan tombol login saat belum login di halaman guest', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee(route('login'));
});

it('menampilkan nama dan menu logout untuk student yang sedang login di halaman guest', function () {
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);

    $this->actingAs($student)
        ->get(route('home'))
        ->assertOk()
        ->assertSee($student->name)
        ->assertSee('Logout')
        ->assertSee(route('student.dashboard'));
});

it('menampilkan nama dan menu logout untuk admin yang sedang login di halaman guest', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);

    $this->actingAs($admin)
        ->get(route('home'))
        ->assertOk()
        ->assertSee($admin->name)
        ->assertSee('Logout')
        ->assertSee(route('admin.dashboard'));
});

it('menampilkan nama user di header halaman student', function () {
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);

    $this->actingAs($student)
        ->get(route('student.grades'))
        ->assertOk()
        ->assertSee($student->name)
        ->assertSee('Logout');
});

it('menampilkan nama user di header halaman admin', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee($admin->name)
        ->assertSee('Logout');
});
