<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the student registration page without a dashboard sidebar', function () {
    $this->get(route('student.register'))
        ->assertOk()
        ->assertSee('Daftar sebagai Student')
        ->assertDontSee('Student Menu');
});

it('registers a student account', function () {
    $this->post(route('student.register.store'), [
        'name' => 'Nafi Nur Fikri',
        'email' => 'nafi@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertRedirect(route('student.register'));

    expect(User::where('email', 'nafi@example.com')->exists())->toBeTrue();
});