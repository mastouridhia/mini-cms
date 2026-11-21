<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * A published post is public; a draft is visible to its author and to admins only.
     * The nullable user lets Laravel call this method for visitors too.
     */
    public function view(?User $user, Post $post): bool
    {
        if ($post->is_published) {
            return true;
        }

        return $user !== null && $this->update($user, $post);
    }

    /**
     * Any logged in user may write a post.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * An author only edits their own posts (admins pass through Gate::before).
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * Deleting follows the same rule as editing.
     */
    public function delete(User $user, Post $post): bool
    {
        return $this->update($user, $post);
    }
}
