<x-app-layout>
    <x-slot name="header">
        <h1 class="flex items-center gap-1 text-sm font-normal">
            <span class="text-gray-700">
                {{ __('Vie Commune') }}
            </span>
        </h1>
    </x-slot>

    {{-- @admin allows you to display content only to admins--}}
    @admin
    {{-- allows you to create a task and assign it to one or more promotions --}}
    <form method="POST" action="{{ route('commonLifeAdmin.store') }}" class="space-y-4">
        @csrf
        <x-forms.input label="Titre" name="title" type="text"
                       :placeholder="__('Titre de la tache')"
                       :messages="$errors->get('title')"/>

        <x-forms.input label="Description" name="description" type="text"
                       :placeholder="__('Description de la tache')"
                       :messages="$errors->get('description')"/>

        <label for="cohort" class="block text-sm font-medium text-gray-700">Affecter a une ou plusieurs promotion</label>
        <select name="cohortAffectation[]" multiple class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            @foreach($cohorts as $cohort)
                <option value="{{$cohort->id}}">{{$cohort->name}}</option>
            @endforeach
        </select>

        <x-forms.primary-button>
            {{ __('Enregister') }}
        </x-forms.primary-button>
    </form>
    @endadmin
    <div class="tasksView mt-8 space-y-4">
        @foreach($task as $taskView)
            <div class="task border border-gray-200 rounded-md p-4 shadow-sm">
                <p class="font-semibold">{{ $taskView->title }}</p>
                <p class="text-sm text-gray-700">{{ $taskView->description }}</p>
                @can('delete', $taskView)
                    <form method="POST" action="{{ route('commonLifeAdmin.delete', $taskView->id) }}" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <x-forms.primary-button>
                            {{ __('Supprimer') }}
                        </x-forms.primary-button>
                    </form>
                @endcan

                @can('update', $taskView)
                    <button type="button"
                            class="open-dialog-btn ml-2 bg-blue-500 hover:bg-blue-600 text-white text-sm px-4 py-2 rounded mt-2"
                            data-dialog-id="edit-dialog-{{ $taskView->id }}">
                        Modifier
                    </button>

                    <dialog id="edit-dialog-{{ $taskView->id }}"
                            class="dialogTask rounded-xl p-6 shadow-xl w-[400px] max-w-full backdrop:bg-black/30">
                    </dialog>
                @endcan

                @student
                @if($taskView->completed === 0)
                    <button class="btn btn-primary open-task-modal mt-2 bg-green-500 hover:bg-green-600 text-white text-sm px-4 py-2 rounded"
                            data-task-id="{{ $taskView->id }}">
                        Tâche accomplie
                    </button>
                @endif
                @endstudent
            </div>
        @endforeach
    </div>

    <div id="taskModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-xl shadow-lg w-[90%] max-w-xl max-h-[80vh] overflow-y-auto p-6 border border-gray-500">
            <div class="flex justify-between items-center mb-4 border border-b-gray-200">
                <h3 class="text-lg font-semibold p-2" id="task-modal-title">Chargement...</h3>
                <button class="text-gray-500 hover:text-gray-800 text-sm" onclick="closeTaskModal()">✖</button>
            </div>
            {{-- will display the content of the modal --}}
            <div id="task-modal-body" class="space-y-4 p-4">

            </div>
        </div>
    </div>


    @student
    {{-- show the task history --}}
    <div class="stainHistory mt-10">
        <h2 class="text-lg font-semibold mb-4">{{ __('Historique des tâches') }}</h2>

        <div class="grid gap-4">
            @foreach($taskCompleteds as $taskCompleted)
                <div class="cardTask bg-gray-50 p-4 rounded-lg shadow-sm">
                    <p class="font-semibold">{{ $taskCompleted->title }}</p>
                    <p class="text-sm text-gray-700">{{ $taskCompleted->description }}</p>
                    <p class="text-sm text-gray-600">Fait le : {{ $taskCompleted->date }}</p>
                    <p class="text-sm text-gray-600">Commentaire : {{ $taskCompleted->student_description }}</p>
                </div>
            @endforeach
        </div>
    </div>
    @endstudent
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.open-task-modal').on('click', function () {
                const taskId = $(this).data('task-id');

                $('#taskModal').removeClass('hidden');
                $('#task-modal-title').text('Chargement...');
                $('#task-modal-body').html('<p>Chargement du formulaire...</p>');

                $.ajax({
                    url: "{{ route('task.modal') }}",
                    type: "GET",
                    data: { taskId: taskId },
                    success: function (response) {
                        $('#task-modal-title').text("Tâche à compléter");
                        $('#task-modal-body').html(response.html);
                    },
                    error: function () {
                        $('#task-modal-body').html("<p>Erreur lors du chargement de la tâche</p>");
                    }
                });
            });
        });

        function closeTaskModal() {
            $('#taskModal').addClass('hidden');
        }
    </script>
</x-app-layout>
