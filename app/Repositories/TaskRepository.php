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
        $taskToDo = $this->model->where(['plan_id' => $planId])->where('status', Task::STATUS_TASK_TO_DO)->get()->toArray();
        $taskInProcess = $this->model->where(['plan_id' => $planId])->where('status', Task::STATUS_TASK_IN_PROCESS)->get()->toArray();
        $taskDone = $this->model->where(['plan_id' => $planId])->where('status', Task::STATUS_TASK_DONE)->get()->toArray();
        $taskReview = $this->model->where(['plan_id' => $planId])->where('status', Task::STATUS_TASK_REVIEW)->get()->toArray();
        return [
            'tasks_to_do' => $taskToDo,
            'tasks_in_process' => $taskInProcess,
            'task_done' => $taskDone,
            'task_review' => $taskReview
        ];
    }
}
