<?php
namespace App\Services;
use App\Models\Plan;
use App\Models\Task;
use App\Repositories\PlanRepository;
use App\Repositories\TaskRepository;
use App\Repositories\PlanMemberRepository;
use App\Repositories\UserRepository;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PlanService
{
    use ResponseTrait;
    /**
     * @var PlanRepository
     */
    protected PlanRepository $planRepository;
    /**
     * @var PlanMemberRepository
     */
    protected PlanMemberRepository $planMemberRepository;
    /**
     * @var TaskRepository
     */
    protected TaskRepository $taskRepository;

    /**
     * @var UserRepository
     */
    protected UserRepository$userRepository;

    /**
     * @param PlanRepository $planRepository
     * @param TaskRepository $taskRepository
     * @param PlanMemberRepository $planMemberRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        PlanRepository $planRepository,
        TaskRepository $taskRepository,
        PlanMemberRepository $planMemberRepository,
        UserRepository $userRepository
    )
    {
        $this->planRepository = $planRepository;
        $this->taskRepository = $taskRepository;
        $this->planMemberRepository = $planMemberRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param $plan
     * @param $userId
     */
    public function createPlan($plan, $userId)
    {
        $plan['uuid'] = Str::uuid();
        $plan['created_at'] = Carbon::today();
        $plan['updated_at'] = Carbon::today();
        $plan['created_by'] = $userId;
        $plan['settings'] = Plan::SETTING_DEFAULT[rand(0, 5)];
        return $this->planRepository->create($plan);
    }

    /**
     * @param $user
     * @param $plan
     * @return bool
     */
    public function createPlanMember($user, $plan): bool
    {
        $idUsers = json_decode($user['list_member'], true);
        $today = Carbon::today();
        foreach ($idUsers as $userId) {
            $member = [
                'uuid'       => Str::uuid(),
                'plan_id'    => $plan['uuid'],
                'user_id'    => $userId,
                'created_at' => $today,
                'updated_at' => $today,
            ];
            $this->planMemberRepository->create($member);
        }
        return true;
    }

    /**
     * @param $userId
     * @return array
     */
    public function getPlans($userId): array
    {
        return $this->planRepository->find(['created_by' => $userId])->map(function($plan) {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $plan['created_at']);
            $plan['date_created'] = $date->format('F d, Y');
            $plan['count_date'] = $date->diffInDays(now());
            $dataReturn = $this->handleStatusPlan($plan['uuid'], $plan);
            $listMember = $this->planMemberRepository->findByConditionWithLimit(['plan_id' => $plan['uuid']], 3);
            $dataUser = [];
            foreach ($listMember as $member) {
                $dataUser[] = $this->userRepository->selectFirstByCondition(
                    ['first_name' , 'last_name' , 'avatar'],
                    ['uuid' => $member['user_id']]
                )->toArray();
            }
            $dataReturn['list_member'] = $dataUser;
            return $dataReturn;
        })->toArray();
    }

    /**
     * @param $planId
     * @param $plan
     * @return array
     */
    private function handleStatusPlan($planId, $plan): array
    {
        $totalTask = $this->taskRepository->findCount(['plan_id' => $planId]);
        $totalTaskDone = $this->taskRepository->findCount(['plan_id' => $planId, 'status' => Task::STATUS_TASK_DONE]);
        $plan['percent'] = empty($totalTask) ? 0 : round(($totalTaskDone / $totalTask) * 100, 2);
        $plan['status'] = empty($totalTask) ? 'In Active' : 'In Progress';
        $plan['status_key'] = empty($totalTask) ? 'in_active' : 'in_progress';

        if(Str::contains($plan['percent'], 100)) {
            $plan['status'] = "Complete";
            $plan['status_key'] = 'complete';
        }
        return $plan->toArray();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function updateDataPlan($data) {
        try {
            DB::beginTransaction();
            $this->planRepository->updateByCondition(
                [
                    'name' => $data['name'],
                    'description' => $data['description']
                ],
                ['uuid' => $data['id_plan']]
            );
            $plan = $this->planRepository->findOne(['uuid' => $data['id_plan']]);
            $listOldMember = $this->planMemberRepository->selectColumnByCondition(
                ['user_id'],
                ['plan_id' => $data['id_plan']]
            );
            dd($listOldMember);
            DB::commit();
            return $this->successWithNoContent("Update success");
        } catch (\Throwable $throwable) {
            DB::rollBack();
            return $this->failedWithErrors(500, 'Update failed');
        }
    }

    public function deleteDataPlan($idPlan) {
        try {
            DB::beginTransaction();
            $this->planMemberRepository->deleteByCondition(['plan_id' => $idPlan]);
            $this->taskRepository->deleteByCondition(['plan_id' => $idPlan]);
            $this->planRepository->deleteByCondition(['uuid' => $idPlan]);
            DB::commit();
            return $this->successWithNoContent('Delete Success');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->failedWithErrors(500, $th->getMessage());
        }
    }
}
