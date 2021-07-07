<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class OrderController extends Controller
{
    public function order(OrderRequest $orderRequest, User $user, Product $product)
    {
        Order::create([
            $user,
            $product,
            $orderRequest
        ]);
        return response(null, 201);
    }
}
