<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return view("pages.admin.categories.index")
            ->with("categories", $categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.admin.categories.form')
            ->with("isEditing", false);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $category = \App\Models\Category::findOrFail($id);
        return view('pages.quizzes.category')
            ->with('category', $category)
            ->with('quizzes', $category->quizzes);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('pages.admin.categories.form')
            ->with("isEditing", true)
            ->with("category", $category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        
        if($id == "new") {
            $category = \App\Models\Category::create($data);
            if ($request->hasFile('image_file')) {
                $request->validate([
                    'image_file' => 'mimes:jpeg,bmp,png'
                ]);
                $request->image_file->store('category/' . $category->id, 'public');
                $data["image"] = $request->image_file->hashName();
                unset($data["image_url"]);
            }
            $category->update($data);
        } else {
            $category = \App\Models\Category::findOrFail($id);
            if ($request->hasFile('image_file')) {
                $request->validate([
                    'image_file' => 'mimes:jpeg,bmp,png'
                ]);
                $request->image_file->store('category/' . $id, 'public');
                $data["image"] = $request->image_file->hashName();
                unset($data["image_url"]);
            }
            $category->update($data);
        }
        return $category->toArray();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
