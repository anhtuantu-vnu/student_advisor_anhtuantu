<?php

namespace App\Repositories;
use App\Repositories\Contracts\RepositoryInterface;
use App\Models\Event;
class EventRepository implements AbstractRepository
{
    public function __construct(Event $model)
    {
        parent::__construct($model);
    }
}
