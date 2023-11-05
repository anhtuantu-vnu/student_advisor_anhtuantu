<?php

namespace App\Repositories;
use App\Repositories\Contracts\RepositoryInterface;
use App\Models\User;

class UserRepository extends AbstractRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * @param $search
     * @return mixed
     */
    public function searchMemberByCondition($search): mixed
    {
        return $this->model->where('email', 'LIKE' , "%$search%")
            ->orWhere('last_name', 'LIKE' , "%$search%")
            ->orWhere('first_name', 'LIKE' , "%$search%")->get();
    }
}
