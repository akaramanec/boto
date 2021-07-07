<?php

namespace App\Telegram;

use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Commands\Command;

/**
 * Class HelpCommand.
 */
class BuyCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'buy';

    /**
     * @var string Command Description
     */
    protected $description = 'buy command, get the order on product';

    /**
     * {@inheritdoc}
     */
    public function handle()
    {
        $telegramUser = \Telegram::getWebhookUpdates()['message'];

        $response = \Telegram::sendMessage([
            'chat_id' => $telegramUser['chat']['id'],
            'text' => 'You buy it!'
        ]);
    }
}
