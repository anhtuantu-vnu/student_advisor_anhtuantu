<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventMember extends Model
{
    use HasFactory;

    const STATUS_GOING = 'going';
    const STATUS_INTERESTED = 'interested';

    protected $guarded = ['id'];
    protected $table = TableConstant::EVENT_MEMBER_TABLE;
}
