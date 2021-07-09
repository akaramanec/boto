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
        if (isset($context)) {
            Log::debug('Conversation.start.context', [$context]);
        }
        $flow = $this->getCurrentFlow($context);
        $this->run($flow, $user, $message, $context);
    }

    public function continue(TelegramUser $user, object $message)
    {
        Log::debug('Conversation.continue', [
            'user' => $user->toArray(),
            'message' => $message->toArray()
        ]);
        $context = Context::get($user);
        Log::debug('Conversation.continue.context', [$context]);
        $flow = $this->getCurrentFlow($context);

        // if user is bot need check by trigger or context
        $context['state'] = null;
        $this->run($flow, $user, $message, $context);
    }

    protected function getCurrentFlow($context): AbstractFlow
    {
        $flow = app(WelcomeFlow::class);
        foreach ($this->flows as $flowName) {
            if (isset($context['flow']) && $flowName == $context['flow']) {
                $flow = app($flowName);
            }
        }
        return $flow;
    }

    protected function run(AbstractFlow $flow, TelegramUser $user, object $message, array $context): void
    {
        $flow->setUser($user);
        $flow->setMessage($message);
        $flow->setContext($context);
        $flow->run($context['state']);
    }
}
