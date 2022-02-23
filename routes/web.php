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



Route::middleware(['auth'])->group(function () {
    Route::redirect('/', '/quizzes');
    Route::get('/quizzes', "App\Http\Controllers\QuizController@index")->name('quizzes');
    Route::get('/quizzes/create', "App\Http\Controllers\QuizController@create")->name('quizzes.create');
    Route::get('/quizzes/{id}', "App\Http\Controllers\QuizController@edit")->name('quizzes.edit');
    Route::post('/quizzes/{id}', "App\Http\Controllers\QuizController@update")->name('quiz.update');
    Route::delete('/quizzes/{id?}', "App\Http\Controllers\QuizController@destroy")->name('quizzes.delete');
    Route::post('/quizzes/{id}/complete', "App\Http\Controllers\QuizController@complete")->name('quizzes.complete');
    Route::get('/quizzes/{id?}/start', "App\Http\Controllers\QuizController@start")->name('quizzes.start');


    Route::post('/quizzes/{id}/question', "App\Http\Controllers\QuestionController@store")->name('question.create');
    Route::get('/quizzes/{id}/question/{question_id?}', "App\Http\Controllers\QuestionController@show")->name('question.get');
    Route::post('/quizzes/{id}/question/{question_id?}', "App\Http\Controllers\QuestionController@update")->name('question.update');
    Route::delete('/quizzes/{id}/question/{question_id?}', "App\Http\Controllers\QuestionController@destroy")->name('question.delete');
    

    Route::post('/session/create', "App\Http\Controllers\SessionController@create")->name('session.create');
    Route::post('/session/update', "App\Http\Controllers\SessionController@update")->name('session.update');

    Route::get('/results', "App\Http\Controllers\SessionController@resultsIndex")->name('session.results.index');
    Route::get('/results/{id}', "App\Http\Controllers\SessionController@results")->name('session.results');
});

require __DIR__.'/auth.php';
