<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRole extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = TableConstant::CLASS_ROLES_TABLE;

    public function class_()
    {
        return $this->belongsTo(Class_::class, 'class_id', 'uuid')
            ->select(['uuid', 'name', 'code', 'start_year', 'end_year']);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }
}
