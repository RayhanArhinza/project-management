<div class="board-container overflow-x-auto pb-6">
    <div class="flex space-x-4 min-w-max p-4 bg-gray-900 rounded-xl overflow-x-auto">
        @foreach($taskLists as $list)
            <div class="board-column w-80 flex-shrink-0">
                <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 hover:border-blue-500 transition-all duration-300">
                    <div class="p-4 border-b border-gray-700 flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-white text-lg">{{ $list->name }}</h3>
                            <p class="text-xs text-gray-400 task-count" id="task-count-{{ $list->id }}">
                                {{ $list->tasks->count() }} tasks
                            </p>
                        </div>
                        <div class="bg-gray-700 rounded-full h-8 w-8 flex items-center justify-center">
                            <span class="text-white text-sm font-bold">{{ $list->tasks->count() }}</span>
                        </div>
                    </div>
                    <div class="task-list p-3 min-h-80 max-h-[calc(100vh-200px)] overflow-y-auto" data-list-id="{{ $list->id }}">
                        @foreach($list->tasks as $task)
                            <div class="task-card bg-gray-700 p-4 mb-3 rounded-lg shadow-md cursor-move transform hover:scale-[1.02] hover:shadow-lg transition-all duration-200"
                                 draggable="true"
                                 data-task-id="{{ $task->id }}">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xs font-medium text-gray-400">#{{ $task->id }}</span>
                                    <span class="px-2 py-1 text-xs rounded-full text-white {{ $task->status == 'completed' ? 'bg-green-500' : ($task->status == 'in_progress' ? 'bg-yellow-500' : 'bg-blue-500') }}">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                </div>
                                <h4 class="text-sm font-medium mb-2 text-white">{{ $task->title }}</h4>
                                <p class="text-xs text-gray-300 mb-3 line-clamp-2">{{ $task->description }}</p>
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-xs px-2 py-1 bg-gray-600 rounded-md text-gray-300">
                                        {{ $task->project->name }}
                                    </span>
                                    @if($task->due_date)
                                        <span class="text-xs px-2 py-1 bg-gray-600 rounded-md text-gray-300">
                                            <i class="far fa-clock mr-1"></i>{{ $task->due_date->format('M d') }}
                                        </span>
                                    @endif
                                </div>
                                @if($task->user)
                                    <div class="flex items-center mt-2 pt-2 border-t border-gray-600">
                                        <div class="avatar w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center text-xs text-white">
                                            {{ substr($task->user->name, 0, 1) }}
                                        </div>
                                        <span class="ml-2 text-xs text-gray-300">{{ $task->user->name }}</span>
                                    </div>
                                @endif
                                <div class="mt-3 pt-2 border-t border-gray-600">
                                    <button
                                        type="button"
                                        class="view-task-btn w-full bg-gray-600 hover:bg-blue-600 text-white text-xs py-2 rounded-md transition-colors duration-200 flex items-center justify-center"
                                        data-task-id="{{ $task->id }}"
                                        data-task-title="{{ $task->title }}"
                                        data-task-description="{{ $task->description }}"
                                        data-task-status="{{ $task->status }}"
                                        data-task-project="{{ $task->project->name }}"
                                        data-task-due-date="{{ $task->due_date ? $task->due_date->format('M d, Y') : 'None' }}"
                                        data-task-user="{{ $task->user ? $task->user->name : 'Unassigned' }}"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
