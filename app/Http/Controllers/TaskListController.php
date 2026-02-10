<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use Illuminate\Http\Request;

class TaskListController extends Controller
{
    public function index()
    {
        $taskLists = TaskList::all();
        return view('tasklists.index', compact('taskLists'));
    }

    public function create()
    {
        return view('tasklists.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|integer',
        ]);

        TaskList::create($request->all());
        return redirect()->route('tasklists.index')->with('success', 'Task list created successfully.');
    }

    public function edit(TaskList $taskList)
    {
        return view('tasklists.edit', compact('taskList'));
    }

    public function update(Request $request, TaskList $taskList)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|integer',
        ]);

        $taskList->update($request->all());
        return redirect()->route('tasklists.index')->with('success', 'Task list updated successfully.');
    }

    public function destroy(TaskList $taskList)
    {
        $taskList->delete();
        return redirect()->route('tasklists.index')->with('success', 'Task list deleted successfully.');
    }
}
