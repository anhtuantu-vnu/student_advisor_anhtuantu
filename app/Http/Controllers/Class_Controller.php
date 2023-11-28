<?php

namespace App\Http\Controllers;

use App\Models\Class_;
use App\Models\ClassRole;
use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Class_Controller extends Controller
{
    public function index()
    {
        $classes = Class_::with('department')->paginate(10);
        return view('front-end.layouts._admin.classes.index', [
            'classes' => $classes,
        ]);
    }

    public function detail($uuid)
    {
        $class_ = Class_::where('uuid', '=', $uuid)
            ->with('department')
            ->first();
        if ($class_ == null) {
            abort(404);
        }

        $classTeachers = ClassRole::where([
            ['class_id', '=', $class_->uuid],
            ['role', '=', _CONST::TEACHER_ROLE],
        ])->with([
            'user' => function ($query) {
                return $query->orderBy('last_name', 'desc')->orderBy('first_name', 'desc');
            }
        ])->get();

        $classStudents = ClassRole::where([
            ['class_id', '=', $class_->uuid],
            ['role', '=', _CONST::STUDENT_ROLE],
        ])->with([
            'user' => function ($query) {
                return $query->orderBy('last_name', 'desc')->orderBy('first_name', 'desc');
            }
        ])->get();

        return view('front-end.layouts._admin.classes.detail', [
            'class_' => $class_,
            'classTeachers' => $classTeachers,
            'classStudents' => $classStudents,
        ]);
    }

    public function update($uuid)
    {
        $departments = Department::all();
        $class_ = Class_::where('uuid', '=', $uuid)
            ->first();
        if ($class_ == null) {
            abort(404);
        }

        return view('front-end.layouts._admin.classes.update', [
            'class_' => $class_,
            'departments' => $departments,
        ]);
    }

    public function postUpdate($uuid, Request $request)
    {
        try {
            DB::beginTransaction();

            $class_ = Class_::where('uuid', '=', $uuid)
                ->first();
            if ($class_ == null) {
                return $this->failedWithErrors(400, __('texts.texts.not_found.' . auth()->user()->lang));
            }

            $name = $request->name;
            $code = $request->code;
            $department = $request->department;
            $start_year = $request->start_year;
            $end_year = $request->end_year;

            if ($name == null || $name == '') {
                return $this->failedWithErrors(400, __('texts.texts.name_required.' . auth()->user()->lang));
            }

            if ($code == null || $code == '') {
                return $this->failedWithErrors(400, __('texts.texts.code_required.' . auth()->user()->lang));
            }

            if ($department == null || $department == '') {
                return $this->failedWithErrors(400, __('texts.texts.department_required.' . auth()->user()->lang));
            }

            $class_->name = $name;
            $class_->code = $code;
            $class_->department_id = $department;
            $class_->start_year = $start_year;
            $class_->end_year = $end_year;
            $class_->save();
            DB::commit();

            return $this->successWithContent($class_);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return $this->failedWithErrors(500, $e->getMessage());
        }
    }

    public function removeMember($uuid, Request $request)
    {
        try {
            DB::beginTransaction();

            $class_ = Class_::where('uuid', '=', $uuid)
                ->first();
            if ($class_ == null) {
                return $this->failedWithErrors(400, __('texts.texts.not_found.' . auth()->user()->lang));
            }

            $classRole = ClassRole::where([
                ['uuid', '=', $request->uuid],
                ['class_id', '=', $class_->uuid],
            ])->first();
            if ($classRole == null) {
                return $this->failedWithErrors(400, __('texts.texts.not_found.' . auth()->user()->lang));
            }

            $classRole->delete();
            DB::commit();

            return $this->successWithContent($class_);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return $this->failedWithErrors(500, $e->getMessage());
        }
    }

    public function addTeachers($uuid, Request $request)
    {
        try {
            DB::beginTransaction();

            $class_ = Class_::where('uuid', '=', $uuid)
                ->first();
            if ($class_ == null) {
                return $this->failedWithErrors(400, __('texts.texts.not_found.' . auth()->user()->lang));
            }

            $uuids = explode(',', $request->uuids);
            $classRolesToInsert = [];
            foreach ($uuids as $uuid) {
                $classRole = ClassRole::where([
                    ['user_id', '=', $uuid],
                    ['class_id', '=', $class_->uuid],
                ])->first();

                if ($classRole == null) {
                    array_push($classRolesToInsert, [
                        'uuid' => Str::uuid(),
                        'class_id' => $class_->uuid,
                        'user_id' => $uuid,
                        'role' => _CONST::TEACHER_ROLE,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }


            if (count($classRolesToInsert) > 0) {
                ClassRole::insert($classRolesToInsert);
            }

            DB::commit();

            return $this->successWithContent($classRolesToInsert);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return $this->failedWithErrors(500, $e->getMessage());
        }
    }

    public function addStudents($uuid, Request $request)
    {
        try {
            DB::beginTransaction();

            $class_ = Class_::where('uuid', '=', $uuid)
                ->first();
            if ($class_ == null) {
                return $this->failedWithErrors(400, __('texts.texts.not_found.' . auth()->user()->lang));
            }

            $uuids = explode(',', $request->uuids);
            $classRolesToInsert = [];
            foreach ($uuids as $uuid) {
                $classRole = ClassRole::where([
                    ['user_id', '=', $uuid],
                    ['class_id', '=', $class_->uuid],
                ])->first();

                if ($classRole == null) {
                    array_push($classRolesToInsert, [
                        'uuid' => Str::uuid(),
                        'class_id' => $class_->uuid,
                        'user_id' => $uuid,
                        'role' => _CONST::STUDENT_ROLE,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }


            if (count($classRolesToInsert) > 0) {
                ClassRole::insert($classRolesToInsert);
            }

            DB::commit();

            return $this->successWithContent($classRolesToInsert);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return $this->failedWithErrors(500, $e->getMessage());
        }
    }
}
