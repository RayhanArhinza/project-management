@extends('layouts.app')
@section('content')

<div class="mb-6 fade-in">
    <h2 class="text-2xl font-bold mb-1">Task Management</h2>
    <p class="text-gray-400">Manage your tasks.</p>
</div>
<!-- Filter Form -->
<div class="bg-white dark:bg-gray-900 shadow-xl rounded-2xl p-6 mb-8 transform transition-all duration-300 hover:scale-[1.01] hover:shadow-2xl">
    <form action="{{ route('tasks.index') }}" method="GET" class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div>
            <label for="project_id" class="block text-sm font-medium text-gray-300 mb-2 tracking-wider">
                <i class="mr-2 text-blue-400 fas fa-project-diagram"></i>Filter by Project
            </label>
            <select name="project_id" id="project_id" class="w-full bg-gray-800 border border-gray-700 text-gray-200 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-2.5 transition-all duration-200 hover:bg-gray-700">
                <option value="" class="bg-gray-800">-- All Projects --</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }} class="bg-gray-800">
                        {{ $project->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="user_id" class="block text-sm font-medium text-gray-300 mb-2 tracking-wider">
                <i class="mr-2 text-green-400 fas fa-user"></i>Filter by User
            </label>
            <select name="user_id" id="user_id" class="w-full bg-gray-800 border border-gray-700 text-gray-200 text-sm rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 block p-2.5 transition-all duration-200 hover:bg-gray-700">
                <option value="" class="bg-gray-800">-- All Users --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }} class="bg-gray-800">
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="priority" class="block text-sm font-medium text-gray-300 mb-2 tracking-wider">
                <i class="mr-2 text-yellow-400 fas fa-exclamation-triangle"></i>Filter by Priority
            </label>
            <select name="priority" id="priority" class="w-full bg-gray-800 border border-gray-700 text-gray-200 text-sm rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 block p-2.5 transition-all duration-200 hover:bg-gray-700">
                <option value="" class="bg-gray-800">-- All Priorities --</option>
                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }} class="bg-gray-800">Low</option>
                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }} class="bg-gray-800">Medium</option>
                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }} class="bg-gray-800">High</option>
            </select>
        </div>

        <div>
            <label for="status" class="block text-sm font-medium text-gray-300 mb-2 tracking-wider">
                <i class="mr-2 text-purple-400 fas fa-tasks"></i>Filter by Status
            </label>
            <select name="status" id="status" class="w-full bg-gray-800 border border-gray-700 text-gray-200 text-sm rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 block p-2.5 transition-all duration-200 hover:bg-gray-700">
                <option value="" class="bg-gray-800">-- All Statuses --</option>
                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }} class="bg-gray-800">Open</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }} class="bg-gray-800">In Progress</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }} class="bg-gray-800">Completed</option>
            </select>
        </div>

        <div class="col-span-full flex ">
            <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white font-bold py-2.5 px-6 rounded-lg shadow-md hover:from-blue-600 hover:to-purple-700  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                <i class="mr-2 fas fa-filter"></i>Filter
            </button>
        </div>
    </form>
</div>

<!-- Tasks Table -->
<div class="bg-white dark:bg-gray-900 p-6 mb-8 slide-in" style="animation-delay: 0.7s">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold">Tasks List</h3>
        <!-- Tombol untuk memunculkan modal add task -->
        <button onclick="openModal('addTaskModal')" class="yellow-btn px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-plus mr-2"></i>Add New Task
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-gray-400 text-sm">
                    <th class="pb-3 font-medium">ID</th>
                    <th class="pb-3 font-medium">Title</th>
                    <th class="pb-3 font-medium">Project</th>
                    <th class="pb-3 font-medium">Task List</th>
                    <th class="pb-3 font-medium">User</th>
                    <th class="pb-3 font-medium">Due Date</th>
                    <th class="pb-3 font-medium">Priority</th>
                    <th class="pb-3 font-medium">Status</th>
                    <th class="pb-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                <tr class="border-b border-gray-800">
                    <td class="py-4 pr-4">{{ $task->id }}</td>
                    <td class="py-4 pr-4">{{ $task->title }}</td>
                    <td class="py-4 pr-4">{{ $task->project->name ?? 'N/A' }}</td>
                    <td class="py-4 pr-4">{{ $task->taskList->name ?? 'N/A' }}</td>
                    <td class="py-4 pr-4">{{ $task->user->name ?? 'N/A' }}</td>
                    <td class="py-4 pr-4 ">
                        {{ $task->due_date ? $task->due_date->format('Y-m-d') : 'N/A' }}
                    </td>
                    <td class="py-4 pr-4">{{ ucfirst($task->priority) }}</td>
                    <td class="py-4 pr-4">
                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                    </td>
                    <td class="py-4">
                        <button
                            onclick="openModal('viewTaskModal-{{ $task->id }}')"
                            class="text-gray-400 hover:text-white mr-2"
                            title="View Details"
                        >
                            <i class="fas fa-eye"></i>
                        </button>
                        <button
                            onclick="openModal('editTaskModal-{{ $task->id }}')"
                            class="text-gray-400 hover:text-white mr-2"
                            title="Edit Task"
                        >
                            <i class="fas fa-edit"></i>
                        </button>
                        <button
                            onclick="openModal('deleteTaskModal-{{ $task->id }}')"
                            class="text-gray-400 hover:text-white"
                            title="Delete Task"
                        >
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add Task Modal -->
<div
    id="addTaskModal"
    class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm overflow-y-auto"

>
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <!--
            my-8 dan overflow-hidden:
            agar modal tidak menempel di atas/bawah & menghindari scrollbar ganda
        -->
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-lg mx-auto my-8 overflow-hidden">
            <!--
                max-h-[calc(100vh-8rem)] overflow-y-auto:
                agar isi modal bisa di-scroll
            -->
            <div class="p-6 max-h-[calc(100vh-8rem)] overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Add New Task</h3>
                    <button
                        onclick="closeModal('addTaskModal')"
                        class="text-gray-400 hover:text-white"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="project_id" class="block text-gray-400 text-sm mb-2">Project</label>
                        <select
                            name="project_id"
                            id="project_id"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500"
                            required
                        >
                            <option value="">Select Project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="task_list_id" class="block text-gray-400 text-sm mb-2">Task List</label>
                        <select
                            name="task_list_id"
                            id="task_list_id"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500"
                            required
                        >
                            <option value="">Select Task List</option>
                            @foreach($taskLists as $list)
                                <option value="{{ $list->id }}">{{ $list->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="title" class="block text-gray-400 text-sm mb-2">Title</label>
                        <input
                            type="text"
                            name="title"
                            id="title"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500"
                            required
                        >
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-gray-400 text-sm mb-2">Description</label>
                        <textarea
                            name="description"
                            id="description"
                            rows="3"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500"
                        ></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="user_id" class="block text-gray-400 text-sm mb-2">Assign User</label>
                        <select
                            name="user_id"
                            id="user_id"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500"
                            required
                        >
                            <option value="">Select User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="due_date" class="block text-gray-400 text-sm mb-2">Due Date</label>
                        <input
                            type="date"
                            name="due_date"
                            id="due_date"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500"
                        >
                    </div>
                    <div class="mb-4">
                        <label for="priority" class="block text-gray-400 text-sm mb-2">Priority</label>
                        <select
                            name="priority"
                            id="priority"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500"
                            required
                        >
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="status" class="block text-gray-400 text-sm mb-2">Status</label>
                        <select
                            name="status"
                            id="status"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500"
                            required
                        >
                            <option value="open">Open</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="flex justify-end">
                        <button
                            type="button"
                            onclick="closeModal('addTaskModal')"
                            class="bg-gray-700 text-white px-6 py-2 rounded-lg mr-2"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="yellow-btn px-6 py-2 rounded-lg"
                        >
                            Add Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Loop untuk setiap Task: View, Edit, Delete Modal -->
@foreach($tasks as $task)
    <!-- View Task Modal -->
    <div
        id="viewTaskModal-{{ $task->id }}"
        class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm overflow-y-auto"
    >
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-lg mx-auto my-8 overflow-hidden">
                <div class="p-6 max-h-[calc(100vh-8rem)] overflow-y-auto">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold">Task Details</h3>
                        <button
                            onclick="closeModal('viewTaskModal-{{ $task->id }}')"
                            class="text-gray-400 hover:text-white"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="mb-4">
                        <p class="text-gray-400 text-sm">Task ID</p>
                        <p class="font-medium">{{ $task->id }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-gray-400 text-sm">Title</p>
                        <p class="font-medium">{{ $task->title }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-gray-400 text-sm">Project</p>
                        <p class="font-medium">{{ $task->project->name ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-gray-400 text-sm">Task List</p>
                        <p class="font-medium">{{ $task->taskList->name ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-gray-400 text-sm">Assigned User</p>
                        <p class="font-medium">{{ $task->user->name ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-gray-400 text-sm">Due Date</p>
                        <p class="font-medium">
                            {{ $task->due_date ? $task->due_date->format('Y-m-d') : 'N/A' }}
                        </p>
                    </div>
                    <div class="mb-4">
                        <p class="text-gray-400 text-sm">Priority</p>
                        <p class="font-medium">{{ ucfirst($task->priority) }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-gray-400 text-sm">Status</p>
                        <p class="font-medium">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </p>
                    </div>
                    <div class="mb-4">
                        <p class="text-gray-400 text-sm">Description</p>
                        <p class="font-medium">
                            {{ $task->description ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="flex justify-end">
                        <button
                            type="button"
                            onclick="closeModal('viewTaskModal-{{ $task->id }}')"
                            class="bg-gray-700 text-white px-6 py-2 rounded-lg"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div
        id="editTaskModal-{{ $task->id }}"
        class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm overflow-y-auto"
    >
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-lg mx-auto my-8 overflow-hidden">
                <div class="p-6 max-h-[calc(100vh-8rem)] overflow-y-auto">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold">Edit Task</h3>
                        <button
                            onclick="closeModal('editTaskModal-{{ $task->id }}')"
                            class="text-gray-400 hover:text-white"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label
                                for="project_id_{{ $task->id }}"
                                class="block text-gray-400 text-sm mb-2"
                            >
                                Project
                            </label>
                            <select
                                name="project_id"
                                id="project_id_{{ $task->id }}"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500"
                                required
                            >
                                @foreach($projects as $project)
                                    <option
                                        value="{{ $project->id }}"
                                        {{ $project->id == $task->project_id ? 'selected' : '' }}
                                    >
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label
                                for="task_list_id_{{ $task->id }}"
                                class="block text-gray-400 text-sm mb-2"
                            >
                                Task List
                            </label>
                            <select
                                name="task_list_id"
                                id="task_list_id_{{ $task->id }}"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500"
                                required
                            >
                                @foreach($taskLists as $list)
                                    <option
                                        value="{{ $list->id }}"
                                        {{ $list->id == $task->task_list_id ? 'selected' : '' }}
                                    >
                                        {{ $list->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label
                                for="title_{{ $task->id }}"
                                class="block text-gray-400 text-sm mb-2"
                            >
                                Title
                            </label>
                            <input
                                type="text"
                                name="title"
                                id="title_{{ $task->id }}"
                                value="{{ $task->title }}"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500"
                                required
                            >
                        </div>
                        <div class="mb-4">
                            <label
                                for="description_{{ $task->id }}"
                                class="block text-gray-400 text-sm mb-2"
                            >
                                Description
                            </label>
                            <textarea
                                name="description"
                                id="description_{{ $task->id }}"
                                rows="3"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500"
                            >{{ $task->description }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label
                                for="user_id_{{ $task->id }}"
                                class="block text-gray-400 text-sm mb-2"
                            >
                                Assign User
                            </label>
                            <select
                                name="user_id"
                                id="user_id_{{ $task->id }}"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500"
                                required
                            >
                                @foreach($users as $user)
                                    <option
                                        value="{{ $user->id }}"
                                        {{ $user->id == $task->user_id ? 'selected' : '' }}
                                    >
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label
                                for="due_date_{{ $task->id }}"
                                class="block text-gray-400 text-sm mb-2"
                            >
                                Due Date
                            </label>
                            <input
                                type="date"
                                name="due_date"
                                id="due_date_{{ $task->id }}"
                                value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500"
                            >
                        </div>
                        <div class="mb-4">
                            <label
                                for="priority_{{ $task->id }}"
                                class="block text-gray-400 text-sm mb-2"
                            >
                                Priority
                            </label>
                            <select
                                name="priority"
                                id="priority_{{ $task->id }}"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500"
                                required
                            >
                                <option value="low" {{ $task->priority == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ $task->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ $task->priority == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label
                                for="status_{{ $task->id }}"
                                class="block text-gray-400 text-sm mb-2"
                            >
                                Status
                            </label>
                            <select
                                name="status"
                                id="status_{{ $task->id }}"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500"
                                required
                            >
                                <option value="open" {{ $task->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div class="flex justify-end">
                            <button
                                type="button"
                                onclick="closeModal('editTaskModal-{{ $task->id }}')"
                                class="bg-gray-700 text-white px-6 py-2 rounded-lg mr-2"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                class="yellow-btn px-6 py-2 rounded-lg"
                            >
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Task Modal -->
    <div
        id="deleteTaskModal-{{ $task->id }}"
        class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm overflow-y-auto"
    >
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto my-8 overflow-hidden">
                <div class="p-6 max-h-[calc(100vh-8rem)] overflow-y-auto">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold">Delete Task</h3>
                        <button
                            onclick="closeModal('deleteTaskModal-{{ $task->id }}')"
                            class="text-gray-400 hover:text-white"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="mb-6 text-center">
                        <div class="inline-flex items-center justify-center bg-red-900 text-red-300 w-16 h-16 rounded-full mb-6">
                            <i class="fas fa-exclamation-triangle text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold mb-2">Are you sure?</h3>
                        <p class="text-gray-400">
                            You are about to delete the task
                            <strong>"{{ $task->title }}"</strong>.
                            This action cannot be undone.
                        </p>
                    </div>
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="flex justify-end">
                            <button
                                type="button"
                                onclick="closeModal('deleteTaskModal-{{ $task->id }}')"
                                class="bg-gray-700 text-white px-6 py-2 rounded-lg mr-2"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                class="bg-red-600 text-white px-6 py-2 rounded-lg"
                            >
                                Delete
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection
