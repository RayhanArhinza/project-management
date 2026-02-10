<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectMemberController;
use App\Http\Controllers\TaskListController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\admin\MembershipController;
use App\Http\Controllers\admin\MemberController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\TransactionController;
use App\Http\Controllers\admin\AdminAuthController;

use App\Http\Controllers\member\MemberAuthController;
use App\Http\Controllers\member\RoleController;
use App\Http\Controllers\member\DashboardController as MemberDashboardController;
use App\Http\Controllers\member\UserController as MemberUserController;




Route::get('/', function () {
    return view('landingpage');
})->name('landingpage'); // Menambahkan nama route

// Routes untuk Authentication
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Admin authentication routes
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('auth.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('auth.login.submit');

Route::get('member/register', [MemberAuthController::class, 'showRegisterForm'])->name('member.auth.register');
Route::post('member/register', [MemberAuthController::class, 'register']);

Route::get('member/login', [MemberAuthController::class, 'showLoginForm'])->name('member.auth.login');
Route::post('member/login', [MemberAuthController::class, 'login']);

Route::post('member/logout', [MemberAuthController::class, 'logout'])->name('member.logout');


Route::middleware(['auth:member'])->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
    Route::post('/generate-snap-token', [DashboardController::class, 'generateSnapToken'])->name('member.generate-snap-token');
    Route::post('/purchase-membership', [DashboardController::class, 'purchaseMembership'])->name('purchase-membership');
    Route::post('/generate-snap-token', [DashboardController::class, 'generateSnapToken'])->name('generate-snap-token');
    Route::post('/handle-payment', [DashboardController::class, 'handlePayment'])->name('handle-payment');
    Route::get('/users', [MemberUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [MemberUserController::class, 'create'])->name('users.create');
    Route::post('/users', [MemberUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [MemberUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [MemberUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [MemberUserController::class, 'destroy'])->name('users.destroy');

        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/edit/{role}', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

});


// Admin routes protected by auth:admin middleware
Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
    // Dashboard for admin
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');

    // Membership routes
    Route::get('/memberships', [MembershipController::class, 'index'])->name('memberships.index');
    Route::get('/memberships/create', [MembershipController::class, 'create'])->name('memberships.create');
    Route::post('/memberships', [MembershipController::class, 'store'])->name('memberships.store');
    Route::get('/memberships/edit/{membership}', [MembershipController::class, 'edit'])->name('memberships.edit');
    Route::put('/memberships/{membership}', [MembershipController::class, 'update'])->name('memberships.update');
    Route::delete('/memberships/{membership}', [MembershipController::class, 'destroy'])->name('memberships.destroy');

    // Member routes
    Route::get('/members', [MemberController::class, 'index'])->name('members.index');
    Route::get('/members/create', [MemberController::class, 'create'])->name('members.create');
    Route::post('/members', [MemberController::class, 'store'])->name('members.store');
    Route::get('/members/{member}', [MemberController::class, 'show'])->name('members.show');
    Route::get('/members/edit/{member}', [MemberController::class, 'edit'])->name('members.edit');
    Route::put('/members/{member}', [MemberController::class, 'update'])->name('members.update');
    Route::delete('/members/{member}', [MemberController::class, 'destroy'])->name('members.destroy');

Route::get('/transactions', [TransactionController::class, 'index'])->name('admin.transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('admin.transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('admin.transactions.store');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('admin.transactions.show');
    Route::post('/transactions/{transaction}/approve', [TransactionController::class, 'approve'])->name('admin.transactions.approve');
    Route::post('/transactions/{transaction}/reject', [TransactionController::class, 'reject'])->name('admin.transactions.reject');

    // Admin management routes
    Route::get('/admins', [AdminController::class, 'index'])->name('admins.index');
    Route::get('/admins/create', [AdminController::class, 'create'])->name('admins.create');
    Route::post('/admins', [AdminController::class, 'store'])->name('admins.store');
    Route::get('/admins/{admin}', [AdminController::class, 'show'])->name('admins.show');
    Route::get('/admins/edit/{admin}', [AdminController::class, 'edit'])->name('admins.edit');
    Route::put('/admins/{admin}', [AdminController::class, 'update'])->name('admins.update');
    Route::delete('/admins/{admin}', [AdminController::class, 'destroy'])->name('admins.destroy');

    Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('auth.logout')->middleware('auth:admin');
});
    // Routes untuk Role

// Routes dengan middleware auth
Route::middleware(['auth', 'check_permission'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Routes untuk Notification
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');


    // Routes untuk Project
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/edit/{project}', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');


    // Routes untuk User
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/edit/{user}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Routes untuk Project Member
    Route::get('/project_members', [ProjectMemberController::class, 'index'])->name('project_members.index');
    Route::get('/project_members/create', [ProjectMemberController::class, 'create'])->name('project_members.create');
    Route::post('/project_members', [ProjectMemberController::class, 'store'])->name('project_members.store');
    Route::get('/project_members/edit/{projectMember}', [ProjectMemberController::class, 'edit'])->name('project_members.edit');
    Route::put('/project_members/{projectMember}', [ProjectMemberController::class, 'update'])->name('project_members.update');
    Route::delete('/project_members/{projectMember}', [ProjectMemberController::class, 'destroy'])->name('project_members.destroy');

    // Routes untuk Task List
    Route::get('/tasklists', [TaskListController::class, 'index'])->name('tasklists.index');
    Route::get('/tasklists/create', [TaskListController::class, 'create'])->name('tasklists.create');
    Route::post('/tasklists', [TaskListController::class, 'store'])->name('tasklists.store');
    Route::get('/tasklists/edit/{taskList}', [TaskListController::class, 'edit'])->name('tasklists.edit');
    Route::put('/tasklists/{taskList}', [TaskListController::class, 'update'])->name('tasklists.update');
    Route::delete('/tasklists/{taskList}', [TaskListController::class, 'destroy'])->name('tasklists.destroy');


    // Routes untuk Task
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/edit/{task}', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/edit/{ticket}', [TicketController::class, 'edit'])->name('tickets.edit');
    Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');
    Route::patch('/tickets/{ticket}/update-status', [TicketController::class, 'updateStatus'])->name('tickets.updateStatus');
    // Routes untuk Board dan Task Status
    Route::get('/boards', [BoardController::class, 'index'])->name('boards.index');
    Route::post('/tasks/update-position', [BoardController::class, 'updateTaskPosition'])->name('tasks.update-position');
    Route::post('/tasks/update-status', [BoardController::class, 'updateTaskStatus'])->name('tasks.update-status');
    Route::get('/boards/filter', [BoardController::class, 'filter'])->name('boards.filter');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
});
