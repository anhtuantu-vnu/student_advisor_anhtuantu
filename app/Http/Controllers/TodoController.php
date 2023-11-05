<?php

namespace App\Http\Controllers;

use App\Services\TaskServices;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
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
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return $this->taskServices->getDataTask($request->input('idPlan'));
    }

    /**
     * @return View
     */
    public function showTasks(): View
    {
//        $tasks = $this->taskServices->getDataTask($request->input('id'));
//        $tasks['id_plan'] = $request->input('id');
        return view('front-end.layouts.task.layout_todo');
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createTask(Request $request): JsonResponse
    {
        return $this->taskServices->initTask($request->input('task'));
    }
}
