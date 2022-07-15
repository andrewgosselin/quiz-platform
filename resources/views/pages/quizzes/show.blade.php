<x-mobile-layout>
    <x-slot name="scripts">
        <script>
            $(".item").on("click", function () {
                let url = $(this).attr("quiz-url");
                window.location.href = url;
            });
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
                font-size: 18pt;
                font-weight: 650;
                color: white;
            }

            * {
                -moz-user-select: none;
                -khtml-user-select: none;
                -webkit-user-select: none;

                /*
                    Introduced in Internet Explorer 10.
                    See http://ie.microsoft.com/testdrive/HTML5/msUserSelect/
                */
                -ms-user-select: none;
                user-select: none;
            }
            .quizImage {
                width: 100%;
                height: 250px;
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
            * {
                color: white;
            }
        </style>
    </x-slot>
    <div class="quizImage text-center mb-3">
        <img id="questionImage" src="/storage/quiz/{{$quiz->id}}/{{$quiz->image ?? ''}}">
    </div>
    <div class="heading-1">{{$quiz->name}}</div>
    <br>
    <div class="pb-1">
        <b>Questions:</b> {{$quiz->questions->count()}}<br>
        @if(auth()->user()->takenQuiz($quiz->id))
        <b>Previous score:</b> {{auth()->user()->sessions()->where("quiz_id", $quiz->id)->first()->score["precentage"]}}%
        @endif
    </div>
    <br>
    @if(auth()->user()->takenQuiz($quiz->id))
        <div class="alert alert-danger" role="alert">You have already taken this quiz.</div>
        <a type="button" class="btn btn-primary" href="/quizzes">Other quizzes</a>
    @else
        <a type="button" class="btn btn-primary" href="/quizzes/{{$quiz->category_id}}/{{$quiz->slug}}/take">Take</a>
        <a type="button" class="btn btn-danger" href="/quizzes">Back</a>
    @endif
</x-mobile-layout>

        

