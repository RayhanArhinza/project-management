@extends('layouts.app')
@section('content')

<div class="mb-6 fade-in">
    <h2 class="text-2xl font-bold mb-1">User Management</h2>
    <p class="text-gray-400">Manage your users.</p>
</div>

<!-- Users Table -->
<div class="bg-white dark:bg-gray-900 p-6 mb-8 slide-in" style="animation-delay: 0.7s">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold">Users List</h3>
        <!-- Tombol untuk memunculkan modal add user -->
        <button onclick="openModal('addUserModal')" class="yellow-btn px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-plus mr-2"></i>Add New User
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-gray-400 text-sm">
                    <th class="pb-3 font-medium">ID</th>
                    <th class="pb-3 font-medium">Avatar</th>
                    <th class="pb-3 font-medium">Name</th>
                    <th class="pb-3 font-medium">Email</th>
                    <th class="pb-3 font-medium">Role</th>
                    <th class="pb-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="border-b border-gray-800">
                    <td class="py-4 pr-4">{{ $user->id }}</td>

                    <!-- Tampilkan Avatar di tabel -->
                    <td class="py-4 pr-4">
                        @if($user->profile && $user->profile->avatar)
                            <img src="{{ asset('avatars/' . $user->profile->avatar) }}"
                                 alt="Avatar"
                                 class="w-10 h-10 rounded-full object-cover">
                        @else
                            <span class="text-gray-500">No Avatar</span>
                        @endif
                    </td>

                    <td class="py-4 pr-4">
                        <p class="font-medium">{{ $user->name }}</p>
                    </td>
                    <td class="py-4 pr-4">
                        <p class="font-medium">{{ $user->email }}</p>
                    </td>
                    <td class="py-4 pr-4">
                        <p class="font-medium">{{ $user->role->name ?? '-' }}</p>
                    </td>
                    <td class="py-4">
                        <button onclick="openModal('viewUserModal-{{ $user->id }}')" class="text-gray-400 hover:text-white mr-2" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="openModal('editUserModal-{{ $user->id }}')" class="text-gray-400 hover:text-white mr-2" title="Edit User">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="openModal('deleteUserModal-{{ $user->id }}')" class="text-gray-400 hover:text-white" title="Delete User">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add User Modal -->
<div id="addUserModal" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-2xl mx-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Add New User</h3>
                <button onclick="closeModal('addUserModal')" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Baris 1: Name, Email -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-400 text-sm mb-2" for="userName">Name</label>
                        <input type="text" name="name" id="userName"
                               class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500"
                               required>
                    </div>
                    <div>
                        <label class="block text-gray-400 text-sm mb-2" for="userEmail">Email</label>
                        <input type="email" name="email" id="userEmail"
                               class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500"
                               required>
                    </div>
                </div>

                <!-- Baris 2: Password, Role -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-400 text-sm mb-2" for="userPassword">Password</label>
                        <input type="password" name="password" id="userPassword"
                               class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500"
                               required>
                    </div>
                    <div>
                        <label class="block text-gray-400 text-sm mb-2" for="userRole">Role</label>
                        <select name="role_id" id="userRole"
                                class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500"
                                required>
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Baris 3: Alamat, Nomor Telepon -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-400 text-sm mb-2" for="alamat">Alamat</label>
                        <input type="text" name="alamat" id="alamat"
                               class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                    </div>
                    <div>
                        <label class="block text-gray-400 text-sm mb-2" for="nomor_telepon">Nomor Telepon</label>
                        <input type="text" name="nomor_telepon" id="nomor_telepon"
                               class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                    </div>
                </div>

                <!-- Baris 4: Avatar (span 2 kolom) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="md:col-span-2">
                        <label class="block text-gray-400 text-sm mb-2" for="avatar">Avatar</label>
                        <input type="file" name="avatar" id="avatar" accept=".jpg,.jpeg,.png"
                               class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="button" onclick="closeModal('addUserModal')"
                            class="bg-gray-700 text-white px-6 py-2 rounded-lg mr-2">
                        Cancel
                    </button>
                    <button type="submit" class="yellow-btn px-6 py-2 rounded-lg">
                        Add User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View, Edit, Delete Modals untuk tiap user -->
@foreach($users as $user)
    <!-- View User Modal -->
    <div id="viewUserModal-{{ $user->id }}" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm transition-all duration-300 ease-in-out">
        <div class="bg-gray-800 rounded-lg shadow-2xl w-full max-w-md mx-auto transform transition-all scale-95 hover:scale-100 duration-300">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6 border-b border-gray-700 pb-4">
                    <h3 class="text-xl font-bold">User Details</h3>
                    <button onclick="closeModal('viewUserModal-{{ $user->id }}')" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        @if($user->profile && $user->profile->avatar)
                            <img src="{{ asset('avatars/' . $user->profile->avatar) }}" alt="Avatar"
                                 class="w-20 h-20 rounded-full object-cover border-2 border-gray-600 shadow-lg">
                        @else
                            <div class="w-20 h-20 rounded-full bg-gray-700 flex items-center justify-center text-gray-400">
                                <i class="fas fa-user text-2xl"></i>
                            </div>
                        @endif
                        <div>
                            <p class="text-lg font-semibold">{{ $user->name }}</p>
                            <p class="text-sm text-gray-400">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Role</p>
                            <p class="font-medium">{{ $user->role->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Phone</p>
                            <p class="font-medium">{{ $user->profile->nomor_telepon ?? '-' }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Address</p>
                        <p class="font-medium">{{ $user->profile->alamat ?? '-' }}</p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button" onclick="closeModal('viewUserModal-{{ $user->id }}')"
                            class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-all duration-300">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal-{{ $user->id }}" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm transition-all duration-300 ease-in-out">
        <div class="bg-gray-800 rounded-lg shadow-2xl w-full max-w-2xl mx-auto transform transition-all scale-95 hover:scale-100 duration-300">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6 border-b border-gray-700 pb-4">
                    <h3 class="text-xl font-bold">Edit User</h3>
                    <button onclick="closeModal('editUserModal-{{ $user->id }}')" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-400 text-sm mb-2" for="editUserName-{{ $user->id }}">Name</label>
                            <input type="text" name="name" id="editUserName-{{ $user->id }}"
                                   value="{{ $user->name }}"
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500 transition-all duration-300"
                                   required>
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm mb-2" for="editUserEmail-{{ $user->id }}">Email</label>
                            <input type="email" name="email" id="editUserEmail-{{ $user->id }}"
                                   value="{{ $user->email }}"
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500 transition-all duration-300"
                                   required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-400 text-sm mb-2" for="editUserPassword-{{ $user->id }}">
                                Password <span class="text-xs">(Leave blank if unchanged)</span>
                            </label>
                            <input type="password" name="password" id="editUserPassword-{{ $user->id }}"
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500 transition-all duration-300">
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm mb-2" for="editUserRole-{{ $user->id }}">Role</label>
                            <select name="role_id" id="editUserRole-{{ $user->id }}"
                                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500 transition-all duration-300"
                                    required>
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ ($user->role_id == $role->id) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-400 text-sm mb-2" for="editAlamat-{{ $user->id }}">Address</label>
                            <input type="text" name="alamat" id="editAlamat-{{ $user->id }}"
                                   value="{{ $user->profile->alamat ?? '' }}"
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500 transition-all duration-300">
                        </div>
                        <div>
                            <label class="block text-gray-400 text-sm mb-2" for="editNomorTelepon-{{ $user->id }}">Phone Number</label>
                            <input type="text" name="nomor_telepon" id="editNomorTelepon-{{ $user->id }}"
                                   value="{{ $user->profile->nomor_telepon ?? '' }}"
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500 transition-all duration-300">
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-400 text-sm mb-2" for="editAvatar-{{ $user->id }}">Avatar</label>
                        <input type="file" name="avatar" id="editAvatar-{{ $user->id }}" accept=".jpg,.jpeg,.png"
                               class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500 transition-all duration-300">
                        @if(isset($user->profile->avatar))
                            <div class="mt-4 flex items-center space-x-4">
                                <img src="{{ asset('avatars/' . $user->profile->avatar) }}" alt="Avatar"
                                     class="w-24 h-24 rounded-full object-cover border-2 border-gray-600 shadow-lg">
                                <p class="text-gray-400">Current avatar</p>
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="closeModal('editUserModal-{{ $user->id }}')"
                                class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-all duration-300">
                            Cancel
                        </button>
                        <button type="submit" class="yellow-btn px-6 py-2 rounded-lg hover:scale-105 transition-all duration-300">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete User Modal -->
    <div id="deleteUserModal-{{ $user->id }}" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm transition-all duration-300 ease-in-out">
        <div class="bg-gray-800 rounded-lg shadow-2xl w-full max-w-md mx-auto transform transition-all scale-95 hover:scale-100 duration-300">
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
                    <p class="text-gray-400">You are about to delete the user <strong>"{{ $user->name }}"</strong>. This action cannot be undone.</p>
                </div>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="closeModal('deleteUserModal-{{ $user->id }}')"
                                class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-all duration-300">
                            Cancel
                        </button>
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg hover:scale-105 transition-all duration-300">
                            Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach


@endsection
