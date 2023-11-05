<?php

namespace App\Repositories;
use App\Repositories\Contracts\RepositoryInterface;
use App\Models\PlanMember;

class PlanMemberRepository extends AbstractRepository
{
    public function __construct(PlanMember $model)
    {
        parent::__construct($model);
    }

    /**
     * @param $idPlan
     * @return mixed
     */
    public function getMemberByPlanId($idPlan): mixed
    {
        return $this->model->where('plan_id' , $idPlan)->with('userByPlan')->get()->map(function($member) {
            return $member['userByPlan'];
        })->toArray();
    }
}
