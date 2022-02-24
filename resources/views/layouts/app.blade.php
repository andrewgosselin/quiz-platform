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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
	
        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


        {{-- Datatables --}}
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css"/>
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css"/>
        

        <script src="{{ asset('js/app.js') }}" defer></script>
        
        @include('layouts.navigation')
    </head>
    <body id="body-pd" class="body-pd">
        <header class="header body-pd" id="header">
            {{-- <div class="header_toggle"> <i class='bx bx-menu bx-x' id="header-toggle"></i> </div> --}}
            <div class="header_text">
                {{ $header }}
            </div>
            {{-- <div class="header_balance" onclick="openDeposit()">${{auth()->user()->balance}}</div> --}}
            {{-- <div class="header_img"> <img src="https://i.imgur.com/hczKIze.jpg" alt=""> </div> --}}
        </header>
        <div class="l-navbar show" id="nav-bar">
            <nav class="nav">
                <div> <a href="#" class="nav_logo"> <i class='bx bx-layer nav_logo-icon'></i> <span class="nav_logo-name">Quiz Platform</span> </a>
                    <div class="nav_list">
                        <a href="/admin/quizzes" class="nav_link {{\Request::routeIs('quizzes') ? "active" : ""}}"> 
                            <i class='bx bx-user nav_icon'></i> 
                            <span class="nav_name">Quizzes</span> 
                        </a>
                        <a href="/results" class="nav_link {{\Request::routeIs('session.results.index') ? "active" : ""}}"> 
                            <i class='bx bx-user nav_icon'></i> 
                            <span class="nav_name">Results</span> 
                        </a>
                    </div>
                </div> 
                {{-- <a href="#" class="nav_link"> 
                    <i class='bx bx-log-out nav_icon'></i> 
                    <span class="nav_name">SignOut</span> 
                </a> --}}
            </nav>
        </div>
        <!--Container Main start-->
        <div class="height-100">
            {{ $slot }}
        </div>

        <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            

            $( document ).ready(function() {
                $('table.datatable').DataTable();
            });
        </script>

        {{ $scripts ?? "" }}

        <!--Container Main end-->
            
    </body>
</html>
