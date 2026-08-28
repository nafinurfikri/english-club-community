<?php

it('renders the student dashboard page', function () {
    $this->get(route('student.dashboard'))
        ->assertOk()
        ->assertSee('English Proficiency Excellence Map')
        ->assertSee('Welcome Back, Budi!')
        ->assertSee('Budi Santoso');
});
