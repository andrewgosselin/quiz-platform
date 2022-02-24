<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Quiz;
use Illuminate\Support\Facades\File;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        "quiz_id", "type", "image", "message", "choices", "select_multiple"
    ];

    public static function boot() {

	    parent::boot();

	    static::deleting(function($item) {
	        File::deleteDirectory(public_path('storage/question/' . $item->id));
	    });
	}

    // ----------- Mutators
    // public function getAnswerAttribute() {
    //     return $this->answers[0] ?? null;
    // }

    public function getChoicesAttribute($value) {
        return json_decode($value, true);
    }
    public function setChoicesAttribute($value) {
        return json_encode($value);
    }
    public function getAnswersAttribute($value) {
        return json_decode($value, true);
    }
    public function setAnswersAttribute($value) {
        return json_decode($value, true);
    }

    // ----------- Relationships
    public function quiz() {
        return $this->belongsTo(Quiz::class);
    }
}
