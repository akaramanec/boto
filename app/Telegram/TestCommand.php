<?php

namespace App\Telegram;

use App\Models\User;
use Telegram\Bot\Actions;
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
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        $user = User::first();
        $this->replyWithMessage(['text' => 'User email in laravel: ' . $user->email]);

        file_put_contents(base_path() . 'bot_response.txt', \Telegram::getUpdates());
        $telegramUser = \Telegram::getUpdates()['message'];
        $text = sprintf('%s: %s' . PHP_EOL, 'Your chat namber', $telegramUser['from']['id']);
        $text .= sprintf('%s: %s' . PHP_EOL, 'Your name', $telegramUser['from']['username']);

        $this->replyWithMessage(compact('text'));
    }
}
