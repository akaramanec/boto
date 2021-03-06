<?php

namespace App\Conversations\Flows;

use App\Conversations\Context;
use App\Models\Order;
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

    protected function buy()
    {
        $currentProduct = $this->getProduct();

        if (!isset($currentProduct) || $currentProduct->availability < 1) {
            $this->telegram()->sendMessage([
                'chat_id' => $this->user->id,
                'text' => __("Something went wrong. Please contact the administrator.")
            ]);
        }

        /** @var Order $order */
        $order = $this->productService->buy($this->user, $currentProduct);
        $this->telegram()->sendMessage([
            'chat_id' => $this->user->id,
            'text' => __("Your order number is {$order->id}. In the near future our manager will contact you to clarify the deal.")
        ]);

        Log::debug('ProductFlow.prev', [
            'product' => $currentProduct,
        ]);
        Context::update($this->user, ['product_id' => null]);
    }

    protected function prev()
    {
        $currentProduct = $this->getProduct();

        /** @var $prevProduct Product */
        $prevProduct  = $this->productService->prev($currentProduct);

        Log::debug('ProductFlow.prev', [
            'product' => $currentProduct,
        ]);
        Context::update($this->user, ['product_id' => $prevProduct->id]);

        $this->sendProductWithKeyboard($prevProduct);
    }

    protected function next()
    {
        $currentProduct = $this->getProduct();

        /** @var $nextProduct Product */
        $nextProduct  = $this->productService->next($currentProduct);

        Log::debug('ProductFlow.next', [
            'product' => $currentProduct,
        ]);
        Context::update($this->user, ['product_id' => $nextProduct->id]);

        $this->sendProductWithKeyboard($nextProduct);
    }

    protected function getProduct(): Product
    {
        if (isset($this->options['product_id']) && !is_null($this->options['product_id'])) {
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

        $caption = 'Name: ' . $product->name . PHP_EOL;
        $caption .= 'Description: ' . $product->description . PHP_EOL;
        $caption .= 'Price: ' . $product->price . PHP_EOL;

        $this->telegram()->sendPhoto([
            'chat_id' => $this->user->id,
            'photo' => InputFile::create($product->image),
            'caption' => $caption,
            'reply_markup' => Keyboard::make([
                'inline_keyboard' => $buttons
            ])
        ]);
    }

}
