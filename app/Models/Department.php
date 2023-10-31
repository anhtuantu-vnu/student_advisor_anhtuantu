<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = TableConstant::DEPARTMENTS_TABLE;

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'subject_id', 'uuid')->select(['uuid', 'name']);
    }
}
