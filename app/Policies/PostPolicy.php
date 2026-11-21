<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * An admin edits every post, an author only edits their own posts.
     */
    public function update(User $user, Post $post): bool
    {
        return $user->isAdmin() || $user->id === $post->user_id;
    }

    /**
     * Deleting follows the same rule as editing.
     */
    public function delete(User $user, Post $post): bool
    {
        return $this->update($user, $post);
    }
}
