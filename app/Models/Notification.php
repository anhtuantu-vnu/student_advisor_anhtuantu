<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = TableConstant::NOTIFICATION_TABLE;

    const EVENT_TYPES = [
        'GOING_TO_EVENT' => 'going_to_event',
        'INTERESTED_IN_EVENT' => 'interested_in_event',
        'INVITED_TO_EVENT' => 'invited_to_event',
        'RESPONDED_TO_EVENT_GOING' => 'responded_to_event_going',
        'RESPONDED_TO_EVENT_REJECTED' => 'responded_to_event_rejected',
        'CANCEL_EVENT' => 'cancel_event',
    ];

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
