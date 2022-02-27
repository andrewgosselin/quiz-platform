<x-guest-layout>
    <x-slot name="scripts">
        <script>

        </script>
    </x-slot>

    <x-slot name="styles">
        <style>
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
            }

            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                    font-size: 3.5rem;
                }
            }
            h1 {
                font-size: 18pt;
                font-weight: 650 !important;
            }
            h3 {
                font-size: 14pt;
                font-weight: 650;
            }

            body {
                background: url("/images/background.jpg") no-repeat center center fixed; 
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
                backdrop-filter: blur(2px);
                height: 100vh;
            }
        </style>
    </x-slot>

    <header>
        <div class="navbar navbar-dark bg-dark shadow-sm" style="background-color: white !important; color: black !important;">
            <div class="container">
                <a href="#" class="navbar-brand d-flex align-items-center" style="color: black !important;">
                    <strong>Quiz Platform</strong>
                </a>
            </div>
            <a type="button" class="btn btn-outline-light" style="margin-right: 15px; border-color: black !important; color: black !important;" href="/admin/quizzes">
                {{auth()->check() ? "Admin Panel" : "Login"}}
            </a>
        </div>
    </header>

    <main>
        {{-- <section class="py-5 text-center container">
            <div class="row py-lg-5">
                <div class="col-lg-6 col-md-8 mx-auto">
                    <h1>Quizzes</h1>
                    <p class="lead text-muted pt-2">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis tellus arcu, blandit ac efficitur pretium, tempor a turpis. Morbi neque lectus, ornare in eros ut, tincidunt maximus dui. Vestibulum ullamcorper.
                    </p>
                </div>
            </div>
        </section> --}}

        <div class="album py-5" style="min-height: 700px;">
            <div class="container">
                {{-- <a type="button" class="btn btn-danger mb-3" href="/" style="background-color: red !important;">Back</a> --}}
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    @foreach($quizzes as $quiz)
                        {{-- @if($quiz->questions->count() > 0) --}}
                            <div class="col">
                                <div class="card shadow-sm">
                                    @if($quiz->image)
                                    <div style="height: 225px; text-align: center; background-color: lightgrey; border: 1px solid lightgrey;">
                                        <img src="/storage/quiz/{{$quiz->id}}/{{$quiz->image}}" style="height: 100%;display: block;margin-left: auto;margin-right: auto;">
                                    </div>
                                    
                                    @else
                                    <svg
                                        class="bd-placeholder-img card-img-top"
                                        width="100%"
                                        height="225"
                                        xmlns="http://www.w3.org/2000/svg"
                                        role="img"
                                        aria-label=""
                                        preserveAspectRatio="xMidYMid slice"
                                        focusable="false"
                                    >
                                        <title>Placeholder</title>
                                        <rect
                                            width="100%"
                                            height="100%"
                                            fill="#55595c"
                                        />
                                        <text
                                            x="50%"
                                            y="50%"
                                            fill="#eceeef"
                                            dy=".3em"
                                        >
                                            {{-- Thumbnail --}}
                                        </text>
                                    </svg>
                                    @endif

                                    <div class="card-body">
                                        <div class="card-text pb-3">
                                            <h3 class="pb-2">{{$quiz->name}}</h3>
                                            {{$quiz->description}}
                                        </div>
                                        <div
                                            class="
                                                d-flex
                                                justify-content-between
                                                align-items-center
                                            "
                                        >
                                            <div class="btn-group">
                                                <a
                                                    type="button"
                                                    class="
                                                        btn
                                                        btn-sm
                                                        btn-outline-secondary
                                                    "
                                                    href="/quizzes/{{$quiz->id}}/start"
                                                >
                                                    Take
                                                </a>
                                            </div>
                                            <small class="text-muted">{{$quiz->questions->count()}} Questions</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {{-- @endif --}}
                    @endforeach
                </div>
            </div>
        </div>
    </main>

</x-guest-layout>

        

