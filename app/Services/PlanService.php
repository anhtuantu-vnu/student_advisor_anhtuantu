<?php
namespace App\Services;
use App\Repositories\PlanRepository;
use App\Repositories\PlanMemberRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class PlanService
{
    /**
     * @var PlanRepository
     */
    protected PlanRepository $planRepository;
    /**
     * @var PlanMemberRepository
     */
    protected PlanMemberRepository $planMemberRepository;

    /**
     * @param PlanRepository $planRepository
     */
    public function __construct(
        PlanRepository $planRepository,
        PlanMemberRepository $planMemberRepository
    )
    {
        $this->planRepository = $planRepository;
        $this->planMemberRepository = $planMemberRepository;
    }

    /**
     * @param $plan
     * @return array
     */
    public function createPlan($plan): mixed
    {
        $plan['uuid'] = Str::uuid();
        $plan['created_at'] = Carbon::today();
        $plan['updated_at'] = Carbon::today();
        $plan['create_by'] = "0a3f33ba-2ac0-4ef0-a95a-7e95ad09ef63";

        return $this->planRepository->create($plan);
    }

    /**
     * @param $user
     * @param $plan
     * @return mixed
     */
    public function createPlanMember($user, $plan): mixed
    {
        $idUsers = json_decode($user['list_member'], true);
        $listMember = [];
        $today = Carbon::today();
        foreach ($idUsers as $userId) {
            $listMember[] = [
                'uuid'       => Str::uuid(),
                'plan_id'    => $plan['uuid'],
                'user_id'    => $userId,
                'created_at' => $today,
                'updated_at' => $today,
            ];
        }

        return $this->planMemberRepository->createMany($listMember);
    }

    /**
     * @param $userId
     * @return Collection
     */
    public function getPlans($userId): mixed
    {
        return $this->planRepository->find(['uuid' => $userId]);
    }
}
