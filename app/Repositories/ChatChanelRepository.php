<?php

namespace App\Repositories;
use App\Repositories\Contracts\RepositoryInterface;
use App\Models\ChatChanel;

class ChatChanelRepository implements AbstractRepository
{
    public function __construct(ChatChanel $model)
    {
        parent::__construct($model);
    }
}
