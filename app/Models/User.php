<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class User extends Authenticatable
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $guarded = ['id'];
    protected $table = TableConstant::USER_TABLE;

    /*
     * Associate Chat Chanel Relation
     */
    public function chanel(): BelongsToMany
    {
        return $this->belongsToMany(ChatChanel::class, 'chat_member', 'user_id', 'chanel_id');
    }
}
