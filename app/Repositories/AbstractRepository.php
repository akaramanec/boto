<?php

namespace App\Repositories;

use Eloquent;

abstract class AbstractRepository
{
    protected $model;

    public function __construct(Eloquent $model)
    {
        $this->model = $model;
    }
}
