<?php

namespace App\Policies;

use App\Models\TradePost;
use App\Models\User;

class TradePostPolicy
{
    public function view(User $user, TradePost $post): bool
    {
        return $user->id === $post->user_id || $user->can('moderate trade posts');
    }

    public function create(User $user): bool
    {
        return $user->hasApprovedCompany() && $user->can('create trade posts');
    }

    public function update(User $user, TradePost $post): bool
    {
        return ($user->id === $post->user_id && $user->can('edit own trade posts'))
            || $user->can('moderate trade posts');
    }

    public function delete(User $user, TradePost $post): bool
    {
        return ($user->id === $post->user_id && $user->can('delete own trade posts'))
            || $user->can('moderate trade posts');
    }
}
