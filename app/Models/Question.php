<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Quiz;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        "quiz_id", "type", "image", "message", "choices", "select_multiple"
    ];

    protected $casts = [
        "choices" => "array",
        "answers" => "array"
    ];

    public function checkAnswer($answer) {

    }

    // ----------- Mutators
    // public function getAnswerAttribute() {
    //     return $this->answers[0] ?? null;
    // }

    // ----------- Relationships
    public function quiz() {
        return $this->belongsTo(Quiz::class);
    }
}
