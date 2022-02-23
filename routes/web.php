<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ImageUploadController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if(auth()->check()) {
        return view('pages.dashboard');
    } else {
        return view('welcome');
    }
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/quizzes', function () {
    $quizzes = \App\Models\Quiz::all();
    return view('pages.quiz.index')
        ->with("quizzes", $quizzes);
})->middleware(['auth'])->name('quizzes');

Route::get('/quizzes/create', function () {
    return view('pages.quiz.form')
        ->with("isEditing", false);
})->middleware(['auth'])->name('quizzes.create');


Route::get('/quizzes/{id}', function ($id) {
    $quiz = \App\Models\Quiz::findOrFail($id);
    // dd($quiz->questions);
    return view('pages.quiz.form')
        ->with("isEditing", true)
        ->with("quiz", $quiz);
})->middleware(['auth'])->name('quizzes.edit');

Route::post('/quizzes/{id}', function ($id) {
    if($id == "new") {
        $quiz = \App\Models\Quiz::create(request()->all());
    } else {
        $quiz = \App\Models\Quiz::findOrFail($id);
        $quiz->update(request()->all());
    }
    return $quiz->toArray();
})->middleware(['auth'])->name('quiz.update');




Route::post('/quizzes/{id}/question', function ($id) {
    $data = request()->all();
    $data["quiz_id"] = $id;
    return \App\Models\Question::create($data)->toArray();
})->middleware(['auth'])->name('question.create');


Route::get('/quizzes/{id}/question/{question_id?}', function ($id, $question_id) {
    $quiz = \App\Models\Quiz::findOrFail($id);
    $question = $quiz->questions()->findOrFail($question_id ?? -1);
    return $question->toArray();
})->middleware(['auth'])->name('question.get');


Route::post('/quizzes/{id}/question/{question_id?}', function ($id, $question_id) {
    $quiz = \App\Models\Quiz::findOrFail($id);
    $question = $quiz->questions()->findOrFail($question_id ?? -1);
    $data = request()->all();
    $question->update($data);
    return $question->toArray();
})->middleware(['auth'])->name('question.update');

Route::delete('/quizzes/{id}/question/{question_id?}', function ($id, $question_id) {
    $quiz = \App\Models\Quiz::findOrFail($id);
    $question = $quiz->questions()->findOrFail($question_id ?? -1);
    $question->delete();
})->middleware(['auth'])->name('question.delete');


Route::delete('/quizzes/{id?}', function ($id) {
    $quiz = \App\Models\Quiz::findOrFail($id);
    $quiz->delete();
})->middleware(['auth'])->name('quizzes.delete');

Route::post('/quizzes/{id}/complete', function ($id) {
    if(session()->has("session_id")) {
        $session_id = session()->get("session_id");
        $session = \App\Models\Session::where("session_id", $session_id)->first();
        if($session) {
            $data = request()->all();
            $data["status"] = "in progress";
            $session->update($data);
            $session = \App\Models\Quiz::score($session);
            return $session;
        } else {
            abort(404, "Session not found.");
        }
    }
})->middleware(['auth'])->name('quizzes.complete');

Route::get('/start/{id?}', function ($id) {
    $quiz = \App\Models\Quiz::findOrFail($id);
    if(session()->has("session_id")) {
        $session = \App\Models\Session::where("session_id", session()->get("session_id"))->first();
        if($session) {
            $session = ($session->status == "completed" || $session->quiz_id !== (int)$id) ? null : $session;
        }
    }
    return view("pages.quiz.session")
        ->with("quiz", $quiz)
        ->with("session", $session ?? null);
})->middleware(['auth'])->name('quizzes.start');


Route::get('/clearSession', function () {
    session()->put("session_id");
})->middleware(['auth'])->name('session.flush');

Route::post('/session/create', function () {
    $session_id = \Illuminate\Support\Str::random(15);
    \App\Models\Session::create([
        "quiz_id" => request()->get("quiz_id"),
        "session_id" => $session_id
    ]);
    session()->put("session_id", $session_id);
    session()->save();
})->middleware(['auth'])->name('session.create');

Route::post('/session/update', function () {
    if(session()->has("session_id")) {
        $session_id = session()->get("session_id");
        $session = \App\Models\Session::where("session_id", $session_id)->first();
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
})->middleware(['auth'])->name('session.update');
Route::post('/session/complete', function () {
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
})->middleware(['auth'])->name('session.complete');

Route::get('image-upload', [ ImageUploadController::class, 'imageUpload' ])->name('image.upload');
Route::post('image-upload', [ ImageUploadController::class, 'imageUploadPost' ])->name('image.upload.post');


require __DIR__.'/auth.php';
