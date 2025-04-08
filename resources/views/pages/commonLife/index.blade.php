<x-app-layout>
    <x-slot name="header">
        <h1 class="flex items-center gap-1 text-sm font-normal">
            <span class="text-gray-700">
                {{ __('Vie Commune') }}
            </span>
        </h1>
    </x-slot>
    <x-slot name="formAdmin">
        <form method="Post" action="{{ route('commonLifeAdmin.store') }}"></form>
        @csrf
        <x-forms.input label="Title" name="title" type="text"
                       :placeholder="__('Titre de la tache')"
                       :messages="$errors->get('title')"/>

        <x-forms.input label="Description" name="description" type="text"
                       :placeholder="__('Description de la tache')"
                       :messages="$errors->get('description')"/>
    </x-slot>
</x-app-layout>
