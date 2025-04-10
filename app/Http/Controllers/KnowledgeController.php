<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class KnowledgeController extends Controller
{
    /**
     * Display the page
     *
     * @return Factory|View|Application|object
     */
    public function index() {
        return view('pages.knowledge.index');
    }

    public function languageStore(Request $request) {
        Language::create([
           'name'=>$request->input('languageName'),
            'difficulty'=>$request->input('languageDifficulty'),
        ]);
    }
}
