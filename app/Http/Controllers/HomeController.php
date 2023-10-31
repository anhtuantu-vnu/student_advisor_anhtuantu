<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function showHome()
    {
        $user = auth()->user();
        if ($user == null) {
            return redirect('/login');
        }

        if ($user->role == _CONST::ADMIN_ROLE) {
            return redirect('/admin/dashboard');
        } else if (
            $user->role == _CONST::TEACHER_ROLE
            || $user->role == _CONST::STUDENT_ROLE
        ) {
            return redirect('/home');
        } else {
            return redirect('/login');
        }
    }
}
