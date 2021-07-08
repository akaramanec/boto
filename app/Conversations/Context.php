<?php

namespace App\Conversations;

use App\Conversations\Flows\AbstractFlow;
use App\Models\TelegramUser;
use Cache;
use Illuminate\Support\Facades\Log;

class Context
{
    public static function save(TelegramUser $user, AbstractFlow $flow, string $state)
    {
        Log::debug(static::class . '.save', [
            'user' => $user->toArray(),
            'flow' => get_class($flow),
            'state' => $state
        ]);

        Cache::forever(self::key($user), [
            'flow' => get_class($flow),
            'state' => $state
        ]);
    }

    public static function get(TelegramUser $user)
    {
        return Cache::get(self::key($user), []);
    }

    private static function key(TelegramUser $user): string
    {
        return 'context_' . $user->id;
    }
}
