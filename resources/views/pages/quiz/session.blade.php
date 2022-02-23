<x-blank-layout>
    <x-slot name="scripts">
        <script>
            var session = @json($session ?? null);
            var endScreen = false;
            $( document ).ready(function() {
                if(session !== null) {
                    setAnswers();
                    goToQuestion(session.current_question, true);
                    
                }

                $("input.choice").on("change", function() {
                    updateSession();
                });
            });

            function startQuiz() {
                $.ajax({
                    type:'POST',
                    url: "{{ route('session.create') }}",
                    data: {},
                    success:function(data){
                        window.session = data;
                        $("#startPage").css("display", "none");
                        $("#endPage").css("display", "none");
                        $("#questionPages .questionPage").removeClass("active");
                        $("#questionPages .questionPage").first().addClass("active");

                        $("#previousQuestionButton").css("display", "none");
                        $("#nextQuestionButton").css("display", "initial");
                        $("#startQuizButton").css("display", "none");
                    }
                });
                
            }

            function updateSession() {
                var current_question = window.endScreen ? -5 : parseInt($(".questionPage.active").attr("questionIndex"));
                let data = {
                    current_question: current_question,
                    answers: getAnswers()
                };
                console.log(data);
                $.ajax({
                    type:'POST',
                    url: "{{ route('session.update') }}",
                    data: data,
                    success:function(data){
                        window.session = data;
                        console.log("Updated session.");
                    }
                });
            }

            function nextQuestion() {
                let current = $(".questionPage.active").attr("questionIndex");
                if(parseInt(current) == ($(`.questionPage`).length - 1)) {
                    goToEndScreen();
                } else {
                    goToQuestion(parseInt(current) + 1);
                }
            }
            function previousQuestion() {
                let current = $(".questionPage.active").attr("questionIndex");
                goToQuestion(parseInt(current) - 1);
            }

            function goToQuestion(question, skipUpdate = false) {
                $("#startPage").css("display", "none");
                $("#startQuizButton").css("display", "none");
                console.log($(`.questionPage[questionIndex=${question}]`));
                
                if(question == -5) {
                    goToEndScreen();
                } else if($(`.questionPage[questionIndex=${question}]`).length > 0) {
                    $(".questionPages").css("display", "initial");
                    let current = $(".questionPage.active").attr("questionIndex");
                    $(".questionPage").removeClass("active");
                    $(`.questionPage[questionIndex=${question}]`).addClass("active");
                    if(question == 0) {
                        $("#previousQuestionButton").css("display", "none");
                        $("#nextQuestionButton").css("display", "initial");
                    } else if(question > 0) {
                        $("#previousQuestionButton").css("display", "initial");
                        $("#nextQuestionButton").css("display", "initial");
                    }
                    if(skipUpdate == false) {
                        updateSession();
                    }
                }
                
            }

            function goToEndScreen() {
                window.endScreen = true;
                $("#questionPages").css("display", "none");
                $(".questionPage").removeClass("active");
                $("#endPage").css("display", "initial");
                updateSession();
            }

            function setAnswers() {
                let answers = session.answers;
                if(answers !== undefined && answers !== null) {
                    console.log(answers);
                    for(let answerIndex = 0; answerIndex < answers.length; answerIndex++) {
                        for(let choiceIndex = 0; choiceIndex < answers[answerIndex].length; choiceIndex++) {
                            if(answers[answerIndex][choiceIndex].selected) {
                                console.log(`#question${answerIndex}-choice${choiceIndex}`)
                                $(`#question${answerIndex}-choice${choiceIndex}`)[0].checked = (answers[answerIndex][choiceIndex].selected == "true");
                            }
                        }
                        
                    }
                }
            }

            function getAnswers() {
                let answers = [];
                $(".questionPage").each(function(index) {
                    var choices = [];
                    $(this).find(".choice").each(function(choiceIndex) {
                        choices[choiceIndex] = {
                            selected: this.checked
                        };
                    });
                    answers[index] = choices;
                });
                console.log(answers);
                return answers;
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
        #endPage {
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
                                    <fieldset id="questionGroup{{$questionIndex}}">
                                        @switch($question->type)
                                            @case("multiple_choice")
                                                @if($question->select_multiple)
                                                    @foreach($question->choices as $choiceIndex => $choice)
                                                    {{$choiceIndex}} {{$choice["choice"]}}
                                                    @endforeach
                                                @else
                                                    @foreach($question->choices as $choiceIndex => $choice)
                                                        <div class="form-check">
                                                            <input class="form-check-input choice" name="questionGroup{{$questionIndex}}" choiceId="{{$choiceIndex}}" type="radio" name="flexRadioDefault" id="question{{$questionIndex}}-choice{{$choiceIndex}}" @if($choiceIndex == 0) checked @endif>
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
                                                        <input class="form-check-input choice" name="questionGroup{{$questionIndex}}" choiceId="{{$choiceIndex}}" type="radio" name="flexRadioDefault" id="question{{$questionIndex}}-choice{{$choiceIndex}}" @if($choiceIndex == 0) checked @endif>
                                                        <label class="form-check-label" for="question{{$questionIndex}}-choice{{$choiceIndex}}">
                                                            {{$choice["choice"]}}
                                                        </label>
                                                    </div>
                                                @endforeach
                                                @break
                                        @endswitch
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div id="endPage" style="display: none;">
                    <h5 class="card-title">This is the end</h5>
                    <p class="card-text">{{$quiz->description}}</p>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-primary float-start" onclick="previousQuestion()" style="display: none;" id="previousQuestionButton">Previous Question</button>
                <button type="button" class="btn btn-primary float-end" onclick="nextQuestion()" style="display: none;" id="nextQuestionButton">Next Question</button>
                <button type="button" class="btn btn-primary float-end" onclick="startQuiz()" id="startQuizButton">Start Quiz</button>
            </div>
        </div>
    </div>
</x-blank-layout>
