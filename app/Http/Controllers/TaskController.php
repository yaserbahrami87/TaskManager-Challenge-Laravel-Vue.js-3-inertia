<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;



class TaskController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $tasks = auth()->user()->tasks()->with('subtasks')->latest()->get();
        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks
        ]);

    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'title'         => 'required|string|max:100',
            'description'   => 'nullable|string|max:200',
            'status'        => 'required|in:todo,in_progress,done',
            'due_date'      => 'nullable|date',
        ]);
        $task = auth()->user()->tasks()->create($data);
//        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');

        return Inertia::render('Tasks/Index', [
            'tasks' => auth()->user()->tasks()->latest()->get(),
            'success' => 'تسک با  موفقیت ذخیره شد ']);
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);

    }

    public  function show(Task $task)
    {
        $tasks = auth()->user()->tasks()->with('subtasks')->latest()->get();
        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks
        ]);
    }

    public function update(Request $request, Task $task)
    {


        $this->authorize('update', $task);

        $data = $request->validate(
        [
            'title'         => 'required|string|max:100',
            'description'   => 'nullable|string|max:200',
            'status'        => 'required|in:todo,in_progress,done',
            'due_date'      => 'nullable|date',
        ]);


        $task->update($data);

        return Inertia::render('Tasks/Index', [
            'tasks' => auth()->user()->tasks()->latest()->get(),
            'success' => 'تسک با موفقیت ویرایش شد'
        ]);
//        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task) {
        $this->authorize('delete', $task);
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}
