<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = TableConstant::TASK_TABLE;
    const STATUS_TASK_DONE = 'done';
    const STATUS_TASK_IN_PROCESS = 'progress';
    const STATUS_TASK_TO_DO = 'to_do';
    const STATUS_TASK_REVIEW = 'review';
}
