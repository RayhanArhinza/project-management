@extends('admin.includes.app')
@section('content')

<div class="mb-6 fade-in mt-6">
    <h2 class="text-2xl font-bold mb-1">Transaction Management</h2>
    <p class="text-gray-400">Manage member transactions.</p>
</div>

<!-- Transactions Table -->
<div class="bg-white dark:bg-gray-900 p-6 mb-8 slide-in" style="animation-delay: 0.7s">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold">Transactions List</h3>
        <button onclick="openModal('addTransactionModal')" class="yellow-btn px-4 py-2 rounded-md text-sm font-medium">
            <i class="fas fa-plus mr-2"></i>Add New Transaction
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-gray-400 text-sm">
                    <th class="pb-3 font-medium">ID</th>
                    <th class="pb-3 font-medium">Transaction Code</th>
                    <th class="pb-3 font-medium">Member</th>
                    <th class="pb-3 font-medium">Membership</th>
                    <th class="pb-3 font-medium">Amount</th>
                    <th class="pb-3 font-medium">Status</th>
                    <th class="pb-3 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                <tr class="border-b border-gray-800">
                    <td class="py-4 pr-4">{{ $transaction->id }}</td>
                    <td class="py-4 pr-4">{{ $transaction->transaction_code }}</td>
                    <td class="py-4 pr-4">{{ $transaction->member->name }}</td>
                    <td class="py-4 pr-4">{{ $transaction->membership->name }}</td>
                    <td class="py-4 pr-4">${{ number_format($transaction->amount, 2) }}</td>
                    <td class="py-4 pr-4">
                        <span class="px-2 py-1 rounded {{ $transaction->status == 'approved' ? 'bg-green-600' : ($transaction->status == 'pending' ? 'bg-yellow-600' : 'bg-red-600') }} text-white text-sm">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </td>
                    <td class="py-4">
                        <button onclick="openModal('viewTransactionModal-{{ $transaction->id }}')" class="text-gray-400 hover:text-white mr-2" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        @if($transaction->status == 'pending')
                        <button onclick="openModal('approveTransactionModal-{{ $transaction->id }}')" class="text-gray-400 hover:text-white mr-2" title="Approve Transaction">
                            <i class="fas fa-check"></i>
                        </button>
                        <button onclick="openModal('rejectTransactionModal-{{ $transaction->id }}')" class="text-gray-400 hover:text-white" title="Reject Transaction">
                            <i class="fas fa-times"></i>
                        </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Fixed Add Transaction Modal -->
<div id="addTransactionModal" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Add New Transaction</h3>
                <button onclick="closeModal('addTransactionModal')" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.transactions.store') }}" method="POST" enctype="multipart/form-data" id="transactionForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2" for="member_id">Member</label>
                    <select name="member_id" id="member_id" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                        <option value="">Select Member</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                        @endforeach
                    </select>
                    @error('member_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2" for="membership_id">Membership</label>
                    <select name="membership_id" id="membership_id" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500" required>
                        <option value="">Select Membership</option>
                        @foreach($memberships as $membership)
                            <option value="{{ $membership->id }}">{{ $membership->name }} (${{ number_format($membership->price, 2) }})</option>
                        @endforeach
                    </select>
                    @error('membership_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-2" for="payment_proof">Payment Proof</label>
                    <div class="relative">
                        <input type="file"
                               name="payment_proof"
                               id="payment_proof"
                               accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                               class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-yellow-500 file:text-black hover:file:bg-yellow-400"
                               required
                               onchange="previewPaymentProof(this)">
                        <div class="text-xs text-gray-400 mt-1">
                            Supported formats: JPEG, PNG, JPG, GIF, WEBP (Max: 5MB)
                        </div>
                    </div>

                    <!-- Preview container -->
                    <div id="paymentProofPreview" class="mt-3 hidden">
                        <div class="text-sm text-gray-400 mb-2">Preview:</div>
                        <img id="previewImage" src="" alt="Payment Proof Preview" class="max-w-full h-32 object-cover rounded-lg border border-gray-600">
                        <button type="button" onclick="removePreview()" class="text-red-500 text-xs mt-1 hover:text-red-400">
                            <i class="fas fa-times"></i> Remove
                        </button>
                    </div>

                    @error('payment_proof')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="button" onclick="closeModal('addTransactionModal')" class="bg-gray-700 text-white px-6 py-2 rounded-lg mr-2">Cancel</button>
                    <button type="submit" class="yellow-btn px-6 py-2 rounded-lg" id="submitBtn">
                        <span id="submitText">Add Transaction</span>
                        <span id="loadingText" class="hidden">
                            <i class="fas fa-spinner fa-spin"></i> Uploading...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- View, Approve, Reject Modals untuk tiap transaksi -->
@foreach($transactions as $transaction)
<!-- View Transaction Modal -->
<div id="viewTransactionModal-{{ $transaction->id }}" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-3xl mx-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-white">Transaction Details</h3>
                <button onclick="closeModal('viewTransactionModal-{{ $transaction->id }}')" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column: Transaction Details -->
                <div class="space-y-4">
                    <div>
                        <p class="text-gray-400 text-sm">Transaction ID</p>
                        <p class="font-medium text-white">{{ $transaction->id }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Transaction Code</p>
                        <p class="font-medium text-white">{{ $transaction->transaction_code }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Member</p>
                        <p class="font-medium text-white">{{ $transaction->member->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Membership</p>
                        <p class="font-medium text-white">{{ $transaction->membership->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Amount</p>
                        <p class="font-medium text-white">${{ number_format($transaction->amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Status</p>
                        <p class="font-medium text-white">{{ ucfirst($transaction->status) }}</p>
                    </div>
                    @if($transaction->start_date)
                    <div>
                        <p class="text-gray-400 text-sm">Start Date</p>
                        <p class="font-medium text-white">{{ $transaction->start_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">End Date</p>
                        <p class="font-medium text-white">{{ $transaction->end_date->format('d M Y') }}</p>
                    </div>
                    @endif
                </div>
                <!-- Right Column: Payment Proof -->
                <div class="flex flex-col items-center">
                    <p class="text-gray-400 text-sm mb-2">Payment Proof</p>
                    <a href="{{ Storage::url($transaction->payment_proof) }}" target="_blank" class="group block">
                        <img src="{{ Storage::url($transaction->payment_proof) }}" alt="Payment Proof" class="w-full max-w-xs h-auto object-contain rounded-lg shadow-md transition-transform duration-200 group-hover:scale-105">
                    </a>
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button type="button" onclick="closeModal('viewTransactionModal-{{ $transaction->id }}')" class="bg-gray-700 text-white px-6 py-2 rounded-lg hover:bg-gray-600">Close</button>
            </div>
        </div>
    </div>
</div>

    <!-- Approve Transaction Modal -->
    @if($transaction->status == 'pending')
    <div id="approveTransactionModal-{{ $transaction->id }}" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Approve Transaction</h3>
                    <button onclick="closeModal('approveTransactionModal-{{ $transaction->id }}')" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mb-6 text-center">
                    <div class="inline-flex items-center justify-center bg-green-900 text-green-300 w-16 h-16 rounded-full mb-6">
                        <i class="fas fa-check text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Are you sure?</h3>
                    <p class="text-gray-400">You are about to approve transaction <strong>{{ $transaction->transaction_code }}</strong> for <strong>{{ $transaction->member->name }}</strong>.</p>
                </div>
                <form action="{{ route('admin.transactions.approve', $transaction->id) }}" method="POST">
                    @csrf
                    <div class="flex justify-end">
                        <button type="button" onclick="closeModal('approveTransactionModal-{{ $transaction->id }}')" class="bg-gray-700 text-white px-6 py-2 rounded-lg mr-2">Cancel</button>
                        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg">Approve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Transaction Modal -->
    <div id="rejectTransactionModal-{{ $transaction->id }}" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Reject Transaction</h3>
                    <button onclick="closeModal('rejectTransactionModal-{{ $transaction->id }}')" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mb-6 text-center">
                    <div class="inline-flex items-center justify-center bg-red-900 text-red-300 w-16 h-16 rounded-full mb-6">
                        <i class="fas fa-times text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Are you sure?</h3>
                    <p class="text-gray-400">You are about to reject transaction <strong>{{ $transaction->transaction_code }}</strong> for <strong>{{ $transaction->member->name }}</strong>.</p>
                </div>
                <form action="{{ route('admin.transactions.reject', $transaction->id) }}" method="POST">
                    @csrf
                    <div class="flex justify-end">
                        <button type="button" onclick="closeModal('rejectTransactionModal-{{ $transaction->id }}')" class="bg-gray-700 text-white px-6 py-2 rounded-lg mr-2">Cancel</button>
                        <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg">Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach
@endsection
