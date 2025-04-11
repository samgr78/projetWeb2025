<?php

namespace App\Http\Controllers;

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
        return view('pages.knowledge.index', compact('languages'));
    }

    public function store(Request $request, GeminiService $geminiService) {
        $this->authorize('create', Knowledge::class);

        $request->validate([
           'knowledgeName' => 'required',
           'knowledgeQuestionNumber' => 'required|integer|min:5|max:25',
           'knowledgeAnswerNumber' => 'required|integer|min:2|max:5' ,
        ]);

        $knowledge=Knowledge::create([
            'name'=>$request->input('knowledgeName'),
            'question_number'=>$request->input('knowledgeQuestionNumber'),
            'answer_number'=>$request->input('knowledgeAnswerNumber'),
            'difficulty'=>$request->input('difficulty'),
        ]);

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . config('services.gemini.api_key');


        $prompt = "Fais-moi un questionnaire de {$knowledge->question_number} questions avec {$knowledge->answer_number} réponses,
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

        return redirect()->route('knowledge.index', [
            'languages' => Language::all(),
            $text
        ]);
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
