<?php

namespace App\Services;

use App\Models\Product;
use App\Models\TelegramUser;
use Illuminate\Support\Facades\Log;

class TelegramCallbackService
{
    /**
     * @var TelegramUser
     */
    private $telegramUser;
    /**
     * @var Product
     */
    private $product;

    public function __construct(TelegramUser $telegramUser, Product $product)
    {
        $this->telegramUser = $telegramUser;
        $this->product = $product;
    }

    public function getNextAction(array $callbackQuery)
    {
        Log::debug('getNextAction.callbackQuery', $callbackQuery);
    }
}
