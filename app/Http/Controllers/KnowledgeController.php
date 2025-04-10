<?php

namespace App\Http\Controllers;

use App\Models\Knowledge;
use App\Models\Language;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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

    public function store(Request $request) {
        $this->authorize('create', Knowledge::class);
        $request->validate([
           'knowledgeName' => 'required',
           'knowledgeQuestionNumber' => 'required|integer|min:5|max:25',
           'knowledgeAnswerNumber' => 'required|integer|min:2|max:5' ,
        ]);
        Knowledge::create([
            'name'=>$request->input('knowledgeName'),
            'question_number'=>$request->input('knowledgeQuestionNumber'),
            'answer_number'=>$request->input('knowledgeAnswerNumber'),
            'difficulty'=>$request->input('difficulty'),
        ]);
        return redirect()->route('knowledge.index');
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
