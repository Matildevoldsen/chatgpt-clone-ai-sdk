<?php

namespace App\Actions\Chats;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ListChats
{
    /**
     * @return Collection<int, Chat>
     */
    public function handle(User $user): Collection
    {
        return $user->chats()
            ->latest('updated_at')
            ->get();
    }
}
