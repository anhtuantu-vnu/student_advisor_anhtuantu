<?php

namespace App\Repositories;
use App\Models\EventMember;

class EventMemberRepository extends AbstractRepository
{
    public function __construct(EventMember $model)
    {
        parent::__construct($model);
    }
}
