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

    protected $casts = [
        "choices" => "array"
    ];

    public static function boot() {

	    parent::boot();

	    static::deleting(function($item) {
	        File::deleteDirectory(public_path('storage/question/' . $item->id));
	    });
	}

    // ----------- Mutators
    public function getChoicesAttribute($value) {
        // Run this twice in case of it being double encoded because of Laravel bug.
        $value = is_array($value) ? $value : json_decode($value, true);
        $value = is_array($value) ? $value : json_decode($value, true);
        return $value;
    }
    // public function getAnswerAttribute() {
    //     return $this->answers[0] ?? null;
    // }

    // public function getAnswersAttribute($value) {
    //     return json_decode($value, true);
    // }

    // ----------- Relationships
    public function quiz() {
        return $this->belongsTo(Quiz::class);
    }
}
