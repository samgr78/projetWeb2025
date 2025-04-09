<?php

namespace App\Http\Controllers;

use App\Models\task;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommonLifeController extends Controller
{
    use AuthorizesRequests;
    public function index() {
        $task = Task::where('completed', false)->get();
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

    public function check(Request $request, $id) {
        $task = Task::findOrFail($id);
        $this->authorize('check', $task);
        $request->validate([
            'idStudent' => 'required',
            'studentDateTask'=>'required|date',
        ]);

        $task->user_id = $request->input('idStudent');
        $task->student_description = $request->input('studentCommentTask');
        $task->date=$request->input('studentDateTask');
        $task->completed = $request->has('isCompleted') && $request->input('isCompleted') == '1' ? 1 : 0;
        $task->save();

        return redirect()->route('common-life.index');
    }

}
