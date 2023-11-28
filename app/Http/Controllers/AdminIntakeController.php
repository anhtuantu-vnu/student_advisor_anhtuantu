<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Intake;
use App\Models\IntakeMember;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminIntakeController extends Controller
{
    public function index()
    {
        $intakes = Intake::with([
            'subject',
        ])
            ->paginate(10);
        return view('front-end.layouts._admin.intakes.index', [
            'intakes' => $intakes,
        ]);
    }

    public function detail($uuid)
    {
        $intake = Intake::where('uuid', '=', $uuid)
            ->with('subject')
            ->first();
        if ($intake == null) {
            abort(404);
        }

        $intakeTeachers = IntakeMember::where([
            ['intake_id', '=', $intake->uuid],
            ['role', '=', _CONST::TEACHER_ROLE],
        ])->with([
            'user' => function ($query) {
                return $query->orderBy('last_name', 'desc')->orderBy('first_name', 'desc');
            }
        ])->get();

        $intakeStudents = IntakeMember::where([
            ['intake_id', '=', $intake->uuid],
            ['role', '=', _CONST::STUDENT_ROLE],
        ])->with([
            'user' => function ($query) {
                return $query->orderBy('last_name', 'desc')->orderBy('first_name', 'desc');
            }
        ])->get();

        return view('front-end.layouts._admin.intakes.detail', [
            'intake' => $intake,
            'intakeTeachers' => $intakeTeachers,
            'intakeStudents' => $intakeStudents,
        ]);
    }

    public function update($uuid)
    {
        $subjects = Subject::all();
        $intake = Intake::where('uuid', '=', $uuid)
            ->first();
        if ($intake == null) {
            abort(404);
        }

        return view('front-end.layouts._admin.intakes.update', [
            'intake' => $intake,
            'subjects' => $subjects,
        ]);
    }

    public function postUpdate($uuid, Request $request)
    {
        try {
            DB::beginTransaction();

            $intake = Intake::where('uuid', '=', $uuid)
                ->first();
            if ($intake == null) {
                return $this->failedWithErrors(400, __('texts.texts.not_found.' . auth()->user()->lang));
            }

            $code = $request->code;
            $subject = $request->subject;
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $start_hour = $request->start_hour;
            $end_hour = $request->end_hour;
            $start_minute = $request->start_minute;
            $end_minute = $request->end_minute;
            $location = $request->location;
            $weekDays = $request->weekDays;

            if ($code == null || $code == '') {
                return $this->failedWithErrors(400, __('texts.texts.code_required.' . auth()->user()->lang));
            }

            if ($subject == null || $subject == '') {
                return $this->failedWithErrors(400, __('texts.texts.subject_required.' . auth()->user()->lang));
            }

            if (
                $start_date == null || $start_date == ''
                || $end_date == null || $end_date == ''
                || $start_hour == null || $start_hour == ''
                || $end_hour == null || $end_hour == ''
                || $start_minute == null || $start_minute == ''
                || $end_minute == null || $end_minute == ''
                || $weekDays == null || $weekDays == ''
            ) {
                return $this->failedWithErrors(400, __('texts.texts.time_information_required.' . auth()->user()->lang));
            }

            if ($location == null || $location == '') {
                return $this->failedWithErrors(400, __('texts.texts.location_required.' . auth()->user()->lang));
            }

            $intake->code = $code;
            $intake->subject_id = $subject;
            $intake->start_date = $start_date;
            $intake->end_date = $end_date;
            $intake->start_hour = $start_hour;
            $intake->end_hour = $end_hour;
            $intake->start_minute = $start_minute;
            $intake->end_minute = $end_minute;
            $intake->location = $location;
            $intake->week_days = $weekDays;
            $intake->save();
            DB::commit();

            return $this->successWithContent($intake);
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

            $intake = Intake::where('uuid', '=', $uuid)
                ->first();
            if ($intake == null) {
                return $this->failedWithErrors(400, __('texts.texts.not_found.' . auth()->user()->lang));
            }

            $intakeMember = IntakeMember::where([
                ['uuid', '=', $request->uuid],
                ['intake_id', '=', $intake->uuid],
            ])->first();
            if ($intakeMember == null) {
                return $this->failedWithErrors(400, __('texts.texts.not_found.' . auth()->user()->lang));
            }

            $intakeMember->delete();
            DB::commit();

            return $this->successWithContent($intake);
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

            $intake = Intake::where('uuid', '=', $uuid)
                ->first();
            if ($intake == null) {
                return $this->failedWithErrors(400, __('texts.texts.not_found.' . auth()->user()->lang));
            }

            $uuids = explode(',', $request->uuids);
            $intakeMembersToInsert = [];
            foreach ($uuids as $uuid) {
                $intakeMember = IntakeMember::where([
                    ['user_id', '=', $uuid],
                    ['intake_id', '=', $intake->uuid],
                ])->first();

                if ($intakeMember == null) {
                    array_push($intakeMembersToInsert, [
                        'uuid' => Str::uuid(),
                        'intake_id' => $intake->uuid,
                        'user_id' => $uuid,
                        'role' => _CONST::TEACHER_ROLE,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }


            if (count($intakeMembersToInsert) > 0) {
                IntakeMember::insert($intakeMembersToInsert);
            }

            DB::commit();

            return $this->successWithContent($intakeMembersToInsert);
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

            $intake = Intake::where('uuid', '=', $uuid)
                ->first();
            if ($intake == null) {
                return $this->failedWithErrors(400, __('texts.texts.not_found.' . auth()->user()->lang));
            }

            $uuids = explode(',', $request->uuids);
            $intakeMembersToInsert = [];
            foreach ($uuids as $uuid) {
                $intakeMember = IntakeMember::where([
                    ['user_id', '=', $uuid],
                    ['intake_id', '=', $intake->uuid],
                ])->first();

                if ($intakeMember == null) {
                    array_push($intakeMembersToInsert, [
                        'uuid' => Str::uuid(),
                        'intake_id' => $intake->uuid,
                        'user_id' => $uuid,
                        'role' => _CONST::STUDENT_ROLE,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }


            if (count($intakeMembersToInsert) > 0) {
                IntakeMember::insert($intakeMembersToInsert);
            }

            DB::commit();

            return $this->successWithContent($intakeMembersToInsert);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return $this->failedWithErrors(500, $e->getMessage());
        }
    }

    public function updateMember($uuid, Request $request)
    {
        try {
            DB::beginTransaction();

            $intake = Intake::where('uuid', '=', $uuid)
                ->first();
            if ($intake == null) {
                return $this->failedWithErrors(400, __('texts.texts.not_found.' . auth()->user()->lang));
            }

            $intake_member = $request->intake_member;
            $intakeMember = IntakeMember::where([
                ['uuid', '=', $intake_member],
                ['intake_id', '=', $intake->uuid],
            ])->first();
            if ($intakeMember == null) {
                return $this->failedWithErrors(400, __('texts.texts.not_found.' . auth()->user()->lang));
            }

            $intakeMember->attendance_points = $request->attendance_points != null ? floatval($request->attendance_points) : null;
            $intakeMember->mid_term_points = $request->mid_term_points != null ? floatval($request->mid_term_points) : null;
            $intakeMember->last_term_points = $request->last_term_points != null ? floatval($request->last_term_points) : null;
            $intakeMember->save();

            DB::commit();

            return $this->successWithContent($intakeMember);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return $this->failedWithErrors(500, $e->getMessage());
        }
    }
}
