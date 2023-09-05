<?php

namespace App\Repositories;
use App\Repositories\Contracts\RepositoryInterface;
use App\Models\ChatMessageReaction;

class ChatMessageReactionRepository implements AbstractRepository
{
    public function __construct(ChatMessageReaction $model)
    {
        parent::__construct($model);
    }
}
