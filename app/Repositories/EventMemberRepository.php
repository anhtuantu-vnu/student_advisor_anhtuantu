<?php

namespace App\Repositories;
use App\Repositories\Contracts\RepositoryInterface;
use App\Models\EventMember;

class EventMemberRepository extends AbstractRepository
{
    public function __construct(EventMember $model)
    {
        parent::__construct($model);
    }
}
