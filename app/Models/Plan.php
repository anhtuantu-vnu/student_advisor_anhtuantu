<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = TableConstant::PLAN_TABLE;
    const NAME = 'name';
    const DESCRIPTION = 'description';
    const CREATED_BY = 'created_by';
}
