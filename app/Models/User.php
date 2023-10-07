<?php

namespace App\Models;

//use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;
    use Authenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $guarded = ['id'];
    protected $table = TableConstant::USER_TABLE;
}
