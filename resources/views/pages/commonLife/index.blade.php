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
            </div>
        @endforeach
    </div>
{{--    </x-slot>--}}
</x-app-layout>
