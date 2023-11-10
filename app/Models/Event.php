<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = TableConstant::EVENT_TABLE;

    const ALL_TYPES = ['event', 'meeting'];

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by', 'uuid');
    }

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by', 'uuid');
    }

    public function eventMembers()
    {
        return $this->hasMany(EventMember::class, 'event_id', 'uuid');
    }
}
