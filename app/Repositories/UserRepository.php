<?php

namespace App\Repositories;
use App\Repositories\Contracts\RepositoryInterface;
use App\Models\User;

class UserRepository implements AbstractRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
