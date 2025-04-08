<?php

namespace App\Http\Controllers;

use App\Models\task;
use Illuminate\Http\Request;

class CommonLifeController extends Controller
{
    public function index() {
        $task = Task::all();
        return view('pages.commonLife.index', compact('task'));
    }
    public function store(Request $request) {
        $task=Task::Create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);
        return redirect()->route('common-life.index');
    }

    public function delete($id) {
        $task=Task::findOrFail($id);
        $task->delete();
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titleEdit' => 'required|string|max:255',
            'descriptionEdit' => 'required|string',
        ]);

        $task = Task::findOrFail($id);
        $task->title = $request->input('titleEdit');
        $task->description = $request->input('descriptionEdit');
        $task->save();

        return redirect()->route('common-life.index');
    }

}
