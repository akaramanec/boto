<?php

namespace App\Conversations\Flows;

class ShopFlow extends AbstractFlow
{
    protected $triggers = [
        'first' => '/start',
    ];

    protected function first()
    {
        $this->telegram()->sendMessage([
            'chat_id' => $this->user->id,
            'text' => __('List of products of our shop')
        ]);

        $this->jump(ProductFlow::class);
    }
}
