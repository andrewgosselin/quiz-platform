<x-mobile-layout>
    <x-slot name="scripts">
        <script>
            $(".item").on("click", function () {
                //if(!$(this).hasClass("disabled")) {
                    let url = $(this).attr("quiz-url");
                    window.location.href = url;
                //}
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

            .disabled-text {
                display: none;
            }

            .item.disabled img {
                opacity: 0.4;
                filter: alpha(opacity=40); /* msie */
            }

            .item.disabled .disabled-text {
                position: absolute; 
                top: 10px;
                left: 20px;
                color: white;
                background-color: rgba(0,0,0,0.4);
                border-radius: 15px;
                padding-left: 5px;
                padding-right: 5px;
                display: block;
            }
            
        </style>
    </x-slot>

    <div class="mb-3" style="height: 50px;">
        <div class="heading-1 float-start">{{$category->name}}</div>
        <a type="button" class="btn btn-danger float-end" href="/quizzes">Back</a>
    </div>
    <section class="cards row">
        @forelse($quizzes as $quiz)
            @php $quiz_taken = auth()->user()->takenQuiz($quiz->id) @endphp
            <div class="item col-md-2 col-sm-6 col-6 text-center @if($quiz_taken) disabled @endif" quiz-url="/quizzes/{{$quiz->category_id}}/{{$quiz->slug}}">
                <img src="/storage/quiz/{{$quiz->id}}/{{$quiz->image ?? ''}}">
                <h4>{{$quiz->name}}</h4>
                <div class="disabled-text">Already taken</div>
            </div>
        @empty
            <div class="item">
                <h4>No quizzes found in {{$category->name}}.</h4>
            </div>
        @endforelse
    </section>
</x-mobile-layout>

        

