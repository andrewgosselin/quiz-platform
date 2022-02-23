<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Quiz;
use App\Models\Question;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($k = 0; $k < 100; $k++) {
            $quiz = Quiz::create([
                "name" => "Test Quiz #" . $k,
                "description" => "This is a quiz to test the quiz functionality."
            ]);
    
            for($i = 0; $i < 15; $i++) {
                Question::create([
                    "quiz_id" => $quiz->id,
                    "type" => "true_false",
                    "image" => null,
                    "message" => "Question " . $i,
                    "choices" => [
                        [
                            "choice" => "True",
                            "correct" => false
                        ],
                        [
                            "choice" => "False",
                            "correct" => true
                        ]
                    ]
                ]);
            }
        }
    }
}
