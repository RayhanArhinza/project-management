@extends('admin.includes.app')
@section('content')

<div class="mb-6 fade-in">
    <h2 class="text-2xl font-bold mb-1">Admin Management</h2>
    <p class="text-gray-400">Manage your admin accounts.</p>
</div>
<div class="bg-white dark:bg-gray-900 shadow-xl rounded-2xl p-6 mb-8 transform transition-all duration-300 hover:scale-[1.01] hover:shadow-2xl">
    <form action="{{ route('admins.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Filter Search for Name and Email -->
        <div>
            <label for="search" class="block text-sm font-medium text-gray-300 mb-2 tracking-wider">
                <i class="mr-2 text-green-400 fas fa-search"></i>Search by Name or Email
            </label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Enter keyword..."
                class="w-full bg-gray-800 border border-gray-700 text-gray-200 text-sm rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 block p-2.5 transition-all duration-200 hover:bg-gray-700">
        </div>

        <!-- Filter Button -->
        <div class="flex items-end">
            <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white font-bold py-2.5 px-6 rounded-lg shadow-md hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                <i class="mr-2 fas fa-filter"></i>Filter
            </button>
        </div>
    </form>
</div>

<!-- Admins Table -->
<div class="bg-white dark:bg-gray-900 p-6 mb-8 slide-in" style="animation-delay: 0.7s">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold">Admins List</h3>
        <!-- Button to open modal for adding new admin -->
        <button onclick="openModal('addAdminModal')" class="yellow-btn px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-plus mr-2"></i>Add New Admin
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-gray-400 text-sm">
                    <th class="pb-3 font-medium">ID</th>
                    <th class="pb-3 font-medium">Name</th>
                    <th class="pb-3 font-medium">Email</th>
                    <th class="pb-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admins as $admin)
                <tr class="border-b border-gray-800">
                    <td class="py-4 pr-4">{{ $admin->id }}</td>
                    <td class="py-4 pr-4">
                        <p class="font-medium">{{ $admin->name }}</p>
                    </td>
                    <td class="py-4 pr-4">
                        <p class="font-medium">{{ $admin->email }}</p>
                    </td>
                    <td class="py-4">
                        <button onclick="openModal('viewAdminModal-{{ $admin->id }}')" class="text-gray-400 hover:text-white mr-2" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="openModal('editAdminModal-{{ $admin->id }}')" class="text-gray-400 hover:text-white mr-2" title="Edit Admin">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="openModal('deleteAdminModal-{{ $admin->id }}')" class="text-gray-400 hover:text-white" title="Delete Admin">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add Admin Modal -->
<div id="addAdminModal" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Add New Admin</h3>
                <button onclick="closeModal('addAdminModal')" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('admins.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2" for="adminName">Name</label>
                    <input type="text" name="name" id="adminName" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2" for="adminEmail">Email</label>
                    <input type="email" name="email" id="adminEmail" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2" for="adminPassword">Password</label>
                    <input type="password" name="password" id="adminPassword" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2" for="adminPasswordConfirmation">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="adminPasswordConfirmation" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal('addAdminModal')" class="bg-gray-700 text-white px-6 py-2 rounded-lg mr-2">Cancel</button>
                    <button type="submit" class="yellow-btn px-6 py-2 rounded-lg">Add Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View, Edit, Delete Modals for each admin -->
@foreach($admins as $admin)
    <!-- View Admin Modal -->
    <div id="viewAdminModal-{{ $admin->id }}" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Admin Details</h3>
                    <button onclick="closeModal('viewAdminModal-{{ $admin->id }}')" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mb-4">
                    <p class="text-gray-400 text-sm">Admin ID</p>
                    <p class="font-medium">{{ $admin->id }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-gray-400 text-sm">Name</p>
                    <p class="font-medium text-lg">{{ $admin->name }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-gray-400 text-sm">Email</p>
                    <p class="font-medium">{{ $admin->email }}</p>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal('viewAdminModal-{{ $admin->id }}')" class="bg-gray-700 text-white px-6 py-2 rounded-lg">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Admin Modal -->
    <div id="editAdminModal-{{ $admin->id }}" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Edit Admin</h3>
                    <button onclick="closeModal('editAdminModal-{{ $admin->id }}')" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('admins.update', $admin->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm mb-2" for="editAdminName-{{ $admin->id }}">Name</label>
                        <input type="text" name="name" id="editAdminName-{{ $admin->id }}" value="{{ $admin->name }}" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm mb-2" for="editAdminEmail-{{ $admin->id }}">Email</label>
                        <input type="email" name="email" id="editAdminEmail-{{ $admin->id }}" value="{{ $admin->email }}" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                    </div>
                    <!-- Optional: Password field (leave blank if not changing) -->
                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm mb-2" for="editAdminPassword-{{ $admin->id }}">Password <span class="text-xs text-gray-500">(Leave blank to keep unchanged)</span></label>
                        <input type="password" name="password" id="editAdminPassword-{{ $admin->id }}" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="closeModal('editAdminModal-{{ $admin->id }}')" class="bg-gray-700 text-white px-6 py-2 rounded-lg mr-2">Cancel</button>
                        <button type="submit" class="yellow-btn px-6 py-2 rounded-lg">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Admin Modal -->
    <div id="deleteAdminModal-{{ $admin->id }}" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Delete Admin</h3>
                    <button onclick="closeModal('deleteAdminModal-{{ $admin->id }}')" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mb-6 text-center">
                    <div class="inline-flex items-center justify-center bg-red-900 text-red-300 w-16 h-16 rounded-full mb-6">
                        <i class="fas fa-exclamation-triangle text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Are you sure?</h3>
                    <p class="text-gray-400">You are about to delete admin <strong>"{{ $admin->name }}"</strong>. This action cannot be undone.</p>
                </div>
                <form action="{{ route('admins.destroy', $admin->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end">
                        <button type="button" onclick="closeModal('deleteAdminModal-{{ $admin->id }}')" class="bg-gray-700 text-white px-6 py-2 rounded-lg mr-2">Cancel</button>
                        <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@endsection
