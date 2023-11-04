<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Symfony\Component\HttpFoundation\Response;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function actionDepartments()
    {
        try {
            $data = [
                'departments'  => Department::all('uuid', 'name', 'description'),
            ];
            return $this->successWithContent($data);
        } catch (\Exception $e) {
            report($e);
            return $this->failedWithError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Error getting departments: ' . $e->getMessage());
        }
    }
}
