<?php

namespace App\Conversations\Flows;

use App\Conversations\Context;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Services\ProductService;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Keyboard\Keyboard;

class ProductFlow extends AbstractFlow
{
    protected $triggers = [
        'first' => '/start',
        'buy' => 'buy',
        'prev' => 'prev',
        'next' => 'next'
    ];

    protected $options = [
        'product_id' => null
    ];

    protected $states = [
        'first', 'continue'
    ];

    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    protected function first()
    {
        $product = $this->getProduct();

        Log::debug('ProductFlow.first', [
            'product' => $product,
        ]);

        Context::update($this->user, ['product_id' => $product->id]);

        $this->sendProductWithKeyboard($product);
    }

    public function continue()
    {
        $context = Context::get($this->user);
        $product = $this->getProduct();

        Log::debug('ProductFlow.continue', [
            'product' => $product,
        ]);

        Context::update($this->user, ['product_id' => $product->id]);

        $this->sendProductWithKeyboard($product);
    }

    protected function buy()
    {
        $product = $this->getProduct();

        Log::debug('ProductFlow.buy', [
            'product' => $product,
        ]);
    }

    protected function prev()
    {
        $product = $this->getProduct();

        Log::debug('ProductFlow.prev', [
            'product' => $product,
        ]);
    }

    protected function next()
    {
        $product = $this->getProduct();

        Log::debug('ProductFlow.next', [
            'product' => $product,
        ]);
    }

    protected function getProduct(): Product
    {
        if (!is_null($this->options['product_id'])) {
            /** @var $product Product */
            $product = $this->productService->getBuId($this->options['product_id']);
        } else {
            /** @var $product Product */
            $product = $this->productService->random();
        }
        return $product;
    }

    protected function sendProductWithKeyboard(Product $product): void
    {
        $buttons = $this->productService->getProductButtons($product);

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
