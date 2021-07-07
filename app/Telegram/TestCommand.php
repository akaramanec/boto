<?php

namespace App\Telegram;

use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Commands\Command;

/**
 * Class HelpCommand.
 */
class TestCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'test';

    /**
     * @var array Command Aliases
     */
    protected $aliases = ['testcommands'];

    /**
     * @var string Command Description
     */
    protected $description = 'test command, Get a list of commands';

    /**
     * {@inheritdoc}
     */
    public function handle()
    {
        $telegramUser = \Telegram::getWebhookUpdates()['message'];

        $buttonBuy = Keyboard::inlineButton(["text" => "buy", "callback_data" => "/buy"]);
        $buttonPrev = Keyboard::inlineButton(["text" => "<< prev", "callback_data" => "/prev"]);
        $buttonNext = Keyboard::inlineButton(["text" => "next >>", "callback_data" => "/next"]);

        $reply_markup = Keyboard::make([
            "inline_keyboard" => [[$buttonBuy, $buttonPrev, $buttonNext]]
        ]);

        \Telegram::sendMessage([
            'chat_id' => $telegramUser['chat']['id'],
            'text' => 'Menu: ',
            'reply_markup' => $reply_markup
        ]);
    }
}
