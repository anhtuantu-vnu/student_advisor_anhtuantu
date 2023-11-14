<?php

namespace App\Services;
use App\Mail\CustomEmail;
use App\Models\Plan;
use App\Models\Task;
use App\Repositories\PlanMemberRepository;
use App\Repositories\PlanRepository;
use App\Repositories\UserRepository;
use App\Repositories\TaskRepository;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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
     * @var PlanRepository
     */
    protected PlanRepository $planRepository;

    /**
     * @param UserRepository $userRepository
     * @param TaskRepository $taskRepository
     * @param PlanMemberRepository $planMemberRepository
     */
    public function __construct(
        UserRepository $userRepository,
        TaskRepository $taskRepository,
        PlanMemberRepository $planMemberRepository,
        PlanRepository $planRepository
    ) {
        $this->taskRepository = $taskRepository;
        $this->userRepository = $userRepository;
        $this->planMemberRepository = $planMemberRepository;
        $this->planRepository = $planRepository;
    }

    /**
     * @param $idPlan
     * @return JsonResponse
     */
    public function getDataTask($idPlan): JsonResponse
    {
        $listTask = $this->taskRepository->getListTaskByPlan($idPlan);
        $listMember = $this->planMemberRepository->getMemberByPlanId($idPlan);
        $author = $this->planRepository->findOne(['uuid' => $idPlan]);
        $listTask['author'] = $author['created_by'];
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
