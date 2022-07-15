<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        $categories = \App\Models\Category::all();
        return view("pages.quizzes.index")
            ->with("categories", $categories);
    }

    public function category($catSlug) {
        $categories = \App\Models\Quiz::getCategories();
        $quizzes = \App\Models\Quiz::where("category", $categories[$catSlug])->get();
        if($quizzes->count() == 0) {
            abort(404);
        } 
        return view("pages.category")
            ->with("quizzes", $quizzes)
            ->with("categories", $categories);
    }
}
