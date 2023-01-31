<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Post;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * // обновление объявления
     *
     * @param User $user
     * @param Post $post
     *
     * @return boolean
     */
    public function update(User $user, Post $post): bool
    {
        return $post->user->id === $user->id;
    }

    /**
     * // удаление объявления
     *
     * @param User $user
     * @param Post $post
     *
     * @return boolean
     */
    public function destroy(User $user, Post $post): bool
    {
        return $this->update($user, $post);
    }
}
