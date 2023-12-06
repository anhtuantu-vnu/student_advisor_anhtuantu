<?php
namespace App\Services;
use App\Mail\SendMailInvitePlan;
use App\Models\Plan;
use App\Models\PlanMember;
use App\Models\Task;
use App\Repositories\PlanRepository;
use App\Repositories\TaskRepository;
use App\Repositories\PlanMemberRepository;
use App\Repositories\UserRepository;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

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

        //create member by author
        $this->planMemberRepository->create([
            'uuid'       => Str::uuid(),
            'plan_id'    => $plan['uuid'],
            'user_id'    => Auth::user()->uuid,
            'created_at' => $today,
            'updated_at' => $today,
            'status_invite' => PlanMember::STATUS_ACCEPT_PLAN
        ]);

        foreach ($idUsers as $userId) {
            $user = $this->userRepository->findOne(['uuid' => $userId]);
            $member = [
                'uuid'       => Str::uuid(),
                'plan_id'    => $plan['uuid'],
                'user_id'    => $userId,
                'created_at' => $today,
                'updated_at' => $today,
                'status_invite' => PlanMember::STATUS_PENDING_ACCEPT_PLAN
            ];
            Mail::to($user['email'])->send(new SendMailInvitePlan([
                'fist_name' => $user['first_name'],
                'author'  => Auth::user()->first_name,
                'plan_name' => $plan['name'],
                'url' => url('/accept-plan?plan_member=') . $member['uuid'],
            ]));
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
        return $this->planMemberRepository->getListPlanByMember($userId)->map(function($planData) {
            $plan = $planData['planByMemberId'];
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $plan['created_at']);
            $plan['date_created'] = $date->format('F d, Y');
            $plan['count_date'] = $date->diffInDays(now());
            $dataReturn = $this->handleStatusPlan($plan['uuid'], $plan);
            $listMember = $this->planMemberRepository->findByConditionWithLimit(['plan_id' => $plan['uuid'], 'status_invite' => PlanMember::STATUS_ACCEPT_PLAN], 3);
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
     * @return JsonResponse
     */
    public function updateDataPlan($data): JsonResponse
    {
        try {
            DB::beginTransaction();
            $this->planRepository->updateByCondition(
                [
                    'name' => $data['name'],
                    'description' => $data['description']
                ],
                ['uuid' => $data['id_plan']]
            );
            $listMemberDelete = json_decode($data['list_member_deleted'], true);
            $this->taskRepository->updateStatusUnAssignTask($listMemberDelete, $data['id_plan']);
            $this->planMemberRepository->deleteMemberWhenUpdatePlan($listMemberDelete, $data['id_plan']);
            $today = Carbon::today();
            $listNewMember = json_decode($data['list_member_add'], true);
            foreach ($listNewMember as $userId) {
                $member = [
                    'uuid'       => Str::uuid(),
                    'plan_id'    => $data['id_plan'],
                    'user_id'    => $userId,
                    'created_at' => $today,
                    'updated_at' => $today,
                ];
                $this->planMemberRepository->create($member);
            }
            DB::commit();
            return $this->successWithNoContent("Update success");
        } catch (\Throwable $throwable) {
            $throwable->getMessage();
            DB::rollBack();
            return $this->failedWithErrors(500, 'Update failed');
        }
    }

    /**
     * @param $idPlan
     * @return JsonResponse
     */
    public function deleteDataPlan($idPlan): JsonResponse
    {
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
