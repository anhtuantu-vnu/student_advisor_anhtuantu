<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemNotification extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = TableConstant::SYSTEM_NOTIFICATION_TABLE;

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by', 'uuid');
    }

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by', 'uuid');
    }
}
