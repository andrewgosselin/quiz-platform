<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Question;
use App\Models\Session;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        "name", "description", "questions_order"
    ];

    protected $casts = [
        "questions_order" => "array"
    ];

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
                "total" => $amountCorrect . " / " . $quiz->questions->count()
            ],
            "status" => "complete"
        ]);
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

    // ----------- Relationships
    public function unordered_questions() {
        return $this->hasMany(Question::class);
    }

    public function sessions() {
        return $this->hasMany(Session::class);
    }
}
