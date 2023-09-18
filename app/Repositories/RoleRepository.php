<?php

namespace App\Repositories;
use App\Repositories\Contracts\RepositoryInterface;
use App\Models\Role;

class RoleRepository extends AbstractRepository
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }
}
