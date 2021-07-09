<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\TelegramUser;
use App\Repositories\ProductRepository;
use Eloquent;

class ProductService extends ProductRepository
{
    public const BUTTON_BUY = 'buy';
    public const BUTTON_PREV = 'prev';
    public const BUTTON_NEXT = 'next';

    protected $buttonTexts = [
        self::BUTTON_BUY => 'Buy',
        self::BUTTON_PREV => '<< Prev',
        self::BUTTON_NEXT => 'Next >>',
    ];

    protected $buttons = [];

    public function getProductButtons(Eloquent $product)
    {
        $row = 0;
        if($product->availability) {
            $this->buttons[$row][] = ['text' => $this->buttonTexts[self::BUTTON_BUY], 'callback_data' => self::BUTTON_BUY];
        }
        $this->buttons[$row][] = ['text' => $this->buttonTexts[self::BUTTON_PREV], 'callback_data' => self::BUTTON_PREV];
        $this->buttons[$row][] = ['text' => $this->buttonTexts[self::BUTTON_NEXT], 'callback_data' => self::BUTTON_NEXT];
        return $this->buttons;
    }

    public function buy(TelegramUser $user, Product $product): Eloquent
    {
        return Order::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'count' => 1,
            'price' => $product->price,
            'sum' => $product->price,
            'status' => 1
        ]);
    }
}
