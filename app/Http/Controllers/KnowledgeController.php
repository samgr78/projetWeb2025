<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Cohort;
use App\Models\Cohorts_knowledge;
use App\Models\Knowledge;
use App\Models\KnowledgeUser;
use App\Models\Language;
use App\Models\Question;
use App\Models\UserAnswer;
use App\Services\GeminiService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use GeminiAPI\Gemini;
use GeminiAPI\Resources\Parts\TextPart;
use Illuminate\Support\Facades\Http;
use Psy\Util\Str;

class KnowledgeController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display the page
     *
     * @return Factory|View|Application|object
     */
    public function index() {
        $userId = auth()->id();
        $cohortId = auth()->user()->cohorts()->first()?->id;

        // we only recover the knowledge assigned to the promotion of the connected student who has not yet completed the questionnaire
        $knowledges = Knowledge::whereHas('cohorts', function ($query) use ($cohortId) {
            $query->where('cohort_id', $cohortId);
        })
            // if the knowledge of the promotion is associated with the student in the pivot table, it is because he has already completed the knowledge
            ->whereDoesntHave('knowledge_user', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();

        $knowledgeId = $knowledges->pluck('id');
        $questions = Question::whereIn('knowledge_id', $knowledgeId)->get();
        $questionId = $questions->pluck('id');
        $answers = Answer::whereIn('question_id', $questionId)->get();
        $languages = Language::all();
        $cohorts = Cohort::all();

        return view('pages.knowledge.index', compact('languages', 'cohorts', 'knowledges', 'questions', 'answers'));
    }


    public function store(Request $request, GeminiService $geminiService) {
        $this->authorize('create', Knowledge::class);

        $request->validate([
            'knowledgeName' => 'required',
            'knowledgeQuestionNumber' => 'required|integer|min:5|max:25',
            'knowledgeAnswerNumber' => 'required|integer|min:2|max:5',
        ]);

        $knowledge = Knowledge::create([
            'name' => $request->input('knowledgeName'),
            'question_number' => $request->input('knowledgeQuestionNumber'),
            'answer_number' => $request->input('knowledgeAnswerNumber'),
            'difficulty' => $request->input('difficulty'),
        ]);

        // allows you to assign several promotions to the knowledge
        $cohortIds = $request->input('cohortAffectationKnowledge', []);

        foreach ($cohortIds as $cohortId) {
            Cohorts_knowledge::create([
                'cohort_id' => $cohortId,
                'knowledge_id' => $knowledge->id,
            ]);
        }

        // using gemini api
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . config('services.gemini.api_key');

        $prompt = "Génère un questionnaire clair au format strict suivant :

            Format : Question: [texte] | Réponses: [réponse1, réponse2] | Bonne réponse

            Ne saute pas de ligne, une seule ligne par question, sans retour à la ligne dans les réponses.
            Évite les étoiles **, les titres, et toute mise en forme inutile.

            Fais-moi un questionnaire de {$knowledge->question_number} questions avec {$knowledge->answer_number} réponses,
            sur les langages de programmation suivants : " . $knowledge->languages->pluck('name')->join(', ') .
                        " avec une difficulté de : {$knowledge->difficulty}.";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ]);
        $text = $response->json('candidates.0.content.parts.0.text');

        // verification to avoid crashes
        if (!$text || !is_string($text)) {
            return back()->with('error', 'Erreur : aucune réponse générée par gemini.');
        }

        // will allow us to separate the question from the answers, and from the correct answer
        $questionsAndAnswers = $this->parseQuestionsAndAnswers($text);

        // save question and answiers in db
        foreach ($questionsAndAnswers as $questionData) {
            $question = $knowledge->questions()->create([
                'question' => $questionData['question'],
            ]);

            foreach ($questionData['answers'] as $answerData) {
                //dd($answerData);
                $question->answers()->create([
                    'answer' => $answerData['text'],
                    'is_correct' => $answerData['is_correct'] ? 1 : 0,
                ]);
            }
        }

        return redirect()->route('knowledge.index', [
            'languages' => Language::all(),
        ]);
    }

    protected function parseQuestionsAndAnswers(string $text): array
    {
        $questions = [];

        // Separate text line by line
        $lines = explode("\n", $text);

        foreach ($lines as $line) {
            // Clean the space line
            $line = trim($line);

            // allows you to check the format of the API response to separate everything
            if (preg_match('/^Question\s*:\s*(.*?)\s*\|\s*Réponses\s*:\s*\[(.*?)\]\s*\|\s*Bonne réponse\s*:\s*(.*)$/i', $line, $matches)) {
                $questionText = trim($matches[1]);
                $answersRaw = explode(',', $matches[2]);
                $correctAnswer = trim($matches[3]);

                $answers = array_map(function ($answer) use ($correctAnswer) {
                    $cleanedAnswer = trim($answer);
                    return [
                        'text' => $cleanedAnswer,
                        // checks if the response is the correct response and returns a boolean
                        'is_correct' => strcasecmp($cleanedAnswer, $correctAnswer) === 0,
                    ];
                }, $answersRaw);

                $questions[] = [
                    'question' => $questionText,
                    'answers' => $answers,
                ];
            }
        }

        return $questions;
    }




    public function languageStore(Request $request) {

        $this->authorize('createLanguage', Knowledge::class);

        Language::create([
           'name'=>$request->input('languageName'),
            'difficulty'=>$request->input('languageDifficulty'),
        ]);

        return redirect()->route('knowledge.index');
    }

    // retrieves and saves the user's responses to the questionnaire
    public function userAnswersStore(Request $request)
    {
        $userId = $request->input('userId');
        $answerIds = $request->input('answerKnowledge', []);

        foreach ($answerIds as $answerId) {
            UserAnswer::create([
                'user_id' => $userId,
                'answer_id' => $answerId,
            ]);}

        KnowledgeUser::create([
            'user_id' => $userId,
            'knowledge_id' => $request->input('knowledgeId'),
        ]);
        return redirect()->route('knowledge.index');
    }

    // allows you to inject the content of the questionnaire to avoid having to load all the content of all the questionnaires
    public function getKnowledgeQuestions(Request $request)
    {
        $knowledgeId = $request->input('knowledgeId');

        $questions = Question::where('knowledge_id', $knowledgeId)->get();
        $questionIds = $questions->pluck('id');
        $answers = Answer::whereIn('question_id', $questionIds)->get();

        $view = view('partials.knowledge-modal-content', compact('questions', 'answers'))->render();

        return response()->json(['html' => $view]);
    }
}
