<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\TelegramUser;
use Illuminate\Support\Facades\Log;
use Telegram;

class TelegramController extends Controller
{
    public function wedhook()
    {
        if (isset(Telegram::getWebhookUpdates()['message'])) {
            $telegramMessage = Telegram::getWebhookUpdates()['message'];

            if (!TelegramUser::whereId($telegramMessage['from']['id'])->first()) {
                TelegramUser::create($telegramMessage['from']);
            }
        } else {
            Log::error('not message', Telegram::bot()->getWebhookUpdate());
        }
//
//        if (isset($telegramMessage['text'])) {
//            if ($telegramMessage['text'] == 'buy') {
//
//            }
//        }
        Telegram::commandsHandler(true);
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
