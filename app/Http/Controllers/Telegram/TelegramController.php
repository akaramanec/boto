<?php

namespace App\Http\Controllers\Telegram;

use App\Conversations\Conversation;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\TelegramUser;
use App\Repositories\TelegramUserRepository;
use App\Services\TelegramCallbackService;
use Illuminate\Support\Facades\Log;
use Telegram;

class TelegramController extends Controller
{
    /**
     * @var TelegramCallbackService
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
        $user = $message->getFrom();

        $user = $this->users->store($user->toArray());

        $this->conversation->start($user, $message);

//        if (Telegram::bot()->getWebhookUpdate()['callback_query']) {
//            $this->callbackService->nextStep(Telegram::bot()->getWebhookUpdate()['callback_query']);
//        }

//        if (isset(Telegram::getWebhookUpdates()['message'])) {
//            $telegramMessage = Telegram::getWebhookUpdates()['message'];
//
//            if (!TelegramUser::whereId($telegramMessage['from']['id'])->first()) {
//                TelegramUser::create($telegramMessage['from']);
//            }
//        }
    }

    public function show()
    {
        return Product::inRandomOrder()->first();
    }

    public function next(Product $product)
    {
        return Product::where('id', '>', $product->id)->first() ?? $product;
    }

    public function prev(Product $product)
    {
        return Product::where('id', '<', $product->id)->first() ?? $product;
    }
}
