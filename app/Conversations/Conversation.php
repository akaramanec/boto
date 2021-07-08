<?php

namespace App\Conversations;

use App\Conversations\Flows\AbstractFlow;
use App\Conversations\Flows\ShopFlow;
use App\Conversations\Flows\WelcomeFlow;
use App\Models\Product;
use App\Models\TelegramUser;
use Illuminate\Support\Facades\Log;

class Conversation
{
    protected $flows = [
        WelcomeFlow::class,
        ShopFlow::class
    ];

    public function start(TelegramUser $user, object $message)
    {
        Log::debug('Conversation.start', [
                'user' => $user->toArray(),
                'message' => $message->toArray()
            ]);

        $context = Context::get($user);
        $flow = app(WelcomeFlow::class);

        foreach ($this->flows as $flowName) {
            if (isset($context['flow']) && $flowName == $context['flow']) {
                $flow = app($flowName);
            }
        }

        $flow->getFlow(get_class($flow))->run();
    }
}
