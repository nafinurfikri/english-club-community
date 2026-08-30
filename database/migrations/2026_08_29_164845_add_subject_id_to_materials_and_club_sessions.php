<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->foreignId('subject_id')->nullable()->after('club_session_id')->constrained('subjects')->cascadeOnDelete();
        });

        Schema::table('club_sessions', function (Blueprint $table) {
            $table->foreignId('subject_id')->nullable()->after('title')->constrained('subjects')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('club_sessions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('subject_id');
        });

        Schema::table('materials', function (Blueprint $table) {
            $table->dropConstrainedForeignId('subject_id');
        });
    }
};
