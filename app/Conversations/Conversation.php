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
    /**
     * @var Context
     */
    private $context;

    public function __construct(
        Product $product,
        Context $context
    )
    {
        $this->product = $product;
        $this->context = $context;
    }

    public function start(TelegramUser $user, object $message)
    {
        Log::debug('Conversation.start', [
                'user' => $user->toArray(),
                'message' => $message->toArray()
            ]);

        foreach ($this->flows as $flow) {
            /** @var AbstractFlow $flow */
            $flow = app($flow);
            $flow->setUser($user);
            $flow->setMessage($message);
            $state = $flow->run();
            if (is_string($state)) {
                $this->context->save($user, $flow, $state);
                break;
            }
        }
    }
}
