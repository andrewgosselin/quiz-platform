<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Models\Category;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quizzes = Quiz::all();
        return view('pages.admin.quizzes.index')
            ->with("quizzes", $quizzes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('pages.admin.quizzes.form')
            ->with("isEditing", false)
            ->with("categories", $categories);
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
    public function show($category_id, $id)
    {
        $quiz = \App\Models\Quiz::where('category_id', $category_id)->where('slug', $id)->first();
        if($quiz == null) {
            abort(404);
        }
        return view('pages.quizzes.show')
            ->with('quiz', $quiz);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $quiz = Quiz::findOrFail($id);
        $categories = Category::all();
        return view('pages.admin.quizzes.form')
            ->with("isEditing", true)
            ->with("quiz", $quiz)
            ->with("categories", $categories);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        
        if($id == "new") {
            $quiz = \App\Models\Quiz::create($data);
            if ($request->hasFile('image_file')) {
                $request->validate([
                    'image_file' => 'mimes:jpeg,bmp,png'
                ]);
                $request->image_file->store('quiz/' . $quiz->id, 'public');
                $data["image"] = $request->image_file->hashName();
                unset($data["image_url"]);
            }
            $quiz->update($data);
        } else {
            $quiz = \App\Models\Quiz::findOrFail($id);
            if ($request->hasFile('image_file')) {
                $request->validate([
                    'image_file' => 'mimes:jpeg,bmp,png'
                ]);
                $request->image_file->store('quiz/' . $id, 'public');
                $data["image"] = $request->image_file->hashName();
                unset($data["image_url"]);
            }
            $quiz->update($data);
        }
        return $quiz->toArray();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->delete();
    }

    public function take($category_id, $id) {
        $quiz = \App\Models\Quiz::where('category_id', $category_id)->where('slug', $id)->first();
        if($quiz == null) {
            abort(404);
        }
        $quizTaken = auth()->user()->is_admin ? false : auth()->user()->takenQuiz($quiz->id);
        // if(session()->has("session_id")) {
        //     $session = auth()->user()->active_sessions->first();
        //     if($session) {
        //         if(request()->has("newSession")) {
        //             $session->delete();
        //             $session = null;
        //             session()->put("session_id", null);
        //         } else {
        //             $session = ($session->status == "complete" || $session->quiz_id !== $quiz->id) ? null : $session;
        //         }
        //     }
        // }
        session()->put("session_id", null);
        foreach(auth()->user()->active_sessions as $activeSession) {
            $activeSession->delete();
        }
        return view("pages.quizzes.session")
            ->with("quiz", $quiz)
            ->with("quiz_taken", $quizTaken)
            ->with("session", $session ?? null);
    }

    

    public function complete($id) {
        if(session()->has("session_id")) {
            $session_id = session()->get("session_id");
            $session = \App\Models\Session::where("session_id", $session_id)->first();
            if($session) {
                $data = request()->all();
                $data["status"] = "complete";
                $session->update($data);
                $session = \App\Models\Quiz::score($session);
                // $this->updateExternal($session);
                session()->put("session_id", null);
                return $session;
            } else {
                abort(404, "Session not found.");
            }
        }
    }

    public function updateExternal($session) {
        $url = env("CRM_API_URL") . "/AddToList";
        $response = Http::get($url, [
            'F9domain' => env('CRM_API_F9_DOMAIN', null),
            'F9key' => env('CRM_API_F9_KEY', null),
            'F9list' => env('CRM_API_F9_LIST', null),
            'first_name' => $session->first_name ?? '',
            'last_name' => $session->last_name ?? '',
            'email' => $session->email ?? '',
            'street' => $session->address_1,
            'city' => $session->city,
            'state' => $session->state,
            'zip' => $session->zip,
            'number1' => $session->phone_number,
            'F9updateCRM' => env('CRM_API_UPDATE_CRM', false)
        ]);
    }

    
}
