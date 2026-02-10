<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
{
    $query = Task::query();

    // Filter berdasarkan project
    if ($request->filled('project_id')) {
        $query->where('project_id', $request->project_id);
    }

    // Filter berdasarkan user
    if ($request->filled('user_id')) {
        $query->where('user_id', $request->user_id);
    }

    // Filter berdasarkan priority
    if ($request->filled('priority')) {
        $query->where('priority', $request->priority);
    }

    // Filter berdasarkan status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $tasks = $query->get();
    $projects = Project::all();
    $taskLists = TaskList::all();
    $users = User::all();

    return view('tasks.index', compact('tasks', 'projects', 'taskLists', 'users'));
}


    public function create()
    {
        $projects = Project::all();
        $taskLists = TaskList::all();
        $users = User::all();
        return view('tasks.create', compact('projects', 'taskLists', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id'     => 'required|exists:projects,id',
            'task_list_id'   => 'required|exists:task_lists,id',
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'user_id'        => 'required|exists:users,id',
            'due_date'       => 'nullable|date',
            'priority'       => 'required|in:low,medium,high',
            'status'         => 'required|in:open,in_progress,completed',
        ]);

        // Karena model Task sudah memiliki boot method untuk generate ID custom,
        // kita cukup membuat record baru tanpa harus melakukan generate ID di sini.
        Task::create($request->all());

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function edit(Task $task)
    {
        $projects = Project::all();
        $taskLists = TaskList::all();
        $users = User::all();
        return view('tasks.edit', compact('task', 'projects', 'taskLists', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'project_id'     => 'required|exists:projects,id',
            'task_list_id'   => 'required|exists:task_lists,id',
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'user_id'        => 'required|exists:users,id',
            'due_date'       => 'nullable|date',
            'priority'       => 'required|in:low,medium,high',
            'status'         => 'required|in:open,in_progress,completed',
        ]);

        $task->update($request->all());

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}
