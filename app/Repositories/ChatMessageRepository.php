<?php

namespace App\Repositories;

use App\Models\Room;

class ChatMessageRepository extends AbstractRepository
{
    public function __construct(Room $model)
    {
        parent::__construct($model);
    }
}
