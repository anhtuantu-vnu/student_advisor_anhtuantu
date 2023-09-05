<?php

namespace App\Repositories;
use App\Repositories\Contracts\RepositoryInterface;
use App\Models\PlanMember;

class PlanMemberRepository implements AbstractRepository
{
    public function __construct(PlanMember $model)
    {
        parent::__construct($model);
    }
}
