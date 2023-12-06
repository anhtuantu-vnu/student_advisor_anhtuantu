<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanMember extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = TableConstant::PLAN_MEMBER_TABLE;
    CONST STATUS_ACCEPT_PLAN = 1;
    CONST STATUS_PENDING_ACCEPT_PLAN = 0;

    /**
     * @return BelongsTo
     */
    public function userByPlan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }

    /**
     * @return BelongsTo
     */
    public function planByMemberId(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'uuid');
    }
}
