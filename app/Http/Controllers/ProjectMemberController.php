<?php

namespace App\Http\Controllers;

use App\Models\ProjectMember;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectMemberController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        $users = User::all();
        $projectMembers = ProjectMember::all();
        return view('project_members.index', compact('projectMembers', 'projects', 'users'));
    }

    public function create()
    {
        return view('project_members.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        foreach ($request->user_ids as $userId) {
            // Check if the member already exists to prevent duplicates
            if (!ProjectMember::where('project_id', $request->project_id)
                              ->where('user_id', $userId)
                              ->exists()) {
                ProjectMember::create([
                    'project_id' => $request->project_id,
                    'user_id' => $userId
                ]);
            }
        }

        return redirect()->route('project_members.index')
                         ->with('success', 'Project Members added successfully.');
    }

    public function edit(ProjectMember $projectMember)
    {
        $projects = Project::all();
        $users = User::all();
        return view('project_members.edit', compact('projectMember', 'projects', 'users'));
    }

    public function update(Request $request, ProjectMember $projectMember)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $projectMember->update($request->all());
        return redirect()->route('project_members.index')
                         ->with('success', 'Project Member updated successfully.');
    }

    public function destroy(ProjectMember $projectMember)
    {
        $projectMember->delete();
        return redirect()->route('project_members.index')
                         ->with('success', 'Project Member deleted successfully.');
    }
}
