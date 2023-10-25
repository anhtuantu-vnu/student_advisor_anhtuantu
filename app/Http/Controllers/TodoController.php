<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;

class TodoController extends Controller
{
    /**
     * @var TaskRepository
     */
    protected TaskRepository $taskRepository;
    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * @param TaskRepository $taskRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        TaskRepository $taskRepository,
        UserRepository $userRepository
    )
    {
        $this->taskRepository = $taskRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return View
     */
    public function showToDo(Request $request): View
    {
        $tasksData = $this->taskRepository->getListTaskByPlan($request->input('id'));
        $tasks = $this->userRepository->getDataUserInTask($tasksData);
        dd($tasks);
        return view('front-end.layouts.task.layout_todo', compact('tasks'));
    }
}
