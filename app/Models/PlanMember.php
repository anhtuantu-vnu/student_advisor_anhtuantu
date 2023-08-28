<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanMember extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'uuid'];
    protected $table = TableConstant::PLAN_MEMBER_TABLE;
}
