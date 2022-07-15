<x-blank-layout>
    <x-slot name="scripts">
        <script>
            var expire_date_var_name = "quiz_{{$quiz->id}}_expire_date"
            var session = @json($session ?? null);
            var endScreen = false;
            $( document ).ready(function() {
                const queryString = window.location.search;
                const urlParams = new URLSearchParams(queryString);
                if(urlParams.has('newSession')) {
                    localStorage.setItem(window.expire_date_var_name, null);
                }
                history.pushState(null, "", location.href.split("?")[0]);
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
                    data: {
                        quiz_id: "{{$quiz->id ?? ''}}"
                    },
                    success:function(data){
                        window.session = data;
                        $("#startPage").css("display", "none");
                        goToQuestion(0);

                        $("#previousQuestionButton").css("display", "none");
                        $("#nextQuestionButton").css("display", "initial");
                        $("#startQuizButton").css("display", "none");
                    }
                });
                
            }

            function submitQuiz() {
                $('.loading').addClass('active');
                let data = {
                    answers: getAnswers(),
                    first_name: $('#firstNameInput').val(),
                    last_name: $('#lastNameInput').val(),
                    email: $('#emailInput').val(),
                    phone_number: $('#phoneNumberInput').val(),
                    address_1: $('#addressOneInput').val(),
                    address_2: $('#addressTwoInput').val(),
                    city: $('#cityInput').val(),
                    state: $('#stateInput').val(),
                    zip: $('#zipInput').val()
                };
                let required = ["first_name", "last_name", "email", "phone_number"];
                for(let i = 0; i < required.length; i++) {
                    if(data[required[i]] == "" || data[required[i]] == undefined) {
                        toastr.error("Not all required fields are filled!");
                        return;
                    }
                }
                if(!$("#termsCheckbox")[0].checked) {
                    toastr.error("You must accept the terms and conditions.");
                    return;
                }
                $.ajax({
                    type:'POST',
                    url: "{{ route('ajax.quizzes.complete', $quiz->id) }}",
                    data: data,
                    success:function(data){
                        localStorage.setItem(window.expire_date_var_name, null);
                        window.location.href = "/results/" + data.session_id;
                        $('.loading').removeClass('active');
                    },
                    error: function(e) {
                        $('.loading').removeClass('active');
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
                    url: "{{ route('ajax.session.update') }}",
                    data: data,
                    success:function(data){
                        window.session = data;
                        console.log("Updated session.");
                    }
                });
            }

            function checkQuestionAnswered() {
                let answered = false;
                $(".questionPage.active").find("input").each(function() {
                    if(answered == false) {
                        console.log(this.checked);
                        answered = this.checked == true;
                    }
                });
                return answered;
            }

            function nextQuestion() {
                let questionAnswered = checkQuestionAnswered();
                if(questionAnswered) {
                    let current = $(".questionPage.active").attr("questionIndex");
                    if(parseInt(current) == ($(`.questionPage`).length - 1)) {
                        goToEndScreen();
                    } else {
                        goToQuestion(parseInt(current) + 1);
                    }
                } else {
                    toastr.error("You must fill in an answer.");
                }
            }
            function previousQuestion() {
                let current = $(".questionPage.active").attr("questionIndex");
                if(window.endScreen) {
                    goToQuestion($(".questionPage").length - 1);
                } else {
                    goToQuestion(parseInt(current) - 1);
                }
            }

            function goToQuestion(question, skipUpdate = false) {
                $("#startPage").css("display", "none");
                $("#startQuizButton").css("display", "none");
                console.log($(`.questionPage[questionIndex=${question}]`));
                
                
                if($(`.questionPage[questionIndex=${question}]`).length > 0) {
                    $("#pageCounter").css("display", "initial");
                    $("#currentPageOutput").html(question + 1);
                    
                    window.endScreen = false;
                    $("#questionPages").css("display", "initial");
                    let current = $(".questionPage.active").attr("questionIndex");
                    $(".questionPage").removeClass("active");
                    $(`.questionPage[questionIndex=${question}]`).addClass("active");
                    $('#submitQuizButton').css("display", "none");
                    if(question == 0) {
                        $("#previousQuestionButton").css("display", "none");
                        $("#nextQuestionButton").css("display", "initial");
                    } else if(question == ($(`.questionPage`).length - 1)) {
                        $("#previousQuestionButton").css("display", "initial");
                        $("#nextQuestionButton").css("display", "none");
                        $("#submitAnswersButton").css("display", "initial");
                    } else if(question > 0) {
                        $("#submitAnswersButton").css("display", "none");
                        $("#previousQuestionButton").css("display", "initial");
                        $("#nextQuestionButton").css("display", "initial");
                    }
                    if(question < ($(`.questionPage`).length - 1)) {
                        $("#submitAnswersButton").css("display", "none");
                    }
                    if(skipUpdate == false) {
                        updateSession();
                    }
                }
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
    <link href="https://fonts.cdnfonts.com/css/futura-md-bt" rel="stylesheet">
    <style>
        html, body { margin: 0; padding: 0; }
        body {
            background-color: #2384C6;
        }

        #startPage {
            padding: 10px;
        }
        

        .card-body {
            padding: 0;
        }
        .stepsContainer {
            width: 50%;
            overflow:hidden;
            min-height: 85vh;
        }
        @media only screen and (max-width: 900px) {
            .stepsContainer {
                width: 90%;
            }
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
        .answers {
            padding: 10px;
        }

        label.required:after {
            content:" *";
            color:red;
        }

        .questionImage {
            width: 100%;
            height: 160px;
            border: 1px solid gray;
            background-color: lightgrey;
            position: relative;
            text-align: center;
            margin-bottom: 10px;
            overflow:hidden;
        }
        .questionImage img {
            width: auto;
            height: 100%;
        }

        .quizContainer {
            margin-top: 50px;
            margin-bottom: 50px;
        }

        .card-title {
            font-size: 14pt;
            font-weight: 650;
        }

        #pageCounter {
            display: none;
        }


        .loading {
            position: fixed;
            top: 0; right: 0;
            bottom: 0; left: 0;
            background-color: rgba(0,0,0,0.4);
            display: none;
            z-index: 99;
        }
        .loading.active {
            display: block;
        }
        .loader {
            left: 50%;
            margin-left: -4em;
            font-size: 10px;
            border: .8em solid rgba(218, 219, 223, 1);
            border-left: .8em solid rgba(58, 166, 165, 1);
            animation: spin 1.1s infinite linear;
        }
        .loader, .loader:after {
            border-radius: 50%;
            width: 8em;
            height: 8em;
            display: block;
            position: absolute;
            top: 50%;
            margin-top: -4.05em;
        }

        @keyframes spin {
        0% {
            transform: rotate(360deg);
        }
        100% {
            transform: rotate(0deg);
        }
        }
    </style>
    <div class="loading">
        <div class="loader"></div>
    </div>
    <div class="quizContainer p-4 d-flex justify-content-center w-100">

        <div class="stepsContainer card">
            <div class="card-header card-title text-center" style="font-size: 20pt;">
                {{$quiz->name}}
                <span class="float-sm-end" id="pageCounter"><span id="currentPageOutput">0</span> / {{$quiz->questions->count()}}</span>
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
                            <div class="row">
                                <div class="col">
                                    @if($question->image)
                                        <div class="questionImage">
                                            <img src="/storage/question/{{$question->id}}/{{$question->image}}"> 
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="questionBox">
                                        <h5>Question #{{$questionIndex + 1}}</h5>
                                        <p>{{$question->message}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="answers">
                                        <h5>Answer</h5>
                                        <fieldset id="questionGroup{{$questionIndex}}">
                                            @switch($question->type)
                                                @case("multiple_choice")
                                                    @if($question->select_multiple)
                                                        @foreach($question->choices as $choiceIndex => $choice)
                                                            <div class="form-check">
                                                                <input class="form-check-input choice" name="questionGroup{{$questionIndex}}" choiceId="{{$choiceIndex}}" type="checkbox" value="" id="question{{$questionIndex}}-choice{{$choiceIndex}}">
                                                                <label class="form-check-label" for="question{{$questionIndex}}-choice{{$choiceIndex}}">
                                                                    {{$choice["choice"]}}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        @foreach($question->choices as $choiceIndex => $choice)
                                                            <div class="form-check">
                                                                <input class="form-check-input choice" name="questionGroup{{$questionIndex}}" choiceId="{{$choiceIndex}}" type="radio" name="flexRadioDefault" id="question{{$questionIndex}}-choice{{$choiceIndex}}">
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
                                                            <input class="form-check-input choice" name="questionGroup{{$questionIndex}}" choiceId="{{$choiceIndex}}" type="radio" name="flexRadioDefault" id="question{{$questionIndex}}-choice{{$choiceIndex}}">
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
                            <div class="answerBox">
                                
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-danger float-start" onclick="previousQuestion()" style="display: none;" id="previousQuestionButton">Previous</button>
                <button type="button" class="btn btn-primary float-end" onclick="nextQuestion()" style="display: none;" id="nextQuestionButton">Next</button>
                <button type="button" class="btn btn-primary float-end" onclick="startQuiz()" id="startQuizButton">Start Quiz</button>
                <button type="button" class="btn btn-success float-end" onclick="submitQuiz()" style="display: none;" id="submitQuizButton">Submit Quiz</button>
                <button type="button" class="btn btn-success float-end" onclick="goToEndScreen()" style="display: none;" id="submitAnswersButton">Submit Answers</button>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Providing your number and clicking “yes,” is your electronic signature authorizing Senior Healthcare Advisors and its affiliates, agents, representatives, and service providers (collectively, “Senior Healthcare Advisors”) to send you marketing telephone calls, pre-recorded calls, text messages and emails at the number and email address you provided using an ATDS or automated system for the selection or dialing of telephone numbers. Your consent is not required as a condition of purchase. You also are confirming that you are the subscriber to, or the customary user of, the telephone number and e-mail address you provided. You may unsubscribe at any time.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <br>
</x-blank-layout>
