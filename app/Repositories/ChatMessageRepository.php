<?php

namespace App\Repositories;
use App\Repositories\Contracts\RepositoryInterface;
use App\Models\ChatMessage;

class ChatMessageRepository extends AbstractRepository
{
    public function __construct(ChatMessage $model)
    {
        parent::__construct($model);
    }
}
