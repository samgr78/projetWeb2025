<x-app-layout>
    <x-slot name="header">
        <h1 class="flex items-center gap-1 text-sm font-normal">
            <span class="text-gray-700">
                {{ __('Vie Commune') }}
            </span>
        </h1>
    </x-slot>
{{--    <x-slot name="formAdmin">--}}
        <form method="POST" action="{{ route('commonLifeAdmin.store') }}">
            @csrf
            <x-forms.input label="Titre" name="title" type="text"
                           :placeholder="__('Titre de la tache')"
                           :messages="$errors->get('title')"/>

            <x-forms.input label="Description" name="description" type="text"
                           :placeholder="__('Description de la tache')"
                           :messages="$errors->get('description')"/>

            <x-forms.primary-button>
                {{ __('Enregister') }}
            </x-forms.primary-button>
        </form>

    <div class="tasksView">
        @foreach($task as $taskView)
            <div class="task">
                <p>{{$taskView->title}}</p>
                <p>{{$taskView->description}}</p>
                <form method="POST" action="{{route('commonLifeAdmin.delete', $taskView->id)}}">
                    @csrf
                    @method('Delete')
                    <x-forms.primary-button>
                        {{ __('Supprimer') }}
                    </x-forms.primary-button>
                </form>
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
            </div>
        @endforeach
    </div>
{{--    </x-slot>--}}
    <script src="{{ asset('js/dialog.js') }}"></script>
</x-app-layout>
