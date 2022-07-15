<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Categories') }}
        </h2>
    </x-slot>

    <x-slot name="scripts">
        <script>
            function deleteQuiz(id) {
                $.ajax({
                    type:'DELETE',
                    url:"{{ route('ajax.quizzes.delete') }}/" + id,
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
            <a type="button" class="btn btn-success mb-4" href="/admin/categories/create">New Category</a>
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
                    @foreach($categories as $category)
                        <tr id="category-{{$category->id}}">
                            <td>{{$category->name}}</td>
                            <td>{{$category->name}}</td>
                            <td>{{$category->name}}</td>
                            <td>{{$category->name}}</td>
                            <td>
                                <a type="button" class="btn btn-primary" href="/admin/categories/{{$category->id}}">Edit</a>
                                <a type="button" class="btn btn-info" href="/quizzes/{{$category->id}}" target="_blank">View</a>
                                {{-- <a type="button" class="btn btn-info">Share</a> --}}
                                <a type="button" class="btn btn-danger" onclick="deleteCategory('{{$category->id}}')">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
