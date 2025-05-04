<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;


class SubtaskController extends Controller
{
    public function store(Request $request, Task $task) {
        $data = $request->validate(['title' => 'required']);
        $subtask = $task->subtasks()->create($data);
        $this->updateTaskStatusIfNeeded($task);
        return back()->with('success', 'Subtask created.');
    }

    public function update(Request $request, Subtask $subtask) {
        $data = $request->validate(['title' => 'required', 'is_done' => 'required|boolean']);
        $subtask->update($data);
        $this->updateTaskStatusIfNeeded($subtask->task);
        return back()->with('success', 'Subtask updated.');
    }

    public function destroy(Subtask $subtask) {
        $task = $subtask->task;
        $subtask->delete();
        $this->updateTaskStatusIfNeeded($task);
        return back()->with('success', 'Subtask deleted.');
    }

    protected function updateTaskStatusIfNeeded(Task $task) {
        $allDone = $task->subtasks()->count() > 0 && $task->subtasks()->where('is_done', false)->count() === 0;
        if ($allDone) {
            $task->update(['status' => 'done']);
        } else if ($task->status === 'done') {
            $task->update(['status' => 'in_progress']);
        }
    }
}
