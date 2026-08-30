<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('club_sessions', function (Blueprint $table) {
            $table->text('attendance_code')->nullable()->after('attendance_code_hash');
            $table->timestamp('attendance_code_expires_at')->nullable()->after('attendance_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('club_sessions', function (Blueprint $table) {
            $table->dropColumn(['attendance_code', 'attendance_code_expires_at']);
        });
    }
};
