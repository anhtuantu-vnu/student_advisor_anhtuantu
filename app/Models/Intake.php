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

    public function intakeMembers()
    {
        return $this->hasMany(IntakeMember::class, 'intake_id', 'uuid');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'uuid')
            ->select(['uuid', 'name', 'code', 'description']);
    }
}
