<?php

namespace App\Http\Controllers;

use App\Models\task;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommonLifeController extends Controller
{
    use AuthorizesRequests;
    public function index() {
        $task = Task::all();
        return view('pages.commonLife.index', compact('task'));
    }
    public function store(Request $request) {
        $this->authorize('create', Task::class);
        $task=Task::Create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);
        return redirect()->route('common-life.index');
    }

    public function delete($id) {
        $task=Task::findOrFail($id);
        $this->authorize('delete', $task);
        $task->delete();
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $this->authorize('update', $task);
        $request->validate([
            'titleEdit' => 'required|string|max:255',
            'descriptionEdit' => 'required|string',
        ]);

        $task->title = $request->input('titleEdit');
        $task->description = $request->input('descriptionEdit');
        $task->save();

        return redirect()->route('common-life.index');
    }

}
