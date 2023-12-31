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
        $checkTask = $this->model->where(['plan_id' => $planId])->first();
        return $this->transferDataMember([
            'tasks_to_do' => $taskToDo,
            'tasks_in_process' => $taskInProcess,
            'tasks_review' => $taskReview,
            'tasks_done' => $taskDone,
            'is_task' => $checkTask,
        ]);
    }


    /**
     * @param $dataTasks
     * @return array
     */
    private function transferDataMember ($dataTasks) :array
    {
        $config = [
            'tasks_to_do' => [
                'key' => 'tasks_to_do',
                'type' => __('texts.texts.to_do.' . auth()->user()->lang),
                'backgroundTag' => '#1e74fd',
                'border' => 'border-primary',
            ],
            'tasks_in_process' => [
                'key' => 'tasks_in_process',
                'type' => __('texts.texts.in_process.' . auth()->user()->lang),
                'backgroundTag' => '#fe9431',
                'border' => 'border-warning',
            ],
            'tasks_review' => [
                'key' => 'task_review',
                'type' => __('texts.texts.review.' . auth()->user()->lang),
                'backgroundTag' => '#673bb7',
                'border' => 'border-secondary',
            ],
            'tasks_done' => [
                'key' => 'task_done',
                'type' =>  __('texts.texts.done.' . auth()->user()->lang),
                'backgroundTag' => '#10d876',
                'border' => 'border-success',
            ]
        ];

        foreach ($dataTasks as $key => $data) {
            if($key === 'is_task') continue;
            $dataTasks[$key]['config'] = $config[$key];
        }
        return $dataTasks;
    }

    /**
     * @param $listIdMember
     * @param $idPlan
     * @return mixed
     */
    public function updateStatusUnAssignTask($listIdMember, $idPlan): mixed
    {
        return $this->model->whereIn('assigned_to', $listIdMember)->where('plan_id' , $idPlan)->update(['assigned_to' => null]);
    }
}
