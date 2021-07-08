<?php

namespace App\Conversations;

use App\Conversations\Flows\AbstractFlow;
use App\Conversations\Flows\WelcomeFlow;
use App\Models\Product;
use App\Models\TelegramUser;
use Illuminate\Support\Facades\Log;

class Conversation
{
    public function start(TelegramUser $user, object $message)
    {
        Log::debug('Conversation.start', [
                'user' => $user->toArray(),
                'message' => $message->toArray()
            ]);

        $flow = app(WelcomeFlow::class);
        $flow->setUser($user);
        $flow->setMessage($message);
        $flow->run();
    }
}
