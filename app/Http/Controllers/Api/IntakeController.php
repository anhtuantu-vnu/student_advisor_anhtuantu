<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\_CONST;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\IntakeMember;

class IntakeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getIntakeTeacherInfo($uuid)
    {
        try {
            $currentUser = Auth::guard('api')->user();

            $thisIntake = IntakeMember::where([
                ['user_id', '=', $currentUser->uuid],
                ['intake_id', '=', $uuid],
            ])->first();

            if ($thisIntake == null) {
                return $this->failedWithErrors(404, 'You are not in this class');
            }

            $thisTeacherMembers = IntakeMember::where([
                ['role', '=', _CONST::TEACHER_ROLE],
                ['intake_id', '=', $uuid],
            ])->with('user')->get();

            $data = [
                'intakeTeachers'  => $thisTeacherMembers,
            ];
            return $this->successWithContent($data);
        } catch (\Exception $e) {
            report($e);
            return $this->failedWithErrors(500, 'Error getting intake teacher info: ' . $e->getMessage());
        }
    }
}
