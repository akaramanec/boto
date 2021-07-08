<?php

namespace App\Repositories;

use App\Models\TelegramUser;
use Eloquent;

class TelegramUserRepository extends AbstractRepository
{
    public function __construct(TelegramUser $user)
    {
        parent::__construct($user);
    }

    public function store(array $user): Eloquent
    {
        return $this->model->firstOrCreate(['id' => $user['id']], $user);
    }
}
