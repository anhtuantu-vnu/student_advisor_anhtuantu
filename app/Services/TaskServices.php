<?php

namespace App\Services;
use App\Models\Task;
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
     * @param UserRepository $userRepository
     * @param TaskRepository $taskRepository
     */
    public function __construct(
        UserRepository $userRepository,
        TaskRepository $taskRepository
    ) {
        $this->taskRepository = $taskRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param $idTask
     * @return array
     */
    public function getDataTask($idTask): array
    {
        $listMember = $this->userRepository->find()->toArray();
        $listTask = $this->taskRepository->getListTaskByPlan($idTask);
        $listTask['members'] = $listMember;
        return $listTask;
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
