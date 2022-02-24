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
    use HasFactory;

    protected $fillable = [
        "name", "description", "image", "questions_order", "passing_score"
    ];

    protected $casts = [
        "questions_order" => "array"
    ];

    public function share() {
        
    }

    public static function score($session) {
        $quiz = Quiz::find($session->quiz_id);
        $results = [];
        $amountCorrect = 0;
        foreach($quiz->questions as $questionIndex => $question) {
            foreach($question->choices as $choiceIndex => $choice) {
                if($choice["correct"] == "true") {
                    if(array_key_exists($questionIndex, $session->answers)) {
                        $selected = $session->answers[$questionIndex][$choiceIndex]["selected"] == "true";
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
        $session->update([
            "score" => [
                "correct" => $amountCorrect,
                "precentage" => $amountCorrect / $quiz->questions->count() * 100,
                "total" => $amountCorrect . " / " . $quiz->questions->count(),
                "results" => $results
            ],
            "status" => "complete"
        ]);

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

        return $session;
    }

    public function getQuestionsAttribute() {
        if ( ! is_null($this->questions_order)) {
            $order = $this->questions_order;
            $list = $this->unordered_questions->sortBy(function($model) use ($order){
                return array_search($model->getKey(), $order);
            });
            return collect($list->values());
        }
        return $this->unordered_questions;
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
