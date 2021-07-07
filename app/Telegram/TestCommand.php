<?php

namespace App\Telegram;

use App\Models\User;
use Telegram\Bot\Actions;
use Telegram\Bot\Api;
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
        $telegramUser = \Telegram::getUpdates();
        dd($telegramUser);
        $text = sprintf('%s: %s' . PHP_EOL, 'Your chat namber', $telegramUser['from']['id']);
        $text .= sprintf('%s: %s' . PHP_EOL, 'Your name', $telegramUser['from']['username']);

        $this->replyWithMessage(['text' => $text ?? 'response null']);
    }
}
