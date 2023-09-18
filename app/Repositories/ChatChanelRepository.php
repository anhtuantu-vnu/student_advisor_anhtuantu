<?php

namespace App\Repositories;
use App\Repositories\Contracts\RepositoryInterface;
use App\Models\ChatChanel;

class ChatChanelRepository extends AbstractRepository
{
    public function __construct(ChatChanel $model)
    {
        parent::__construct($model);
    }
}
