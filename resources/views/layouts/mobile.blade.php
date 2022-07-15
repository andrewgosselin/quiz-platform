<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Quiz Platform</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    {{-- <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet"> --}}


    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.2/mdb.min.css"
        rel="stylesheet"
        />
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> --}}

    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link href="https://unpkg.com/@pqina/flip/dist/flip.min.css" rel="stylesheet">
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
    {{-- Datatables --}}
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css"/>
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css"/> --}}
    <style>
        body {
            padding-top: 90px;
            padding-bottom: 70px;
        }
        html, body {
            background-color: #8400F4 !important;
        }
        .bg-custom {
            --bs-bg-opacity: 1;
            background-color: #E700F5 !important;
        }
        .navbar {
            border-radius: 0px 0px 20px 20px;
            padding-top: 10px;
            padding-bottom: 15px;
            color: white;
            box-shadow: 0 1px 2px rgba(0,0,0,0.07), 
                0 2px 4px rgba(0,0,0,0.07), 
                0 4px 8px rgba(0,0,0,0.07), 
                0 8px 16px rgba(0,0,0,0.07),
                0 16px 32px rgba(0,0,0,0.07), 
                0 32px 64px rgba(0,0,0,0.07);
        }
        .user-avatar {
            width: 15%;
            float:left;
            height: 50px;
        }
        .user-avatar img {
            height: 100%;
            max-width: 100%;
            border-radius: 25px;
            border: 2px solid white;
        }
        .user-info {
            float:left;
            width: 30%;
            text-align: left !important;
            padding-left: 10px;
            padding-right: 10px;
        }
        .navigation {
            float:left;
            width: 18%;
            height: 100%;
            padding-top: 12px;
        }

        .info {
            width: 100%;
            height: 50px;
        }

        .sub-heading-1 {
            font-weight: 600;
        }
        .user-info .progress {
            border-radius: 15px;
        }


        /* (A) WRONG ORIENTATION - SHOW MESSAGE HIDE CONTENT */
        @media only screen and (orientation:landscape) {
            #mobile { display:block; }
            #app { display:none; }
        }
        
        /* (B) CORRECT ORIENTATION - SHOW CONTENT HIDE MESSAGE */
        @media only screen and (orientation:portrait) {
            #mobile { display:none; }
            #app { display:block; }
        }

        @media only screen and (max-width: 768px) {
            #app {
                display: block;
            }
            #mobile {
                display: none;
            }
        }
        @media only screen and (min-width: 768px) {
            #app {
                display: none;
            }
            #mobile {
                display: block;
            }
        }
    </style>
    {{ $styles ?? '' }}
</head>
<body>
    <div id="mobile" class="container">
        <div class="row text-center">
            <div class="col">
                <div class="heading-1" role="alert">
                This site is currently only optimized for mobile devices in portrait mode.
                </div>
            </div>
        </div>
    </div>
    
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-custom" style="-webkit-animation: fadeInDown 0.5s">
            <div class="container-fluid">
                <div class="info text-center">
                    
                    <div class="navigation">
                        
                        <button class="navbar-toggler collapsed" type="button" data-mdb-toggle="collapse"
                            data-mdb-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                            aria-label="Toggle navigation"
                            @if(isset($navigation))
                                @if($navigation->toHtml() ?? "" == "session")
                                style="opacity: 0;"
                                @endif
                            @endif
                            >
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                    
                    <div class="user-avatar">
                        <img src="https://www.w3schools.com/howto/img_avatar.png">
                    </div>
                    <div class="user-info">
                        <div id="userLevelDisplay" class="sub-heading-1">Level {{auth()->user()->social->level}}</div>
                        <div class="progress text-center" style="height: 20px; position: relative;">
                            <div style="position: absolute; right: 5px; color: black; top: 1px" id="userXpDisplayOutput">{{auth()->user()->social->current_xp}}/{{auth()->user()->social->total_xp}}</div>
                            <div class="progress-bar" role="progressbar" style="width: {{auth()->user()->social->current_xp / auth()->user()->social->total_xp * 100}}%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" id="userXpDisplay"></div>
                        </div>
                    </div>
                    <div class="user-info">
                        <div class="sub-heading-1">Coins</div>
                        <div id="userCoinsDisplay">{{auth()->user()->social->coins}}</div>
                    </div>
                </div>
                
                <div class="navbar-collapse collapse" id="navbarCollapse" style="">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/quizzes">Quizzes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="/admin/quizzes" target="_blank">Admin Panel</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="container-fluid" style="-webkit-animation: fadeIn 0.5s;">
            {{ $slot ?? "" }}
        </main>

        <!--Modal: modalConfirmNewSession-->
        <div class="modal fade" id="modalConfirmNewSession" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm modal-notify modal-danger" role="document">
                <!--Content-->
                <div class="modal-content text-center">
                    <!--Header-->
                    <div class="modal-header d-flex justify-content-center">
                        <p class="heading">Quiz In Progress</p>
                    </div>

                    <!--Body-->
                    <div class="modal-body">

                        You currently have a quiz in progress.<br>
                        Would you like to continue the previous quiz?<br><br>
                        Clicking "Delete" will delete all progress on the old quiz session.
                    </div>

                    <!--Footer-->
                    <div class="modal-footer flex-center">
                        <a class="btn  btn-danger" data-dismiss="modal" href="?clearSessions">Delete</a>
                        <a type="button" class="btn  btn-success" data-dismiss="modal" href="{{$continueActiveSessionUrl ?? ""}}">Continue</a>
                    </div>
                </div>
                <!--/.Content-->
            </div>
        </div>
        <!--Modal: modalConfirmNewSession-->
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- MDB -->
    <script
        type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.2/mdb.min.js"
        ></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script> --}}
    <!-- Scripts -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script> --}}
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="https://unpkg.com/@pqina/flip/dist/flip.min.js"></script>

    {{-- <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script> --}}


    <script src="{{ asset('js/app.js') }}" defer></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $(document).ready(function() {
            @if(isset($hasActiveSession) && $hasActiveSession)
                var myModal = new bootstrap.Modal($('#modalConfirmNewSession')[0])
                myModal.toggle();
            @endif
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            if(urlParams.has('clearSessions')) {
                history.pushState(null, "", location.href.split("?")[0]);
            }
            $('html, body').animate({scrollTop: '0px'}, 300);
        });

        function updateSocial(social) {
            console.log(social);
            $('#userCoinsDisplay').html(social.coins);
            $('#userLevelDisplay').html(`Level ${social.level}`);
            $('#userXpDisplayOutput').html(`${social.current_xp}/${social.total_xp}`);
            $('#userXpDisplay').css("width", `${social.current_xp / social.total_xp * 100}%`);
        }

    </script>

    {{ $scripts ?? '' }}

    <!--Container Main end-->

</body>

</html>
