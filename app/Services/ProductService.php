<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }
}
