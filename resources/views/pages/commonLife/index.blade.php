<x-app-layout>
    <x-slot name="header">
        <h1 class="flex items-center gap-1 text-sm font-normal">
            <span class="text-gray-700">
                {{ __('Vie Commune') }}
            </span>
        </h1>
    </x-slot>
{{--    <x-slot name="formAdmin">--}}

    @admin
        <form method="POST" action="{{ route('commonLifeAdmin.store') }}">
            @csrf
            <x-forms.input label="Titre" name="title" type="text"
                           :placeholder="__('Titre de la tache')"
                           :messages="$errors->get('title')"/>

            <x-forms.input label="Description" name="description" type="text"
                           :placeholder="__('Description de la tache')"
                           :messages="$errors->get('description')"/>

            <label for="cohort">Affecter a une ou plusieurs promotion</label>
            <select name="cohortAffectation[]" multiple>
                @foreach($cohorts as $cohort)
                    <option value="{{$cohort->id}}">{{$cohort->name}}</option>
                @endforeach
            </select>

            <x-forms.primary-button>
                {{ __('Enregister') }}
            </x-forms.primary-button>
        </form>
    @endadmin
    <div class="tasksView">
        @foreach($task as $taskView)
            <div class="task">
                <p>{{$taskView->title}}</p>
                <p>{{$taskView->description}}</p>
                @can('delete', $taskView)
                <form method="POST" action="{{route('commonLifeAdmin.delete', $taskView->id)}}">
                    @csrf
                    @method('Delete')
                    <x-forms.primary-button>
                        {{ __('Supprimer') }}
                    </x-forms.primary-button>
                </form>
                @endcan
                @can('update', $taskView)
                <button type="button"
                        class="open-dialog-btn ml-2 bg-blue-500 hover:bg-blue-600 text-white text-sm px-4 py-2 rounded"
                        data-dialog-id="edit-dialog-{{ $taskView->id }}">
                    Modifier
                </button>

                <dialog id="edit-dialog-{{ $taskView->id }}"
                        class="dialogTask rounded-xl p-6 shadow-xl w-[400px] max-w-full backdrop:bg-black/30">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold">Modifier la tâche</h2>
                        <button type="button"
                                class="close-dialog-btn text-gray-500 hover:text-gray-700 text-xl font-bold"
                                data-dialog-id="edit-dialog-{{ $taskView->id }}">
                            &times;
                        </button>
                    </div>

                    <form class="formUpdate" method="POST" action="{{ route('commonLifeAdmin.update', $taskView->id) }}">
                        @csrf
                        @method('PUT')
                        <x-forms.input label="Titre" name="titleEdit"
                                       :value="$taskView->title" type="text" :placeholder="__('Nouveau titre')"
                                       :messages="$errors->get('titleEdit')"/>

                        <x-forms.input label="Description" name="descriptionEdit"
                                       :value="$taskView->description" type="text" :placeholder="__('Nouvelle description')"
                                       :messages="$errors->get('descriptionEdit')"/>

                        <x-forms.primary-button>
                            Enregistrer
                        </x-forms.primary-button>
                    </form>
                </dialog>
                @endcan
                @student
                @if($taskView->completed === 0)
                    <button class="btn btn-primary" data-modal-toggle="#modal_1">
                        Tache accomplie
                    </button>
                @endif
                <div class="modal" data-modal="true" id="modal_1">
                    <div class="modal-content max-w-[600px] top-[20%]">
                        <div class="modal-header">
                            <h3 class="modal-title">
                                {{$taskView->title}}
                            </h3>
                            <button class="btn btn-xs btn-icon btn-light" data-modal-dismiss="true">
                                <i class="ki-outline ki-cross">
                                </i>
                            </button>
                        </div>
                        <div class="modal-body">
                            {{$taskView->description}}
                        </div>
                        <form method="POST" action="{{route ('commonLifeCheckStudent.check', $taskView->id)}}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="idStudent" value="{{auth()->user()->id}}">
                            <input type="hidden" name="isCompleted" value="1">
                            <x-forms.input data-modal-autofocus="true" label="Commentaire" name="studentCommentTask" type="text" :placeholder="__('Facultatif')"/>
                            <x-forms.input data-modal-autofocus="true" label="Date de la finalisation de la tache" name="studentDateTask" type="date"/>

                            <x-forms.primary-button>
                                Enregistrer
                            </x-forms.primary-button>

                        </form>
                    </div>
                </div>
                @endstudent
            </div>
        @endforeach
    </div>

    @student
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
{{--    </x-slot>--}}
    <script src="{{ asset('js/dialog.js') }}"></script>
</x-app-layout>
