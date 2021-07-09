<?php

namespace App\Conversations;

use App\Conversations\Flows\AbstractFlow;
use App\Conversations\Flows\ProductFlow;
use App\Conversations\Flows\ShopFlow;
use App\Conversations\Flows\WelcomeFlow;
use App\Models\TelegramUser;
use App\Repositories\TelegramUserRepository;
use Illuminate\Support\Facades\Log;
use Telegram;
use Telegram\Bot\Objects\Update;

class Conversation
{
    protected $flows = [
        ShopFlow::class,
        ProductFlow::class
    ];
    /**
     * @var TelegramUserRepository
     */
    protected $users;

    public function __construct(TelegramUserRepository $userRepository)
    {
        $this->users = $userRepository;
    }

    public function start(Update $update)
    {
        $message = $update->getMessage();
        $user = $message->getFrom();

        /** @var TelegramUser $user */
        $user = $this->users->store($user->toArray());
        $context = Context::get($user);

        if (isset($context)) {
            Log::debug('Conversation.start.context', [$context]);
        }
        $flow = $this->getCurrentFlow($context);
        $this->run($flow, $user, $message, $context);
    }

    public function continue(Update $update)
    {
        $message = $update->getMessage();
        $message->text = $update->callbackQuery->data;
        $chatId = $update->getChat()->id;

        /** @var TelegramUser $user */
        $user = $this->users->getBuId($chatId);
        $context = Context::get($user);

        Telegram::bot()->deleteMessage([
            'chat_id' => $chatId,
            'message_id' => $message->messageId
        ]);

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
        $flow->setOptions($context['options'] ?? []);
        $flow->run($context['state']);
    }
}
