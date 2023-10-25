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
    public function __construct() {

    }

    /**
     * @param $tasks
     * @return array
     */
    public function getDataUserInTask($tasks): array
    {
        foreach ($tasks as $key => $task) {
            foreach ($task as $keyTask => $dataTask) {
                dd($this->selectColumnByCondition(['avatar', 'email', 'uuid']));
            }
            $tasks[$key] = $task;
        }
        return $tasks;
    }
}
