<?php

namespace App\Conversations;

use App\Conversations\Flows\AbstractFlow;
use App\Models\TelegramUser;
use Cache;
use Illuminate\Support\Facades\Log;

class Context
{
    public static function save(TelegramUser $user, AbstractFlow $flow, string $state, array $options = null)
    {
        Log::debug('Context.save', [
            'user' => $user->toArray(),
            'flow' => get_class($flow),
            'state' => $state,
            'options' => $options
        ]);

        Cache::forever(self::key($user), [
            'flow' => get_class($flow),
            'state' => $state,
            'options' => $options
        ]);
    }

    public static function get(TelegramUser $user)
    {
        return Cache::get(self::key($user), []);
    }

    public static function update(TelegramUser $user, array $options = [])
    {
        $currentContext = self::get($user);

        Log::debug('Context.update', [
            'user' => $user->toArray(),
            'options' => $currentContext
        ]);


        Cache::forever(self::key($user), [
            'flow' => $currentContext['flow'],
            'state' => $currentContext['state'],
            'options' => $options
        ]);
    }

    private static function key(TelegramUser $user): string
    {
        return 'context_' . $user->id;
    }
}
