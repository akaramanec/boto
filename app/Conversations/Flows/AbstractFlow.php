<?php

namespace App\Conversations\Flows;

use App\Conversations\Context;
use App\Models\TelegramUser;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Telegram;
use Telegram\Bot\Api;

abstract class AbstractFlow
{
    /** @var TelegramUser */
    protected $user;
    /** @var object */
    protected $message;

    protected $context = [];
    protected $options = [];
    protected $triggers = [];
    protected $states = ['first'];

    public function telegram(): Api
    {
        return Telegram::bot();
    }

    public function getFlow(string $flow): AbstractFlow
    {
        if (!class_exists($flow)) {
            throw new InvalidArgumentException('Flow does not exists.');
        }

        /** @var AbstractFlow $flow */
        $flow = app($flow);
        $flow->setUser($this->user);
        $flow->setMessage($this->message);
        $flow->setContext($this->context);
        $flow->setOptions($this->options);

        return $flow;
    }

    public function setUser(TelegramUser $user)
    {
        $this->user = $user;
    }

    public function setMessage(object $message)
    {
        $this->message = $message;
    }

    public function setContext(array $context)
    {
        $this->context = $context;
    }

    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    public function getStates(): array
    {
        return $this->states;
    }

    /**
     * @param string|null $state
     */
    public function run(string $state = null): bool
    {
        Log::debug(static::class . '.run', [
            'user' => $this->user->toArray(),
            'message' => $this->message->toArray(),
            'state' => $state
        ]);

        if (!is_null($state)) {
            Context::save($this->user, $this, $state);
            $this->$state();
            return true;
        }

        $state = $this->findByContext();

        if (!is_null($state)) {
            Context::save($this->user, $this, $state);
            $this->$state();
            return true;
        }

        $state = $this->findByTrigger();

        if (!is_null($state)) {
            Context::save($this->user, $this, $state);
            $this->$state();
            return true;
        }

        return false;
    }

    protected function jump(string $flow, string $state = 'first')
    {
        $this->getFlow($flow)->run($state);
    }

    private function findByTrigger(): ?string
    {
        $state = null;
        foreach ($this->triggers as $key => $trigger) {
            if (hash_equals($trigger, $this->message->text)) {
                $state = $key;
            }
        }

        Log::debug(static::class . '.findByTrigger', ['nextState' => $state]);
        return $state;
    }

    private function findByContext(): ?string
    {
        $nextState = null;

        if (
            isset($this->context['flow']) &&
            isset($this->context['state']) &&
            class_exists($this->context['flow']) &&
            method_exists(app($this->context['flow']), $this->context['state'])
        ) {

            $flow = $this->getFlow($this->context['flow']);
            $nextState = $this->getNextState($flow);
        }
        Log::debug(static::class . '.findByContext', ['nextState' => $nextState]);
        return $nextState;
    }

    private function getNextState($flow): ?string
    {
        $states = $flow->getStates();
        $currentStateId = collect($states)->search($this->context['state']);

        if (isset($states[$currentStateId + 1])) {
            return $states[$currentStateId + 1];
        }
        return null;
    }

    abstract protected function first();
}
