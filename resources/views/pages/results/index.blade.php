<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Results') }}
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
                    }
                });
            }
        </script>
    </x-slot>

    <div class="pt-4">
        <div class="quizList pb-5">
            <table class="table table-striped datatable">
                <thead>
                    <tr>
                        <th scope="col">Quiz</th>
                        <th scope="col">Participant's Name</th>
                        <th scope="col">Participant's Email</th>
                        <th scope="col">Score</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sessions as $session)
                        <tr id="session-{{$session->session_id}}">
                            <td>{!! $session->quiz->name ?? "<span style='color: red;'>Quiz deleted</span>" !!}</td>
                            <td>{{$session->first_name}} {{$session->last_name}}</td>
                            <td>{{$session->email}}</td>
                            <td>{{$session->score["precentage"]}}% ({{$session->score["total"]}})</td>
                            <td>
                                @if($session->quiz)
                                    <a type="button" class="btn btn-primary"  href="/results/{{$session->session_id}}" target="_blank">View</a>
                                @else
                                    <button type="button" class="btn btn-primary" disabled>View</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
