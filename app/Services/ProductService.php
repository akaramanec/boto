<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Eloquent;

class ProductService extends ProductRepository
{
    public const BUTTON_BUY = 'buy';
    public const BUTTON_PREV = 'prev';
    public const BUTTON_NEXT = 'next';

    protected $buttons = [];

    public function getProductButtons(Eloquent $product): array
    {
        $row = 0;
        if($product->availability) {
            $this->buttons[$row][] = [self::BUTTON_BUY];
        }
        $this->buttons[$row][] = [self::BUTTON_PREV];
        $this->buttons[$row][] = [self::BUTTON_NEXT];
        return $this->buttons;
    }
}
