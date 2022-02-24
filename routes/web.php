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

Route::get('/', 'App\Http\Controllers\HomeController@index');
Route::get('/results/{id}', "App\Http\Controllers\SessionController@results")->name('session.results');
Route::get('/quizzes/{id?}/start', "App\Http\Controllers\QuizController@start")->name('quizzes.start');

Route::post('/admin/quizzes/{id}/complete', "App\Http\Controllers\QuizController@complete")->name('quizzes.complete');
Route::post('/session/create', "App\Http\Controllers\SessionController@create")->name('session.create');
Route::post('/session/update', "App\Http\Controllers\SessionController@update")->name('session.update');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/quizzes', "App\Http\Controllers\QuizController@index")->name('quizzes');
    Route::get('/admin/quizzes/{id}', "App\Http\Controllers\QuizController@edit")->name('quizzes.edit');
    Route::get('/admin/quizzes/create', "App\Http\Controllers\QuizController@create")->name('quizzes.create');
    Route::post('/admin/quizzes/{id}', "App\Http\Controllers\QuizController@update")->name('quiz.update');
    Route::post('/admin/quizzes/{id}/question', "App\Http\Controllers\QuestionController@store")->name('question.create');
    Route::get('/admin/quizzes/{id}/question/{question_id?}', "App\Http\Controllers\QuestionController@show")->name('question.get');
    Route::post('/admin/quizzes/{id}/question/{question_id?}', "App\Http\Controllers\QuestionController@update")->name('question.update');
    Route::delete('/admin/quizzes/{id}/question/{question_id?}', "App\Http\Controllers\QuestionController@destroy")->name('question.delete');
    Route::delete('/admin/quizzes/{id?}', "App\Http\Controllers\QuizController@destroy")->name('quizzes.delete');
    Route::get('/results', "App\Http\Controllers\SessionController@resultsIndex")->name('session.results.index');
    
});

require __DIR__.'/auth.php';
