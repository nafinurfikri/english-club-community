<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the student login page without a dashboard sidebar', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee('Login Student')
        ->assertDontSee('Student Menu');
});

it('logs a student in with valid credentials', function () {
    $user = User::factory()->create([
        'email' => 'student@example.com',
        'password' => 'password123',
        'status' => 'active',
    ]);

    $this->post(route('login.store'), [
        'email' => 'student@example.com',
        'password' => 'password123',
    ])->assertRedirect(route('student.dashboard'));

    $this->assertAuthenticatedAs($user);
});

it('rejects invalid student credentials', function () {
    User::factory()->create([
        'email' => 'student@example.com',
        'password' => 'password123',
        'status' => 'active',
    ]);

    $this->post(route('login.store'), [
        'email' => 'student@example.com',
        'password' => 'wrong-password',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});
