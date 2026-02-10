@extends('layouts.app')
@section('content')
<div class="mb-6 fade-in">
    <h2 class="text-2xl font-bold mb-1">Task Management</h2>
    <p class="text-gray-400">Manage your tasks.</p>
</div>

<div class="container mx-auto px-4 py-8">
    {{-- Filter Form --}}
    <div class="card shadow-xl rounded-2xl p-6 mb-8 transform transition-all duration-300 hover:scale-[1.01] hover:shadow-2xl">
        <form action="{{ route('tickets.index') }}" method="GET" class="grid grid-cols-2 md:grid-cols-3 gap-6">
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

            <!-- Filter by User dihilangkan -->

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
                <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white font-bold py-2.5 px-6 rounded-lg shadow-md hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    <i class="mr-2 fas fa-filter"></i>Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Ticket Grid with Enhanced Card Design --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tickets as $ticket)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-l-4
            @switch($ticket->priority)
                @case('high') border-red-500 @break
                @case('medium') border-yellow-500 @break
                @case('low') border-green-500 @break
                @default border-gray-300
            @endswitch">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white">{{ $ticket->title }}</h3>
                    <span class="
                        px-3 py-1 rounded-full text-xs font-medium
                        @switch($ticket->status)
                            @case('open') bg-blue-100 text-blue-800 @break
                            @case('in_progress') bg-yellow-100 text-yellow-800 @break
                            @case('completed') bg-green-100 text-green-800 @break
                            @default bg-gray-100 text-gray-800
                        @endswitch
                    ">
                        {{ ucfirst($ticket->status) }}
                    </span>
                </div>

                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                        <i class="fas fa-project-diagram mr-2 text-indigo-500"></i>
                        <span>{{ optional($projects->firstWhere('id', $ticket->project_id))->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                        <i class="fas fa-calendar-alt mr-2 text-purple-500"></i>
                        <span>{{ $ticket->due_date ? \Carbon\Carbon::parse($ticket->due_date)->format('d M Y') : 'No due date' }}</span>
                    </div>
                </div>

                <div class="flex space-x-2">
                    <form action="{{ route('tickets.updateStatus', $ticket->id) }}" method="POST" class="flex-grow">
                        @csrf
                        @method('PATCH')
                        <div class="flex items-center space-x-2">
                            <select name="status" class="w-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 text-sm">
                                <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ $ticket->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-2 rounded-lg text-sm flex items-center">
                                <i class="fas fa-sync mr-1"></i> Update
                            </button>
                        </div>
                    </form>
                    <button onclick="openModal('viewTicketModal-{{ $ticket->id }}')" class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded-lg text-sm flex items-center">
                        <i class="fas fa-eye mr-1"></i> View
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 bg-gray-100 dark:bg-gray-800 rounded-xl">
            <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
            <p class="text-gray-600 dark:text-gray-300">No tickets found. Create your first ticket!</p>
        </div>
        @endforelse
    </div>
</div>

{{-- Modal untuk menampilkan detail tiket --}}
@foreach($tickets as $ticket)
<div id="viewTicketModal-{{ $ticket->id }}" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-[#1E2029] rounded-2xl shadow-2xl w-full max-w-md">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-500 rounded-t-2xl p-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                    <span class="text-purple-600 font-bold text-lg">T</span>
                </div>
                <h3 class="text-white font-semibold">Ticket Details</h3>
            </div>
            <button onclick="closeModal('viewTicketModal-{{ $ticket->id }}')" class="text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- Konten Detail Tiket --}}
        <div class="p-4 space-y-4">
            @php
                $detailFields = [
                    ['icon' => 'fas fa-info-circle', 'label' => 'Description', 'value' => $ticket->description ?? 'No description provided'],
                    ['icon' => 'fas fa-project-diagram', 'label' => 'Project', 'value' => optional($projects->firstWhere('id', $ticket->project_id))->name ?? 'N/A'],
                    ['icon' => 'fas fa-calendar-alt', 'label' => 'Due Date', 'value' => $ticket->due_date ? \Carbon\Carbon::parse($ticket->due_date)->format('d M Y H:i') : 'No due date'],
                    ['icon' => 'fas fa-exclamation-triangle', 'label' => 'Priority', 'value' => ucfirst($ticket->priority), 'iconColor' => match($ticket->priority) {
                        'high' => 'text-red-500',
                        'medium' => 'text-yellow-500',
                        'low' => 'text-green-500',
                        default => 'text-gray-500'
                    }],
                    ['icon' => 'fas fa-check-circle', 'label' => 'Status', 'value' => ucfirst($ticket->status), 'iconColor' => match($ticket->status) {
                        'open' => 'text-blue-500',
                        'in_progress' => 'text-yellow-500',
                        'completed' => 'text-green-500',
                        default => 'text-gray-500'
                    }]
                ];
            @endphp

            @foreach($detailFields as $field)
                <div class="bg-[#2C2F3A] rounded-lg p-3 flex items-center space-x-3">
                    <div class="w-10 flex justify-center">
                        <i class="{{ $field['icon'] }} text-xl {{ $field['iconColor'] ?? 'text-purple-500' }}"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">{{ $field['label'] }}</p>
                        <p class="text-white">{{ $field['value'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Tombol Aksi --}}
        <div class="p-4 flex space-x-3">
            <button onclick="closeModal('viewTicketModal-{{ $ticket->id }}')" class="flex-1 bg-[#2C2F3A] text-white py-2 rounded-lg hover:bg-opacity-80 transition-colors">
                Close
            </button>
        </div>
    </div>
</div>
@endforeach

@endsection
