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

        return view('front-end.layouts.layout_home');
    }
}
