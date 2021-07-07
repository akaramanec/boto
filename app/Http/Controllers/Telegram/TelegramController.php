<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\TelegramUser;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function wedhook()
    {
        $telegramMessage = \Telegram::getWebhookUpdates()['message'];

        if (!TelegramUser::whereId($telegramMessage['from']['id'])->first()) {
            TelegramUser::create($telegramMessage['from']);
        }

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
