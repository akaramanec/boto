<?php

namespace App\Conversations\Flows;

use App\Models\TelegramUser;
use Illuminate\Support\Facades\Log;
use Telegram;
use Telegram\Bot\Api;

abstract class AbstractFlow
{
    /** @var TelegramUser */
    protected $user;
    /** @var object */
    protected $message;
    /** @var array */
    protected $triggers = [];
    /** @var array */
    protected $sates = [];

    public function setUser(TelegramUser $user)
    {
        $this->user = $user;
    }

    public function setMessage(object $message)
    {
        $this->message = $message;
    }

    public function telegram(): Api
    {
        return Telegram::bot();
    }

    /**
     * @param string|null $state
     */
    public function run(string $state = null)
    {
        Log::debug(static::class . '.run', [
            'user' => $this->user->toArray(),
            'message' => $this->message->toArray(),
            'state' => $state
        ]);

        foreach ($this->triggers as $trigger) {
            if (hash_equals($trigger, $this->message->text)) {
                $state = 'first';
            }
        }

        return $state == null ? null : $this->$state();
    }

    abstract protected function first();

}
