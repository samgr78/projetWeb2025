<x-app-layout>
    <x-slot name="header">
        <h1 class="flex items-center gap-1 text-sm font-normal text-gray-800">
            <span class="text-gray-700">
                {{ __('Bilans de connaissances') }}
            </span>
        </h1>
    </x-slot>

    @teacher
    <div class="container flex justify-center">
        <div class="knowledge space-y-6 w-[60%]">
            <div class="title card-header">
                <h2 class="text-lg font-semibold text-gray-900">Création de bilan de connaissances</h2>
            </div>
            <div class="languageChoice p-2">
                {{-- create and save a knowledge --}}
                <form method="post" action="{{route('knowledge.store')}}" class="space-y-5 bg-white p-6 rounded-xl shadow-sm border border-dashed border-gray-300">
                    @csrf

                    <x-forms.input class="p-4" label="Nom du questionnaire" name="knowledgeName" type="text" :placeholder="__('Merci de donner un nom clair et precis')"/>
                    <x-forms.input class="p-4" label="Nombre de question" name="knowledgeQuestionNumber" type="number" :placeholder="__('Merci de donner un nombre de question entre 5 et 25')"/>
                    <x-forms.input class="p-4" label="Nombre choix de réponse" name="knowledgeAnswerNumber" type="number" :placeholder="__('Merci de donner un nombre de question entre 2 et 5')"/>

                    <label class="p-4" for="difficultyForm" class="block text-sm font-medium text-gray-700">Choisissez la difficulté du questionnaire</label>
                    <select class="p-4" name="difficulty" id="difficultySelection" class="form-select form-select-solid w-full mt-1">
                        <option value="intermediate">--Choisissez une difficulté--</option>
                        <option value="beginner">Debutant</option>
                        <option value="intermediate">Intermediaire</option>
                        <option value="advanced">Difficulté avancée</option>
                    </select>

                    {{-- show all available language --}}
                    <p class="p-4">Choisissez les languages évalués</p>
                    @foreach($languages as $language)
                        <p class="flex items-center space-x-2  text-gray-800 p-2 text-1.2xl">
                            <input type="checkbox" name="language_id[]" value="{{$language->id}}" class="form-check-input" onclick="myFunction(this)">
                            <span>{{$language->name}}</span>
                        </p>
                        <p class="choiceComment text-xs text-green-600 font-medium ml-6 hidden">le language {{$language->name}} a été ajouté au questionnaire</p>
                    @endforeach

                    {{-- show all available cohort --}}
                    <label for="cohortKnowledge" class="block text-sm font-medium text-gray-700 mt-4">Affecter a une ou plusieurs promotion</label>
                    <select name="cohortAffectationKnowledge[]" multiple class="form-multiselect form-multiselect-solid w-full mt-1">
                        @foreach($cohorts as $cohort)
                            <option value="{{$cohort->id}}">{{$cohort->name}}</option>
                        @endforeach
                    </select>

                    <x-forms.primary-button class="btn btn-primary mt-4">
                        {{ __('Ajoutez') }}
                    </x-forms.primary-button>
                </form>
                @if(isset($text))
                    <p class="text-sm text-green-600 mt-4">{{ $text }}</p>
                @endif
            </div>
        </div>
    </div>
    @endteacher

    @admin
    <div class="continer flex justify-center ">
    <div class="card  flex-col" style="width: 70%;">
        <div class="card-header">
            <h2 class="text-lg font-semibold mt-10 mb-4 text-gray-800">Ajoutez un language de programmation pour les questionnaires</h2>
        </div>
        <div class="card-body">
            <form method="post" action="{{route ('knowledge-language.store')}}" class="bg-white p-6 rounded-xl shadow-sm  space-y-4">
                @csrf

                <x-forms.input class="p-4" label="Nom du language" name="languageName" type="text" :placeholder="__('Merci de donner un nom clair et precis')"/>

                <label for="difficultyLanguageForm" class="block text-sm font-medium text-gray-700 p-2">Choisissez la difficulté du language</label>
                <select name="languageDifficulty" id="difficultyLanguageSelection" class="form-select form-select-solid w-full">
                    <option value="intermediate">--Choisissez une difficulté--</option>
                    <option value="beginner">Debutant</option>
                    <option value="intermediate">Intermediaire</option>
                    <option value="advanced">Difficulté avancée</option>
                </select>

                <x-forms.primary-button class="btn btn-primary w-full">
                    {{ __('Ajoutez') }}
                </x-forms.primary-button>
            </form>
        </div>
    </div>
    </div>
    {{-- admin content to add new language --}}
    @endadmin

    @student
    {{-- allows the injection of certain data which is not possible in a .js file --}}
    <script>
        window.authUserId = {{ auth()->id() }};
        window.csrfToken = '{{ csrf_token() }}';
    </script>

    <div class="grid gap-6 mt-10">
        @foreach($knowledges as $knowledge)
            <div class="card bg-white border border-gray-200 shadow-sm rounded-xl">
                <div class="card-header border-b px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-800">
                        {{$knowledge->name}}
                    </h3>
                </div>
                <div class="card-body px-6 py-4 text-sm text-gray-700 space-y-1">
                    <p>Nombre de questions: <span class="font-medium">{{ $knowledge->question_number }}</span></p>
                    <p>Nombre de réponses: <span class="font-medium">{{ $knowledge->answer_number }}</span></p>
                    <p>Difficulté: <span class="font-medium">{{ $knowledge->difficulty }}</span></p>
                </div>
            </div>

            <button class="btn btn-primary btn-sm btn-light-primary mt-2 w-fit open-modal w-[100px]" data-knowledge-id="{{ $knowledge->id }}">
                Commencer le QCM
            </button>
        @endforeach
    </div>

    <div id="dynamicModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-xl shadow-xl w-[90%] max-w-xl max-h-[80vh] overflow-y-auto p-6 border border-gray-500 p-2">
            <div class="flex justify-between card-header items-center mb-4 ">
                <h3 class="text-lg font-semibold text-gray-800" id="modal-title">Chargement...</h3>
                <button class="text-gray-500 hover:text-gray-800 text-sm" onclick="closeModal()">✖</button>
            </div>
            <div id="modal-body" class="space-y-4">

            </div>
        </div>
    </div>
    @endstudent

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.open-modal').on('click', function () {
                const knowledgeId = $(this).data('knowledge-id');

                $('#dynamicModal').removeClass('hidden');
                $('#modal-title').text('Chargement...');
                $('#modal-body').html('<p>Chargement des questions...</p>');

                $.ajax({
                    url: "{{ route('knowledge.questions') }}",
                    type: "GET",
                    data: { knowledgeId: knowledgeId },
                    success: function (response) {
                        $('#modal-title').text("QCM");
                        $('#modal-body').html(`
                        <form action="{{ route('usersAnswer.store') }}" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="userId" value="{{ auth()->user()->id }}">
                            <input type="hidden" name="knowledgeId" value="${knowledgeId}">
                            ${response.html}
                        </form>
                    `);
                    },
                    error: function () {
                        $('#modal-body').html("<p>Erreur lors du chargement.</p>");
                    }
                });
            });
        });

        function closeModal() {
            $('#dynamicModal').addClass('hidden');
        }
    </script>
</x-app-layout>
