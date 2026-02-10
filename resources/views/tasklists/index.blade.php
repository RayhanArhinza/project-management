@extends('layouts.app')
@section('content')

<div class="mb-6 fade-in">
    <h2 class="text-2xl font-bold mb-1">List Management</h2>
    <p class="text-gray-400">Manage your  lists.</p>
</div>

<!-- Task Lists Table -->
<div class="bg-white dark:bg-gray-900 p-6 mb-8 slide-in" style="animation-delay: 0.7s">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold">Lists</h3>
        <!-- Tombol untuk memunculkan modal add list -->
        <button onclick="openModal('addTaskListModal')" class="yellow-btn px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-plus mr-2"></i>Add New List
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-gray-400 text-sm">
                    <th class="pb-3 font-medium">ID</th>
                    <th class="pb-3 font-medium">Name</th>
                    <th class="pb-3 font-medium">Position</th>
                    <th class="pb-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($taskLists as $taskList)
                <tr class="border-b border-gray-800">
                    <td class="py-4 pr-4">{{ $taskList->id }}</td>
                    <td class="py-4 pr-4">
                        <p class="font-medium">{{ $taskList->name }}</p>
                    </td>
                    <td class="py-4 pr-4">
                        <p class="font-medium">{{ $taskList->position }}</p>
                    </td>
                    <td class="py-4">
                        <button onclick="openModal('viewTaskListModal-{{ $taskList->id }}')" class="text-gray-400 hover:text-white mr-2" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="openModal('editTaskListModal-{{ $taskList->id }}')" class="text-gray-400 hover:text-white mr-2" title="Edit Task List">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="openModal('deleteTaskListModal-{{ $taskList->id }}')" class="text-gray-400 hover:text-white" title="Delete Task List">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add Task List Modal -->
<div id="addTaskListModal" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Add New Task List</h3>
                <button onclick="closeModal('addTaskListModal')" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('tasklists.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2" for="taskListName">Task List Name</label>
                    <input type="text" name="name" id="taskListName" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2" for="taskListPosition">Position</label>
                    <input type="number" name="position" id="taskListPosition" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal('addTaskListModal')" class="bg-gray-700 text-white px-6 py-2 rounded-lg mr-2">Cancel</button>
                    <button type="submit" class="yellow-btn px-6 py-2 rounded-lg">Add Task List</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View, Edit, Delete Modals untuk tiap task list -->
@foreach($taskLists as $taskList)
    <!-- View Task List Modal -->
    <div id="viewTaskListModal-{{ $taskList->id }}" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Task List Details</h3>
                    <button onclick="closeModal('viewTaskListModal-{{ $taskList->id }}')" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mb-4">
                    <p class="text-gray-400 text-sm">ID</p>
                    <p class="font-medium">{{ $taskList->id }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-gray-400 text-sm">Name</p>
                    <p class="font-medium text-lg">{{ $taskList->name }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-gray-400 text-sm">Position</p>
                    <p class="font-medium">{{ $taskList->position }}</p>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal('viewTaskListModal-{{ $taskList->id }}')" class="bg-gray-700 text-white px-6 py-2 rounded-lg">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Task List Modal -->
    <div id="editTaskListModal-{{ $taskList->id }}" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Edit Task List</h3>
                    <button onclick="closeModal('editTaskListModal-{{ $taskList->id }}')" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('tasklists.update', $taskList->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm mb-2" for="editTaskListName-{{ $taskList->id }}">Task List Name</label>
                        <input type="text" name="name" id="editTaskListName-{{ $taskList->id }}" value="{{ $taskList->name }}" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm mb-2" for="editTaskListPosition-{{ $taskList->id }}">Position</label>
                        <input type="number" name="position" id="editTaskListPosition-{{ $taskList->id }}" value="{{ $taskList->position }}" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="closeModal('editTaskListModal-{{ $taskList->id }}')" class="bg-gray-700 text-white px-6 py-2 rounded-lg mr-2">Cancel</button>
                        <button type="submit" class="yellow-btn px-6 py-2 rounded-lg">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Task List Modal -->
    <div id="deleteTaskListModal-{{ $taskList->id }}" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Delete Task List</h3>
                    <button onclick="closeModal('deleteTaskListModal-{{ $taskList->id }}')" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mb-6 text-center">
                    <div class="inline-flex items-center justify-center bg-red-900 text-red-300 w-16 h-16 rounded-full mb-6">
                        <i class="fas fa-exclamation-triangle text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Are you sure?</h3>
                    <p class="text-gray-400">You are about to delete the task list <strong>"{{ $taskList->name }}"</strong>. This action cannot be undone.</p>
                </div>
                <form action="{{ route('tasklists.destroy', $taskList->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end">
                        <button type="button" onclick="closeModal('deleteTaskListModal-{{ $taskList->id }}')" class="bg-gray-700 text-white px-6 py-2 rounded-lg mr-2">Cancel</button>
                        <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection
