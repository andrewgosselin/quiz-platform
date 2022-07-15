<x-mobile-layout>
    <x-slot name="navigation">session</x-slot>
    <x-slot name="scripts">
        <script>
            // Hid all of the answers in the model by default.
            var questions = @json($quiz->questions);

            var session = @json($session ?? null);

            var current_question = 0;

            var answers = [];

            var state = "CLEAR";

            var countInterval = null;
            var countTimeout = null;
            var countRan = 0;


            var timeLimitInterval = null;
            var timeLimitRan = 0;

            var submitted = false;

            $( document ).ready(function() {
                @if(!$quiz_taken)
                    const queryString = window.location.search;
                    const urlParams = new URLSearchParams(queryString);
                    if(urlParams.has('newSession')) {
                        // localStorage.setItem(window.expire_date_var_name, null);
                    }
                    history.pushState(null, "", location.href.split("?")[0]);
                    if(session !== null) {
                        loadQuestion(session.current_question);
                    } else {
                        $('#startScreen').addClass("active");
                    }

                    $("input.choice").on("change", function() {
                        updateSession();
                    });
                @endif
                
            });

            function selectAnswer(element, index) {
                if($(element).attr("disabled") == undefined) {
                    console.log($(element).attr("disabled"));
                    window.answers[window.current_question] = index;
                    $("#choice-" + index).addClass("selected");
                    $(".answer").attr("disabled", true);
                    nextQuestion();
                }
            }

            function nextQuestion() {
                if((window.current_question + 1) < window.questions.length) {
                    loadQuestion((window.current_question + 1));
                } else {
                    submitQuiz();
                }
            }

            function loadQuestion(index, count = true) {
                window.countRan = 0;
                window.timeLimitRan = 0;
                window.clearInterval(window.timeLimitInterval);
                window.clearInterval(window.countInterval);
                window.clearTimeout(window.countTimeout);
                if(count) {
                    $('#questionDelayTimerOutput').html(5 - window.countRan);
                    window.countInterval = window.setInterval(function(){
                        $("#startScreen").removeClass("active");
                        $("#questionScreen").removeClass("active");
                        $("#readyScreen").css("opacity", 0);
                        $("#readyScreen").addClass("active");
                        $("#readyScreen").animate({opacity: 1}, 2000);
                        $('#questionDelayTimerOutput').html(5 - window.countRan);
                        setTime(5 - window.countRan, 5);
                        window.countRan++;
                        console.log("counting", window.countRan);
                        if(window.countRan == 6) {
                            window.clearTimeout(window.countTimeout);
                            loadQuestion(index, false);
                            window.clearInterval(window.countInterval);
                        }
                    }, 1000);
                } else {
                    window.current_question = index;
                    $('#startScreen').removeClass("active");
                    $("#readyScreen").removeClass("active");
                    $('#questionScreen').addClass("active");
                    var question = window.questions[index];
                    $('#questionImage').attr('src', `/storage/question/${question.id}/${question.image}`);
                    $('#questionNumber').html("Question " + (index + 1));
                    $('#questionMessage').html(question.message);
                    $('#questionChoices').html("");
                    for(let i = 0; i < question.choices.length; i++) {
                        $('#questionChoices').append(`
                            <div class="col-6 mb-3">
                                <div id="choice-${i}" class="answer" onclick="selectAnswer(this, ${i})">
                                    <div style="display: table-cell; vertical-align: middle;">
                                        <div>${question.choices[i]}</div>
                                    </div>
                                </div>
                            </div>
                        `);
                    }
                    window.timeLimitRan = 0;
                    window.timeLimitInterval = window.setInterval(function(){
                        $('#timeLimitTimerOutput').html(5 - window.countRan);
                        window.timeLimitRan++;
                        console.log("counting", window.timeLimitRan);
                        if(window.timeLimitRan == 5) {
                            selectAnswer(false);
                            window.clearInterval(window.timeLimitInterval);
                        }
                    }, 1000);

                    updateSession();
                    document.body.scrollTop = document.documentElement.scrollTop = 0;
                }
            }

            function startQuiz() {
                $.ajax({
                    type:'POST',
                    url: "{{ route('session.create') }}",
                    data: {
                        quiz_id: "{{$quiz->id ?? ''}}"
                    },
                    success:function(data){
                        window.session = data;
                        loadQuestion(0);
                    }
                });
            }

            function updateSession() {
                let data = {
                    current_question: window.current_question,
                    answers: window.answers
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

            function submitQuiz() {
                if(window.submitted == false) {
                    window.submitted = true;
                    $('.loading').addClass('active');
                    let data = {
                        answers: window.answers
                    };
                    $.ajax({
                        type:'POST',
                        url: "{{ route('ajax.quizzes.complete', $quiz->id) }}",
                        data: data,
                        success:function(data){
                            console.log("results", data);
                            updateSocial(data.user_social);
                            $("#resultsPassed").html(`${data.session.score.passing ? "Passed" : "Failed"}`);
                            $("#resultsPassed").css("color", `${data.session.score.passing ? "#00AB66" : "red"}`)
                            $("#resultsPrecentage").html(`${data.session.score.precentage} %`);
                            $("#resultsMessage").html(
                                data.session.score.passing ?
                                `You recieved <span style="color: yellow">{{$quiz->prize}}</span> coins.` :
                                "You did not recieve any coins."
                            );
                            $('#questionScreen').removeClass('active');
                            $('#resultsScreen').addClass('active');
                            $('.loading').removeClass('active');
                        },
                        error: function(e) {
                            $('.loading').removeClass('active');
                        }
                    });
                }
            }

            function hideScreens() {
                
            }
            function openScreen() {

            }

            function setTime(seconds, max) {
                let amount = 475 - ((475 / 100) * (seconds / max * 100));
                console.log(amount);
                $("circle").animate({strokeDashoffset: amount});
                number.innerHTML = seconds;
            }
        </script>
    </x-slot>

    <x-slot name="styles">
        <style>
            .cards .item {
                background: transparent;
                position: relative;
                color: white;
                cursor: pointer;
            }
            .item img {
                width: 100%;
                height: 135px;
                border-radius: 25px;
                margin-bottom: 5px;
            }

            .heading-1 {
                font-size: 20pt;
                font-weight: 650;
                color: white;
            }
            .heading-2 {
                font-size: 18pt;
                font-weight: 600;
                color: white;
            }
            .heading-3 {
                font-size: 16pt;
                font-weight: 550;
                color: white;
            }

          
            .quizImage {
                width: 100%;
                height: 20%;
                background-color: gray;
                border-radius: 25px;
                position: relative;
                overflow: hidden;
                box-shadow: 0 1px 2px rgba(0,0,0,0.07), 
                0 2px 4px rgba(0,0,0,0.07), 
                0 4px 8px rgba(0,0,0,0.07), 
                0 8px 16px rgba(0,0,0,0.07),
                0 16px 32px rgba(0,0,0,0.07), 
                0 32px 64px rgba(0,0,0,0.07);
            }

            .quizImage img {
                height: 100%;
            }

            .answer {
                background-color: white;
                height: 100px;
                box-shadow: 0 1px 2px rgba(0,0,0,0.07), 
                0 2px 4px rgba(0,0,0,0.07), 
                0 4px 8px rgba(0,0,0,0.07), 
                0 8px 16px rgba(0,0,0,0.07),
                0 16px 32px rgba(0,0,0,0.07), 
                0 32px 64px rgba(0,0,0,0.07);
                text-align: center;
                display: table; 
                overflow: hidden; 
                width: 100%;
                border-radius: 15px;
                cursor: pointer;
            }
            body {
            }

            #questionScreen, #startScreen, #readyScreen, #resultsScreen {
                display: none;
            }

            .round-time-bar {
            overflow: hidden;
            border-radius: 25px;
            background-color: gray;
            }
            .round-time-bar div {
            height: 10px;
            animation: roundtime calc(var(--duration) * 1s) steps(var(--duration))
                forwards;
            transform-origin: left center;
            background: linear-gradient(to bottom, red, #900);
            border-radius: 25px;
            }

            .round-time-bar[data-style="smooth"] div {
            animation: roundtime calc(var(--duration) * 1s) linear forwards;
            }

            @keyframes roundtime {
            to {
                /* More performant than `width` */
                transform: scaleX(0);
            }
            }

            .outputTime .output {
                border-radius: 25px;
                background-color: white;
                padding: 5px;
                padding-left: 10px;
                padding-right: 10px;
                width: auto;
            }

            .screen {
                display: none;
                opacity: 0;
            }
            .screen.active {
                display: initial !important;
                animation: fade-in 1s;
                animation: backInLeft 1s;
                opacity: 1;
            }

            @keyframes fade-in {
                from {
                    opacity: 0;
                }
                to {
                    opacity: 1;
                }
            }

            @keyframes fade-out {
                from {
                    opacity: 1;
                }
                to {
                    opacity: 0;
                }
            }

            .answer.selected {
                background-color: #4BB543;
                color: white;
                font-weight: 700;
            }

            
.skill {
  width: 160px;
  height: 160px;
  position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    transform: -webkit-translate(-50%, -50%);
    transform: -moz-translate(-50%, -50%);
    transform: -ms-translate(-50%, -50%);
}

.outer {
  height: 160px;
  width: 160px;
  border-radius: 50%;
  padding: 20px;
/*   box-shadow: 6px 6px 10px -1px rgba(0,0,0,0.15),
              -6px -6px 10px -1px rgba(255,255,255,0.7) */
}

.inner {
  height: 120px;
  width: 120px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
/*   box-shadow: inset 4px 4px 6px -1px rgba(0,0,0,0.2),
              inset -4px -4px 6px -1px rgba(255,255,255,0.7),
              -0.5px -0.5px 0px rgba(255,255,255,1),
              0.5px 0.5px 0px rgba(0,0,0,0.15),
              0px 12px 10px -10px rgba(0,0,0,0.05); */
}

#number {
  font-weight: 600px;
  color: #555;
}

circle {
  fill: none;
  stroke: url(#GradientColor);
  stroke-width: 20px;
  stroke-dasharray: 472;
  stroke-dashoffset: 472;
/*   animation: anim 2s linear forwards; */
}

svg {
  position: absolute;
  top: 0;
  left: 0;
}

@keyframes anim {
  100% {
    stroke-dashoffset: 0;
  }
}

#readyScreen #number {
    font-weight: 600;
    font-size: 19pt;
}
        </style>
    </x-slot>
    <div class="row">
        @if($quiz_taken)
            <div class="col pt-2">
                <div class="heading-2 text-danger pb-3">You have already taken this quiz.</div>
                <a type="button" class="btn btn-success" href="/quizzes">Other quizzes</a>
            </div>
        @endif
        <div class="col screen pt-2 text-center" id="startScreen">
            <div class="quizImage text-center mb-3">
                <img src="/storage/quiz/{{$quiz->id}}/{{$quiz->image ?? ''}}">
            </div>
            <div class="heading-1">{{$quiz->name}}</div>
            <br>
            <div class="heading-1">Are you ready to start?</div>
            <div class="heading-3">Once you start, you can't go back.</div>
            <br>
            <a type="button" class="btn btn-danger" href="/quizzes">I'm not ready</a>
            <button type="button" class="btn btn-success" onclick="startQuiz()">Start</button>
        </div>
        <div class="col screen pt-2" id="readyScreen">
  <div class="skill">
  <div class="outer">
    <div class="inner">
      <div id="number" class="text-light"></div>
    </div>
  </div>
  
  <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="160px" height="160px">
         <defs>
            <linearGradient id="GradientColor">
               <stop offset="0%" stop-color="#e91e63" />
               <stop offset="100%" stop-color="#673ab7" />
            </linearGradient>
         </defs>
         <circle cx="80" cy="80" r="70" stroke-linecap="round" />
 </svg>
</div>
        </div>
        <div class="col screen pt-1 text-center" id="questionScreen">
            {{-- <div class="outputTime text-center">
                <div class="output">0:05</div>
            </div> --}}
            <div class="round-time-bar mb-4" data-style="smooth" style="--duration: 5;">
                <div></div>
            </div>
            <div class="quizImage text-center">
                <img id="questionImage">
            </div>
            <div class="heading-2 mt-3" id="questionNumber"></div>
            <div class="heading-3 mt-3" id="questionMessage"></div>
            <div class="answers mt-4">
                <div class="row" id="questionChoices">
                </div>
            </div>
        </div>
        <div class="col screen pt-1 text-center" id="resultsScreen">
            {{-- <div class="outputTime text-center">
                <div class="output">0:05</div>
            </div> --}}
            <div class="heading-1 mt-1 mb-3" id="resultsTitle">Results</div>
            <div class="heading-2" id="resultsPassed"></div>
            <div class="heading-2 mb-3" id="resultsPrecentage"></div>
            <div class="heading-3 mb-4" id="resultsMessage"></div>
            {{-- <a type="button" class="btn btn-success" href="?newSession">Retry</a> --}}
            <a type="button" class="btn btn-danger" href="/quizzes">Back to Quizzes</a>
        </div>
    </div>
</x-mobile-layout>

        

