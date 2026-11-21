<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * An admin changes the role of other users, never their own
     * (otherwise the last admin could lock everyone out).
     */
    public function changeRole(User $user, User $target): bool
    {
        return $user->isAdmin() && $user->id !== $target->id;
    }
}
