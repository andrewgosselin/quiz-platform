<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckActiveSessions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->check() && !str_contains(\Route::currentRouteName(), "ajax") && \Route::currentRouteName() !== "quizzes.start") {
            $active_sessions = auth()->user()->active_sessions;
            foreach($active_sessions as $session) {
                $session->delete();
            }
        }
        // if($request->has("clearSessions")) {
        //     if(auth()->check()) {
        //         $active_sessions = auth()->user()->active_sessions;
        //         foreach($active_sessions as $session) {
        //             $session->delete();
        //         }
        //     }
        // }
        // if(auth()->check() && \Route::currentRouteName() !== "quizzes.start") {
        //     $active_sessions = auth()->user()->active_sessions;
        //     view()->share('hasActiveSession', $active_sessions->count() > 0 ?? false);
        //     if($active_sessions && $active_sessions->count() > 0) {
        //         $quiz = $active_sessions->last()->quiz;
        //         foreach($active_sessions as $session) {
        //             if($session->quiz_id !== $quiz->id) {
        //                 $session->delete();
        //             }
        //         }
        //         $url = "/quizzes/" . $quiz->category_id . "/" . $quiz->slug . "/take";
        //         view()->share('continueActiveSessionUrl', $url);
        //     }
        // }
        return $next($request);
    }
}
