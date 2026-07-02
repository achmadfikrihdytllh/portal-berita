<?php

namespace App\Policies;

use App\Models\News;
use App\Models\User;

class NewsPolicy
{
    public function update(User $user, News $news): bool
    {
        if ($user->role === 'author') {
            return $news->user_id === $user->id;
        }

        return $user->isAuthor(); // admin & editor selalu boleh
    }

    public function delete(User $user, News $news): bool
    {
        if ($user->role === 'author') {
            return $news->user_id === $user->id;
        }

        return $user->isEditor(); // author lain tidak boleh hapus milik orang lain
    }
}
