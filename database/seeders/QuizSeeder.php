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
        $quiz = Quiz::create([
            "name" => "Test Quiz #1",
            "description" => "This is a quiz to test the quiz functionality."
        ]);

        Question::create([
            "quiz_id" => $quiz->id,
            "type" => "multiple_choice",
            "image" => null,
            "message" => "How many licks does it take to get to the center of a tootsie pop?",
            "choices" => [
                [
                    "choice" => "Test choice #1",
                    "correct" => false
                ],
                [
                    "choice" => "Test choice #2",
                    "correct" => true
                ]
            ]
        ]);

        for($i = 0; $i < 100; $i++) {
            Question::create([
                "quiz_id" => $quiz->id,
                "type" => "true_false",
                "image" => null,
                "message" => "Question " . $i,
                "choices" => [
                    "True" => true,
                    "False" => false
                ]
            ]);
        }
    }
}
