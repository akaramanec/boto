<?php

namespace App\Conversations;

use App\Conversations\Flows\AbstractFlow;
use App\Conversations\Flows\WelcomeFlow;
use App\Models\Product;
use App\Models\TelegramUser;
use Illuminate\Support\Facades\Log;

class Conversation
{
    protected $flows = [
        WelcomeFlow::class,
    ];

    public function start(TelegramUser $user, object $message)
    {
        Log::debug('Conversation.start', [
                'user' => $user->toArray(),
                'message' => $message->toArray()
            ]);

        $context = Context::get($user);

        foreach ($this->flows as $flow) {
            /** @var AbstractFlow $flow */
            $flow = app($flow);
            $flow->setUser($user);
            $flow->setMessage($message);
            $flow->setContext($context);
            $flow->run();
        }
    }
}
