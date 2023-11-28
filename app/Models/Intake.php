<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intake extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = TableConstant::INTAKES_TABLE;

    const WEEKDAYS_MAP = array(
        "1" => 'sunday',
        "2" => 'monday',
        "3" => 'tuesday',
        "4" => 'wednesday',
        "5" => 'thurday',
        "6" => 'friday',
        "7" => 'saturday',
    );

    const WEEKDAYS_MAP_VI = array(
        "1" => 'chủ nhật',
        "2" => 'thứ hai',
        "3" => 'thứ ba',
        "4" => 'thứ tư',
        "5" => 'thứ năm',
        "6" => 'thứ sáu',
        "7" => 'thứ bảy',
    );

    public function intakeMembers()
    {
        return $this->hasMany(IntakeMember::class, 'intake_id', 'uuid');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'uuid')
            ->select(['uuid', 'name', 'code', 'description']);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }
}
