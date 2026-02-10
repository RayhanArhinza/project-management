@extends('layouts.app')

@section('content')

@php
    // Gunakan metode yang sama dengan controller untuk mendapatkan route auth
    $authPrefixes = [];
    foreach (Route::getRoutes() as $route) {
        if ($route->getName() &&
            (in_array('auth', $route->gatherMiddleware()) ||
             (isset($route->action['middleware']) &&
              (is_array($route->action['middleware']) &&
               in_array('auth', $route->action['middleware']))))) {
            $prefix = explode('.', $route->getName())[0];
            if (!in_array($prefix, $authPrefixes)) {
                $authPrefixes[] = $prefix;
            }
        }
    }
    sort($authPrefixes);
@endphp

<div class="mb-6 fade-in">
    <h2 class="text-2xl font-bold mb-1">Role Management</h2>
    <p class="text-gray-400">Manage your roles and their permissions.</p>
</div>

<!-- Roles Table -->
<div class="bg-white dark:bg-gray-900 p-6 mb-8 slide-in" style="animation-delay: 0.7s">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold">Roles List</h3>
        <button onclick="openModal('addRoleModal')" class="yellow-btn px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-plus mr-2"></i>Add New Role
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-gray-400 text-sm">
                    <th class="pb-3 font-medium">ID</th>
                    <th class="pb-3 font-medium">Name</th>
                    <th class="pb-3 font-medium">Permissions</th>
                    <th class="pb-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                <tr class="border-b border-gray-800">
                    <td class="py-4 pr-4">{{ $role->id }}</td>
                    <td class="py-4 pr-4">
                        <p class="font-medium">{{ $role->name }}</p>
                    </td>
                    <td class="py-4 pr-4">
                        @if(is_array($role->permissions) && count($role->permissions) > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($role->permissions as $perm)
                                    <span class="bg-gray-700 text-gray-300 px-2 py-1 rounded-md text-xs">{{ $perm }}</span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-gray-500">No permissions</span>
                        @endif
                    </td>
                    <td class="py-4">
                        <button onclick="openModal('viewRoleModal-{{ $role->id }}')" class="text-gray-400 hover:text-white mr-2" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="openModal('editRoleModal-{{ $role->id }}')" class="text-gray-400 hover:text-white mr-2" title="Edit Role">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="openModal('deleteRoleModal-{{ $role->id }}')" class="text-gray-400 hover:text-white" title="Delete Role">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add Role Modal -->
<div id="addRoleModal" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm transition-opacity duration-300 ease-in-out">
    <div class="bg-gray-900 rounded-xl shadow-lg w-full max-w-lg mx-auto transform scale-95 transition-transform duration-300 ease-in-out">
        <div class="p-6">
            <!-- Header -->
            <div class="flex justify-between items-center border-b border-gray-700 pb-4 mb-4">
                <h3 class="text-xl font-semibold text-white">Add New Role</h3>
                <button onclick="closeModal('addRoleModal')" class="text-gray-400 hover:text-red-400 transition duration-200">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Form -->
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-300 text-sm mb-2" for="roleName">Role Name</label>
                    <input type="text" name="name" id="roleName"
                           class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-yellow-500"
                           required>
                </div>

                <!-- Permissions -->
                <div class="mb-4">
                    <label class="block text-gray-300 text-sm mb-2">Permissions</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($authPrefixes as $prefix)
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" name="permissions[]" id="add_perm_{{ $prefix }}" value="{{ $prefix }}"
                                       class="w-5 h-5 text-yellow-500 bg-gray-800 border-gray-700 rounded focus:ring-yellow-400 focus:ring-2 transition duration-200">
                                <label for="add_perm_{{ $prefix }}" class="text-gray-300 text-sm cursor-pointer">
                                    {{ ucfirst($prefix) }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('addRoleModal')"
                            class="bg-gray-700 text-white px-5 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                            class="bg-yellow-500 text-gray-900 px-5 py-2 rounded-lg font-semibold hover:bg-yellow-400 transition duration-200">
                        Add Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Role Modal -->
@foreach($roles as $role)
    <div id="editRoleModal-{{ $role->id }}" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-2xl mx-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-white">Edit Role</h3>
                    <button onclick="closeModal('editRoleModal-{{ $role->id }}')" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('roles.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-300 text-sm mb-2" for="editRoleName-{{ $role->id }}">Role Name</label>
                        <input type="text" name="name" id="editRoleName-{{ $role->id }}"
                               value="{{ $role->name }}"
                               class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500"
                               required>
                    </div>
                    <!-- Permissions (menggunakan data authPrefixes) -->
                    <div class="mb-4">
                        <label class="block text-gray-300 text-sm mb-2">Permissions</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($authPrefixes as $prefix)
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox"
                                           name="permissions[]"
                                           id="edit_perm_{{ $role->id }}_{{ $prefix }}"
                                           value="{{ $prefix }}"
                                           @if(is_array($role->permissions) && in_array($prefix, $role->permissions))
                                               checked
                                           @endif
                                           class="w-5 h-5 text-yellow-500 bg-gray-800 border-gray-700 rounded focus:ring-yellow-400 focus:ring-2 transition duration-200">
                                    <label for="edit_perm_{{ $role->id }}_{{ $prefix }}" class="text-gray-300 text-sm cursor-pointer">
                                        {{ ucfirst($prefix) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="closeModal('editRoleModal-{{ $role->id }}')" class="bg-gray-700 text-white px-6 py-2 rounded-lg mr-2">Cancel</button>
                        <button type="submit" class="bg-yellow-500 text-gray-900 px-6 py-2 rounded-lg font-semibold hover:bg-yellow-400 transition duration-200">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- View Role Modal -->
@foreach($roles as $role)
    <div id="viewRoleModal-{{ $role->id }}" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-lg mx-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-white">View Role</h3>
                    <button onclick="closeModal('viewRoleModal-{{ $role->id }}')" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mb-4">
                    <h4 class="text-lg font-semibold text-white">Role Name:</h4>
                    <p class="text-gray-300">{{ $role->name }}</p>
                </div>
                <div class="mb-4">
                    <h4 class="text-lg font-semibold text-white">Permissions:</h4>
                    @if(is_array($role->permissions) && count($role->permissions) > 0)
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($role->permissions as $perm)
                                <span class="bg-gray-700 text-gray-300 px-2 py-1 rounded-md text-xs">{{ $perm }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 mt-2">No permissions assigned.</p>
                    @endif
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal('viewRoleModal-{{ $role->id }}')" class="bg-gray-700 text-white px-6 py-2 rounded-lg">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- Delete Role Modal -->
@foreach($roles as $role)
    <div id="deleteRoleModal-{{ $role->id }}" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-red-500">Delete Role</h3>
                    <button onclick="closeModal('deleteRoleModal-{{ $role->id }}')" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <p class="text-gray-300 mb-6">Are you sure you want to delete the role <strong>{{ $role->name }}</strong>? This action cannot be undone.</p>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('deleteRoleModal-{{ $role->id }}')" class="bg-gray-700 text-white px-5 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                        Cancel
                    </button>
                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-5 py-2 rounded-lg font-semibold hover:bg-red-600 transition duration-200">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection
