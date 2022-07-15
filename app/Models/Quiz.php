<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Question;
use App\Models\Session;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class Quiz extends Model
{
    protected $fillable = [
        "name", "category_id", "slug", "description", "image", "questions_order", "passing_score", "prize"
    ];

    protected $casts = [
        "questions_order" => "array"
    ];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->slug = \Illuminate\Support\Str::slug($model->name);
        });
    }

    public static function score($session) {
        $quiz = Quiz::find($session->quiz_id);
        $results = [];
        $amountCorrect = 0;
        foreach($quiz->questions as $questionIndex => $question) {
            foreach($question->raw_choices as $choiceIndex => $choice) {
                if($choice["correct"] == "true") {
                    if(array_key_exists($questionIndex, $session->answers)) {
                        $selected = $choiceIndex == $session->answers[$questionIndex];
                        $results[$questionIndex] = $selected;
                    } else {
                        $results[$questionIndex] = false;
                    }
                }
            }
        }
        foreach($results as $correct) {
            if($correct) {
                $amountCorrect++;
            }
        }
        $precentage = $amountCorrect / $quiz->questions->count() * 100;
        $passing = ($precentage) >= $quiz->passing_score;

        $session->update([
            "score" => [
                "correct" => $amountCorrect,
                "precentage" => $precentage,
                "total" => $amountCorrect . " / " . $quiz->questions->count(),
                "results" => $results,
                "passing" => $passing
            ],
            "status" => "complete"
        ]);

        if($passing) {
            auth()->user()->social->addCoins($quiz->prize);
            auth()->user()->social->addXp(450);
        }

        try {
            // Send quiz complete email.
            $pdf = PDF::loadView('pages.quizzes.results', [
                "quiz" => $quiz,
                "session" => $session
            ]);
            if (!file_exists(storage_path('app/public/results/' . $session->session_id))) {
                mkdir(storage_path('app/public/results/' . $session->session_id), 0777, true);
            }
            $pdf->save(storage_path('app/public/results/' . $session->session_id . '/results.pdf'));
            Mail::to($session->email)->send(new \App\Mail\TestResults($session, $quiz));
            unlink(storage_path('app/public/results/' . $session->session_id . '/results.pdf'));
        } catch (\Throwable $th) {
            //throw $th;
        }
        $social = auth()->user()->social->toArray();
        $social["level"] = auth()->user()->social->level;
        $social["current_xp"] = auth()->user()->social->current_xp;
        $social["total_xp"] = auth()->user()->social->total_xp;
        return [
            "session" => $session,
            "user_social" => $social
        ];
    }

    public function getQuestionsAttribute() {
        $questions = $this->unordered_questions->makeHidden(['choices.correct']);
        if ( ! is_null($this->questions_order)) {
            $order = $this->questions_order;
            $list = $this->unordered_questions->sortBy(function($model) use ($order){
                return array_search($model->getKey(), $order);
            });
            $questions =  collect($list->values());
        }
        return $questions;
    }

    public function getQuestionsOrderAttribute($value) {
        // Run this twice in case of it being double encoded because of Laravel bug.
        $value = is_array($value) ? $value : json_decode($value, true);
        $value = is_array($value) ? $value : json_decode($value, true);
        return $value;
    }

    // ----------- Relationships
    public function unordered_questions() {
        return $this->hasMany(Question::class);
    }

    public function sessions() {
        return $this->hasMany(Session::class);
    }
}
