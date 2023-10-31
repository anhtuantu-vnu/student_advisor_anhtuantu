<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $guarded = ['id'];

    protected $hidden = ['password', 'created_at', 'updated_at', 'id'];

    protected $table = TableConstant::USER_TABLE;

    public function classRoles()
    {
        return $this->hasMany(ClassRole::class, 'user_id', 'uuid');
    }

    public function intakeMembers()
    {
        return $this->hasMany(IntakeMember::class, 'user_id', 'uuid');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'uuid')
            ->select(['uuid', 'name', 'description']);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
