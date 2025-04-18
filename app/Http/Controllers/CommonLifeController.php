<?php

namespace App\Http\Controllers;

use App\Models\Cohort;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommonLifeController extends Controller
{
    use AuthorizesRequests;
    public function index() {
        $userId= auth()->user()->id;
        $userCohortIds = auth()->user()->cohorts->pluck('id');
        // retrieves the tasks that are assigned to the user's promotion
        $task = Task::whereHas('cohort', function ($query) use ($userCohortIds) {
            $query->whereIn('cohorts.id', $userCohortIds);
        })->get();
        // allows you to recover the user's tasks that have already been done for their history
        $taskCompleteds=Task::where('completed', true ) ->where('user_id', $userId)->get();
        $cohorts=Cohort::all();
        return view('pages.commonLife.index', compact('task', 'taskCompleteds','cohorts' ));
    }
    public function store(Request $request) {
        $this->authorize('create', Task::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'cohortAffectation' => 'required|array',
            'cohortAffectation.*' => 'exists:cohorts,id',
        ]);
        $task=Task::Create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);
        // allows the pivot table to be filled automatically
        $task->cohort()->attach($validated['cohortAffectation']);
        return redirect()->route('common-life.index');
    }

    public function delete($id) {
        // policy
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

    // allows the user to validate a task and comment on it
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

    // allow not to load all the content of all the tasks and to do it only when the user presses the button
    public function getTaskModal(Request $request)
    {
        $taskId = $request->input('taskId');
        $task = Task::findOrFail($taskId);

        return response()->json([
            'html' => view('partials.task-modal-content', compact('task'))->render()
        ]);
    }
}
