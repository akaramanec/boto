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
        $message = $update->getMessage();

        if (isset($update['callback_query'])) {
            Telegram::bot()->editMessageReplyMarkup(['reply_markup' => ['inline_keyboard' => []]]);
            $message->text = $update->callbackQuery->data;
            $user = $this->users->getBuId($update->getChat()->id);
            Log::debug('webhook.callback_query', ['user' => $user]);
            $this->conversation->continue($user, $message);
        } else {
            $user = $message->getFrom();
            Log::debug('webhook.getFrom', ['user' => $user]);
            $user = $this->users->store($user->toArray());
            $this->conversation->start($user, $message);
        }
    }
}
