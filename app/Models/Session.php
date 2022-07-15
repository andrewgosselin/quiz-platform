<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Quiz;

class Session extends Model
{
    use HasFactory;
    protected $fillable = [
        "session_id", "quiz_id", "status", "current_question", "answers", "score", "first_name", "last_name", "email", "phone_number", "address_1", "address_2", "city", "state", "zip"
    ];

    protected $casts = [
        "score" => "array",
        "answers" => "array", 
        "secondary_information" => "array"
    ];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->user_id = auth()->user()->id;
        });
    }

    public function quiz() {
        return $this->belongsTo(Quiz::class);
    }
}
