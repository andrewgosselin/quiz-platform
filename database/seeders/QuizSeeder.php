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
        $category = \App\Models\Category::create([
            "image" => "https://cdn.pixabay.com/photo/2018/07/31/22/08/lion-3576045__480.jpg",
            "name" => "Animals"
        ]);

        $quiz = $category->quizzes()->create([
            "image" => "https://a-z-animals.com/media/2021/01/mammals-400x300.jpg",
            "name" => "Test Quiz",
            "passing_score" => 50,
            "prize" => 1500
        ]);

        $quiz->unordered_questions()->createMany([
            [
                "type" => "true_false", 
                "image" => "https://upload.wikimedia.org/wikipedia/commons/thumb/4/45/Equus_quagga_burchellii_-_Etosha%2C_2014.jpg/1200px-Equus_quagga_burchellii_-_Etosha%2C_2014.jpg", 
                "message" => "This is a test question.", 
                "choices" => [
                    [
                        "choice" => "True",
                        "correct" => true
                    ],
                    [
                        "choice" => "False",
                        "correct" => false
                    ]
                ], 
                "explanation" => "This is incorrect and heres why."
            ],
            [
                "type" => "true_false", 
                "image" => "https://upload.wikimedia.org/wikipedia/commons/thumb/4/45/Equus_quagga_burchellii_-_Etosha%2C_2014.jpg/1200px-Equus_quagga_burchellii_-_Etosha%2C_2014.jpg", 
                "message" => "This is a test question 2.", 
                "choices" => [
                    [
                        "choice" => "True",
                        "correct" => true
                    ],
                    [
                        "choice" => "False",
                        "correct" => false
                    ]
                ], 
                "explanation" => "This is incorrect and heres why."
            ]
        ]);
    }
}
