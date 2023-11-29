<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::paginate(10);
        return view('front-end.layouts._admin.departments.index', [
            'departments' => $departments,
        ]);
    }

    public function create()
    {
        return view('front-end.layouts._admin.departments.create');
    }

    public function postCreate(Request $request)
    {
        try {
            DB::beginTransaction();

            $vi = $request->vi;
            $en = $request->en;
            $description = $request->description;

            if ($vi == null || $vi == '' || $en == null || $en == '') {
                return $this->failedWithErrors(400, __('texts.texts.name_required.' . auth()->user()->lang));
            }

            $department = new Department();
            $department->name = json_encode(
                [
                    "vi" => $vi,
                    "en" => $en,
                ]
            );
            $department->uuid = Str::uuid();
            $department->description = $description;
            $department->updated_by = auth()->user()->uuid;
            $department->save();
            DB::commit();

            return $this->successWithContent([
                'department' => $department,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return $this->failedWithErrors(500, $e->getMessage());
        }
    }

    public function update($uuid)
    {
        $department = Department::where('uuid', '=', $uuid)
            ->with('updatedByUser')
            ->first();
        if ($department == null) {
            abort(404);
        }

        return view('front-end.layouts._admin.departments.update', [
            'department' => $department,
        ]);
    }

    public function postUpdate($uuid, Request $request)
    {
        try {
            DB::beginTransaction();

            $department = Department::where('uuid', '=', $uuid)->first();
            if ($department == null) {
                return $this->failedWithErrors(400, __('texts.texts.not_found.' . auth()->user()->lang));
            }

            $vi = $request->vi;
            $en = $request->en;
            $description = $request->description;

            if ($vi == null || $vi == '' || $en == null || $en == '') {
                return $this->failedWithErrors(400, __('texts.texts.name_required.' . auth()->user()->lang));
            }

            $department->name = json_encode(
                [
                    "vi" => $vi,
                    "en" => $en,
                ]
            );
            $department->description = $description;
            $department->updated_by = auth()->user()->uuid;
            $department->save();
            DB::commit();

            return $this->successWithContent($department);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return $this->failedWithErrors(500, $e->getMessage());
        }
    }
}
