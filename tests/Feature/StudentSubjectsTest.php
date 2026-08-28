<?php

it('renders the student subjects page', function () {
    $this->get(route('student.subjects'))
        ->assertOk()
        ->assertSee('Kurikulum & Mata Pelajaran')
        ->assertSee('Advanced Grammar')
        ->assertSee('TOEFL Preparation');
});
