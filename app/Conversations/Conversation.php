<?php

namespace App\Conversations;

use App\Conversations\Flows\AbstractFlow;
use App\Conversations\Flows\ProductFlow;
use App\Conversations\Flows\ShopFlow;
use App\Conversations\Flows\WelcomeFlow;
use App\Models\Product;
use App\Models\TelegramUser;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Objects\CallbackQuery;

class Conversation
{
    protected $flows = [
        ShopFlow::class,
        ProductFlow::class
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
            if (isset($context['flow'])){
                Log::debug('Conversation.start', [
                    'context.flow' => [$context['flow']],
                    'flowName' => $flowName
                ]);
                if ($flowName == $context['flow']) {
                    $flow = app($flowName);
                }
            }
        }

        $flow->setUser($user);
        $flow->setMessage($message);
        $flow->setContext($context);
        $flow->run($context['state'] ?? null);
    }
}
