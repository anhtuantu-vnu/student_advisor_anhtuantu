<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

use App\Models\Intake;
use App\Models\IntakeMember;
use Illuminate\Support\Facades\Mail;

use App\Mail\CustomEmail;

class IntakeController extends Controller
{
    /**
     * @return View
     */
    public function showIntakeDetails($uuid): View
    {
        $thisIntake = Intake::where('uuid', '=', $uuid)->with(['subject'])->first();
        $intakeMembersStudents = IntakeMember::where([
            ['intake_id', '=', $uuid],
            ['role', '=', _CONST::STUDENT_ROLE],
        ])->with(['user' => function ($query) {
            $query->with('department');
        }])->get();

        $intakeMembersTeachers = IntakeMember::where([
            ['intake_id', '=', $uuid],
            ['role', '=', _CONST::TEACHER_ROLE],
        ])->with(['user' => function ($query) {
            $query->with('department');
        }])->get();

        return view('front-end.layouts.intake.detail', [
            'intake' => $thisIntake,
            'intakeMembersStudents' => $intakeMembersStudents,
            'intakeMembersTeachers' => $intakeMembersTeachers,
        ]);
    }

    public function sendCustomEmail(Request $request)
    {
        try {
            $subject = $request->subject;
            $fromName = $request->fromName;
            $toName = $request->toName;
            $content = $request->content;
            $toEmail = $request->toEmail;

            $ccEmails = [];
            if (isset($request->ccEmails)) {
                $ccEmails = explode(",", $request->ccEmails);
            }
            Mail::to($toEmail)->cc($ccEmails)->send(new CustomEmail($subject, $content, $fromName, $toName));

            $data = [
                'message' => 'email sent successfully',
            ];
            return $this->successWithContent($data);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->failedWithErrors(500, $exception->getMessage());
        }
    }
}
