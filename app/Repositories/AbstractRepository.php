<?php

namespace App\Repositories;

use Eloquent;

class AbstractRepository
{
    protected $model;

    public function __construct(Eloquent $model)
    {
        $this->model = $model;
    }
}
