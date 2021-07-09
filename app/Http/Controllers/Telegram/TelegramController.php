<?php

namespace App\Http\Controllers\Telegram;

use App\Conversations\Conversation;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\TelegramUser;
use App\Repositories\TelegramUserRepository;
use App\Services\TelegramKeybordService;
use Illuminate\Support\Facades\Log;
use Telegram;

class TelegramController extends Controller
{
    /**
     * @var TelegramKeybordService
     */
    protected $conversation;
    /**
     * @var TelegramUserRepository
     */
    protected $users;

    public function __construct(
        TelegramUserRepository $userRepository,
        Conversation $conversation
    )
    {
        $this->conversation = $conversation;
        $this->users = $userRepository;
    }

    public function webhook()
    {
        $update = Telegram::bot()->getWebhookUpdate();
        if (!(array)$update->callbackQuery) {
            Log::debug('empty', [$update->callbackQuery]);
        }

//
//        Log::debug('webhook', [
//                $update->callbackQuery ?? null,
//                $update->callback_query ?? null,
//                $update['callback_query'] ?? null
//        ]);

        $message = $update->getMessage();
        $user = $message->getFrom();
        $user = $this->users->store($user->toArray());

        $this->conversation->start($user, $message);
    }
}
