<?php

namespace App\Repositories;

use App\Models\Product;
use Eloquent;

class ProductRepository extends AbstractRepository
{
    public function __construct(Product $product)
    {
        parent::__construct($product);
    }

    public function random(): Eloquent
    {
        return $this->model::inRandomOrder()->first();
    }

    public function getBuId(int $id): Eloquent
    {
        return $this->model::where('id', $id)->first();
    }

    public function next(Product $product): Eloquent
    {
        return $this->model::where('id', '>', $product->id)->first() ?? $product;
    }

    public function prev(Product $product): Eloquent
    {
        return $this->model::where('id', '<', $product->id)->orderBy('id', 'desc')->first() ?? $product;
    }
}
