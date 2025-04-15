<?php

namespace App\Http\Controllers;

use App\Models\Cohort;
use App\Models\Knowledge;
use App\Models\Language;
use App\Services\GeminiService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use GeminiAPI\Gemini;
use GeminiAPI\Resources\Parts\TextPart;
use Illuminate\Support\Facades\Http;

class KnowledgeController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display the page
     *
     * @return Factory|View|Application|object
     */
    public function index() {
        $languages = Language::all();
        $cohorts = Cohort::all();
        return view('pages.knowledge.index', compact('languages', 'cohorts'));
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

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . config('services.gemini.api_key');

        $prompt = "Génère un questionnaire clair au format strict suivant :

            Format : Question: [texte] | Réponses: [réponse1, réponse2]

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

        if (!$text || !is_string($text)) {
            return back()->with('error', 'Erreur : aucune réponse générée par l’API Gemini.');
        }

        $questionsAndAnswers = $this->parseQuestionsAndAnswers($text);

        // save question and answiers in db
        foreach ($questionsAndAnswers as $questionData) {
            $question = $knowledge->questions()->create([
                'question' => $questionData['question'],
            ]);

            foreach ($questionData['answers'] as $answerText) {
                $question->answers()->create([
                    'answer' => $answerText,
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

        // Supprimer tout le contenu avant le vrai questionnaire (optionnel mais propre)
        if (str_contains($text, '**Questionnaire:**')) {
            $text = Str::after($text, '**Questionnaire:**');
        }

        // Fusionner les lignes pour éviter les coupures en plein milieu des réponses
        $text = preg_replace('/\n+/', "\n", $text); // Nettoyer les lignes vides
        $lines = explode("\n", $text);

        foreach ($lines as $line) {
            // Enlever les numéros, espaces, markdown
            $cleanLine = preg_replace('/^\s*\d+\.\s*/', '', $line);
            $cleanLine = strip_tags(trim($cleanLine));

            if (preg_match('/Question\s*:\s*(.*?)\s*\|\s*Réponses\s*:\s*\[(.*?)\]/i', $cleanLine, $matches)) {
                $questionText = trim($matches[1]);

                // Nettoie les réponses
                $rawAnswers = explode(',', $matches[2]);
                $answers = array_map('trim', $rawAnswers);

                if (!empty($questionText) && count($answers) > 0) {
                    $questions[] = [
                        'question' => $questionText,
                        'answers' => $answers,
                    ];
                }
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

}
