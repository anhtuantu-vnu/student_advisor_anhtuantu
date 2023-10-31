<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = TableConstant::SUBJECTS_TABLE;

    public function intakes()
    {
        return $this->hasMany(Intake::class, 'subject_id', 'uuid');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'uuid')
            ->select(['uuid', 'name']);
    }
}
