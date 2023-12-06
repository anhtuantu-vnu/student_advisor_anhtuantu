<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntakeSchedule extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = TableConstant::INTAKE_SCHEDULES_TABLE;

    const WEEKDAYS_MAP = array(
        "1" => 'sunday',
        "2" => 'monday',
        "3" => 'tuesday',
        "4" => 'wednesday',
        "5" => 'thurday',
        "6" => 'friday',
        "7" => 'saturday',
    );
}
