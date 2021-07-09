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
    /** @var array */
    protected $context = [];
    /** @var array */
    protected $triggers = [];
    /** @var array */
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

        $flow = app($flow);
        $flow->setUser($this->user);
        $flow->setMessage($this->message);
        $flow->setContext($this->context);

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
        foreach ($this->triggers as $trigger) {
            try {
                if (hash_equals($trigger, $this->message->text)) {
                    $state = 'first';
                }
            } catch (\Exception $exception) {
                Log::debug('hash_equals', [$trigger, $this->message]);
            }
        }
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

    abstract protected function processingCallback();
}
