<?php

namespace App\Services;
use App\Models\Plan;
use App\Models\Task;
use App\Repositories\PlanMemberRepository;
use App\Repositories\UserRepository;
use App\Repositories\TaskRepository;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TaskServices
{
    use ResponseTrait;
    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;
    /**
     * @var TaskRepository
     */
    protected TaskRepository $taskRepository;
    /**
     * @var PlanMemberRepository
     */
    protected PlanMemberRepository $planMemberRepository;

    /**
     * @param UserRepository $userRepository
     * @param TaskRepository $taskRepository
     * @param PlanMemberRepository $planMemberRepository
     */
    public function __construct(
        UserRepository $userRepository,
        TaskRepository $taskRepository,
        PlanMemberRepository $planMemberRepository
    ) {
        $this->taskRepository = $taskRepository;
        $this->userRepository = $userRepository;
        $this->planMemberRepository = $planMemberRepository;
    }

    /**
     * @param $idPlan
     * @return JsonResponse
     */
    public function getDataTask($idPlan): JsonResponse
    {
        $listTask = $this->taskRepository->getListTaskByPlan($idPlan);
        $listMember = $this->planMemberRepository->getMemberByPlanId($idPlan);
        return $this->successWithContentAttach($listTask, $listMember);
    }

    /**
     * @param $task
     * @return JsonResponse
     */
    public function initTask($task): JsonResponse
    {
        $task['uuid'] = Str::uuid();
        $task['created_at'] = Carbon::today();
        $task['updated_at'] = Carbon::today();
        $task['created_by'] = Auth::user()->uuid;
        $task['user_id'] = Auth::user()->uuid;
        $task['status'] = Task::STATUS_TASK_TO_DO;
        $result = $this->taskRepository->create($task);
        return $this->successWithContent($result);
    }
}
