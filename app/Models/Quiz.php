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
        "name", "description", "question_order"
    ];

    // ----------- Relationships
    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function sessions() {
        return $this->hasMany(Session::class);
    }
}
