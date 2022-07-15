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

            .item.disabled img {
                opacity: 0.4;
                filter: alpha(opacity=40); /* msie */
            }
            
        </style>
    </x-slot>

    <div class="mb-3" style="height: 50px;">
        <div class="heading-1 float-start">Start playing now!</div>
        {{-- <a type="button" class="btn btn-danger float-end" href="/quizzes">Back</a> --}}
    </div>
    <section class="cards row">
        @foreach($categories as $category)
            <div class="item col-md-2 col-sm-6 col-6 text-center" quiz-url="/quizzes/{{$category->id}}">
                <img src="/storage/category/{{$category->id}}/{{$category->image ?? ''}}">
                <h4>{{$category->name}}</h4>
            </div>
        @endforeach
    </section>
</x-mobile-layout>

        

