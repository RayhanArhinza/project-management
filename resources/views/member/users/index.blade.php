@extends('member.includes.app')
@section('content')

<div class="mb-6 fade-in">
    <h2 class="text-2xl font-bold mb-1">User Management</h2>
    <p class="text-gray-400">Manage your users.</p>
</div>

<!-- Quota Information Card -->
<div class="bg-white dark:bg-gray-900 p-6 mb-6 slide-in" style="animation-delay: 0.3s">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold mb-2">User Quota Status</h3>
            <p class="text-gray-400">
                Using <span class="font-bold text-yellow-400">{{ $currentUserCount }}</span> of
                <span class="font-bold text-green-400">{{ $maxUsers }}</span> available users
            </p>
            <p class="text-sm text-gray-500 mt-1">
                Remaining: <span class="font-bold {{ $remainingQuota <= 5 ? 'text-red-400' : 'text-green-400' }}">{{ $remainingQuota }}</span> users
            </p>
        </div>
        <div class="text-right">
            <!-- Progress Bar -->
            <div class="w-32 h-2 bg-gray-700 rounded-full mb-2">
                <div class="h-full rounded-full {{ $currentUserCount >= $maxUsers * 0.9 ? 'bg-red-500' : ($currentUserCount >= $maxUsers * 0.7 ? 'bg-yellow-500' : 'bg-green-500') }}"
                     style="width: {{ ($currentUserCount / $maxUsers) * 100 }}%"></div>
            </div>
            <span class="text-sm text-gray-400">{{ number_format(($currentUserCount / $maxUsers) * 100, 1) }}% used</span>
        </div>
    </div>
</div>

<!-- Alert jika kuota hampir habis -->
@if($remainingQuota <= 5 && $remainingQuota > 0)
<div class="bg-yellow-900/50 border border-yellow-600 text-yellow-200 px-4 py-3 rounded mb-6" role="alert">
    <div class="flex">
        <div class="py-1">
            <i class="fas fa-exclamation-triangle mr-2"></i>
        </div>
        <div>
            <p class="font-bold">Warning: Low User Quota</p>
            <p class="text-sm">You only have {{ $remainingQuota }} user slots remaining. Consider upgrading your membership.</p>
        </div>
    </div>
</div>
@elseif($remainingQuota <= 0)
<div class="bg-red-900/50 border border-red-600 text-red-200 px-4 py-3 rounded mb-6" role="alert">
    <div class="flex">
        <div class="py-1">
            <i class="fas fa-ban mr-2"></i>
        </div>
        <div>
            <p class="font-bold">Quota Reached</p>
            <p class="text-sm">You have reached your maximum user limit. Please upgrade your membership to create more users.</p>
        </div>
    </div>
</div>
@endif

<!-- Users Table -->
<div class="bg-white dark:bg-gray-900 p-6 mb-8 slide-in" style="animation-delay: 0.7s">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold">Users List</h3>
        <!-- Tombol untuk memunculkan modal add user - disabled jika kuota habis -->
        <button onclick="openModal('addUserModal')"
                class="{{ $remainingQuota <= 0 ? 'bg-gray-600 cursor-not-allowed' : 'yellow-btn hover:bg-yellow-600' }} px-4 py-2 rounded-md text-sm font-medium transition-colors"
                {{ $remainingQuota <= 0 ? 'disabled' : '' }}>
            <i class="fas fa-plus mr-2"></i>Add New User
            @if($remainingQuota <= 0)
                <span class="ml-2 text-xs">(Quota Reached)</span>
            @endif
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-gray-400 text-sm">
                    <th class="pb-3 font-medium">ID</th>
                    <th class="pb-3 font-medium">Name</th>
                    <th class="pb-3 font-medium">Email</th>
                    <th class="pb-3 font-medium">Role</th>
                    <th class="pb-3 font-medium">Created At</th>
                    <th class="pb-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="border-b border-gray-800">
                    <td class="py-4 pr-4">{{ $user->id }}</td>
                    <td class="py-4 pr-4">
                        <p class="font-medium">{{ $user->name }}</p>
                    </td>
                    <td class="py-4 pr-4">
                        <p class="font-medium">{{ $user->email }}</p>
                    </td>
                    <td class="py-4 pr-4">
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($user->role_id == 1) bg-green-900 text-green-300
                            @elseif($user->role_id == 2) bg-blue-900 text-blue-300
                            @elseif($user->role_id == 3) bg-purple-900 text-purple-300
                            @else bg-gray-900 text-gray-300
                            @endif">
                            {{ $user->role->name ?? 'No Role' }}
                        </span>
                    </td>
                    <td class="py-4 pr-4">
                        <p class="font-medium">{{ $user->created_at->format('Y-m-d') }}</p>
                    </td>
                    <td class="py-4">
                        <button onclick="openModal('editUserModal-{{ $user->id }}')" class="text-gray-400 hover:text-white mr-2" title="Edit User">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="openModal('deleteUserModal-{{ $user->id }}')" class="text-gray-400 hover:text-white" title="Delete User">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-gray-400">
                        <i class="fas fa-users text-4xl mb-4"></i>
                        <p>No users found. {{ $remainingQuota > 0 ? 'Create your first user!' : 'Upgrade your membership to add users.' }}</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add User Modal -->
<div id="addUserModal" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Add New User</h3>
                <button onclick="closeModal('addUserModal')" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Quota Warning in Modal -->
            @if($remainingQuota <= 5 && $remainingQuota > 0)
            <div class="bg-yellow-900/50 border border-yellow-600 text-yellow-200 px-3 py-2 rounded mb-4 text-sm">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Only {{ $remainingQuota }} user slots remaining!
            </div>
            @elseif($remainingQuota <= 0)
            <div class="bg-red-900/50 border border-red-600 text-red-200 px-3 py-2 rounded mb-4 text-sm">
                <i class="fas fa-ban mr-2"></i>
                User quota reached. Please upgrade your membership.
            </div>
            @endif

            <form action="{{ route('member.users.store') }}" method="POST" id="addUserForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2" for="userName">Name</label>
                    <input type="text" name="name" id="userName" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" {{ $remainingQuota <= 0 ? 'disabled' : 'required' }}>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2" for="userEmail">Email</label>
                    <input type="email" name="email" id="userEmail" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" {{ $remainingQuota <= 0 ? 'disabled' : 'required' }}>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2" for="userRole">Role</label>
                    <select name="role_id" id="userRole" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" {{ $remainingQuota <= 0 ? 'disabled' : 'required' }}>
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2" for="userPassword">Password</label>
                    <input type="password" name="password" id="userPassword" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" {{ $remainingQuota <= 0 ? 'disabled' : 'required' }}>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2" for="userPasswordConfirmation">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="userPasswordConfirmation" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" {{ $remainingQuota <= 0 ? 'disabled' : 'required' }}>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal('addUserModal')" class="bg-gray-700 text-white px-6 py-2 rounded-lg mr-2">Cancel</button>
                    <button type="submit" class="{{ $remainingQuota <= 0 ? 'bg-gray-600 cursor-not-allowed' : 'yellow-btn' }} px-6 py-2 rounded-lg" {{ $remainingQuota <= 0 ? 'disabled' : '' }}>
                        {{ $remainingQuota <= 0 ? 'Quota Reached' : 'Add User' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit, Delete Modals untuk tiap user -->
@foreach($users as $user)
    <!-- Edit User Modal -->
    <div id="editUserModal-{{ $user->id }}" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Edit User</h3>
                    <button onclick="closeModal('editUserModal-{{ $user->id }}')" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('member.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm mb-2" for="editUserName-{{ $user->id }}">Name</label>
                        <input type="text" name="name" id="editUserName-{{ $user->id }}" value="{{ $user->name }}" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm mb-2" for="editUserEmail-{{ $user->id }}">Email</label>
                        <input type="email" name="email" id="editUserEmail-{{ $user->id }}" value="{{ $user->email }}" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm mb-2" for="editUserRole-{{ $user->id }}">Role</label>
                        <select name="role_id" id="editUserRole-{{ $user->id }}" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="closeModal('editUserModal-{{ $user->id }}')" class="bg-gray-700 text-white px-6 py-2 rounded-lg mr-2">Cancel</button>
                        <button type="submit" class="yellow-btn px-6 py-2 rounded-lg">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete User Modal -->
    <div id="deleteUserModal-{{ $user->id }}" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Delete User</h3>
                    <button onclick="closeModal('deleteUserModal-{{ $user->id }}')" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mb-6 text-center">
                    <div class="inline-flex items-center justify-center bg-red-900 text-red-300 w-16 h-16 rounded-full mb-6">
                        <i class="fas fa-exclamation-triangle text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Are you sure?</h3>
                    <p class="text-gray-400">You are about to delete the user <strong>"{{ $user->name }}"</strong> with role <strong>"{{ $user->role->name ?? 'No Role' }}"</strong>. This action cannot be undone.</p>
                </div>
                <form action="{{ route('member.users.destroy', $user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end">
                        <button type="button" onclick="closeModal('deleteUserModal-{{ $user->id }}')" class="bg-gray-700 text-white px-6 py-2 rounded-lg mr-2">Cancel</button>
                        <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<script>
// Disable form submission if quota is reached
@if($remainingQuota <= 0)
document.getElementById('addUserForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('You have reached your user quota limit. Please upgrade your membership.');
});
@endif
</script>

@endsection
