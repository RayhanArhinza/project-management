<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\User;
use App\Models\Task;
use App\Models\Role;
use App\Models\TaskList;
use App\Models\ProjectMember;

class DashboardController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();

        // ------------------------------------------------
        // 1. Data umum (Projects, Users, Tasks, Roles)
        // ------------------------------------------------
        if ($currentUser->role_id == 2) {
            // Role Project Manager (misal role_id = 2)
            $totalProjects = Project::count();
            $totalUsers    = User::count();
            $totalTasks    = Task::count();
            $totalRoles    = Role::count();

            $latestTasks = Task::latest()->take(4)->get();

            $taskStatusCounts = Task::groupBy('status')
                ->select('status', DB::raw('count(*) as count'))
                ->pluck('count', 'status')
                ->toArray();

            $projects = Project::with('tasks')->get();
            $projectStatus = [];
            foreach ($projects as $project) {
                $completed  = $project->tasks->where('status', 'completed')->count();
                $inProgress = $project->tasks->where('status', 'in_progress')->count();
                $open       = $project->tasks->where('status', 'open')->count();

                $projectStatus[] = [
                    'name'        => $project->name,
                    'completed'   => $completed,
                    'in_progress' => $inProgress,
                    'open'        => $open,
                    'total'       => $project->tasks->count(),
                ];
            }

            $users = User::with('tasks')->get();
            $userStatusData = [];
            foreach ($users as $user) {
                $completed  = $user->tasks->where('status', 'completed')->count();
                $inProgress = $user->tasks->where('status', 'in_progress')->count();
                $open       = $user->tasks->where('status', 'open')->count();

                $userStatusData[] = [
                    'user'        => $user->name,
                    'completed'   => $completed,
                    'in_progress' => $inProgress,
                    'open'        => $open,
                ];
            }
        } else {
            // ------------------------------------------------
            // Jika user bukan Project Manager,
            // hanya data milik user yang login
            // ------------------------------------------------
            $userId = $currentUser->id;

            $totalTasks = Task::where('user_id', $userId)->count();
            $latestTasks = Task::where('user_id', $userId)->latest()->take(4)->get();

            $taskStatusCounts = Task::where('user_id', $userId)
                ->groupBy('status')
                ->select('status', DB::raw('count(*) as count'))
                ->pluck('count', 'status')
                ->toArray();

            $projects = Project::whereHas('tasks', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })->with(['tasks' => function($query) use ($userId) {
                $query->where('user_id', $userId);
            }])->get();

            $projectStatus = [];
            foreach ($projects as $project) {
                $completed  = $project->tasks->where('status', 'completed')->count();
                $inProgress = $project->tasks->where('status', 'in_progress')->count();
                $open       = $project->tasks->where('status', 'open')->count();

                $projectStatus[] = [
                    'name'        => $project->name,
                    'completed'   => $completed,
                    'in_progress' => $inProgress,
                    'open'        => $open,
                    'total'       => $project->tasks->count(),
                ];
            }

            $users = User::where('id', $userId)->with(['tasks' => function($query) use ($userId) {
                $query->where('user_id', $userId);
            }])->get();

            $userStatusData = [];
            foreach ($users as $user) {
                $completed  = $user->tasks->where('status', 'completed')->count();
                $inProgress = $user->tasks->where('status', 'in_progress')->count();
                $open       = $user->tasks->where('status', 'open')->count();

                $userStatusData[] = [
                    'user'        => $user->name,
                    'completed'   => $completed,
                    'in_progress' => $inProgress,
                    'open'        => $open,
                ];
            }

            // Data tambahan
            $totalProjects = Project::whereHas('tasks', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })->count();
            $totalUsers = User::where('id', $userId)->count(); // hanya 1 user
            $totalRoles = Role::where('id', $currentUser->role_id)->count();
        }

        // ------------------------------------------------
        // 2. Data Pie Chart: Jumlah user di tiap project
        // ------------------------------------------------
        $projectUserCountsRaw = ProjectMember::select('project_id', DB::raw('COUNT(DISTINCT user_id) as total_users'))
            ->groupBy('project_id')
            ->get();

        $pieProjectLabels = [];
        $pieProjectData   = [];

        foreach ($projectUserCountsRaw as $row) {
            $project = Project::find($row->project_id);
            $projectName = $project ? $project->name : 'Project #'.$row->project_id;

            $pieProjectLabels[] = $projectName;
            $pieProjectData[]   = $row->total_users;
        }

        // ------------------------------------------------
        // 3. Data Grafik TaskList (opsional)
        // ------------------------------------------------
        $allTaskLists = TaskList::all()->keyBy('id');
        $allProjects  = Project::with('tasks')->get();

        $projectTaskListData = [];
        foreach ($allProjects as $project) {
            $taskListCounts = [];
            // Inisialisasi 0
            foreach ($allTaskLists as $tlId => $tlObj) {
                $taskListCounts[$tlId] = 0;
            }

            foreach ($project->tasks as $task) {
                if ($task->task_list_id && isset($taskListCounts[$task->task_list_id])) {
                    $taskListCounts[$task->task_list_id]++;
                }
            }

            $labels = [];
            $counts = [];
            foreach ($taskListCounts as $taskListId => $count) {
                $taskListName = isset($allTaskLists[$taskListId])
                    ? $allTaskLists[$taskListId]->name
                    : 'Unknown';
                $labels[] = $taskListName;
                $counts[] = $count;
            }

            $projectTaskListData[] = [
                'project_name' => $project->name,
                'labels'       => $labels,
                'counts'       => $counts,
            ];
        }

        // ------------------------------------------------
        // 4. Donut Chart: Jumlah user pada setiap role
        // ------------------------------------------------
        // Kita ambil semua role dan hitung jumlah user di setiap role
        $roles = Role::withCount('users')->get();

        // Siapkan label & data untuk donut chart
        // misalnya: label = nama role, data = berapa user
        $donutRoleLabels = $roles->pluck('name');         // ["Admin", "Project Manager", ...]
        $donutRoleData   = $roles->pluck('users_count');  // [10, 5, ...]

        // ------------------------------------------------
        // 5. Return view
        // ------------------------------------------------
        return view('dashboard', compact(
            'totalProjects',
            'totalUsers',
            'totalTasks',
            'totalRoles',
            'latestTasks',
            'taskStatusCounts',
            'projectStatus',
            'userStatusData',
            'projectTaskListData',
            'pieProjectLabels',
            'pieProjectData',
            // Donut chart
            'donutRoleLabels',
            'donutRoleData'
        ));
    }
}
