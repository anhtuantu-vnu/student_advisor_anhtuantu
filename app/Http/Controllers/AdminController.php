<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Department;
use App\Models\Subject;
use App\Models\Class_;
use App\Models\Intake;

class AdminController extends Controller
{
    public function index()
    {
        $teachersCount = User::where('role', '=', _CONST::TEACHER_ROLE)->count();
        $studentsCount = User::where('role', '=', _CONST::STUDENT_ROLE)->count();
        $departmentsCount = Department::count();
        $subjectsCount = Subject::count();
        $classesCount = Class_::count();
        $intakesCount = Intake::count();

        return view('front-end.layouts._admin.dashboard', [
            'teachersCount' => $teachersCount,
            'studentsCount' => $studentsCount,
            'departmentsCount' => $departmentsCount,
            'subjectsCount' => $subjectsCount,
            'classesCount' => $classesCount,
            'intakesCount' => $intakesCount,
        ]);
    }
}
