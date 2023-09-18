<?php

namespace App\Repositories;
use App\Repositories\Contracts\RepositoryInterface;
use App\Models\Plan;

class PlanRepository extends AbstractRepository
{
    public function __construct(Plan $model)
    {
        parent::__construct($model);
    }
}
