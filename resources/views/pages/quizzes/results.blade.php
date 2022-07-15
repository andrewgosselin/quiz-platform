<!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Results</title>

    @if(!isset($isEmail) || !$isEmail)
      <!-- Font Awesome -->
      <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"
        rel="stylesheet"
      />
      <!-- Google Fonts -->
      <link
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
        rel="stylesheet"
      />
      <!-- MDB -->
      <link
        href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.2/mdb.min.css"
        rel="stylesheet"
      />
      <!-- MDB -->
      <script
        type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.2/mdb.min.js"
      ></script>
    @endif
    <style type="text/css">
        .takenBy {
            float: left;
            height: 200px;
            width: 50%;
            line-height: 15px;
        }
        .details {
            float: right;
            height: 200px;
            width: 50%;
            line-height: 10px;
        }
        .detailsSection {
            height: 525px;
            width: 100%;
        }
        body {
            padding: 100px;
        }
    </style>
    <style>
      /* -------------------------------------
          GLOBAL RESETS
      ------------------------------------- */
      
      /*All the styling goes here*/
      
      img {
        border: none;
        -ms-interpolation-mode: bicubic;
        max-width: 100%; 
      }

      body {
        background-color: #f6f6f6;
        font-family: sans-serif;
        -webkit-font-smoothing: antialiased;
        font-size: 14px;
        line-height: 1.4;
        margin: 0;
        padding: 0;
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%; 
      }

      table {
        border-collapse: separate;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
        width: 100%; }
        table td {
          font-family: sans-serif;
          font-size: 14px;
          vertical-align: top; 
      }

      /* -------------------------------------
          BODY & CONTAINER
      ------------------------------------- */

      .body {
        background-color: #f6f6f6;
        width: 100%; 
      }

      /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
      .container {
        display: block;
        margin: 0 auto !important;
        /* makes it centered */
        max-width: 800px;
        padding: 10px;
        width: 800px; 
      }

      /* This should also be a block element, so that it will fill 100% of the .container */
      .content {
        box-sizing: border-box;
        display: block;
        margin: 0 auto;
        max-width: 800px;
        padding: 10px; 
      }

      /* -------------------------------------
          HEADER, FOOTER, MAIN
      ------------------------------------- */
      .main {
        background: #ffffff;
        border-radius: 3px;
        width: 100%; 
      }

      .wrapper {
        box-sizing: border-box;
        padding: 20px; 
      }

      .content-block {
        padding-bottom: 10px;
        padding-top: 10px;
      }

      .footer {
        clear: both;
        margin-top: 10px;
        text-align: center;
        width: 100%; 
      }
        .footer td,
        .footer p,
        .footer span,
        .footer a {
          color: #999999;
          font-size: 12px;
          text-align: center; 
      }

      /* -------------------------------------
          TYPOGRAPHY
      ------------------------------------- */
      h1,
      h2,
      h3,
      h4 {
        color: #000000;
        font-family: sans-serif;
        font-weight: 400;
        line-height: 1.4;
        margin: 0;
        margin-bottom: 30px; 
      }

      h1 {
        font-size: 35px;
        font-weight: 300;
        text-align: center;
        text-transform: capitalize; 
      }

      p,
      ul,
      ol {
        font-family: sans-serif;
        font-size: 14px;
        font-weight: normal;
        margin: 0;
        margin-bottom: 15px; 
      }
        p li,
        ul li,
        ol li {
          list-style-position: inside;
          margin-left: 5px; 
      }

      a {
        color: #3498db;
        text-decoration: underline; 
      }

      /* -------------------------------------
          BUTTONS
      ------------------------------------- */
      .btn {
        box-sizing: border-box;
        width: 100%; }
        .btn > tbody > tr > td {
          padding-bottom: 15px; }
        .btn table {
          width: auto; 
      }
        .btn table td {
          background-color: #ffffff;
          border-radius: 5px;
          text-align: center; 
      }
        .btn a {
          background-color: #ffffff;
          border: solid 1px #3498db;
          border-radius: 5px;
          box-sizing: border-box;
          color: #3498db;
          cursor: pointer;
          display: inline-block;
          font-size: 14px;
          font-weight: bold;
          margin: 0;
          padding: 12px 25px;
          text-decoration: none;
          text-transform: capitalize; 
      }

      .btn-primary table td {
        background-color: #3498db; 
      }

      .btn-primary a {
        background-color: #3498db;
        border-color: #3498db;
        color: #ffffff; 
      }

      /* -------------------------------------
          OTHER STYLES THAT MIGHT BE USEFUL
      ------------------------------------- */
      .last {
        margin-bottom: 0; 
      }

      .first {
        margin-top: 0; 
      }

      .align-center {
        text-align: center; 
      }

      .align-right {
        text-align: right; 
      }

      .align-left {
        text-align: left; 
      }

      .clear {
        clear: both; 
      }

      .mt0 {
        margin-top: 0; 
      }

      .mb0 {
        margin-bottom: 0; 
      }

      .preheader {
        color: transparent;
        display: none;
        height: 0;
        max-height: 0;
        max-width: 0;
        opacity: 0;
        overflow: hidden;
        mso-hide: all;
        visibility: hidden;
        width: 0; 
      }

      .powered-by a {
        text-decoration: none; 
      }

      hr {
        border: 0;
        border-bottom: 1px solid #f6f6f6;
        margin: 20px 0; 
      }

      /* -------------------------------------
          RESPONSIVE AND MOBILE FRIENDLY STYLES
      ------------------------------------- */
      @media only screen and (max-width: 620px) {
        table.body h1 {
          font-size: 28px !important;
          margin-bottom: 10px !important; 
        }
        table.body p,
        table.body ul,
        table.body ol,
        table.body td,
        table.body span,
        table.body a {
          font-size: 16px !important; 
        }
        table.body .wrapper,
        table.body .article {
          padding: 10px !important; 
        }
        table.body .content {
          padding: 0 !important; 
        }
        table.body .container {
          padding: 0 !important;
          width: 100% !important; 
        }
        table.body .main {
          border-left-width: 0 !important;
          border-radius: 0 !important;
          border-right-width: 0 !important; 
        }
        table.body .btn table {
          width: 100% !important; 
        }
        table.body .btn a {
          width: 100% !important; 
        }
        table.body .img-responsive {
          height: auto !important;
          max-width: 100% !important;
          width: auto !important; 
        }
      }

      /* -------------------------------------
          PRESERVE THESE STYLES IN THE HEAD
      ------------------------------------- */
      @media all {
        .ExternalClass {
          width: 100%; 
        }
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
          line-height: 100%; 
        }
        .apple-link a {
          color: inherit !important;
          font-family: inherit !important;
          font-size: inherit !important;
          font-weight: inherit !important;
          line-height: inherit !important;
          text-decoration: none !important; 
        }
        #MessageViewBody a {
          color: inherit;
          text-decoration: none;
          font-size: inherit;
          font-family: inherit;
          font-weight: inherit;
          line-height: inherit;
        }
        .btn-primary table td:hover {
          background-color: #34495e !important; 
        }
        .btn-primary a:hover {
          background-color: #34495e !important;
          border-color: #34495e !important; 
        } 
      }

        ul { 
            font-weight: bold;
            list-style-position: inside;
            margin-left: -44px;
            list-style-type:none;
        }
        .normal { font-weight: normal; }


        .list-heading {
          font-size: 11pt;
          font-weight: 650;
          padding: 0;
          margin: 0;
        }
        .list-main-heading {
          font-size: 13pt;
          font-weight: 650;
          padding: 0;
          margin: 0;
        }

        .heading {
          margin-bottom: 0 !important;
        }

        h5 {
          font-size: 11pt !important;
          font-weight: 650;
          margin-top: 15px;
        }
    </style>
  </head>
  <body>
    <span class="preheader">This is preheader text. Some clients will show this text as a preview.</span>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
      <tr>
        <td>&nbsp;</td>
        <td class="container">
          <li class="content">

            <!-- START CENTERED WHITE CONTAINER -->
            <table role="presentation" class="main">

              <!-- START MAIN CONTENT AREA -->
              <tr>
                <td class="wrapper">
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>
                        <h1 class="heading">Quiz Results - {{$quiz->name}}</h1>
                        <div class="detailsSection" style="text-align:center;">
                            @if($session->score["precentage"] < $quiz->passing_score)
                                <h2 style="color: red; margin-bottom: 30px; margin-top: 15px;">Failed (Under {{$quiz->passing_score}}%)</h2>
                            @else
                                <h2 style="color: green; margin-bottom: 30px; margin-top: 15px;">Passed! ({{$session->score["precentage"]}}%)</h2>
                            @endif
                            <hr style="height: 1px; background-color: gray; border: none;">
                            <div style="text-align:center;">
                                <h2>Details</h2>
                            </div>
                            <div class="takenBy">
                                <h5 style="font-size: 10pt">QUIZ ID:</h5> {{$session->quiz_id}}
                                <h5 style="font-size: 10pt">TAKEN BY:</h5> {{$session->first_name}} {{$session->last_name}} <br><br>
                                {{$session->email}} <br><br>
                                {{$session->phone_number}} <br><br>
                                {{$session->address_1}}, <br>{{$session->city}},
                                {{$session->state}} {{$session->zip}}
                            </div>
                            <div class="details">
                                <!-- <h5 style="font-size: 10pt">SUBMISSION DATE:</h5> {{$session->id}} -->
                                <h5 style="font-size: 10pt">SESSION ID:</h5> {{$session->session_id}}
                                <h5 style="font-size: 10pt">SCORE PRECENTAGE:</h5> {{$session->score["precentage"]}}%
                                <h5 style="font-size: 10pt">SCORE TOTAL:</h5> {{$session->score["total"]}}
                            </div>
                            
                            <div style="text-align:center;">
                              @if(isset($isEmail) && $isEmail)
                                <a href="{{url('/quizzes/' . $quiz->id . '/start?newSession')}}">Click to retry for a better score.</a>
                              @else
                              <hr style="height: 1px !important; background-color: gray !important; border: none !important;">
                                <div style="padding-left: 200px; padding-right: 200px; padding-top: 250px;">
                                  Click the button below to retry for a better score!
                                  <a type="button" class="btn btn-primary mt-3" href="{{url('/quizzes/' . $quiz->id . '/start?newSession')}}">Retry</a>
                                </div>
                              @endif
                            </div>
                        </div>
                        
                        <hr style="height: 1px; background-color: gray; border: none;">
                        <div style="text-align:center;">
                            <h2>Questions</h2>
                        </div>
                        <ul>
                            @foreach($quiz->questions as $index => $question)
                                <li style="padding: 20px;">
                                    <div style="padding-bottom: 10px;">
                                        <span class="list-main-heading"><span class="normal"><span class="list-main-heading">{{$index + 1}})</span> {{$question->message}}</span></span>
                                    </div>
                                    <div style="padding-left: 20px;">
                                      <p class="list-heading">Answer</p>
                                      <div style="padding-left: 20px;">
                                          @foreach($session->answers[$index] as $choiceIndex => $answer)
                                              @if($answer["selected"] == "true")
                                                  <span class="normal">{{$question->choices[$choiceIndex]["choice"]}}</span><br>
                                              @endif
                                          @endforeach
                                      </div>
                                      <p class="list-heading">Explanation</p>
                                      <div style="padding-left: 20px;">
                                          @if($session->score["results"][$index] == true)
                                              <span class="normal" style="color: green;">This answer is correct.</span><br>
                                          @else
                                              <span class="normal" style="color: red;">This answer is incorrect.</span><br>
                                          @endif
                                          <span class="normal">{{$question->explanation}}</span>
                                      </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <hr>
                        <hr style="height: 1px !important; background-color: gray !important; border: none !important;">
                        <br>
                        <h5 style="color: red;">WARNING:</h5> This is a very early test document, the final quiz results page will be styled differently.
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

            <!-- END MAIN CONTENT AREA -->
            </table>
            <!-- END CENTERED WHITE CONTAINER -->


          </div>
        </td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </body>
</html>