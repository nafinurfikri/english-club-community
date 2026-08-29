<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\ClubSession;
use App\Models\GradeCategory;
use App\Models\LandingSection;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.test'],
            ['name' => 'Administrator', 'password' => Hash::make('password'), 'role' => 'admin', 'status' => 'active']
        );

        User::updateOrCreate(
            ['email' => 'student@example.test'],
            ['name' => 'Budi Santoso', 'password' => Hash::make('password'), 'role' => 'siswa', 'status' => 'active']
        );

        GradeCategory::firstOrCreate(['name' => 'Speaking']);
        GradeCategory::firstOrCreate(['name' => 'Expression']);

        $demoSubjects = [
            ['name' => 'Advanced Grammar', 'level' => 'Level 3 - Int', 'teacher' => 'Dr. Elizabeth Stone', 'description' => 'Comprehensive review of complex grammatical structures and advanced usage.'],
            ['name' => 'Conversational Practice', 'level' => 'Level 2 - Basic', 'teacher' => 'James Mitchell, M.A.', 'description' => 'Interactive discussion sessions aimed at enhancing everyday fluency.'],
            ['name' => 'Listening Comprehension', 'level' => 'Level 1 - Beg', 'teacher' => 'Sarah Connor, B.E.d', 'description' => 'Designed to build auditory skills focused on daily conversations.'],
            ['name' => 'Public Speaking', 'level' => 'Level 4 - Adv', 'teacher' => 'Prof. Arthur Pendelton', 'description' => 'Developing rhetorical speaking capacities, body language, and stage presence.'],
            ['name' => 'Business English', 'level' => 'Professional Class', 'teacher' => 'William Sterling, MBA', 'description' => 'Instruction focused on corporate presentations and email etiquette.'],
            ['name' => 'TOEFL Preparation', 'level' => 'Special Prep', 'teacher' => 'Dr. Amanda Ross', 'description' => 'Rigorous diagnostic testing and skill builder tasks for the TOEFL exam.'],
        ];

        foreach ($demoSubjects as $index => $subject) {
            Subject::firstOrCreate(
                ['name' => $subject['name']],
                [...$subject, 'sort_order' => $index, 'is_published' => true]
            );
        }

        $landingSections = [
            'hero' => ['title' => 'Belajar Bahasa Inggris Bareng Komunitas', 'subtitle' => 'Gabung dengan English Club Community, tempat latihan speaking, sharing, dan kegiatan seru bareng teman sebaya.'],
            'about' => ['title' => 'Apa itu English Club?', 'body' => 'English Club Community adalah wadah bagi siswa untuk mengasah kemampuan bahasa Inggris melalui kegiatan yang menyenangkan dan kolaboratif.'],
            'cta' => ['title' => 'Yuk, Gabung Sekarang!', 'body' => 'Jadi bagian dari EC Dwiguna dan asah kemampuan bahasa Inggrismu bareng komunitas.'],
        ];

        foreach ($landingSections as $key => $content) {
            LandingSection::updateOrCreate(
                ['key' => $key],
                ['draft_content' => $content, 'published_content' => $content]
            );
        }

        ClubSession::firstOrCreate(
            ['title' => 'Routine English Practice'],
            ['description' => 'Sesi latihan rutin English Club.', 'scheduled_at' => now()->addDay()]
        );

        Announcement::firstOrCreate(
            ['slug' => 'selamat-datang-di-ec-dwiguna'],
            [
                'title' => 'Selamat Datang di EC Dwiguna',
                'body' => 'Informasi terbaru kegiatan English Club tersedia di sini.',
                'type' => 'agenda',
                'published_at' => now(),
            ]
        );
    }
}
