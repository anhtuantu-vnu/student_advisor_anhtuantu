<?php

namespace App\Http\Controllers;

use App\Services\TaskServices;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * @var TaskServices
     */
    protected TaskServices $taskServices;

    /**
     * @param TaskServices $taskServices
     */
    public function __construct(
        TaskServices $taskServices
    )
    {
        $this->taskServices = $taskServices;
    }

    /**
     * @param Request $request
     * @return View
     */
    public function showToDo(Request $request): View
    {
        $tasks = $this->taskServices->getDataTask($request->input('id'));
        return view('front-end.layouts.task.layout_todo', compact('tasks'));
    }
}
