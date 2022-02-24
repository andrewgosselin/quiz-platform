<x-app-layout>
    <style>

        .choicesTableContainer {
            height: 400px;
            width: 100%;
            border: 1px solid gray;
            overflow-y: scroll;
        }

        .questionsTableContainer {
            height: 400px;
            width: 100%;
            border: 1px solid gray;
            overflow-y: scroll;
        }
        .questionsTableToolBar {
            border-bottom: 1px solid gray;
            padding-top: 10px;
            padding-left: 10px;
            padding-right: 10px;
            padding-bottom: 15px;
            height: 55px;
            position: relative;
        }
        #addQuestionButton {
            float:right;
        }

        #addChoiceButton {
            float:right;
        }
        #dragQuestionHint {
            float:left;
            margin-top: 5px;
        }
        .questionImageContainer {
            width: 100%;
            height: 160px;
            border: 1px solid gray;
            background-color: lightgrey;
            position: relative;
            text-align: center;
        }
        .questionImageContainer img {
            width: auto;
            height: 100%;
        }
        .questionImageContainer button {
            position: absolute;
            top: 5px;
            left: 5px;
        }
    </style>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if($isEditing == true)
            {{ __('Edit Quiz - ' . $quiz->name) }}
            @else
            {{ __('New Quiz') }}
            @endif
        </h2>
    </x-slot>

    <x-slot name="scripts">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/TableDnD/0.9.1/jquery.tablednd.js" integrity="sha256-d3rtug+Hg1GZPB7Y/yTcRixO/wlI78+2m08tosoRn7A=" crossorigin="anonymous"></script>

        <script>
            $( document ).ready(function() {
                $("#questionsTable").tableDnD();
                $('#questionImageUploadButton').click(function(){ 
                    $('#questionImageUploadInput').trigger('click'); 
                });
                $("#questionTypeInput").on("change", function() {
                    if($(this).val() == "multiple_choice") {
                        $('#trueFalseSection').css("display", "none");
                        $('#multipleChoiceSection').css("display", "block");
                    } else {
                        $('#multipleChoiceSection').css("display", "none");
                        $('#trueFalseSection').css("display", "block");
                    }
                });
            });

            function loadFile(event) {
                var output = $(".questionImageContainer img")[0];
                output.src = URL.createObjectURL(event.target.files[0]);
                output.onload = function() {
                    URL.revokeObjectURL(output.src) // free memory
                }
            }

            function addChoice(choice = "", choice_correct = false) {
                $("#choicesTable tbody").append(`
                    <tr>
                        <td>
                            <input type="text" class="form-control choiceInput" value="${choice}">
                        </td>
                        <td>
                            <input class="form-check-input choiceCorrectInput" type="checkbox" ${(choice_correct == "true" || choice_correct == true) ? "checked" : ""}>
                        </td>
                        <td>
                            <a type="button" class="btn btn-danger" onclick="$(this).parent().parent().remove()">Delete</a>
                        </td>
                    </tr>
                `);
            }

            function getQuestionData() {
                var data = new FormData();
                data.append('message', $("#questionMessageInput").val());
                data.append('type', $("#questionTypeInput").val());
                data.append('image_file', $('#questionImageUploadInput')[0].files[0]); 
                
                if(data.get("type") == "multiple_choice") {
                    let choices = [];
                    $("#choicesTable tbody tr").each(function( index ) {
                        choices[index] = {
                            choice: $(this).find(".choiceInput").val(),
                            correct: $(this).find(".choiceCorrectInput")[0].checked
                        };
                    });
                    data.append('choices', JSON.stringify(choices));
                    data.append('select_multiple', $("#selectMultipleInput")[0].checked ? 1 : 0);
                } else {
                    let trueCorrect = $("#trueCorrectInput")[0].checked;
                    let choices = [
                        {
                            choice: "True",
                            correct: trueCorrect
                        },
                        {
                            choice: "False",
                            correct: !trueCorrect
                        }
                    ];
                    data.append('choices', JSON.stringify(choices));
                    data.append('select_multiple', 0);
                }
                console.log(data);
                return data;
            }

            function addQuestion() {
                $.ajax({
                    type:'POST',
                    url:"{{ route('question.create', $quiz->id ?? '-1') }}",
                    data: getQuestionData(),
                    contentType: false,
                    processData: false,
                    success:function(data){
                        $("#questionsTable tbody").append(`
                            <tr questionId="${data.id}">
                                <td>${data.message}</td>
                                <td>${data.type}</td>
                                <td>
                                    <a type="button" class="btn btn-primary" onclick="openEditQuestion('${data.id}')">Edit</a>
                                    <a type="button" class="btn btn-danger" onclick="deleteQuestion('${data.id}')">Delete</a>
                                </td>
                            </tr>
                        `);
                        $("#questionModal").modal("hide");
                    }
                });
            }

            function deleteQuestion(id) {
                $.ajax({
                    type:'DELETE',
                    url:"{{ route('question.delete', ['id' => $quiz->id ?? '-1']) }}/" + id,
                    success:function(data){
                        $(`tr[questionId=${id}]`).remove();
                    }
                });
            }


            function saveQuestion() {
                let id = $("#editedQuestionId").val();
                $.ajax({
                    type:'POST',
                    url: "{{ route('question.update', ['id' => $quiz->id ?? '-1']) }}/" + id,
                    data: getQuestionData(),
                    contentType: false,
                    processData: false,
                    success:function(data){
                        $("#questionModal").modal("hide");
                    }
                });
            }

            function openEditQuestion(id) {
                $.ajax({
                    type:'GET',
                    url:"{{ route('question.get', ['id' => $quiz->id ?? '-1']) }}/" + id,
                    success:function(data){;
                        $("#editedQuestionId").val(id);
                        console.log(data);
                        openQuestionModal("edit", "/storage/question/" + data.id + "/" + data.image, data.message, data.type, data.choices, data.select_multiple);
                    }
                });
            }

            function openNewQuestion() {
                openQuestionModal("new");
            }

            function openQuestionModal(operation, image = "", message = "", type = "multiple_choice", extra1 = [], extra2 = false) {
                $('#questionModal').find('input[type=text]').val('');
                $('#questionModal').find('input[type=checkbox]').attr('checked', false);
                $('#questionModal').find('input[type=file]').val('');
                $('#questionModal').find('.questionImageContainer img').attr('src', '');
                
                $('.editQuestionForm').css('display', 'none');
                $('.newQuestionForm').css('display', 'none');
                $('.' + operation + 'QuestionForm').css('display', 'initial');

                $('.questionImageContainer img')[0].src = image;
                $("#questionMessageInput").val(message);
                $("#questionTypeInput").val(type).change();
                if(type == "multiple_choice") {
                    $("#choicesTable tbody").html("");
                    console.log(extra1);
                    for(let i = 0; i < extra1.length; i++) {
                        addChoice(extra1[i].choice, extra1[i].correct);
                    }
                    $("#selectMultipleInput")[0].checked = extra2;
                } else if(type == "true_false") {
                    $("#trueCorrectInput")[0].checked = extra1[0].correct == "true";
                }

                $("#questionModal").modal("show");
            }

            function submitQuiz() {
                let data = {
                    name: $("#nameInput").val(),
                    description: $("#descriptionInput").val()
                };

                @if($isEditing == true)
                    data.questions_order = [];
                    $("#questionsTable tr").each(function(index) {
                        let questionId = $(this).attr("questionId");
                        data.questions_order.push(questionId);
                    });
                @endif

                $.ajax({
                    type:'POST',
                    url:"{{ route('quiz.update', ['id' => $quiz->id ?? 'new']) }}",
                    data: data,
                    success:function(data){
                        @if($isEditing == true)
                            window.location.href = "/quizzes"
                        @else
                            window.location.href = "/quizzes/" + data.id;
                        @endif
                    }
                });
            }
        </script>
    </x-slot>

    <div class="pt-4">
        <div class="mb-3">
            <label for="nameInput" class="form-label">Name</label>
            <input type="email" class="form-control" id="nameInput" value="{{$quiz->name ?? ""}}">
        </div>
        <div class="mb-3">
            <label for="descriptionInput" class="form-label">Description</label>
            <textarea class="form-control" id="descriptionInput" rows="3">{{$quiz->description ?? ""}}</textarea>
        </div>

        @if($isEditing == true) 
            <div class="mb-3">
                <label for="descriptionInput" class="form-label">Questions</label>
                <div class="mb-3 questionsTableContainer">
                    <div class="questionsTableToolBar">
                        <p id="dragQuestionHint">You can drag and reorder the questions.</p>
                        <button type="button" class="btn btn-success" id="addQuestionButton" onclick="openNewQuestion()">Add Question</button>
                    </div>
                    <table class="table table-striped" id="questionsTable">
                        <thead>
                            <tr>
                                <th scope="col">Question</th>
                                <th scope="col">Type</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quiz->questions ?? [] as $index => $question)
                                <tr questionId="{{$question->id}}">
                                    <td>{{$question->message}}</td>
                                    <td>{{$question->type}}</td>
                                    <td>
                                        <a type="button" class="btn btn-primary" onclick="openEditQuestion('{{$question->id}}')">Edit</a>
                                        <a type="button" class="btn btn-danger" onclick="deleteQuestion('{{$question->id}}')">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        <button type="submit" class="btn btn-primary mb-4" onclick="submitQuiz()">Submit</button>
    </div>

    <div class="modal fade" id="questionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title newQuestionForm" id="exampleModalLabel">New Question</h5>
                <h5 class="modal-title editQuestionForm" id="exampleModalLabel">Edit Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input id="editedQuestionId" style="display: none;" value="-1">
                <div class="mb-3">
                    <div class="questionImageContainer">
                        <img>
                        <button type="button" class="btn btn-primary" id="questionImageUploadButton">Upload...</button>
                    </div>
                    <input type="file" id="questionImageUploadInput" onchange="loadFile(event)" style="display:none"/> 
                </div>
                <div class="mb-3">
                    <label for="questionMessageInput" class="col-form-label">Question</label>
                    <input type="text" class="form-control" id="questionMessageInput">
                </div>
                <div class="mb-3">
                    <label for="questionTypeInput" class="col-form-label">Type</label>
                    <select class="form-select" aria-label="Default select example" id="questionTypeInput">
                        <option value="multiple_choice" selected>Multiple Choice</option>
                        <option value="true_false">True / False</option>
                    </select>
                </div>
                <div id="multipleChoiceSection">
                    <div class="mb-3">
                        <label for="descriptionInput" class="form-label">Choices</label>
                        <div class="choicesTableContainer">
                            <div class="questionsTableToolBar">
                                <p id="dragQuestionHint">You can drag and reorder the choices.</p>
                                <button type="button" class="btn btn-success" id="addChoiceButton" onclick="addChoice()">Add Choice</button>
                            </div>
                            <table class="table table-striped" id="choicesTable">
                                <thead>
                                    <tr>
                                        <th scope="col">Choice</th>
                                        <th scope="col">Correct</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="selectMultipleInput" checked>
                        <label class="form-check-label" for="selectMultipleInput">
                            Can select multiple choices.
                        </label>
                    </div>
                </div>
                <div class="mb-3" id="trueFalseSection" style="display: none;">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="trueCorrectInput" checked>
                        <label class="form-check-label" for="trueCorrectInput">
                            Answer is True
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success newQuestionForm" onclick="addQuestion()">Create</button>
                <button type="button" class="btn btn-success editQuestionForm" onclick="saveQuestion()">Save</button>
            </div>
            </div>
        </div>
    </div>
</x-app-layout>
