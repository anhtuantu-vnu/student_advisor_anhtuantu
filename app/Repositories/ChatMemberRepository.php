<?php

namespace App\Repositories;
use App\Repositories\Contracts\RepositoryInterface;
use App\Models\ChatMember;

class ChatMemberRepository implements AbstractRepository
{
    public function __construct(ChatMember $model)
    {
        parent::__construct($model);
    }
}
