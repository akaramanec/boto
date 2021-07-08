<?php

namespace App\Conversations\Flows;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Support\Facades\Log;

class ProductFlow extends AbstractFlow
{
    protected $triggers = [];

    protected function first()
    {
        $products = Product::all();

        Log::debug('products', [
            'all' => $products->toArray(),
        ]);

        $this->telegram()->sendMessage([
            'chat_id' => $this->user->id,
            'text' => __('List of products of our shop')
        ]);
    }
}
