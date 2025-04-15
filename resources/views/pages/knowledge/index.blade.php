<x-app-layout>
    <x-slot name="header">
        <h1 class="flex items-center gap-1 text-sm font-normal">
            <span class="text-gray-700">
                {{ __('Bilans de connaissances') }}
            </span>
        </h1>
    </x-slot>
    @teacher
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
                        <p>{{$language->name}}: <input type="checkbox" name="language_id[]" value="{{$language->id}}" class="languageCheck" onclick="myFunction(this)"></p>
                        <p class="choiceComment" style="display:none">le language {{$language->name}} a été ajouté au questionnaire</p>
                    @endforeach

                    <label for="cohortKnowledge">Affecter a une ou plusieurs promotion</label>
                    <select name="cohortAffectationKnowledge[]" multiple>
                        @foreach($cohorts as $cohort)
                            <option value="{{$cohort->id}}">{{$cohort->name}}</option>
                        @endforeach
                    </select>

                    <x-forms.primary-button>
                        {{ __('Ajoutez') }}
                    </x-forms.primary-button>
                </form>
                @if(isset($text))
                    <p>{{ $text }}</p>
                @endif

            </div>
        </div>
    @endteacher

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

    @student
    @foreach($knowledges as $knowledge)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    {{$knowledge->name}}
                </h3>
            </div>
            <div class="card-body">
                Nombre de questions: {{$knowledge->question_number}}
                Nombre de réponses: {{$knowledge->answer_number}}
                Difficulté: {{$knowledge->difficulty}}
{{--                Language évalué: {{$language}}--}}
            </div>
            <div class="card-footer justify-center">
                <button class="btn btn-primary" data-modal-toggle="#modal_3_1">
                    Show Modal
                </button>
            </div>
        </div>

        <div class="modal" data-modal="true" id="modal_3_1">
            <div class="modal-content modal-center-y max-w-[600px] max-h-[95%]">
                <div class="modal-header">
                    <h3 class="modal-title">
                        {{$knowledge->name}}
                    </h3>
                    <button class="btn btn-xs btn-icon btn-light" data-modal-dismiss="true">
                        <i class="ki-outline ki-cross">
                        </i>
                    </button>
                </div>
                <div class="modal-body scrollable-y py-0 my-5 pl-6 pr-3 mr-3">
                    <form action="{{route('usersAnswer.store')}}" method="post">
                        @csrf
                        <input type="hidden" name="userId" value="{{ auth()->user()->id }}">
                        <input type="hidden" name="knowledgeId" value="{{ $knowledge->id }}">
                        @foreach($questions->where('knowledge_id', $knowledge->id) as $question)
                            <p>{{ $question->question }}</p>
                            @foreach($answers->where('question_id', $question->id) as $answer)
                                <p>{{ $answer->answer }}:
                                    <input type="checkbox" name="answerKnowledge[]" value="{{ $answer->id }}" class="answerCheck">
                                </p>
                            @endforeach
                        @endforeach
                            <x-forms.primary-button>
                                {{ __('Terminer le qcm') }}
                            </x-forms.primary-button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
    @endstudent



    <script src="{{ asset('js/knowledge.js') }}"></script>
</x-app-layout>

