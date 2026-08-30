<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\ClubSession;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AttendanceService
{
    public function checkIn(User $user, ClubSession $clubSession, string $code): Attendance
    {
        return DB::transaction(function () use ($user, $clubSession, $code) {
            $clubSession = ClubSession::query()->lockForUpdate()->findOrFail($clubSession->id);

            if (! $clubSession->isOpen() || ! $clubSession->attendance_code_hash) {
                throw ValidationException::withMessages(['code' => 'Kode presensi tidak valid atau sesi sudah ditutup.']);
            }

            if ($clubSession->isAttendanceCodeExpired()) {
                throw ValidationException::withMessages(['code' => 'Kode presensi sudah kedaluwarsa. Minta kode baru kepada instruktur.']);
            }

            if (! Hash::check($code, $clubSession->attendance_code_hash)) {
                throw ValidationException::withMessages(['code' => 'Kode presensi tidak valid.']);
            }

            if (Attendance::where('club_session_id', $clubSession->id)->where('user_id', $user->id)->exists()) {
                throw ValidationException::withMessages(['code' => 'Anda sudah melakukan presensi pada sesi ini.']);
            }

            return Attendance::create([
                'club_session_id' => $clubSession->id,
                'user_id' => $user->id,
                'attended_at' => now(),
            ]);
        });
    }
}
