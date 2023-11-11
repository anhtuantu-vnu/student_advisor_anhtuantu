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

    /**
     * @param $listIdMember
     * @param $idPlan
     * @return mixed
     */
    public function deleteMemberWhenUpdatePlan($listIdMember, $idPlan): mixed
    {
        return $this->model->whereIn('user_id', $listIdMember)->where('plan_id' , $idPlan)->delete();
    }

    /**
     * @param $idMember
     * @return mixed
     */
    public function getListPlanByMember($idMember): mixed
    {
        return $this->model->where('user_id', $idMember)->with('planByMemberId')->get();
    }

    /**
     * @param $idMember
     * @param $limit
     * @return mixed
     */
    public function getListPlanByMemberLimit($idMember , $limit): mixed
    {
        return $this->model->where('user_id', $idMember)->with('planByMemberId')->paginate($limit);
    }
}
