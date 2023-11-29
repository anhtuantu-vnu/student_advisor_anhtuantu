<?php

namespace App\Http\Controllers;

use App\Imports\StudentScheduleImport;
use App\Traits\ResponseTrait;
use App\Imports\StudentImport;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    use ResponseTrait;
    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function index(): \Illuminate\Foundation\Application|View|Factory|Application
    {
        return view('front-end.layouts.import.import');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadFile(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            Excel::import(new StudentImport, $request->file('file'));
            DB::commit();
            return $this->successWithContent('Import success');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return $this->failedWithErrors(500, $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadFileSchedule(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            Excel::import(new StudentScheduleImport, $request->file('file'));
            DB::commit();
            return $this->successWithContent('Import success');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return $this->failedWithErrors(500, $e->getMessage());
        }
    }
}
