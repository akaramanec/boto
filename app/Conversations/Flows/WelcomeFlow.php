<?php


namespace App\Conversations\Flows;


use App\Models\BotMessage;

class WelcomeFlow extends AbstractFlow
{
    protected $triggers = [
        'first' => '/start'
    ];

    protected function first()
    {
        $this->telegram()->sendMessage([
            'chat_id' => $this->user->id,
            'text' => BotMessage::where('briefly', 'welcome')->first()
        ]);

        $this->jump(ShopFlow::class);
    }
}
