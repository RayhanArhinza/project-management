@extends('admin.includes.app')
@section('content')

<div class="mb-6 fade-in">
    <h2 class="text-2xl font-bold mb-1">Member Management</h2>
    <p class="text-gray-400">Manage your members.</p>
</div>
<div class="bg-white dark:bg-gray-900 shadow-xl rounded-2xl p-6 mb-8 transform transition-all duration-300 hover:scale-[1.01] hover:shadow-2xl">
    <form action="{{ route('members.index') }}" method="GET" class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <!-- Filter by Membership -->
        <div>
            <label for="membership_id" class="block text-sm font-medium text-gray-300 mb-2 tracking-wider">
                <i class="mr-2 text-blue-400 fas fa-users"></i>Filter by Membership
            </label>
            <select name="membership_id" id="membership_id" class="w-full bg-gray-800 border border-gray-700 text-gray-200 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-2.5 transition-all duration-200 hover:bg-gray-700">
                <option value="" class="bg-gray-800">-- All Memberships --</option>
                @foreach($memberships as $membership)
                    <option value="{{ $membership->id }}" {{ request('membership_id') == $membership->id ? 'selected' : '' }} class="bg-gray-800">
                        {{ $membership->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Filter Search untuk Nama dan Email -->
        <div>
            <label for="search" class="block text-sm font-medium text-gray-300 mb-2 tracking-wider">
                <i class="mr-2 text-green-400 fas fa-search"></i>Search by Name or Email
            </label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Masukkan kata kunci..."
                class="w-full bg-gray-800 border border-gray-700 text-gray-200 text-sm rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 block p-2.5 transition-all duration-200 hover:bg-gray-700">
        </div>

        <!-- Tombol Filter -->
        <div class="col-span-full flex">
            <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white font-bold py-2.5 px-6 rounded-lg shadow-md hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                <i class="mr-2 fas fa-filter"></i>Filter
            </button>
        </div>
    </form>
</div>

<!-- Members Table -->
<div class="bg-white dark:bg-gray-900 p-6 mb-8 slide-in" style="animation-delay: 0.7s">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold">Members List</h3>
        <!-- Tombol untuk memunculkan modal add member -->
        <button onclick="openModal('addMemberModal')" class="yellow-btn px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-plus mr-2"></i>Add New Member
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-gray-400 text-sm">
                    <th class="pb-3 font-medium">ID</th>
                    <th class="pb-3 font-medium">Name</th>
                    <th class="pb-3 font-medium">Email</th>
                    <th class="pb-3 font-medium">Membership</th>
                    <th class="pb-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $member)
                <tr class="border-b border-gray-800">
                    <td class="py-4 pr-4">{{ $member->id }}</td>
                    <td class="py-4 pr-4">
                        <p class="font-medium">{{ $member->name }}</p>
                    </td>
                    <td class="py-4 pr-4">
                        <p class="font-medium">{{ $member->email }}</p>
                    </td>
                    <td class="py-4 pr-4">
                        <p class="font-medium">{{ $member->membership ? $member->membership->name : 'N/A' }}</p>
                    </td>
                    <td class="py-4">
                        <button onclick="openModal('viewMemberModal-{{ $member->id }}')" class="text-gray-400 hover:text-white mr-2" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="openModal('editMemberModal-{{ $member->id }}')" class="text-gray-400 hover:text-white mr-2" title="Edit Member">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="openModal('deleteMemberModal-{{ $member->id }}')" class="text-gray-400 hover:text-white" title="Delete Member">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add Member Modal -->
<div id="addMemberModal" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Add New Member</h3>
                <button onclick="closeModal('addMemberModal')" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('members.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2" for="memberName">Name</label>
                    <input type="text" name="name" id="memberName" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2" for="memberEmail">Email</label>
                    <input type="email" name="email" id="memberEmail" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2" for="memberPassword">Password</label>
                    <input type="password" name="password" id="memberPassword" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2" for="memberPasswordConfirmation">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="memberPasswordConfirmation" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2" for="membershipSelect">Membership</label>
                    <select name="membership_id" id="membershipSelect" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                        <option value="">Select Membership</option>
                        @foreach($memberships as $membership)
                        <option value="{{ $membership->id }}">{{ $membership->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal('addMemberModal')" class="bg-gray-700 text-white px-6 py-2 rounded-lg mr-2">Cancel</button>
                    <button type="submit" class="yellow-btn px-6 py-2 rounded-lg">Add Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View, Edit, Delete Modals untuk tiap member -->
@foreach($members as $member)
    <!-- View Member Modal -->
    <div id="viewMemberModal-{{ $member->id }}" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Member Details</h3>
                    <button onclick="closeModal('viewMemberModal-{{ $member->id }}')" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mb-4">
                    <p class="text-gray-400 text-sm">Member ID</p>
                    <p class="font-medium">{{ $member->id }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-gray-400 text-sm">Name</p>
                    <p class="font-medium text-lg">{{ $member->name }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-gray-400 text-sm">Email</p>
                    <p class="font-medium">{{ $member->email }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-gray-400 text-sm">Membership</p>
                    <p class="font-medium">{{ $member->membership ? $member->membership->name : 'N/A' }}</p>
                </div>
                <!-- Tampilkan start_date dan end_date -->
                <div class="mb-4">
                    <p class="text-gray-400 text-sm">Start Date</p>
                    <p class="font-medium">{{ $member->start_date }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-gray-400 text-sm">End Date</p>
                    <p class="font-medium">{{ $member->end_date }}</p>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal('viewMemberModal-{{ $member->id }}')" class="bg-gray-700 text-white px-6 py-2 rounded-lg">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Member Modal -->
    <div id="editMemberModal-{{ $member->id }}" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Edit Member</h3>
                    <button onclick="closeModal('editMemberModal-{{ $member->id }}')" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('members.update', $member->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm mb-2" for="editMemberName-{{ $member->id }}">Name</label>
                        <input type="text" name="name" id="editMemberName-{{ $member->id }}" value="{{ $member->name }}" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm mb-2" for="editMemberEmail-{{ $member->id }}">Email</label>
                        <input type="email" name="email" id="editMemberEmail-{{ $member->id }}" value="{{ $member->email }}" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                    </div>
                    <!-- Optional: field untuk password apabila ingin diperbarui -->
                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm mb-2" for="editMemberPassword-{{ $member->id }}">Password <span class="text-xs text-gray-500">(Kosongkan jika tidak ingin diubah)</span></label>
                        <input type="password" name="password" id="editMemberPassword-{{ $member->id }}" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm mb-2" for="editMembershipSelect-{{ $member->id }}">Membership</label>
                        <select name="membership_id" id="editMembershipSelect-{{ $member->id }}" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                            <option value="">Select Membership</option>
                            @foreach($memberships as $membership)
                            <option value="{{ $membership->id }}" @if($membership->id == $member->membership_id) selected @endif>
                                {{ $membership->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="closeModal('editMemberModal-{{ $member->id }}')" class="bg-gray-700 text-white px-6 py-2 rounded-lg mr-2">Cancel</button>
                        <button type="submit" class="yellow-btn px-6 py-2 rounded-lg">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Member Modal -->
    <div id="deleteMemberModal-{{ $member->id }}" class="fixed inset-0 z-50 hidden overflow-auto flex = items-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Delete Member</h3>
                    <button onclick="closeModal('deleteMemberModal-{{ $member->id }}')" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mb-6 text-center">
                    <div class="inline-flex items-center justify-center bg-red-900 text-red-300 w-16 h-16 rounded-full mb-6">
                        <i class="fas fa-exclamation-triangle text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Are you sure?</h3>
                    <p class="text-gray-400">You are about to delete the member <strong>"{{ $member->name }}"</strong>. This action cannot be undone.</p>
                </div>
                <form action="{{ route('members.destroy', $member->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end">
                        <button type="button" onclick="closeModal('deleteMemberModal-{{ $member->id }}')" class="bg-gray-700 text-white px-6 py-2 rounded-lg mr-2">Cancel</button>
                        <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@endsection
