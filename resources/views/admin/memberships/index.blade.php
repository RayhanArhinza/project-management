@extends('admin.includes.app')
@section('content')

<div class="mb-6 fade-in mt-6">
    <h2 class="text-2xl font-bold mb-1">Membership Management</h2>
    <p class="text-gray-400">Manage your memberships.</p>
</div>

<!-- Memberships Table -->
<div class="bg-white dark:bg-gray-900 p-6 mb-8 slide-in" style="animation-delay: 0.7s">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold">Memberships List</h3>
        <!-- Tombol untuk memunculkan modal add membership -->
        <button onclick="openModal('addMembershipModal')" class="yellow-btn px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-plus mr-2"></i>Add New Membership
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-gray-400 text-sm">
                    <th class="pb-3 font-medium">ID</th>
                    <th class="pb-3 font-medium">Name</th>
                    <th class="pb-3 font-medium">Valid Days</th>
                    <th class="pb-3 font-medium">Price</th>
                    <th class="pb-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($memberships as $membership)
                <tr class="border-b border-gray-800">
                    <td class="py-4 pr-4">{{ $membership->id }}</td>
                    <td class="py-4 pr-4">
                        <p class="font-medium">{{ $membership->name }}</p>
                    </td>
                    <td class="py-4 pr-4">{{ $membership->valid_days }} days</td>
                    <td class="py-4 pr-4">${{ number_format($membership->price, 2) }}</td>
                    <td class="py-4">
                        <button onclick="openModal('viewMembershipModal-{{ $membership->id }}')" class="text-gray-400 hover:text-white mr-2" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="openModal('editMembershipModal-{{ $membership->id }}')" class="text-gray-400 hover:text-white mr-2" title="Edit Membership">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="openModal('deleteMembershipModal-{{ $membership->id }}')" class="text-gray-400 hover:text-white" title="Delete Membership">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add Membership Modal -->
<div id="addMembershipModal" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Add New Membership</h3>
                <button onclick="closeModal('addMembershipModal')" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('memberships.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2" for="membershipName">Membership Name</label>
                    <input type="text" name="name" id="membershipName" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2" for="validDays">Valid Days</label>
                    <input type="number" name="valid_days" id="validDays" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" >
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2" for="price">Price (USD)</label>
                    <input type="number" step="0.01" name="price" id="price" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" >
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal('addMembershipModal')" class="bg-gray-700 text-white px-6 py-2 rounded-lg mr-2">Cancel</button>
                    <button type="submit" class="yellow-btn px-6 py-2 rounded-lg">Add Membership</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View, Edit, Delete Modals untuk tiap membership -->
@foreach($memberships as $membership)
    <!-- View Membership Modal -->
    <div id="viewMembershipModal-{{ $membership->id }}" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Membership Details</h3>
                    <button onclick="closeModal('viewMembershipModal-{{ $membership->id }}')" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mb-4">
                    <p class="text-gray-400 text-sm">Membership ID</p>
                    <p class="font-medium">{{ $membership->id }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-gray-400 text-sm">Membership Name</p>
                    <p class="font-medium text-lg">{{ $membership->name }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-gray-400 text-sm">Valid Days</p>
                    <p class="font-medium text-lg">{{ $membership->valid_days }} days</p>
                </div>
                <div class="mb-4">
                    <p class="text-gray-400 text-sm">Price</p>
                    <p class="font-medium text-lg">${{ number_format($membership->price, 2) }}</p>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal('viewMembershipModal-{{ $membership->id }}')" class="bg-gray-700 text-white px-6 py-2 rounded-lg">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Membership Modal -->
    <div id="editMembershipModal-{{ $membership->id }}" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Edit Membership</h3>
                    <button onclick="closeModal('editMembershipModal-{{ $membership->id }}')" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('memberships.update', $membership->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm mb-2" for="editMembershipName-{{ $membership->id }}">Membership Name</label>
                        <input type="text" name="name" id="editMembershipName-{{ $membership->id }}" value="{{ $membership->name }}" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm mb-2" for="editValidDays-{{ $membership->id }}">Valid Days</label>
                        <input type="number" name="valid_days" id="editValidDays-{{ $membership->id }}" value="{{ $membership->valid_days }}" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" >
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm mb-2" for="editPrice-{{ $membership->id }}">Price (USD)</label>
                        <input type="number" step="0.01" name="price" id="editPrice-{{ $membership->id }}" value="{{ $membership->price }}" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" >
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="closeModal('editMembershipModal-{{ $membership->id }}')" class="bg-gray-700 text-white px-6 py-2 rounded-lg mr-2">Cancel</button>
                        <button type="submit" class="yellow-btn px-6 py-2 rounded-lg">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Membership Modal -->
    <div id="deleteMembershipModal-{{ $membership->id }}" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Delete Membership</h3>
                    <button onclick="closeModal('deleteMembershipModal-{{ $membership->id }}')" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mb-6 text-center">
                    <div class="inline-flex items-center justify-center bg-red-900 text-red-300 w-16 h-16 rounded-full mb-6">
                        <i class="fas fa-exclamation-triangle text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Are you sure?</h3>
                    <p class="text-gray-400">You are about to delete the membership <strong>"{{ $membership->name }}"</strong>. This action cannot be undone.</p>
                </div>
                <form action="{{ route('memberships.destroy', $membership->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end">
                        <button type="button" onclick="closeModal('deleteMembershipModal-{{ $membership->id }}')" class="bg-gray-700 text-white px-6 py-2 rounded-lg mr-2">Cancel</button>
                        <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection
