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
    protected $telegramUser;
    /**
     * @var Product
     */
    protected $product;

    protected $actions = [
        '/buy', '/prev', '/next'
    ];

    public function __construct(TelegramUser $telegramUser, Product $product)
    {
        $this->telegramUser = $telegramUser;
        $this->product = $product;
    }

    public function start(TelegramUser $user)
    {

    }

    public function nextStep(array $callbackQuery)
    {
        Log::debug('TelegramCallbackService.nextStep', $callbackQuery);

        foreach ($this->actions as $action) {
            if ($callbackQuery['data'] == $action) {
                $this->$action($callbackQuery);
            }
        }
    }

    public function buy(array $callbackQuery)
    {
        Log::debug('TelegramCallbackService.buy', $callbackQuery['message']['product']['id']);
    }
}
