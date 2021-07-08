<?php

namespace App\Conversations;

use App\Conversations\Flows\AbstractFlow;
use App\Models\TelegramUser;
use Cache;
use Illuminate\Support\Facades\Log;

class Context
{
    public function save(TelegramUser $user, AbstractFlow $flow, string $state)
    {
        Log::debug(static::class . '.run', [
            'user' => $user->toArray(),
            'flow' => get_class($flow),
            'state' => $state
        ]);

        Cache::forever($this->key($user), [
            'flow' => get_class($flow),
            'state' => $state
        ]);
    }

    private function key(TelegramUser $user)
    {
        return 'context_' . $user->id;
    }
}
