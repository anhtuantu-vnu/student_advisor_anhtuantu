<?php

namespace App\Http\Controllers;

use App\Models\Class_;
use App\Models\ClassRole;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->type != null ? $request->type : _CONST::TEACHER_ROLE;
        $users = User::where('role', '=', $type)->with('department')->paginate(10);
        return view('front-end.layouts._admin.users.index', [
            'users' => $users,
            'type' => $type,
        ]);
    }

    public function update($uuid)
    {
        $departments = Department::all();
        $user = User::where('uuid', '=', $uuid)
            ->first();
        if ($user == null) {
            abort(404);
        }

        return view('front-end.layouts._admin.users.update', [
            'user' => $user,
            'departments' => $departments,
        ]);
    }

    public function postUpdate($uuid, Request $request)
    {
        try {
            DB::beginTransaction();

            $user = User::where('uuid', '=', $uuid)
                ->first();
            if ($user == null) {
                return $this->failedWithErrors(400, __('texts.texts.not_found.' . auth()->user()->lang));
            }

            $last_name = $request->last_name;
            $first_name = $request->first_name;
            $email = $request->email;
            $phone = $request->phone;
            $date_of_birth = $request->date_of_birth;
            $gender = $request->gender;
            $unique_id = $request->unique_id;
            $role = $request->role;
            $department = $request->department;

            if (
                $last_name == null || $last_name == ''
                || $first_name == null || $first_name == ''
            ) {
                return $this->failedWithErrors(400, __('texts.texts.name_required.' . auth()->user()->lang));
            }

            if ($email == null || $email == '') {
                return $this->failedWithErrors(400, __('texts.texts.email_required.' . auth()->user()->lang));
            }

            if ($unique_id == null || $unique_id == '') {
                return $this->failedWithErrors(400, __('texts.texts.code_required.' . auth()->user()->lang));
            }

            $user->last_name = $last_name;
            $user->first_name = $first_name;
            $user->email = $email;
            $user->phone = $phone;
            $user->date_of_birth = $date_of_birth;
            $user->gender = $gender;
            $user->unique_id = $unique_id;
            $user->role = $role;
            $user->department_id = $department;
            $user->save();
            DB::commit();

            return $this->successWithContent($user);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return $this->failedWithErrors(500, $e->getMessage());
        }
    }
}
