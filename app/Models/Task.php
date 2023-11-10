<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = TableConstant::TASK_TABLE;

    /**
     * @return BelongsTo
     */
    public function userAssign(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to', 'uuid');
    }

    /**
     * @return BelongsTo
     */
    public function userCreatedBy(): BelongsTo {
        return $this->belongsTo(User::class, 'assigned_to', 'uuid');
    }

    const STATUS_TASK_DONE = 'done';
    const STATUS_TASK_IN_PROCESS = 'progress';
    const STATUS_TASK_TO_DO = 'to_do';
    const STATUS_TASK_REVIEW = 'review';
}
