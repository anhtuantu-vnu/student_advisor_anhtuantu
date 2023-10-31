<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Class_ extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = TableConstant::CLASS__TABLE;

    public function classRoles()
    {
        return $this->hasMany(ClasRole::class, 'class_id', 'uuid');
    }
}
