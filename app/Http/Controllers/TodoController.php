<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class TodoController extends Controller
{

    /**
     * @return View
     */
    public function showToDo(): View
    {
        return view('front-end.layouts.layout_todo');
    }
}
