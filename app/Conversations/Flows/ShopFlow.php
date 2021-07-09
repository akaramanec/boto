<?php

namespace App\Conversations\Flows;

use App\Models\BotMessage;

class ShopFlow extends AbstractFlow
{
    protected $triggers = [
        'first' => '/start',
    ];

    protected function first()
    {
        $this->telegram()->sendMessage([
            'chat_id' => $this->user->id,
            'text' => BotMessage::where('briefly', 'shop_intro')->first()
        ]);

        $this->jump(ProductFlow::class);
    }
}
