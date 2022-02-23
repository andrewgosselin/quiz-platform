<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Session;

class SessionController extends Controller
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
        $session_id = \Illuminate\Support\Str::random(15);
        Session::create([
            "quiz_id" => request()->get("quiz_id"),
            "session_id" => $session_id
        ]);
        session()->put("session_id", $session_id);
        session()->save();
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
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request)
    {
        if(session()->has("session_id")) {
            $session_id = session()->get("session_id");
            $session = Session::where("session_id", $session_id)->first();
            if($session) {
                $data = request()->all();
                $data["score"] = [];
                $data["status"] = "in progress";
                $session->update($data);
                return $session;
            } else {
                abort(404, "Session not found.");
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function resultsIndex() {
        $sessions = \App\Models\Session::where("status", "complete")->get();
        return view('pages.results.index')
            ->with("sessions", $sessions);
    }

    public function results($id) {
        $session = \App\Models\Session::where("session_id", $id)->first();
        return view('pages.quizzes.results')
            ->with("session", $session)
            ->with("quiz", $session->quiz);
    }
}
