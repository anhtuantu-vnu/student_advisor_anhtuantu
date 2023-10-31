<?php

namespace App\Repositories;
use App\Repositories\Contracts\RepositoryInterface;
use App\Models\Task;

class TaskRepository extends AbstractRepository
{
    public function __construct(Task $model)
    {
        parent::__construct($model);
    }

    /**
     * @param $planId
     * @return array
     */
    public function getListTaskByPlan($planId): array
    {
        $taskToDo = $this->model->where(['plan_id' => $planId])->where('status', Task::STATUS_TASK_TO_DO)->with('userAssign')->get()->toArray();
        $taskInProcess = $this->model->where(['plan_id' => $planId])->where('status', Task::STATUS_TASK_IN_PROCESS)->with('userAssign')->get()->toArray();
        $taskDone = $this->model->where(['plan_id' => $planId])->where('status', Task::STATUS_TASK_DONE)->with('userAssign')->get()->toArray();
        $taskReview = $this->model->where(['plan_id' => $planId])->where('status', Task::STATUS_TASK_REVIEW)->with('userAssign')->get()->toArray();
        return [
            'tasks_to_do' => $taskToDo,
            'tasks_in_process' => $taskInProcess,
            'task_done' => $taskDone,
            'task_review' => $taskReview
        ];
    }
}
