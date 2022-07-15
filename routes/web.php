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

Route::get('/logout', function() {
    session()->flush();
});

Route::get('/test', function() {
    \App\Models\User::create([
        "name" => "Johnny Tester",
        "email" => "johnny.tester@demo.com",
        "password" => bcrypt("appletree734"),
        "is_admin" => true
    ]);
});

Route::view("/mobile-preview", "pages.mobile-preview");

Route::middleware(['auth'])->group(function () {
    Route::get('/clearSessions', function() {
        auth()->user()->sessions()->delete();
        return redirect("/quizzes");
    });

    Route::get('/', 'App\Http\Controllers\HomeController@index');
    Route::get('/quizzes', 'App\Http\Controllers\HomeController@index');
    Route::get('/quizzes/{category_id}', "App\Http\Controllers\CategoryController@show")->name('quizzes.category');
    Route::get('/quizzes/{category_id}/{quiz_id}', "App\Http\Controllers\QuizController@show")->name('quizzes.show');
    Route::get('/quizzes/{category_id}/{quiz_id}/take', "App\Http\Controllers\QuizController@take")->name('quizzes.start');
    Route::post('/admin/quizzes/{id}/complete', "App\Http\Controllers\QuizController@complete")->name('ajax.quizzes.complete');
        
    Route::middleware(['admin'])->group(function() {
        Route::get('/category/{catSlug}', 'App\Http\Controllers\HomeController@category');
        // Route::get('/results/{id}', "App\Http\Controllers\SessionController@results")->name('session.results');
        
        
        Route::get('/admin/quizzes/create', "App\Http\Controllers\QuizController@create")->name('quizzes.create');
        
        Route::post('/session/create', "App\Http\Controllers\SessionController@create")->name('session.create');
        Route::post('/session/update', "App\Http\Controllers\SessionController@update")->name('ajax.session.update');
        Route::delete('/session/destroy', "App\Http\Controllers\SessionController@destroy")->name('ajax.session.destroy');
        
        Route::get('/admin/categories', "App\Http\Controllers\CategoryController@index")->name('categories');
        Route::get('/admin/categories/create', "App\Http\Controllers\CategoryController@create")->name('quizzes.create');
        Route::get('/admin/categories/{id}', "App\Http\Controllers\CategoryController@edit")->name('categories');
        Route::post('/admin/categories/{id}', "App\Http\Controllers\CategoryController@update")->name('ajax.categories.update');
        
        Route::get('/admin/quizzes', "App\Http\Controllers\QuizController@index")->name('quizzes');
        Route::get('/admin/quizzes/create', "App\Http\Controllers\QuizController@create")->name('quizzes.create');
        Route::get('/admin/quizzes/{id}', "App\Http\Controllers\QuizController@edit")->name('quizzes.edit');
        Route::post('/admin/quizzes/{id}', "App\Http\Controllers\QuizController@update")->name('ajax.quiz.update');
        Route::post('/admin/quizzes/{id}/question', "App\Http\Controllers\QuestionController@store")->name('question.create');
        Route::get('/admin/quizzes/{id}/question/{question_id?}', "App\Http\Controllers\QuestionController@show")->name('question.get');
        Route::post('/admin/quizzes/{id}/question/{question_id?}', "App\Http\Controllers\QuestionController@update")->name('ajax.question.update');
        Route::delete('/admin/quizzes/{id}/question/{question_id?}', "App\Http\Controllers\QuestionController@destroy")->name('ajax.question.delete');
        Route::delete('/admin/quizzes/{id?}', "App\Http\Controllers\QuizController@destroy")->name('ajax.quizzes.delete');
        // Route::get('/admin/results', "App\Http\Controllers\SessionController@resultsIndex")->name('session.results.index');
    });
});

require __DIR__.'/auth.php';
