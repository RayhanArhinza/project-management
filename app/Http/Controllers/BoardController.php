<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskList;
use App\Models\Task;
use App\Models\Project;

class BoardController extends Controller
{
    public function index(Request $request)
    {
        $projectId = $request->query('project_id');

        // Jika belum memilih project, redirect ke project pertama
        if (!$projectId) {
            $firstProject = Project::first();
            if ($firstProject) {
                return redirect()->route('boards.index', ['project_id' => $firstProject->id]);
            }
        }

        // Ambil task list dan filter tasks sesuai project_id
        $taskLists = TaskList::with(['tasks' => function($query) use ($projectId) {
            $query->where('project_id', $projectId)
                  ->orderBy('position', 'asc');
        }])->orderBy('position', 'asc')->get();

        $projects = Project::all();

        // Jika request AJAX, kembalikan partial view board saja
        if ($request->ajax()) {
            return view('boards._board', compact('taskLists', 'projectId'));
        }

        return view('boards.index', compact('taskLists', 'projects', 'projectId'));
    }

    /**
     * Memperbarui posisi (list) sebuah task dengan drag & drop.
     */
    public function updateTaskPosition(Request $request)
    {
        $taskId = $request->taskId;
        $newListId = $request->listId;

        // Ambil task yang di-drag
        $task = Task::findOrFail($taskId);

        // Simpan list lama & project ID (agar bisa dipakai menghitung jumlah task)
        $oldListId = $task->task_list_id;
        $projectId = $task->project_id;

        // Update list_id task ke list baru
        $task->task_list_id = $newListId;
        $task->save();

        // Hanya hitung task yang berada di project yang sama
        $sourceListCount = Task::where('task_list_id', $oldListId)
                               ->where('project_id', $projectId)
                               ->count();

        $targetListCount = Task::where('task_list_id', $newListId)
                               ->where('project_id', $projectId)
                               ->count();

        return response()->json([
            'success'         => true,
            'message'         => 'Task position updated successfully',
            'sourceListId'    => $oldListId,
            'sourceListCount' => $sourceListCount,
            'targetListId'    => $newListId,
            'targetListCount' => $targetListCount
        ]);
    }

    /**
     * Memperbarui status sebuah task (open, in_progress, completed, blocked).
     */
    public function updateTaskStatus(Request $request)
    {
        $request->validate([
            'taskId' => 'required|exists:tasks,id',
            'status' => 'required|in:open,in_progress,completed,blocked'
        ]);

        try {
            $task = Task::findOrFail($request->taskId);
            $task->status = $request->status;
            $task->save();

            return response()->json([
                'success' => true,
                'message' => 'Task status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update task status: ' . $e->getMessage()
            ], 500);
        }
    }
}
