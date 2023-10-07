<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatChanel extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = TableConstant::CHAT_CHANNEL_TABLE;
}
