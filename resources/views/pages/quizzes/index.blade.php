<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quizzes') }}
        </h2>
    </x-slot>

    <x-slot name="scripts">
        <script>
            function deleteQuiz(id) {
                $.ajax({
                    type:'DELETE',
                    url:"{{ route('quizzes.delete') }}/" + id,
                    success:function(data){
                        $("#quiz-" + id).remove();
                        toastr.error('Quiz has been deleted.');
                    },
                    error:function(e) {
                        toastr.error('Please refresh and try again.', "Something went wrong");
                        console.error(e);
                    }
                });
            }
        </script>
    </x-slot>

    <div class="pt-4">
        <div class="quizList pb-5">
            <a type="button" class="btn btn-success mb-4" href="/admin/quizzes/create">New Quiz</a>
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Questions</th>
                        <th scope="col">Active Sessions</th>
                        <th scope="col">Completed Sessions</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quizzes as $quiz)
                        <tr id="quiz-{{$quiz->id}}">
                            <td>{{$quiz->name}}</td>
                            <td>{{$quiz->questions->count()}}</td>
                            <td>{{$quiz->sessions->where("status", "in progress")->count()}}</td>
                            <td>{{$quiz->sessions->where("status", "complete")->count()}}</td>
                            <td>
                                <a type="button" class="btn btn-primary" href="/admin/quizzes/{{$quiz->id}}">Edit</a>
                                <a type="button" class="btn btn-info" href="/quizzes/{{$quiz->id}}/start?newSession" target="_blank">Take</a>
                                {{-- <a type="button" class="btn btn-info">Share</a> --}}
                                <a type="button" class="btn btn-danger" onclick="deleteQuiz('{{$quiz->id}}')">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
