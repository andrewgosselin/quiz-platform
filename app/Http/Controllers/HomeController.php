<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        $quizzes = \App\Models\Quiz::all();
        return view("pages.landing")
            ->with("quizzes", $quizzes);
    }
}
