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
            <x-forms.input label="Nom de l'engagement" name="engagementName" type="text" :placeholder="__('Merci de donner un nom clair et précis')"/>
            <x-forms.input label="Description de l'engagement" name="engagementDescription" type="text" :placeholder="__('Merci de donner une description avec: la date; le lieux, etc')"/>
            <x-forms.input label="Date de l'évènement" name="engagementDate" type="datetime"/>
            <x-forms.input label="Entrez le nombre d'étudiant nécessaire pour cette mission" name="engagementStudentNumber" type="number"/>
            <x-forms.input label="Entrez le lieu de l'évènement" name="engagementPlace" type="text"/>

            <x-forms.primary-button>
                {{ __('Enregistrer') }}
            </x-forms.primary-button>
        </form>
    @endadmin
</x-app-layout>
