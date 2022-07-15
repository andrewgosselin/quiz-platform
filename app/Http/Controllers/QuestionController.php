<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Question;
use App\Models\Quiz;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $data = request()->all();
        $data["quiz_id"] = $id;
        $data["choices"] = is_array($data["choices"]) ? $data["choices"] : json_decode($data["choices"], true);
        $question = Question::create($data);
        if ($request->hasFile('image_file')) {
            
            $request->validate([
                'image_file' => 'mimes:jpeg,bmp,png'
            ]);
            $request->image_file->store('question/' . $question->id, 'public');
            $data["image"] = $request->image_file->hashName();
            unset($data["image_url"]);
        }
        
        $question->update($data);
        return $question->toArray();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function show($id, $question_id)
    {
        $quiz = Quiz::findOrFail($id);
        $question = $quiz->unordered_questions()->findOrFail($question_id ?? -1);
        $output = $question->toArray();
        $output["choices"] = $question->raw_choices;
        return $output;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $question_id)
    {
        $quiz = Quiz::findOrFail($id);
        $question = $quiz->unordered_questions()->findOrFail($question_id ?? -1);
        $data = request()->all();
        if ($request->hasFile('image_file')) {
            $request->validate([
                'image_file' => 'mimes:jpeg,bmp,png'
            ]);
            $request->image_file->store('question/' . $question_id, 'public');
            $data["image"] = $request->image_file->hashName();
            unset($data["image_url"]);
        }
        $question->update($data);
        return $question->toArray();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $question_id)
    {
        $quiz = Quiz::findOrFail($id);
        $question = $quiz->unordered_questions()->findOrFail($question_id ?? -1);
        $question->delete();
    }

    public function complete() {
        if(session()->has("session_id")) {
            $session_id = session()->get("session_id");
            $session = \App\Models\Session::where("session_id", $session_id)->first();
            if($session) {
                $data = request()->all();
                $data["status"] = "complete";
                $data["current_question"] = -1;
                $session->update($data);
                return $session;
            } else {
                abort(404, "Session not found.");
            }
        }
    }
}
