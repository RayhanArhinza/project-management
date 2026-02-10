@extends('layouts.app')
@section('content')

<div class="mb-6 fade-in">
    <h2 class="text-2xl font-bold mb-1">Project Member Management</h2>
    <p class="text-gray-400">Manage your project members.</p>
</div>

<!-- Project Members Table -->
<div class="bg-white dark:bg-gray-900 p-6 mb-8 slide-in" style="animation-delay: 0.7s">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold">Project Members List</h3>
        <button onclick="openModal('addProjectMemberModal')" class="yellow-btn px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-plus mr-2"></i>Add New Project Member
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-gray-400 text-sm">
                    <th class="pb-3 font-medium">ID</th>
                    <th class="pb-3 font-medium">Project</th>
                    <th class="pb-3 font-medium">Users</th>
                    <th class="pb-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $groupedMembers = $projectMembers->groupBy('project_id');
                @endphp

                @foreach($groupedMembers as $projectId => $members)
                @php
                    $project = $members->first()->project;
                @endphp
                <tr class="border-b border-gray-800">
                    <td class="py-4 pr-4">{{ $projectId }}</td>
                    <td class="py-4 pr-4">{{ $project->name ?? 'N/A' }}</td>
                    <td class="py-4 pr-4">
                        <div class="flex flex-wrap gap-2">
                            @foreach($members as $member)
                            <span class="bg-gray-700 px-3 py-1 rounded-full text-sm">
                                {{ $member->user->name ?? 'N/A' }}
                            </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="py-4">
                        <button onclick="openModal('viewProjectMemberModal-{{ $projectId }}')" class="text-gray-400 hover:text-white mr-2" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="openModal('editProjectMemberModal-{{ $projectId }}')" class="text-gray-400 hover:text-white mr-2" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="openModal('deleteProjectMemberModal-{{ $projectId }}')" class="text-gray-400 hover:text-white" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add Project Member Modal - Updated untuk multiple users -->
<div id="addProjectMemberModal" class="fixed inset-0 z-50 hidden flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
    <!-- Kontainer modal dengan max-height dan scroll -->
    <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-2xl mx-auto max-h-[80vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Add New Project Members</h3>
                <button onclick="closeModal('addProjectMemberModal')" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('project_members.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2" for="project">Project</label>
                    <select name="project_id" id="project" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- User selection fields container -->
                <div id="userFieldsContainer">
                    <div class="user-field-group mb-4 flex items-end gap-2">
                        <div class="flex-grow">
                            <label class="block text-gray-400 text-sm mb-2" for="user_0">User</label>
                            <select name="user_ids[]" id="user_0" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500 user-select" required>
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" onclick="removeUserField(this)" class="bg-red-600 text-white p-2 rounded-lg mb-1" title="Remove user">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>

                <div class="flex justify-between">
                    <button type="button" onclick="addUserField()" class="bg-blue-600 text-white px-4 py-2 rounded-lg mb-4">
                        <i class="fas fa-plus mr-2"></i>Add Another User
                    </button>

                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeModal('addProjectMemberModal')" class="bg-gray-700 text-white px-6 py-2 rounded-lg">Cancel</button>
                        <button type="submit" class="yellow-btn px-6 py-2 rounded-lg">Add Project Members</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Project Member Modal - Updated untuk multiple users -->
@foreach($projectMembers as $projectMember)
    <!-- View Project Member Modal (contoh sederhana) -->
    <div id="viewProjectMemberModal-{{ $projectMember->id }}" class="fixed inset-0 z-50 hidden flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-2xl mx-auto max-h-[80vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Project Member Details</h3>
                    <button onclick="closeModal('viewProjectMemberModal-{{ $projectMember->id }}')" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <!-- Isi detail modal -->
                <p>Detail project member...</p>
            </div>
        </div>
    </div>

    <!-- Edit Project Member Modal -->
    <div id="editProjectMemberModal-{{ $projectMember->id }}" class="fixed inset-0 z-50 hidden flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-2xl mx-auto max-h-[80vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Edit Project Member</h3>
                    <button onclick="closeModal('editProjectMemberModal-{{ $projectMember->id }}')" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('project_members.update', $projectMember->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm mb-2" for="project-{{ $projectMember->id }}">Project</label>
                        <select name="project_id" id="project-{{ $projectMember->id }}" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                            <option value="">Select Project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ $projectMember->project_id == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- User selection fields container untuk edit -->
                    <div id="editUserFieldsContainer-{{ $projectMember->id }}">
                        <div class="user-field-group mb-4 flex items-end gap-2">
                            <div class="flex-grow">
                                <label class="block text-gray-400 text-sm mb-2" for="user-{{ $projectMember->id }}">User</label>
                                <select name="user_id" id="user-{{ $projectMember->id }}" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                                    <option value="">Select User</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ $projectMember->user_id == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" onclick="removeUserField(this)" class="bg-red-600 text-white p-2 rounded-lg mb-1" title="Remove user">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <button type="button" onclick="addUserField('editUserFieldsContainer-{{ $projectMember->id }}')" class="bg-blue-600 text-white px-4 py-2 rounded-lg mb-4">
                            <i class="fas fa-plus mr-2"></i>Add Another User
                        </button>

                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="closeModal('editProjectMemberModal-{{ $projectMember->id }}')" class="bg-gray-700 text-white px-6 py-2 rounded-lg">Cancel</button>
                            <button type="submit" class="yellow-btn px-6 py-2 rounded-lg">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Project Member Modal (contoh sederhana) -->
    <div id="deleteProjectMemberModal-{{ $projectMember->id }}" class="fixed inset-0 z-50 hidden flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto max-h-[80vh] overflow-y-auto">
            <div class="p-6">
                <h3 class="text-xl font-bold mb-4">Confirm Delete</h3>
                <p class="mb-6">Are you sure you want to delete this project member?</p>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal('deleteProjectMemberModal-{{ $projectMember->id }}')" class="bg-gray-700 text-white px-6 py-2 rounded-lg">Cancel</button>
                    <form action="{{ route('project_members.destroy', $projectMember->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="yellow-btn px-6 py-2 rounded-lg">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script>
    // Function untuk membuka modal
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    // Function untuk menutup modal
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    // Menambahkan field user baru
    function addUserField(containerId = 'userFieldsContainer') {
        const container = document.getElementById(containerId);
        const fieldCount = container.querySelectorAll('.user-field-group').length;
        const newField = document.createElement('div');
        newField.className = 'user-field-group mb-4 flex items-end gap-2';
        newField.innerHTML = `
            <div class="flex-grow">
                <label class="block text-gray-400 text-sm mb-2" for="user_${fieldCount}">User</label>
                <select name="${containerId === 'userFieldsContainer' ? 'user_ids[]' : 'user_id'}" id="user_${fieldCount}" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500 user-select" required>
                    <option value="">Select User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="button" onclick="removeUserField(this)" class="bg-red-600 text-white p-2 rounded-lg mb-1" title="Remove user">
                <i class="fas fa-trash"></i>
            </button>
        `;
        container.appendChild(newField);
    }

    // Menghapus field user
    function removeUserField(button) {
        const container = button.closest('.user-field-group');
        // Jangan hapus field terakhir
        if (document.querySelectorAll('.user-field-group').length > 1) {
            container.remove();
        } else {
            alert('You need at least one user field.');
        }
    }

    // Menutup modal ketika mengklik di luar konten modal (opsional)
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.backdrop-blur-sm').forEach(function(modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
        });
    });
</script>

@endsection
