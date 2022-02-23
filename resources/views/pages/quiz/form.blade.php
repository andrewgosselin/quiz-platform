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

            function addChoice(choice = "", choice_correct = false) {
                $("#choicesTable tbody").append(`
                    <tr>
                        <td>
                            <input type="text" class="form-control choiceInput" value="${choice}">
                        </td>
                        <td>
                            <input class="form-check-input choiceCorrectInput" type="checkbox" ${choice_correct == "true" ? "checked" : ""}>
                        </td>
                        <td>
                            <a type="button" class="btn btn-danger" onclick="$(this).parent().parent().remove()">Delete</a>
                        </td>
                    </tr>
                `);
            }

            function getQuestionData() {
                let data = {
                    message: $("#questionMessageInput").val(),
                    type: $("#questionTypeInput").val()
                };
                if(data.type == "multiple_choice") {
                    data.choices = [];
                    $("#choicesTable tbody tr").each(function( index ) {
                        data.choices[index] = {
                            choice: $(this).find(".choiceInput").val(),
                            correct: $(this).find(".choiceCorrectInput")[0].checked
                        };
                    });
                    data.select_multiple = $("#selectMultipleInput")[0].checked ? 1 : 0;
                } else if(data.type == "true_false") {
                    let trueCorrect = $("#trueCorrectInput")[0].checked;
                    data.choices = [
                        {
                            choice: "True",
                            correct: trueCorrect
                        },
                        {
                            choice: "False",
                            correct: !trueCorrect
                        }
                    ];
                    data.select_multiple = 0;
                }
                return data;
            }

            function addQuestion() {
                $.ajax({
                    type:'POST',
                    url:"{{ route('question.create', $quiz->id ?? '-1') }}",
                    data: getQuestionData(),
                    success:function(data){
                        console.log(data);
                        $("#questionModal").modal("hide");
                    }
                });
            }

            function deleteQuestion(id) {
                $.ajax({
                    type:'DELETE',
                    url:"{{ route('question.delete', ['id' => $quiz->id ?? '-1']) }}/" + id,
                    success:function(data){
                    }
                });
            }


            function saveQuestion() {
                let id = $("#editedQuestionId").val();
                $.ajax({
                    type:'POST',
                    url: "{{ route('question.update', ['id' => $quiz->id ?? '-1']) }}/" + id,
                    data: getQuestionData(),
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
                        openQuestionModal("edit", data.message, data.type, data.choices, data.select_multiple);
                    }
                });
            }

            function openNewQuestion() {
                openQuestionModal("new");
            }

            function openQuestionModal(operation, message = "", type = "multiple_choice", extra1 = [], extra2 = false) {
                $('.editQuestionForm').css('display', 'none');
                $('.newQuestionForm').css('display', 'none');
                $('.' + operation + 'QuestionForm').css('display', 'initial');


                $("#questionMessageInput").val(message);
                $("#questionTypeInput").val(type).change();
                if(type == "multiple_choice") {
                    $("#choicesTable tbody").html("");
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
                    description: $("#descriptionInput").val(),
                    question_order: $("#questionOrderInput").val()
                };
                
                $.ajax({
                    type:'POST',
                    url:"{{ route('quiz.update', ['id' => $quiz->id ?? 'new']) }}",
                    data: data,
                    success:function(data){
                        @if($isEditing == true)
                            // Show toast
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
        
        <div class="mb-3" @if($isEditing !== true) style="display: none" @endif>
            <label for="questionOrderInput" class="form-label">Question Order</label>
            <select class="form-select" aria-label="Default select example" id="questionOrderInput">
                <option value="random" @if($quiz->question_order ?? "" == "random") selected @endif>Random</option>
                <option value="ordered" @if($quiz->question_order ?? "" == "ordered") selected @endif>Ordered</option>
            </select>
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
                                <tr>
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
        <button type="submit" class="btn btn-primary" onclick="submitQuiz()">Submit</button>
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
