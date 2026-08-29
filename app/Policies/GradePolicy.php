<?php

namespace App\Policies;

use App\Models\Grade;
use App\Models\User;

class GradePolicy
{
    public function view(User $user, Grade $grade): bool
    {
        return $user->isAdmin() || ($grade->user_id === $user->id && $grade->published_at !== null);
    }
}
