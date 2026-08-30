<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\Material;
use App\Models\User;

class MaterialPolicy
{
    public function view(User $user, Material $material): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($material->subject_id) {
            return $this->hasAttendedAnySessionOfSubject($user, $material->subject_id);
        }

        return $this->hasAttendedSession($user, $material->club_session_id);
    }

    private function hasAttendedSession(User $user, ?int $clubSessionId): bool
    {
        if ($clubSessionId === null) {
            return false;
        }

        return Attendance::where('club_session_id', $clubSessionId)
            ->where('user_id', $user->id)
            ->where('status', Attendance::STATUS_HADIR)
            ->exists();
    }

    private function hasAttendedAnySessionOfSubject(User $user, int $subjectId): bool
    {
        return Attendance::where('user_id', $user->id)
            ->where('status', Attendance::STATUS_HADIR)
            ->whereHas('clubSession', fn ($query) => $query->where('subject_id', $subjectId))
            ->exists();
    }
}
