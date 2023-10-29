<?php

namespace App\Services;
use App\Repositories\UserRepository;
use App\Repositories\TaskRepository;

class TaskServices
{
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
        return $this->taskRepository->getListTaskByPlan($idTask);
    }
}
