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

        .categoryImageContainer {
            width: 100%;
            height: 160px;
            border: 1px solid gray;
            background-color: lightgrey;
            position: relative;
            text-align: center;
        }
        .categoryImageContainer img {
            width: auto;
            height: 100%;
        }
        .categoryImageContainer button {
            position: absolute;
            top: 5px;
            left: 5px;
        }
    </style>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if($isEditing == true)
            {{ __('Edit Category - ' . $category->name) }}
            @else
            {{ __('New Category') }}
            @endif
        </h2>
    </x-slot>

    <x-slot name="scripts">
        <script>
            $( document ).ready(function() {
                $('#categoryImageUploadButton').click(function(){ 
                    $('#categoryImageUploadInput').trigger('click'); 
                });
            });

            function loadFile(event, image_selector) {
                var output = $(image_selector)[0];
                output.src = URL.createObjectURL(event.target.files[0]);
                output.onload = function() {
                    URL.revokeObjectURL(output.src) // free memory
                }
            }

            function submitCategory() {
                $("#submitCategoryButton").attr("disabled", true);
                toastr.info("Saving quiz...");
                var data = new FormData();
                data.append('name', $("#nameInput").val());
                data.append('image_file', $('#categoryImageUploadInput')[0].files[0]); 
                if($("#nameInput").val() == "") {
                    toastr.error("You must fill in the name.");
                    $("#submitCategoryButton").attr("disabled", false);
                    return;
                }
                $.ajax({
                    type:'POST',
                    url:"{{ route('ajax.categories.update', ['id' => $category->id ?? 'new']) }}",
                    data: data,
                    contentType: false,
                    processData: false,
                    success:function(data){
                        window.location.href = "/admin/categories";
                    },
                    error:function(e) {
                        toastr.error('Please refresh and try again.', "Something went wrong");
                        console.error(e);
                        $("#submitCategoryButton").attr("disabled", false);
                    }
                });
            }

        </script>
    </x-slot>

    <div class="pt-4">
        <div class="mb-3">
            <div class="categoryImageContainer">
                <img @if($isEditing == true) src="/storage/category/{{$category->id}}/{{$category->image ?? ''}}" @endif>
                <button type="button" class="btn btn-primary" id="categoryImageUploadButton">Upload...</button>
            </div>
            <input type="file" id="categoryImageUploadInput" onchange="loadFile(event, '.categoryImageContainer img')" style="display:none"/> 
        </div>
        <div class="mb-3">
            <label for="nameInput" class="form-label">Name</label>
            <input type="email" class="form-control" id="nameInput" value="{{$category->name ?? ""}}">
        </div>
        <button type="submit" class="btn btn-primary mb-4" onclick="submitCategory()" id="submitCategoryButton">Submit</button>
    </div>
</x-app-layout>
