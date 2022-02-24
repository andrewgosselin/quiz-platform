<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="" />
        <meta
            name="author"
            content="Mark Otto, Jacob Thornton, and Bootstrap contributors"
        />
        <meta name="generator" content="Hugo 0.84.0" />
        <title>Quiz Platform</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">

        
        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
        <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
	

        {{-- Datatables --}}
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css"/>
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css"/>
        
        <meta name="theme-color" content="#7952b3" />

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
        </style>
    </head>
    <body>
        <header>
            <div class="navbar navbar-dark bg-dark shadow-sm">
                <div class="container">
                    <a href="#" class="navbar-brand d-flex align-items-center">
                        <strong>Quiz Platform</strong>
                    </a>
                </div>
                <a type="button" class="btn btn-primary" style="margin-right: 15px;" href="/admin/quizzes">
                    Admin Panel
                </a>
            </div>
        </header>

        <main>
            <section class="py-5 text-center container">
                <div class="row py-lg-5">
                    <div class="col-lg-6 col-md-8 mx-auto">
                        <h1 class="fw-light">Quizzes</h1>
                        <p class="lead text-muted">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis tellus arcu, blandit ac efficitur pretium, tempor a turpis. Morbi neque lectus, ornare in eros ut, tincidunt maximus dui. Vestibulum ullamcorper.
                        </p>
                    </div>
                </div>
            </section>

            <div class="album py-5 bg-light" style="min-height: 700px;">
                <div class="container">
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                        @foreach($quizzes as $quiz)
                            @if($quiz->questions->count() > 0)
                                <div class="col">
                                    <div class="card shadow-sm">
                                        @if($quiz->image)
                                        <img src="/storage/quiz/{{$quiz->id}}/{{$quiz->image}}" height="225">
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
                                            <p class="card-text">
                                                <h3>{{$quiz->name}}</h3>
                                                {{$quiz->description}}
                                            </p>
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
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </main>

        <footer class="text-muted py-5">
            <div class="container">
                <p class="float-end mb-1">
                    <a href="#">Back to top</a>
                </p>
            </div>
        </footer>

        <script
            src="/docs/5.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"
        ></script>
    </body>
</html>
