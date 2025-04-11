<x-app-layout>
    <x-slot name="header">
        <h1 class="flex items-center gap-1 text-sm font-normal">
            <span class="text-gray-700">
                {{ __('Projet engagement') }}
            </span>
        </h1>
    </x-slot>
    @admin
    <form action="">
        <x-forms.input label="Nom de l'engagement" name="titleEngagement" type="text" :placeholder="__('Nouveau titre')"
    </form>
    @endadmin
</x-app-layout>
