<?php

namespace App\TelegramCommands;

use Telegram;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Commands\Command;

/**
 * Class HelpCommand.
 */
class ShopCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'shop';

    /**
     * @var string Command Description
     */
    protected $description = 'Starts browsing products';

    /**
     * {@inheritdoc}
     * @throws Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function handle()
    {
        $telegramUser = Telegram::bot()->getWebhookUpdate()['message'];

        $buttonBuy = Keyboard::inlineButton(["text" => "buy", "callback_data" => "buy"]);
        $buttonPrev = Keyboard::inlineButton(["text" => "<< prev", "callback_data" => "prev"]);
        $buttonNext = Keyboard::inlineButton(["text" => "next >>", "callback_data" => "next"]);

        $reply_markup = Keyboard::make([
            "inline_keyboard" => [[$buttonBuy, $buttonPrev, $buttonNext]]
        ]);

        Telegram::bot()->sendPhoto([
            'chat_id' => $telegramUser['chat']['id'],
            'caption' => 'Menu: ',
            'reply_markup' => $reply_markup
        ]);
    }
}
