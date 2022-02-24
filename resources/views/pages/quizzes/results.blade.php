<html>
    <head>
        <meta content="text/html; charset=UTF-8" http-equiv="content-type" />
        <style type="text/css">
            .takenBy {
                float: left;
                height: 200px;
                width: 50%;
            }
            .details {
                float: right;
                height: 200px;
                width: 50%;
            }
            .detailsSection {
                height: 350px;
                width: 100%;
            }
            body {
                padding: 100px;
            }
        </style>
    </head>
    <body class="c1">
        <h1 class="heading">Quiz Results</h1>
        <h3 class="sub-heading">Session {{$session->session_id}} - {{$quiz->name}}</h3>
        <hr>
        <div class="detailsSection">
            <div class="takenBy">
                <h5>QUIZ ID:</h5> {{$session->quiz_id}}
                <h5>TAKEN BY:</h5> {{$session->first_name}} {{$session->last_name}} <br><br>
                {{$session->email}} <br><br>
                {{$session->phone_number}} <br><br>
                {{$session->address_1}} <br> {{$session->city}}
                {{$session->state}} {{$session->zip}}
                
                
            </div>
            <div class="details">
                <!-- <h5>SUBMISSION DATE:</h5> {{$session->id}} -->
                <h5>SESSION ID:</h5> {{$session->session_id}}
                <h5>SCORE PRECENTAGE:</h5> {{$session->score["precentage"]}}%
                <h5>SCORE TOTAL:</h5> {{$session->score["total"]}}
            </div>
        </div>
        <hr>
        <br>
        <h5 style="color: red;">WARNING:</h5> This is a very early test document, the final quiz results page will be extremely different.
    </body>
</html>
