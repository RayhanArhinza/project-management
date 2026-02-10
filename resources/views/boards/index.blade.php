@extends('layouts.app')

@section('content')
<div class="mb-6 fade-in">
    <h2 class="text-2xl font-bold mb-1">Task Board</h2>
    <p class="text-gray-400">Manage your tasks with drag and drop functionality.</p>
</div>

<!-- Filter Project (tanpa tombol submit) -->
<div class="mb-4">
    <div class="flex flex-col">
        <label for="project_id" class="text-gray-300 mb-2">Project:</label>
        <select name="project_id" id="project_id" class="bg-gray-800 text-white px-2 py-3 rounded w-full">
            @foreach($projects as $project)
                <option value="{{ $project->id }}" {{ (isset($projectId) && $projectId == $project->id) ? 'selected' : '' }}>
                    {{ $project->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<!-- Tempat board akan dimuat -->
<div id="board-container">
    @include('boards._board')
</div>

<!-- Task Preview Modal -->
<div id="taskPreviewModal" class="fixed inset-0 z-50 hidden overflow-auto flex justify-center items-center px-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md mx-4 overflow-hidden">
        <div class="p-4 border-b border-gray-700 flex justify-between items-center">
            <h3 class="font-bold text-white text-lg" id="modalTaskTitle">Task Title</h3>
            <button id="closeTaskModal" class="text-gray-400 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-4">
            <div class="mb-4">
                <div class="flex justify-between items-center mb-2">
                    <div class="flex items-center">
                        <select id="taskStatusSelect" class="bg-gray-700 text-white text-xs py-1 px-2 rounded-full border border-gray-600 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="open">Open</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="blocked">Blocked</option>
                        </select>
                        <div class="ml-2 hidden" id="statusUpdateSpinner">
                            <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg"
                                 fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                    <span class="text-xs text-gray-400" id="modalTaskId">#123</span>
                </div>
                <div class="border-b border-gray-700 pb-3">
                    <h4 class="text-sm font-medium mb-1 text-gray-400">Description</h4>
                    <p class="text-sm text-white" id="modalTaskDescription">Task description goes here.</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h4 class="text-xs font-medium mb-1 text-gray-400">Project</h4>
                    <p class="text-sm text-white" id="modalTaskProject">Project Name</p>
                </div>
                <div>
                    <h4 class="text-xs font-medium mb-1 text-gray-400">Due Date</h4>
                    <p class="text-sm text-white" id="modalTaskDueDate">Mar 15</p>
                </div>
                <div class="col-span-2">
                    <h4 class="text-xs font-medium mb-1 text-gray-400">Assigned To</h4>
                    <div class="flex items-center">
                        <div class="avatar w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center text-xs text-white" id="modalTaskUserAvatar">
                            U
                        </div>
                        <span class="ml-2 text-sm text-white" id="modalTaskUser">Username</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-2">
                <button id="closeModalBtn" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm rounded-md transition-colors duration-200">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let draggedTask = null;
    let currentTaskId = null;

    // Fungsi untuk bind event drag & drop pada task card dan task list
    function bindDragAndDrop() {
        // Bind event untuk task card
        document.querySelectorAll('.task-card').forEach(task => {
            task.addEventListener('dragstart', function (e) {
                draggedTask = this;
                e.dataTransfer.setData('task-id', this.dataset.taskId);
            });
        });

        // Bind event untuk task list
        document.querySelectorAll('.task-list').forEach(list => {
            list.addEventListener('dragover', function (e) {
                e.preventDefault();
            });
            list.addEventListener('drop', function (e) {
                e.preventDefault();
                if (!draggedTask) return;
                let taskId = draggedTask.dataset.taskId;
                let newListId = this.dataset.listId;

                // Pindahkan task secara visual
                this.appendChild(draggedTask);

                // Kirim request untuk update posisi task
                fetch('{{ route('tasks.update-position') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ taskId, listId: newListId })
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById(`task-count-${data.sourceListId}`).textContent =
                        `${data.sourceListCount} tasks`;
                    document.getElementById(`task-count-${data.targetListId}`).textContent =
                        `${data.targetListCount} tasks`;
                })
                .catch(error => {
                    console.error('Error updating task position:', error);
                });
            });
        });
    }

    // Fungsi untuk bind event pada tombol view task (modal)
    function bindViewTaskModal() {
        document.querySelectorAll('.view-task-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();

                const taskId = this.dataset.taskId;
                const taskTitle = this.dataset.taskTitle;
                const taskDescription = this.dataset.taskDescription;
                const taskStatus = this.dataset.taskStatus;
                const taskProject = this.dataset.taskProject;
                const taskDueDate = this.dataset.taskDueDate;
                const taskUser = this.dataset.taskUser;

                currentTaskId = taskId;

                document.getElementById('modalTaskId').textContent = `#${taskId}`;
                document.getElementById('modalTaskTitle').textContent = taskTitle;
                document.getElementById('modalTaskDescription').textContent = taskDescription;
                document.getElementById('taskStatusSelect').value = taskStatus;
                document.getElementById('modalTaskProject').textContent = taskProject;
                document.getElementById('modalTaskDueDate').textContent = taskDueDate;
                document.getElementById('modalTaskUser').textContent = taskUser;
                if (taskUser && taskUser !== 'Unassigned') {
                    document.getElementById('modalTaskUserAvatar').textContent = taskUser.charAt(0);
                } else {
                    document.getElementById('modalTaskUserAvatar').textContent = '?';
                }

                document.getElementById('taskPreviewModal').classList.remove('hidden');
            });
        });
    }

    // Bind event awal
    bindDragAndDrop();
    bindViewTaskModal();

    // Modal events
    const modal = document.getElementById('taskPreviewModal');
    const closeModal = document.getElementById('closeTaskModal');
    const closeModalBtn = document.getElementById('closeModalBtn');

    closeModal.addEventListener('click', () => modal.classList.add('hidden'));
    closeModalBtn.addEventListener('click', () => modal.classList.add('hidden'));
    modal.addEventListener('click', e => {
        if (e.target === modal) modal.classList.add('hidden');
    });

    // Update task status via modal
    document.getElementById('taskStatusSelect').addEventListener('change', function() {
        if (!currentTaskId) return;
        const newStatus = this.value;
        const statusUpdateSpinner = document.getElementById('statusUpdateSpinner');
        statusUpdateSpinner.classList.remove('hidden');

        fetch('{{ route('tasks.update-status') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                taskId: currentTaskId,
                status: newStatus
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const taskCard = document.querySelector(`.task-card[data-task-id="${currentTaskId}"]`);
                if (taskCard) {
                    const statusBadge = taskCard.querySelector('span:nth-child(2)');
                    statusBadge.textContent =
                        newStatus.charAt(0).toUpperCase() + newStatus.replace('_', ' ').slice(1);
                    statusBadge.className = 'px-2 py-1 text-xs rounded-full text-white';
                    if (newStatus === 'completed') {
                        statusBadge.classList.add('bg-green-500');
                    } else if (newStatus === 'open') {
                        statusBadge.classList.add('bg-blue-500');
                    } else if (newStatus === 'in_progress') {
                        statusBadge.classList.add('bg-yellow-500');
                    } else {
                        statusBadge.classList.add('bg-yellow-500'); // untuk 'blocked'
                    }

                    const viewBtn = taskCard.querySelector('.view-task-btn');
                    if (viewBtn) {
                        viewBtn.dataset.taskStatus = newStatus;
                    }
                }
            } else {
                alert('Failed to update status: ' + data.message);
            }
            statusUpdateSpinner.classList.add('hidden');
        })
        .catch(error => {
            console.error('Error updating task status:', error);
            statusUpdateSpinner.classList.add('hidden');
            alert('An error occurred while updating the status');
        });
    });

    // Event AJAX: Update board ketika dropdown filter berubah
    document.getElementById('project_id').addEventListener('change', function(){
        const projectId = this.value;
        fetch(`{{ route('boards.index') }}?project_id=` + projectId, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
             document.getElementById('board-container').innerHTML = html;
             // Re-bind event untuk drag & drop dan modal pada konten baru
             bindDragAndDrop();
             bindViewTaskModal();
        })
        .catch(error => console.error('Error fetching board:', error));
    });
});
</script>
@endsection
