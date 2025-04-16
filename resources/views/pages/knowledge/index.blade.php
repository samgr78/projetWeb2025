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
    <script>
        window.authUserId = {{ auth()->id() }};
        window.csrfToken = '{{ csrf_token() }}';
    </script>

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
        </div>

        <button class="btn btn-primary open-modal" data-knowledge-id="{{ $knowledge->id }}">
            Show Modal
        </button>

    @endforeach

    <div id="dynamicModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-xl shadow-lg w-[90%] max-w-xl max-h-[80vh] overflow-y-auto p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold" id="modal-title">Chargement...</h3>
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

                // Affiche la modale
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
