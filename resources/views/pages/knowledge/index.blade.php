<x-app-layout>
    <x-slot name="header">
        <h1 class="flex items-center gap-1 text-sm font-normal">
            <span class="text-gray-700">
                {{ __('Bilans de connaissances') }}
            </span>
        </h1>
    </x-slot>

        <div class="knowledge">
            <div class="title">
                <h2>Création de bilan de connaissances</h2>
            </div>
            <div class="languageChoice">
                <form method="post" action="{{route('knowledge.store')}}">
                    @csrf

                    <x-forms.input label="Nom du questionnaire" name="knowledgeName" type="text" :placeholder="__('Merci de donner un nom clair et precis')"/>
                    <x-forms.input label="Nombre de question" name="knowledgeQuestionNumber" type="number" :placeholder="__('Merci de donner un nombre de question entre 5 et 25')"/>
                    <x-forms.input label="Nombre choix de réponse" name="knowledgeAnswerNumber" type="number" :placeholder="__('Merci de donner un nombre de question entre 2 et 5')"/>

                    <label for="difficultyForm">Choisissez la difficulté du questionnaire</label>
                    <select name="difficulty" id="difficultySelection">
                        <option value="intermediate">--Choisissez une difficulté--</option>
                        <option value="beginner">Debutant</option>
                        <option value="intermediate">Intermediaire</option>
                        <option value="advanced">Difficulté avancée</option>
                    </select>

                    @foreach($languages as $language)
                        <p>{{$language->name}}: <input type="checkbox" class="languageCheck" onclick="myFunction()"></p>
                        <p class="choiceComment" style="display:none">le language {{$language->name}} a été ajouté au questionnaire</p>
                    @endforeach

                    <x-forms.primary-button>
                        {{ __('Ajoutez') }}
                    </x-forms.primary-button>
                </form>
            </div>
        </div>

    @admin
    <h2>Ajoutez un language de programmation pour les questionnaires</h2>
    <form method="post" action="{{route ('knowledge-language.store')}}">
        @csrf

        <x-forms.input label="Nom du language" name="languageName" type="text" :placeholder="__('Merci de donner un nom clair et precis')"/>

        <label for="difficultyLanguageForm">Choisissez la difficulté du language</label>
        <select name="languageDifficulty" id="difficultyLanguageSelection">
            <option value="intermediate">--Choisissez une difficulté--</option>
            <option value="beginner">Debutant</option>
            <option value="intermediate">Intermediaire</option>
            <option value="advanced">Difficulté avancée</option>
        </select>

        <x-forms.primary-button>
            {{ __('Ajoutez') }}
        </x-forms.primary-button>
    </form>
    @endadmin
    <script src="{{ asset('js/knowledge.js') }}"></script>
</x-app-layout>

