@extends('layouts.app')
@section('content')

<div class="mb-6 fade-in">
    <h2 class="text-2xl font-bold mb-1">Project Management</h2>
    <p class="text-gray-400">Manage your projects.</p>
</div>

<!-- Projects Table -->
<div class="bg-white dark:bg-gray-900 p-6 mb-8 slide-in" style="animation-delay: 0.7s">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold">Projects List</h3>
        <!-- Tombol untuk memunculkan modal add project -->
        <button onclick="openModal('addProjectModal')" class="yellow-btn px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-plus mr-2"></i>Add New Project
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-gray-400 text-sm">
                    <th class="pb-3 font-medium">ID</th>
                    <th class="pb-3 font-medium">Name</th>
                    <th class="pb-3 font-medium">Owner</th>
                    <th class="pb-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $project)
                <tr class="border-b border-gray-800">
                    <td class="py-4 pr-4">{{ $project->id }}</td>
                    <td class="py-4 pr-4">
                        <p class="font-medium">{{ $project->name }}</p>
                    </td>
                    <td class="py-4 pr-4">
                        <p class="font-medium">{{ $project->owner }}</p>
                    </td>
                    <td class="py-4">
                        <button onclick="openModal('viewProjectModal-{{ $project->id }}')" class="text-gray-400 hover:text-white mr-2" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="openModal('editProjectModal-{{ $project->id }}')" class="text-gray-400 hover:text-white mr-2" title="Edit Project">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="openModal('deleteProjectModal-{{ $project->id }}')" class="text-gray-400 hover:text-white" title="Delete Project">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add Project Modal -->
<div id="addProjectModal" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Add New Project</h3>
                <button onclick="closeModal('addProjectModal')" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('projects.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2" for="projectName">Project Name</label>
                    <input type="text" name="name" id="projectName" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2" for="projectDescription">Description</label>
                    <textarea name="description" id="projectDescription" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2" for="projectOwner">Owner</label>
                    <input type="text" name="owner" id="projectOwner" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal('addProjectModal')" class="bg-gray-700 text-white px-6 py-2 rounded-lg mr-2">Cancel</button>
                    <button type="submit" class="yellow-btn px-6 py-2 rounded-lg">Add Project</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View, Edit, Delete Modals untuk tiap project -->
@foreach($projects as $project)
    <!-- View Project Modal -->
    <div id="viewProjectModal-{{ $project->id }}" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Project Details</h3>
                    <button onclick="closeModal('viewProjectModal-{{ $project->id }}')" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mb-4">
                    <p class="text-gray-400 text-sm">Project ID</p>
                    <p class="font-medium">{{ $project->id }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-gray-400 text-sm">Project Name</p>
                    <p class="font-medium text-lg">{{ $project->name }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-gray-400 text-sm">Description</p>
                    <p class="font-medium">{{ $project->description }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-gray-400 text-sm">Owner</p>
                    <p class="font-medium">{{ $project->owner }}</p>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal('viewProjectModal-{{ $project->id }}')" class="bg-gray-700 text-white px-6 py-2 rounded-lg">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Project Modal -->
    <div id="editProjectModal-{{ $project->id }}" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Edit Project</h3>
                    <button onclick="closeModal('editProjectModal-{{ $project->id }}')" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('projects.update', $project->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm mb-2" for="editProjectName-{{ $project->id }}">Project Name</label>
                        <input type="text" name="name" id="editProjectName-{{ $project->id }}" value="{{ $project->name }}" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm mb-2" for="editProjectDescription-{{ $project->id }}">Description</label>
                        <textarea name="description" id="editProjectDescription-{{ $project->id }}" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">{{ $project->description }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm mb-2" for="editProjectOwner-{{ $project->id }}">Owner</label>
                        <input type="text" name="owner" id="editProjectOwner-{{ $project->id }}" value="{{ $project->owner }}" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="closeModal('editProjectModal-{{ $project->id }}')" class="bg-gray-700 text-white px-6 py-2 rounded-lg mr-2">Cancel</button>
                        <button type="submit" class="yellow-btn px-6 py-2 rounded-lg">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Project Modal -->
    <div id="deleteProjectModal-{{ $project->id }}" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Delete Project</h3>
                    <button onclick="closeModal('deleteProjectModal-{{ $project->id }}')" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mb-6 text-center">
                    <div class="inline-flex items-center justify-center bg-red-900 text-red-300 w-16 h-16 rounded-full mb-6">
                        <i class="fas fa-exclamation-triangle text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Are you sure?</h3>
                    <p class="text-gray-400">You are about to delete the project <strong>"{{ $project->name }}"</strong>. This action cannot be undone.</p>
                </div>
                <form action="{{ route('projects.destroy', $project->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end">
                        <button type="button" onclick="closeModal('deleteProjectModal-{{ $project->id }}')" class="bg-gray-700 text-white px-6 py-2 rounded-lg mr-2">Cancel</button>
                        <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection
