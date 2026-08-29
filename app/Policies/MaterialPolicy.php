<?php

namespace App\Policies;

use App\Models\Material;
use App\Models\User;

class MaterialPolicy
{
    public function view(User $user, Material $material): bool
    {
        return $user->isAdmin() || $material->clubSession->attendances()->where('user_id', $user->id)->exists();
    }
}
