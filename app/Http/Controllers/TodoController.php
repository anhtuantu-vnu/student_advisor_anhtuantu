<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Repositories\TaskRepository;
use App\Services\TaskServices;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TodoController extends Controller
{
    /**
     * @var TaskServices
     */
    protected TaskServices $taskServices;
    /**
     * @var TaskRepository
     */
    protected TaskRepository $taskRepository;

    /**
     * @param TaskServices $taskServices
     */
    public function __construct(
        TaskServices $taskServices,
        TaskRepository $taskRepository
    )
    {
        $this->taskServices = $taskServices;
        $this->taskRepository = $taskRepository;
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
     * @param Request $request
     * @return JsonResponse
     */
    public function updateTask(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $this->taskRepository->updateByCondition(
                [
                    'assigned_to' => $request->input('task.member_selected'),
                    'description' => $request->input('task.description')
                ],
                ['id' => $request->input('task.id')]
            );
            DB::commit();
            return $this->successWithNoContent('Update success');
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->failedWithErrors($th->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteTask(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $this->taskRepository->deleteByCondition(['id' => $request->input('id')]);
            DB::commit();
            return $this->successWithNoContent('Delete success');
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->failedWithErrors($th->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateStatusTask(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
//            $this->taskRepository->updateByCondition(
//                ['status' => substr($request->input('status'), 6)],
//                ['id' => $request->input('idTask')]
//            );
            $this->taskServices->sendMailWhenUpdateStatusTask($request->input('idTask'));
            DB::commit();
            return $this->successWithNoContent('Update success');
        } catch (\Throwable $throwable) {
            DB::rollBack();
            return $this->failedWithErrors(500, $throwable->getMessage());
        }}
    /**
     * @return View
     */
    public function showTasks(): View
    {
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
