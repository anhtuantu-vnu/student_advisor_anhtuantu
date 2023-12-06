<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntakeMember extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = TableConstant::INTAKE_MEMBERS_TABLE;

    public function intake()
    {
        return $this->belongsTo(Intake::class, 'intake_id', 'uuid')
            ->select([
                'uuid',
                'code',
                'start_date',
                'end_date',
                'duration_weeks',
                'start_hour',
                'start_minute',
                'end_hour',
                'end_minute',
                'week_days',
                'subject_id',
                'location',
            ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid')
            ->select([
                'uuid',
                'first_name',
                'last_name',
                'email',
                'phone',
                'avatar',
                'unique_id',
                'role',
                'date_of_birth',
                'lang',
                'department_id',
                'gender',
            ]);
    }
}
