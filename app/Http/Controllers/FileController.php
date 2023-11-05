<?php

namespace App\Http\Controllers;

use App\Traits\ResponseTrait;
use App\Imports\StudentImport;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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
        Excel::queueImport(new StudentImport, $request->file('file'));
        return $this->successWithContent('Import success');
    }
}
