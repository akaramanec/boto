<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function wedhook()
    {
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
