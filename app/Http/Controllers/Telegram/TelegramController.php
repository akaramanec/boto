<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\TelegramUser;
use App\Services\TelegramCallbackService;
use Illuminate\Support\Facades\Log;
use Telegram;

class TelegramController extends Controller
{
    /**
     * @var TelegramCallbackService
     */
    private $callbackService;

    public function __construct(TelegramCallbackService $callbackService)
    {

        $this->callbackService = $callbackService;
    }

    public function webhook()
    {

        if (Telegram::bot()->getWebhookUpdate()['callback_query']) {
            $this->callbackService->getNextAction(Telegram::bot()->getWebhookUpdate()['callback_query']);
        }

        if (isset(Telegram::getWebhookUpdates()['message'])) {
            $telegramMessage = Telegram::getWebhookUpdates()['message'];

            if (!TelegramUser::whereId($telegramMessage['from']['id'])->first()) {
                TelegramUser::create($telegramMessage['from']);
            }
        }

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
