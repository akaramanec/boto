<?php

namespace App\Conversations\Flows;

use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Services\ProductService;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Keyboard\Keyboard;

class ProductFlow extends AbstractFlow
{
    protected $triggers = [
        'buy', 'prev', 'next'
    ];

    protected $options = [
        'product_id' => null
    ];

    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    protected function first()
    {
        if (!is_null($this->options['product_id'])) {
            /** @var $product Product */
            $product = $this->productService->getBuId($this->options['product_id']);
        } else {
            /** @var $product Product */
            $product = $this->productService->random();
        }

        Log::debug('ProductFlow.first', [
            'product' => $product,
        ]);

        $buttons =  $this->productService->getProductButtons($product);

        $this->telegram()->sendPhoto([
            'chat_id' => $this->user->id,
            'photo' => InputFile::create($product->image),
            'caption' => $product->name . " >> " . $product->description,
            'reply_markup' => Keyboard::make([
                'inline_keyboard' => $buttons
            ])
        ]);
    }


}
