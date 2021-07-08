<?php


namespace App\Repositories;


use App\Models\TelegramUser;
use phpDocumentor\Reflection\Types\Collection;

class TelegramUserRepository extends AbstractRepository
{
    public function __construct(TelegramUser $user)
    {
        parent::__construct($user);
    }

    public function store(array $user)
    {
        return $this->model->firstOrCreate($user);
    }
}
