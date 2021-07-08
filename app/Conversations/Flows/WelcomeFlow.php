<?php


namespace App\Conversations\Flows;


class WelcomeFlow extends AbstractFlow
{
    protected $triggers = [
        '/start'
    ];

    protected function first()
    {
        $this->telegram()->sendMessage([
            'chat_id' => $this->user->id,
            'text' => __('Welcome to Boto shop')
        ]);

        $this->jump(ShopFlow::class);
    }
}
