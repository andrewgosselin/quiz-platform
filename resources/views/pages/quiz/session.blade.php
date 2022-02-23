<x-guest-layout>
    <x-slot name="scripts">
        <script>
            function startQuiz() {
                $("#startPage").css("display", "none");
                $("#questionPages .questionPage").removeClass("active");
                $("#questionPages .questionPage").first().addClass("active");

                $("#previousQuestionButton").css("display", "none");
                $("#nextQuestionButton").css("display", "initial");
                $("#startQuizButton").css("display", "none");
            }

            function nextQuestion() {
                let current = $(".questionPage.active").attr("questionIndex");
                $(".questionPage").removeClass("active");
                $(`.questionPage[questionIndex=${parseInt(current) + 1}]`).addClass("active");
                if((parseInt(current) + 1) > 0) {
                    $("#previousQuestionButton").css("display", "initial");
                    $("#nextQuestionButton").css("display", "initial");
                } else if(parseInt(current) + 1 == ($(".questionPage").length - 1)) {
                    $("#previousQuestionButton").css("display", "initial");
                    $("#nextQuestionButton").css("display", "none");
                }
            }
            function previousQuestion() {
                let current = $(".questionPage.active").attr("questionIndex");
                $(".questionPage").removeClass("active");
                $(`.questionPage[questionIndex=${parseInt(current) - 1}]`).addClass("active");
                if((parseInt(current) - 1) == 0) {
                    $("#previousQuestionButton").css("display", "none");
                    $("#nextQuestionButton").css("display", "initial");
                }
            }
        </script>
    </x-slot>

    <style>
        html, body { margin: 0; padding: 0; }
        body {
            background-color: gray;
        }

        #startPage {
            padding: 10px;
        }

        .card-body {
            padding: 0;
        }

        .stepsContainer {
            height: 800px;
            width: 900px;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%); /* for IE 9 */
            -webkit-transform: translate(-50%, -50%); /* for Safari */
            background-color: white;
            position: absolute;
        }
        #questionPages {
            height: 100%;
            width: 100%;
        }
        .questionPage {
            display: none;
            border-collapse:collapse;
            height: 100%;
            width: 100%;
        }
        .questionPage.active {
            display : table;
        }

        .questionBox {
            padding: 10px;
        }
        .answerBox {
            background-color: lightgrey;
            display : table-row;
            vertical-align : bottom;
        }
        .answers {
            padding: 10px;
        }
    </style>

    <div class="pt-4">

        <div class="stepsContainer card">
            <div class="card-header">
                <h3>{{$quiz->name}}</h3>
                {{-- <h5>Question #0</h5> --}}
            </div>
            <div class="card-body">
                <div id="startPage">
                    <h5 class="card-title">Description</h5>
                    <p class="card-text">{{$quiz->description}}</p>
                </div>
                <div id="questionPages">
                    @foreach($quiz->questions as $questionIndex => $question)
                        <div class="questionPage" questionId="{{$question->id}}" questionIndex="{{$questionIndex}}">
                            @if($question->image && $question->image !== "")
                                <div class="questionBox">
                                    <img src="{{$question->image}}">
                                </div>
                            @endif
                            
                            <div class="questionBox">
                                <h5>Question</h5>
                                <p>{{$question->message}}</p>
                            </div>
                            <div class="answerBox">
                                <div class="answers">
                                    <h5>Answer</h5>
                                    @switch($question->type)
                                        @case("multiple_choice")
                                            @if($question->select_multiple)
                                                @foreach($question->choices as $choiceIndex => $choice)
                                                {{$choiceIndex}} {{$choice["choice"]}}
                                                @endforeach
                                            @else
                                                @foreach($question->choices as $choiceIndex => $choice)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="question{{$questionIndex}}-choice{{$choiceIndex}}" @if($choiceIndex == 0) checked @endif>
                                                        <label class="form-check-label" for="question{{$questionIndex}}-choice{{$choiceIndex}}">
                                                            {{$choice["choice"]}}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @endif
                                            @break
                                        @case("true_false")
                                            @foreach($question->choices as $choiceIndex => $choice)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="question{{$questionIndex}}-choice{{$choiceIndex}}" @if($choiceIndex == 0) checked @endif>
                                                    <label class="form-check-label" for="question{{$questionIndex}}-choice{{$choiceIndex}}">
                                                        {{$choiceIndex}}
                                                    </label>
                                                </div>
                                            @endforeach
                                            @break
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-primary float-start" onclick="previousQuestion()" style="display: none;" id="previousQuestionButton">Previous Question</button>
                <button type="button" class="btn btn-primary float-end" onclick="nextQuestion()" style="display: none;" id="nextQuestionButton">Next Question</button>
                <button type="button" class="btn btn-primary float-end" onclick="startQuiz()" id="startQuizButton">Start Quiz</button>
            </div>
        </div>
    </div>
</x-guest-layout>
