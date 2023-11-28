<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::paginate(10);
        return view('front-end.layouts._admin.subjects.index', [
            'subjects' => $subjects,
        ]);
    }

    public function update($uuid)
    {
        $departments = Department::all();
        $subject = Subject::where('uuid', '=', $uuid)
            ->with('updatedByUser')
            ->first();
        if ($subject == null) {
            abort(404);
        }

        return view('front-end.layouts._admin.subjects.update', [
            'subject' => $subject,
            'departments' => $departments,
        ]);
    }

    public function postUpdate($uuid, Request $request)
    {
        try {
            DB::beginTransaction();

            $subject = Subject::where('uuid', '=', $uuid)->first();
            if ($subject == null) {
                return $this->failedWithErrors(400, __('texts.texts.not_found.' . auth()->user()->lang));
            }

            $vi = $request->vi;
            $en = $request->en;
            $code = $request->code;
            $color = $request->color;
            $department = $request->department;
            $description = $request->description;

            if ($vi == null || $vi == '' || $en == null || $en == '') {
                return $this->failedWithErrors(400, __('texts.texts.name_required.' . auth()->user()->lang));
            }

            if ($code == null || $code == '') {
                return $this->failedWithErrors(400, __('texts.texts.code_required.' . auth()->user()->lang));
            }

            $subject->name = json_encode(
                [
                    "vi" => $vi,
                    "en" => $en,
                ]
            );
            $subject->code = $code;
            $subject->color = $color;
            $subject->department_id = $department;
            $subject->description = $description;
            $subject->updated_by = auth()->user()->uuid;
            $subject->save();
            DB::commit();

            return $this->successWithContent($subject);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return $this->failedWithErrors(500, $e->getMessage());
        }
    }
}
