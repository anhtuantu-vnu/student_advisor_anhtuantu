<?php

namespace App\Repositories;
use App\Repositories\Contracts\RepositoryInterface;
use App\Models\Plan;

class PlanRepository implements RepositoryInterface
{
    public function __construct(Plan $model)
    {
        parent::__construct($model);
    }
}
