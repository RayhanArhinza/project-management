<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Menampilkan daftar tiket untuk user yang sedang login beserta filter.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $query = Task::where('user_id', $userId);

        // Filter berdasarkan project jika ada parameter 'project_id'
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->input('project_id'));
        }

        // Filter berdasarkan status jika ada parameter 'status'
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter berdasarkan priority jika ada parameter 'priority'
        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        $tickets = $query->get();
        $projects = Project::all();

        return view('tickets.index', compact('tickets', 'projects'));
    }

    /**
     * Mengubah status tiket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,completed',
        ]);

        $userId = Auth::id();

        $ticket = Task::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        $ticket->status = $request->input('status');
        $ticket->save();

        return redirect()->back()->with('success', 'Status tiket berhasil diperbarui.');
    }
}
