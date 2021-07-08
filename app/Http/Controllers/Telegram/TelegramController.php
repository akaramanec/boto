<?php

namespace App\Http\Controllers\Telegram;

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
    protected $callbackService;
    /**
     * @var TelegramUserRepository
     */
    protected $users;

    protected function __construct(
        TelegramCallbackService $callbackService,
        TelegramUserRepository $userRepository
    )
    {
        $this->callbackService = $callbackService;
        $this->users = $userRepository;
    }

    public function webhook()
    {

        $update = Telegram::bot()->getWebhookUpdate();
        $message = $update->getMessage();
        $user = $message->getFrom();

        $this->users->store($user);

        if (Telegram::bot()->getWebhookUpdate()['callback_query']) {
            $this->callbackService->nextStep(Telegram::bot()->getWebhookUpdate()['callback_query']);
        }

//        if (isset(Telegram::getWebhookUpdates()['message'])) {
//            $telegramMessage = Telegram::getWebhookUpdates()['message'];
//
//            if (!TelegramUser::whereId($telegramMessage['from']['id'])->first()) {
//                TelegramUser::create($telegramMessage['from']);
//            }
//        }

        Telegram::bot()->commandsHandler(true);
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
