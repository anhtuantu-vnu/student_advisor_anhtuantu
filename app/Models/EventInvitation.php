<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventInvitation extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = TableConstant::EVENT_INVITATION_TABLE;

    public function targetUserInfo()
    {
        return $this->belongsTo(User::class, 'target_user', 'uuid');
    }

    public function originUserInfo()
    {
        return $this->belongsTo(User::class, 'origin_user', 'uuid');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'uuid');
    }
}
