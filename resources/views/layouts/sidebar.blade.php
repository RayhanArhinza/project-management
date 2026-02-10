@php
    // Pastikan pengguna sudah login dan ambil role-nya
    $role = auth()->check() ? auth()->user()->role : null;
@endphp

<div id="sidebar" class="sidebar flex flex-col h-screen overflow-hidden">
    <!-- Header Sidebar (Fixed) -->
    <div class="flex-shrink-0 flex items-center justify-between px-4 py-4 ">
        <div class="flex items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 rounded-md">
            <span id="logo-text" class="ml-3 font-bold text-xl text-white ">Catalyst</span>
        </div>
    </div>

    <!-- Kontainer untuk konten yang bisa di-scroll -->
    <div class="flex-1 overflow-y-auto overflow-x-hidden py-2 scroll-smooth" style="scroll-behavior: smooth;">
        <div class="px-2 pb-4">
            {{-- Group 1: Dashboard --}}
            @if($role && $role->hasPermissionByPrefix('dashboard'))
                <h3 class="sidebar-heading text-gray-500 uppercase font-semibold text-xs px-4 mb-2">Dashboard</h3>
                <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-1">
                    <i class="fas fa-home text-lg yellow-text w-6"></i>
                    <span class="sidebar-link-text ml-3">Dashboard</span>
                </a>
            @endif

            {{-- Group 2: Management --}}
            @if($role && $role->hasPermissionByPrefix('tasklists'))
                <h3 class="sidebar-heading text-gray-500 uppercase font-semibold text-xs px-4 mb-2 mt-6">Management</h3>
                <a href="{{ route('tasklists.index') }}" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-1">
                    <i class="fas fa-table-list text-lg yellow-text w-6"></i>
                    <span class="sidebar-link-text ml-3">List</span>
                </a>
            @endif

            @if($role && $role->hasPermissionByPrefix('projects'))
                <a href="{{ route('projects.index') }}" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-1">
                    <i class="fas fa-folder text-lg yellow-text w-6"></i>
                    <span class="sidebar-link-text ml-3">Project</span>
                </a>
            @endif

            @if($role && $role->hasPermissionByPrefix('project_members'))
                <a href="{{ route('project_members.index') }}" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-1">
                    <i class="fas fa-user-friends text-lg yellow-text w-6"></i>
                    <span class="sidebar-link-text ml-3">Project Members</span>
                </a>
            @endif

            @if($role && $role->hasPermissionByPrefix('tasks'))
                <a href="{{ route('tasks.index') }}" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-1">
                    <i class="fas fa-tasks text-lg yellow-text w-6"></i>
                    <span class="sidebar-link-text ml-3">Task</span>
                </a>
            @endif

            @if($role && $role->hasPermissionByPrefix('tickets'))
                <a href="{{ route('tickets.index') }}" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-1">
                    <i class="fas fa-ticket text-lg yellow-text w-6"></i>
                    <span class="sidebar-link-text ml-3">Ticket</span>
                </a>
            @endif

            @if($role && $role->hasPermissionByPrefix('boards'))
                <a href="{{ route('boards.index') }}" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-1">
                    <i class="fas fa-columns text-lg yellow-text w-6"></i>
                    <span class="sidebar-link-text ml-3">Board</span>
                </a>
            @endif

            {{-- Group 3: Administration --}}
            @if($role && $role->hasPermissionByPrefix('users'))
                <h3 class="sidebar-heading text-gray-500 uppercase font-semibold text-xs px-4 mb-2 mt-6">Administration</h3>
                <a href="{{ route('users.index') }}" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-1">
                    <i class="fas fa-user text-lg yellow-text w-6"></i>
                    <span class="sidebar-link-text ml-3">User</span>
                </a>
            @endif

            @if($role && $role->hasPermissionByPrefix('roles'))
                <a href="{{ route('roles.index') }}" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-1">
                    <i class="fas fa-user-cog text-lg yellow-text w-6"></i>
                    <span class="sidebar-link-text ml-3">Role</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Tombol Logout di bagian bawah (Fixed) -->
    <div class="flex-shrink-0 px-6 py-4">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-link flex items-center py-3 px-4 rounded-md w-full text-left">
                <i class="fas fa-sign-out-alt text-lg yellow-text w-6"></i>
                <span class="sidebar-link-text ml-3">Logout</span>
            </button>
        </form>
    </div>
</div>
