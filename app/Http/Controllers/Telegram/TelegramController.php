<?php

namespace App\Http\Controllers\Telegram;

use App\Conversations\Conversation;
use App\Http\Controllers\Controller;
use App\Models\TelegramUser;
use App\Repositories\TelegramUserRepository;
use Illuminate\Support\Facades\Log;
use Telegram;

class TelegramController extends Controller
{
    protected $conversation;

    public function __construct(Conversation $conversation)
    {
        $this->conversation = $conversation;
    }

    public function webhook()
    {
        $update = Telegram::bot()->getWebhookUpdate();

        if (isset($update['callback_query'])) {
            Log::debug('webhook.callback_query', ['update' => $update->toArray()]);
            $this->conversation->continue($update);
        } else {
            Log::debug('webhook.getFrom', ['update' => $update->toArray()]);
            $this->conversation->start($update);
        }
    }
}
