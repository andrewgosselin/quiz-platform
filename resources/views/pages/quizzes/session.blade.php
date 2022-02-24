<x-blank-layout>
    <x-slot name="scripts">
        <script>
            var session = @json($session ?? null);
            var endScreen = false;
            $( document ).ready(function() {
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
                        $("#endPage").css("display", "none");
                        $("#questionPages .questionPage").removeClass("active");
                        $("#questionPages .questionPage").first().addClass("active");

                        $("#previousQuestionButton").css("display", "none");
                        $("#nextQuestionButton").css("display", "initial");
                        $("#startQuizButton").css("display", "none");
                    }
                });
                
            }

            function submitQuiz() {
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
                        $("#endPageError").html("Not all required fields are filled!");
                        $("#endPageError").css("display", "block");
                        return;
                    }
                }
                $.ajax({
                    type:'POST',
                    url: "{{ route('quizzes.complete', $quiz->id) }}",
                    data: data,
                    success:function(data){
                        window.location.href = "/results/" + data.session_id;
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
                
                if(question == -5) {
                    goToEndScreen();
                } else if($(`.questionPage[questionIndex=${question}]`).length > 0) {
                    $("#endPage").css("display", "none");
                    window.endScreen = false;
                    $("#questionPages").css("display", "initial");
                    let current = $(".questionPage.active").attr("questionIndex");
                    $(".questionPage").removeClass("active");
                    $(`.questionPage[questionIndex=${question}]`).addClass("active");
                    $('#submitQuizButton').css("display", "none");
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
                $("#previousQuestionButton").css("display", "initial");
                $("#nextQuestionButton").css("display", "none");
                $("#questionPages").css("display", "none");
                $(".questionPage").removeClass("active");
                $("#endPage").css("display", "block");
                $("#submitQuizButton").css("display", "initial");
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
            padding: 15px;
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
            height: 100px;
        }
        .answerBox {
            display : table-row;
            vertical-align : bottom;
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
        }
        .questionImage img {
            width: auto;
            height: 100%;
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
                            <div class="questionBox">
                                @if($question->image)
                                    <div class="questionImage">
                                        <img src="/storage/question/{{$question->id}}/{{$question->image}}"> 
                                    </div>
                                @endif
                                <h5>Question #{{$questionIndex + 1}}</h5>
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
                    @endforeach
                </div>
                <div id="endPage" style="display: none;">
                    <div class="alert alert-danger" role="alert" id="endPageError" style="display: none;"></div>
                    <h5 class="card-title">Details</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="required" for="firstNameInput">First Name</label>
                            <input type="text" class="form-control" id="firstNameInput" placeholder="First name">
                        </div>
                        <div class="col-md-6">
                            <label class="required" for="lastNameInput">Last name</label>
                            <input type="text" class="form-control" id="lastNameInput" placeholder="Last name">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="required" for="emailInput">Email</label>
                            <input type="email" class="form-control" id="emailInput" placeholder="Email">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="required" for="phoneNumberInput">Phone Number</label>
                            <input type="tel" class="form-control" id="phoneNumberInput" placeholder="Phone number">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="addressOneInput">Address</label>
                            <input type="text" class="form-control" id="addressOneInput" placeholder="1234 Main St">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="addressTwoInput">Address 2</label>
                            <input type="text" class="form-control" id="addressTwoInput" placeholder="Apartment, studio, or floor">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="cityInput">City</label>
                            <input type="text" class="form-control" id="cityInput">
                        </div>
                        <div class="col-md-4">
                            <label for="stateInput">State</label>
                            <select id="stateInput" class="form-control">
                                <option selected>Choose...</option>
                                <option value="AL">Alabama</option>
                                <option value="AK">Alaska</option>
                                <option value="AZ">Arizona</option>
                                <option value="AR">Arkansas</option>
                                <option value="CA">California</option>
                                <option value="CO">Colorado</option>
                                <option value="CT">Connecticut</option>
                                <option value="DE">Delaware</option>
                                <option value="DC">District Of Columbia</option>
                                <option value="FL">Florida</option>
                                <option value="GA">Georgia</option>
                                <option value="HI">Hawaii</option>
                                <option value="ID">Idaho</option>
                                <option value="IL">Illinois</option>
                                <option value="IN">Indiana</option>
                                <option value="IA">Iowa</option>
                                <option value="KS">Kansas</option>
                                <option value="KY">Kentucky</option>
                                <option value="LA">Louisiana</option>
                                <option value="ME">Maine</option>
                                <option value="MD">Maryland</option>
                                <option value="MA">Massachusetts</option>
                                <option value="MI">Michigan</option>
                                <option value="MN">Minnesota</option>
                                <option value="MS">Mississippi</option>
                                <option value="MO">Missouri</option>
                                <option value="MT">Montana</option>
                                <option value="NE">Nebraska</option>
                                <option value="NV">Nevada</option>
                                <option value="NH">New Hampshire</option>
                                <option value="NJ">New Jersey</option>
                                <option value="NM">New Mexico</option>
                                <option value="NY">New York</option>
                                <option value="NC">North Carolina</option>
                                <option value="ND">North Dakota</option>
                                <option value="OH">Ohio</option>
                                <option value="OK">Oklahoma</option>
                                <option value="OR">Oregon</option>
                                <option value="PA">Pennsylvania</option>
                                <option value="RI">Rhode Island</option>
                                <option value="SC">South Carolina</option>
                                <option value="SD">South Dakota</option>
                                <option value="TN">Tennessee</option>
                                <option value="TX">Texas</option>
                                <option value="UT">Utah</option>
                                <option value="VT">Vermont</option>
                                <option value="VA">Virginia</option>
                                <option value="WA">Washington</option>
                                <option value="WV">West Virginia</option>
                                <option value="WI">Wisconsin</option>
                                <option value="WY">Wyoming</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="zipInput">Zip</label>
                            <input type="text" class="form-control" id="zipInput">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-danger float-start" onclick="previousQuestion()" style="display: none;" id="previousQuestionButton">Previous Question</button>
                <button type="button" class="btn btn-primary float-end" onclick="nextQuestion()" style="display: none;" id="nextQuestionButton">Next Question</button>
                <button type="button" class="btn btn-primary float-end" onclick="startQuiz()" id="startQuizButton">Start Quiz</button>
                <button type="button" class="btn btn-success float-end" onclick="submitQuiz()" style="display: none;" id="submitQuizButton">Submit Quiz</button>
            </div>
        </div>
    </div>
</x-blank-layout>
