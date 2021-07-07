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
        $text = sprintf('%s: %s' . PHP_EOL, 'Your chat namber', $telegramUser['from']['id']);
        $keyboard = [
            ['7', '8', '9'],
            ['4', '5', '6'],
            ['1', '2', '3'],
            ['0']
        ];

        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);

        $response = \Telegram::sendMessage([
            'chat_id' => $telegramUser['chat']['id'],
            'text' => 'Hello World',
            'reply_markup' => $reply_markup
        ]);

        $messageId = $response->getMessageId();
        $this->replyWithMessage(compact('text'));
    }
}
