@extends('layouts.app')
@section('content')

<div class="container mx-auto px-4 py-6">
    <div class="mb-8 bg-gradient-to-r from-blue-600 to-purple-700 p-6 rounded-xl shadow-lg">
        <h2 class="text-3xl font-extrabold text-white tracking-wide animate-fade-in">
            Welcome back, {{ Auth::user()->name }} !
        </h2>
    </div>

    {{-- Bagian Kartu Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        @php
            $statsCards = [
                ['icon' => 'fa-folder', 'title' => 'Total Projects', 'value' => $totalProjects, 'color' => 'bg-blue-500'],
                ['icon' => 'fa-users',  'title' => 'Total Users',    'value' => $totalUsers,    'color' => 'bg-green-500'],
                ['icon' => 'fa-tasks',  'title' => 'Total Tasks',    'value' => $totalTasks,    'color' => 'bg-indigo-500'],
                ['icon' => 'fas fa-user-cog','title' => 'Total Roles','value' => $totalRoles,   'color' => 'bg-pink-500']
            ];
        @endphp

        @foreach($statsCards as $card)
            <div class="transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 flex items-center space-x-4">
                    <div class="{{ $card['color'] }} text-white p-4 rounded-lg">
                        <i class="fas {{ $card['icon'] }} text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm uppercase tracking-wider">
                            {{ $card['title'] }}
                        </p>
                        <h3 class="text-3xl font-bold text-gray-800 dark:text-white">
                            {{ $card['value'] }}
                        </h3>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Bagian Latest Tasks & Chart Task Status --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
        {{-- 1. Latest Tasks --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white">Latest Tasks</h3>
                <a href="{{ route('tasks.index') }}" class="text-blue-600 hover:text-blue-800 transition-colors">
                    View All Tasks
                </a>
            </div>

            <div class="grid grid-cols-1 gap-6">
                @foreach ($latestTasks as $task)
                    @php
                        // Menentukan warna badge status
                        $statusColors = [
                            'completed'   => 'bg-green-100 text-green-800',
                            'in_progress' => 'bg-yellow-100 text-yellow-800',
                            'open'        => 'bg-blue-100 text-blue-800'
                        ];
                        $statusColor = $statusColors[strtolower($task->status)] ?? 'bg-gray-100 text-gray-800';
                        $formattedStatus = ucwords(str_replace('_', ' ', $task->status));
                    @endphp

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Project: {{ $task->project->name ?? 'Unassigned' }}
                                </span>
                                <span class="text-xs text-gray-400 dark:text-gray-500">
                                    Due: {{ $task->due_date->format('d M Y') }}
                                </span>
                            </div>

                            <h4 class="text-xl font-bold text-gray-800 dark:text-white mb-2">
                                {{ $task->title }}
                            </h4>

                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    Task List: {{ $task->taskList->name ?? 'No List' }}
                                </span>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                    {{ $formattedStatus }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- 2. Chart Task Status & Pie (Number of members per project) --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg p-6">
            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Task Status Overview</h3>
            <div class="h-68 mb-8">
                <canvas id="taskStatusChart"></canvas>
            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Pie Chart: user per project --}}
                <div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Members per project</h3>
                    <canvas id="userPerProjectPie"></canvas>
                </div>

                {{-- Donut Chart: jumlah user per role --}}
                <div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Users per role</h3>
                    <canvas id="rolePerUserDonut"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Bagian Chart Tasks by List (per Project) --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg p-6 mt-10">
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Tasks by List (per Project)</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($projectTaskListData as $index => $projData)
                <div class="p-4 bg-gray-800 rounded-xl shadow-md">
                    <h4 class="text-xl font-semibold text-white mb-4">
                        {{ $projData['project_name'] }}
                    </h4>
                    <canvas id="projectTaskListChart{{ $index }}" class="w-full h-48"></canvas>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Bagian Chart Task Status per User --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg p-6 mt-10">
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Task Status per User</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($userStatusData as $index => $userData)
                <div class="p-4 bg-gray-800 rounded-xl shadow-md">
                    <h4 class="text-xl font-semibold text-white mb-4">
                        {{ $userData['user'] }}
                    </h4>
                    <canvas id="taskStatusChartUser{{ $index }}" class="w-full h-48"></canvas>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Bagian Tabel Project Status Overview --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg p-6 mt-10">
        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Project Status Overview</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Project
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total Tasks
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Completed
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            In Progress
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Open
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($projectStatus as $status)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $status['name'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $status['total'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                            {{ $status['completed'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-yellow-600">
                            {{ $status['in_progress'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600">
                            {{ $status['open'] }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Script Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctxBar = document.getElementById('taskStatusChart').getContext('2d');
    new Chart(ctxBar, {
        type: 'line',
        data: {
            labels: ['Completed', 'In Progress', 'Open'],
            datasets: [{
                data: [
                    {{ $taskStatusCounts['completed'] ?? 0 }},
                    {{ $taskStatusCounts['in_progress'] ?? 0 }},
                    {{ $taskStatusCounts['open'] ?? 0 }}
                ],
                backgroundColor: [
                    'rgba(34, 197, 94, 0.7)',
                    'rgba(245, 158, 11, 0.7)',
                    'rgba(59, 130, 246, 0.7)'
                ],
                borderColor: [
                    'rgb(34, 197, 94)',
                    'rgb(245, 158, 11)',
                    'rgb(59, 130, 246)'
                ],
                borderWidth: 1,
                borderRadius: 10,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)',
                        drawBorder: false
                    },
                    ticks: {
                        color: 'rgba(255, 255, 255, 0.7)',
                        precision: 0
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: 'rgba(255, 255, 255, 0.7)'
                    }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(255, 255, 255, 0.2)',
                    borderWidth: 1
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeOutQuart'
            }
        }
    });

    // --------------------------------------------------
    // 2. Pie Chart: Number of members per project
    // --------------------------------------------------
    const ctxPie = document.getElementById('userPerProjectPie').getContext('2d');
    const pieProjectLabels = @json($pieProjectLabels);
    const pieProjectData   = @json($pieProjectData);

    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: pieProjectLabels,
            datasets: [{
                data: pieProjectData,
                backgroundColor: [
                    '#F59E0B',
                    '#3B82F6',
                    '#10B981',
                    '#EF4444',
                    '#8B5CF6',
                    '#EC4899',
                    '#F97316',
                    '#14B8A6',
                    '#A855F7',
                    '#6B7280',
                ],
                borderColor: '#ffffff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: 'rgba(255, 255, 255, 0.7)'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(255, 255, 255, 0.2)',
                    borderWidth: 1
                }
            }
        }
    });

    // --------------------------------------------------
    // 3. Donut Chart: jumlah user per role
    // --------------------------------------------------
    const ctxDonut = document.getElementById('rolePerUserDonut').getContext('2d');
    const donutRoleLabels = @json($donutRoleLabels);
    const donutRoleData   = @json($donutRoleData);

    new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: donutRoleLabels,
            datasets: [{
                data: donutRoleData,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)',
                    // ...tambah warna sesuai jumlah role
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    // ...
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: 'rgba(255, 255, 255, 0.7)'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(255, 255, 255, 0.2)',
                    borderWidth: 1
                }
            }
        }
    });

    // --------------------------------------------------
    // 4. Bar Chart: Task Status per User
    // --------------------------------------------------
    const userStatusData = @json($userStatusData);
    userStatusData.forEach((userData, index) => {
        const ctxUser = document.getElementById(`taskStatusChartUser${index}`).getContext('2d');
        new Chart(ctxUser, {
            type: 'bar',
            data: {
                labels: ['Completed', 'In Progress', 'Open'],
                datasets: [{
                    label: 'Tasks',
                    data: [userData.completed, userData.in_progress, userData.open],
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.7)',
                        'rgba(245, 158, 11, 0.7)',
                        'rgba(59, 130, 246, 0.7)'
                    ],
                    borderColor: [
                        'rgb(34, 197, 94)',
                        'rgb(245, 158, 11)',
                        'rgb(59, 130, 246)'
                    ],
                    borderWidth: 1,
                    borderRadius: 10,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#fff'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            color: '#fff'
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(255, 255, 255, 0.2)',
                        borderWidth: 1
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            }
        });
    });

    // --------------------------------------------------
    // 5. Line Chart: Tasks by List (per Project)
    // --------------------------------------------------
    const projectTaskListData = @json($projectTaskListData);
    const colorPalette = [
        '#EF4444',
        '#F59E0B',
        '#10B981',
        '#3B82F6',
        '#8B5CF6',
        '#EC4899',
        '#F97316',
        '#14B8A6',
        '#A855F7',
        '#6B7280',
    ];

    projectTaskListData.forEach((projData, index) => {
        const ctxProj = document.getElementById(`projectTaskListChart${index}`).getContext('2d');
        const bgColors = projData.counts.map((_, i) => {
            return colorPalette[i % colorPalette.length] + '80'; // menambahkan alpha
        });
        const borderColors = projData.counts.map((_, i) => {
            return colorPalette[i % colorPalette.length];
        });
        new Chart(ctxProj, {
            type: 'line',
            data: {
                labels: projData.labels,
                datasets: [{
                    label: 'Total Tasks',
                    data: projData.counts,
                    backgroundColor: bgColors,
                    borderColor: borderColors,
                    borderWidth: 1,
                    borderRadius: 10,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#fff'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            color: '#fff'
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(255, 255, 255, 0.2)',
                        borderWidth: 1
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            }
        });
    });
});
</script>

<style>
    /* Agar chart tampak lebih rapi dengan background gelap */
    #taskStatusChart,
    #userPerProjectPie,
    #rolePerUserDonut, /* ID Donut chart */
    [id^="taskStatusChartUser"],
    [id^="projectTaskListChart"] {
        background: linear-gradient(to right, rgba(45, 55, 72, 0.9), rgba(26, 32, 44, 0.9));
        border-radius: 15px;
        padding: 15px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }
</style>

@endsection
