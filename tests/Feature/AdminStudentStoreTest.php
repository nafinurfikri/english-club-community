<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function studentStoreAdmin(): User
{
    return User::factory()->create(['role' => 'admin', 'status' => 'active']);
}

it('admin dapat menambah siswa aktif', function () {
    $admin = studentStoreAdmin();

    $this->actingAs($admin)
        ->post(route('admin.students.store'), [
            'name' => 'Siswa Baru',
            'email' => 'siswa.baru@example.test',
        ])
        ->assertRedirect();

    $student = User::where('email', 'siswa.baru@example.test')->first();
    expect($student)->not->toBeNull()
        ->and($student->role)->toBe('siswa')
        ->and($student->status)->toBe('active');
});

it('admin dapat menambah siswa dengan password khusus', function () {
    $admin = studentStoreAdmin();

    $this->actingAs($admin)
        ->post(route('admin.students.store'), [
            'name' => 'Siswa Dua',
            'email' => 'siswa.dua@example.test',
            'password' => 'rahasia123',
        ])
        ->assertRedirect();

    $student = User::where('email', 'siswa.dua@example.test')->first();

    expect(app('hash')->check('rahasia123', $student->password))->toBeTrue();
});

it('email siswa harus unik saat ditambahkan admin', function () {
    $admin = studentStoreAdmin();
    User::factory()->create(['email' => 'ada@example.test']);

    $this->actingAs($admin)
        ->post(route('admin.students.store'), [
            'name' => 'Duplikat',
            'email' => 'ada@example.test',
        ])
        ->assertSessionHasErrors('email');
});

it('hanya admin yang dapat menambah siswa', function () {
    $student = User::factory()->create(['role' => 'siswa', 'status' => 'active']);

    $this->actingAs($student)
        ->post(route('admin.students.store'), [
            'name' => 'Nakal',
            'email' => 'nakal@example.test',
        ])
        ->assertForbidden();
});
