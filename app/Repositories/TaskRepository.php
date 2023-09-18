<?php

namespace App\Repositories;
use App\Repositories\Contracts\RepositoryInterface;
use App\Models\Task;

class TaskRepository extends AbstractRepository
{
    public function __construct(Task $model)
    {
        parent::__construct($model);
    }
}
