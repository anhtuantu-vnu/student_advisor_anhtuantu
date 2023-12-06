<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessageReaction extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'uuid'];
    protected $table = TableConstant::CHAT_MESSAGE_REACTION_TABLE;
}
