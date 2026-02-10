<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalyst - Modern Project Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            300: '#fcd34d', // yellow-300
                            400: '#fbbf24', // yellow-400
                            500: '#f59e0b', // yellow-500
                        },
                        dark: {
                            800: '#1f2937', // gray-800
                            900: '#111827', // gray-900
                        }
                    },
                    keyframes: {
                        floating: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    },
                    animation: {
                        floating: 'floating 3s ease-in-out infinite',
                    }
                }
            }
        }
    </script>
        <style>
            /* Sidebar transition */
            .sidebar-transition {
                transition: transform 0.3s ease-in-out;
            }

            /* Overlay transition */
            .overlay-transition {
                transition: opacity 0.3s ease-in-out;
            }
        </style>
</head>
<body class="bg-dark-900 text-white">
    <!-- Header & Navigation -->
<header class="bg-black sticky top-0 z-50">
    <div class="container mx-auto px-4 py-3 flex items-center justify-between">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-primary-500 rounded-full flex items-center justify-center mr-3">
                <i class="fas fa-project-diagram text-black text-xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-primary-500">Catalyst</h1>
        </div>

        <nav class="hidden md:flex items-center space-x-8">
            <a href="#home" class="text-gray-300 hover:text-primary-400 transition">Home</a>
            <a href="#features" class="text-gray-300 hover:text-primary-400 transition">Features</a>
            <a href="#pricing" class="text-gray-300 hover:text-primary-400 transition">Pricing</a>
            <a href="#testimonials" class="text-gray-300 hover:text-primary-400 transition">Testimonials</a>
        </nav>

        <div class="hidden md:flex items-center space-x-4">
            @auth('member')
                <a href="{{ route('member.dashboard') }}" class="bg-primary-500 text-black px-4 py-2 rounded-lg hover:bg-primary-400 transition">
                    <i class="fas fa-home text-lg yellow-text w-6"></i>Member Dashboard
                </a>
            @elseauth('admin')
                <a href="{{ route('admin.dashboard') }}" class="bg-primary-500 text-black px-4 py-2 rounded-lg hover:bg-primary-400 transition">
                    <i class="fas fa-home text-lg yellow-text w-6"></i>Admin Dashboard
                </a>
            @elseauth
                <a href="{{ route('dashboard') }}" class="bg-primary-500 text-black px-4 py-2 rounded-lg hover:bg-primary-400 transition">
                    <i class="fas fa-home text-lg yellow-text w-6"></i>Dashboard
                </a>
            @else
                <a href="{{ route('member.auth.login') }}" class="text-primary-500 hover:text-primary-400 transition">Log In</a>
                <a href="{{ route('member.auth.register') }}" class="bg-primary-500 text-black px-4 py-2 rounded-lg hover:bg-primary-400 transition">Sign Up Free</a>
            @endauth
        </div>

        <button id="mobile-menu-button" class="md:hidden text-gray-300 focus:outline-none">
            <i class="fas fa-bars text-2xl"></i>
        </button>
    </div>
</header>

    <!-- Mobile Sidebar Navigation -->
    <div id="mobile-menu-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden overlay-transition opacity-0"></div>

    <div id="mobile-sidebar" class="fixed top-0 right-0 h-full w-64 bg-dark-900 z-50 shadow-xl sidebar-transition transform translate-x-full">
        <div class="p-5 flex flex-col h-full">
            <div class="flex justify-between items-center mb-8">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center mr-2">
                        <i class="fas fa-project-diagram text-black text-sm"></i>
                    </div>
                    <h2 class="text-xl font-bold text-primary-500">Catalyst</h2>
                </div>
                <button id="close-sidebar" class="text-gray-400 hover:text-white focus:outline-none">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <nav class="flex flex-col space-y-5 flex-grow">
                <a href="#features" class="text-gray-300 hover:text-primary-400 transition text-lg">Features</a>
                <a href="#how-it-works" class="text-gray-300 hover:text-primary-400 transition text-lg">How It Works</a>
                <a href="#pricing" class="text-gray-300 hover:text-primary-400 transition text-lg">Pricing</a>
                <a href="#testimonials" class="text-gray-300 hover:text-primary-400 transition text-lg">Testimonials</a>
            </nav>

            <div class="pt-5 border-t border-gray-700 flex flex-col space-y-4">
                <a href="/login" class="text-primary-500 hover:text-primary-400 transition text-center py-2">Log In</a>
                <a href="/register" class="bg-primary-500 text-black px-4 py-3 rounded-lg hover:bg-primary-400 transition text-center font-medium">Sign Up Free</a>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="py-16 md:py-24 bg-gradient-to-br from-black to-dark-900" id="home">
        <div class="container mx-auto px-4 grid md:grid-cols-2 gap-12 items-center">
            <div class="text-center md:text-left">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
                    <span class="text-primary-500">Streamline</span> Your Project Management
                </h1>
                <p class="text-xl text-gray-300 mb-8">
                    Boost productivity with seamless collaboration, smart workflows, and intuitive task tracking.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                    <a href="/register" class="bg-primary-500 text-black px-8 py-4 rounded-lg text-lg font-semibold hover:bg-primary-400 transition">
                        Get Started Free
                    </a>
                    <a href="#demo" class="bg-transparent border-2 border-primary-500 text-primary-500 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-primary-500 hover:text-black transition">
                        Watch Demo
                    </a>
                </div>
                <div class="mt-10 flex items-center justify-center md:justify-start">
                    <div class="flex -space-x-2">
                        <div class="w-10 h-10 rounded-full bg-blue-500"></div>
                        <div class="w-10 h-10 rounded-full bg-green-500"></div>
                        <div class="w-10 h-10 rounded-full bg-red-500"></div>
                        <div class="w-10 h-10 rounded-full bg-purple-500"></div>
                    </div>
                    <p class="ml-4 text-gray-300">
                        <span class="text-primary-400 font-bold">5,000+</span> teams already using Catalyst
                    </p>
                </div>
            </div>

            <div class="relative">
                <div class="bg-dark-800 p-6 rounded-xl shadow-2xl relative z-10 animate-floating">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-chart-line text-black text-sm"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-primary-400">Dashboard Preview</h3>
                        </div>
                        <div class="flex space-x-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-dark-900 p-4 rounded-lg">
                            <h4 class="text-primary-500 font-semibold mb-2">Active Projects</h4>
                            <div class="text-3xl font-bold">12</div>
                            <div class="text-green-400 text-sm mt-2">
                                <i class="fas fa-arrow-up"></i> 8% from last week
                            </div>
                        </div>
                        <div class="bg-dark-900 p-4 rounded-lg">
                            <h4 class="text-primary-500 font-semibold mb-2">Open Tasks</h4>
                            <div class="text-3xl font-bold">34</div>
                            <div class="text-red-400 text-sm mt-2">
                                <i class="fas fa-arrow-down"></i> 3% from yesterday
                            </div>
                        </div>
                    </div>

                    <div class="bg-dark-900 p-4 rounded-lg mb-6">
                        <h4 class="text-primary-500 font-semibold mb-3">Recent Activities</h4>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex-shrink-0"></div>
                                <div class="ml-3">
                                    <p class="text-sm">Sarah updated <span class="text-primary-400">Homepage Redesign</span></p>
                                    <p class="text-xs text-gray-400">2 hours ago</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex-shrink-0"></div>
                                <div class="ml-3">
                                    <p class="text-sm">Mike created a new ticket <span class="text-primary-400">API Integration</span></p>
                                    <p class="text-xs text-gray-400">5 hours ago</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-sm text-gray-400">
                        <span>Last updated: Today, 11:42 AM</span>
                        <span>
                            <i class="fas fa-sync-alt mr-1"></i> Refresh
                        </span>
                    </div>
                </div>

                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full h-full bg-primary-500 rounded-full blur-3xl opacity-10 -z-10"></div>
            </div>
        </div>
    </section>

    <!-- Feature Highlights -->
    <section id="features" class="py-20 bg-black">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">
                    <span class="text-primary-500">Powerful Features</span> Built for Teams
                </h2>
                <p class="text-gray-300 max-w-2xl mx-auto">
                    Elevate your project management experience with tools designed for efficiency, collaboration, and results.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature Card 1: Ticket Tracking -->
                <div class="bg-dark-900 rounded-xl p-6 hover:shadow-lg hover:shadow-primary-500/10 transition duration-300 group hover:-translate-y-2">
                    <div class="w-14 h-14 bg-primary-500 bg-opacity-20 rounded-lg flex items-center justify-center mb-5 group-hover:bg-primary-500 transition duration-300">
                        <i class="fas fa-chart-line text-2xl text-primary-500 group-hover:text-black"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-primary-400">Ticket Tracking</h3>
                    <p class="text-gray-300">
                        Easily create, assign, and monitor tickets with customizable workflows. Set priorities, deadlines, and track progress in real-time.
                    </p>
                </div>

                <!-- Feature Card 2: Team Collaboration -->
                <div class="bg-dark-900 rounded-xl p-6 hover:shadow-lg hover:shadow-primary-500/10 transition duration-300 group hover:-translate-y-2">
                    <div class="w-14 h-14 bg-primary-500 bg-opacity-20 rounded-lg flex items-center justify-center mb-5 group-hover:bg-primary-500 transition duration-300">
                        <i class="fas fa-users text-2xl text-primary-500 group-hover:text-black"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-primary-400">Team Collaboration</h3>
                    <p class="text-gray-300">
                        Foster seamless teamwork with shared workspaces, real-time updates, and integrated communication tools for effortless collaboration.
                    </p>
                </div>

                <!-- Feature Card 3: Time Efficiency -->
                <div class="bg-dark-900 rounded-xl p-6 hover:shadow-lg hover:shadow-primary-500/10 transition duration-300 group hover:-translate-y-2">
                    <div class="w-14 h-14 bg-primary-500 bg-opacity-20 rounded-lg flex items-center justify-center mb-5 group-hover:bg-primary-500 transition duration-300">
                        <i class="fas fa-clock text-2xl text-primary-500 group-hover:text-black"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-primary-400">Time Efficiency</h3>
                    <p class="text-gray-300">
                        Save time with automated workflows, task templates, and intelligent scheduling that optimizes your team's productivity.
                    </p>
                </div>

                <!-- Feature Card 4: Project Management -->
                <div class="bg-dark-900 rounded-xl p-6 hover:shadow-lg hover:shadow-primary-500/10 transition duration-300 group hover:-translate-y-2">
                    <div class="w-14 h-14 bg-primary-500 bg-opacity-20 rounded-lg flex items-center justify-center mb-5 group-hover:bg-primary-500 transition duration-300">
                        <i class="fas fa-folder-open text-2xl text-primary-500 group-hover:text-black"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-primary-400">Project Management</h3>
                    <p class="text-gray-300">
                        Organize work with flexible project structures, milestones, and dependencies to keep every initiative on track from start to finish.
                    </p>
                </div>

                <!-- Feature Card 5: Task Organization -->
                <div class="bg-dark-900 rounded-xl p-6 hover:shadow-lg hover:shadow-primary-500/10 transition duration-300 group hover:-translate-y-2">
                    <div class="w-14 h-14 bg-primary-500 bg-opacity-20 rounded-lg flex items-center justify-center mb-5 group-hover:bg-primary-500 transition duration-300">
                        <i class="fas fa-tasks text-2xl text-primary-500 group-hover:text-black"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-primary-400">Task Organization</h3>
                    <p class="text-gray-300">
                        Break down complex projects into manageable tasks with subtasks, checklists, and custom fields for complete clarity and focus.
                    </p>
                </div>

                <!-- Feature Card 6: Visual Boards -->
                <div class="bg-dark-900 rounded-xl p-6 hover:shadow-lg hover:shadow-primary-500/10 transition duration-300 group hover:-translate-y-2">
                    <div class="w-14 h-14 bg-primary-500 bg-opacity-20 rounded-lg flex items-center justify-center mb-5 group-hover:bg-primary-500 transition duration-300">
                        <i class="fas fa-th-large text-2xl text-primary-500 group-hover:text-black"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-primary-400">Visual Boards</h3>
                    <p class="text-gray-300">
                        Get a bird's-eye view of your projects with interactive Kanban boards that help visualize workflow and identify bottlenecks.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- User Roles Section -->
    <section class="py-20 bg-gradient-to-br from-dark-900 to-black">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">
                    <span class="text-primary-500">Tailored for Everyone</span> in Your Team
                </h2>
                <p class="text-gray-300 max-w-2xl mx-auto">
                    Catalyst adapts to different roles and requirements to ensure everyone gets exactly what they need.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Admin Role -->
                <div class="bg-black bg-opacity-60 rounded-xl p-8 border border-gray-800 relative overflow-hidden group hover:border-primary-500 transition duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-500 to-primary-300 opacity-0 group-hover:opacity-5 transition duration-300"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-primary-500 bg-opacity-20 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-user-shield text-2xl text-primary-500"></i>
                        </div>
                        <h3 class="text-2xl font-semibold mb-4 text-primary-400">Administrators</h3>
                        <ul class="space-y-3 text-gray-300">
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary-500 mt-1 mr-3"></i>
                                <span>Complete system configuration and control</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary-500 mt-1 mr-3"></i>
                                <span>User management and role assignments</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary-500 mt-1 mr-3"></i>
                                <span>Custom workflow creation and template setup</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary-500 mt-1 mr-3"></i>
                                <span>Security settings and access control</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary-500 mt-1 mr-3"></i>
                                <span>Analytics and reporting across all projects</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Project Manager Role -->
                <div class="bg-black bg-opacity-60 rounded-xl p-8 border border-gray-800 relative overflow-hidden group hover:border-primary-500 transition duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-500 to-primary-300 opacity-0 group-hover:opacity-5 transition duration-300"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-primary-500 bg-opacity-20 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-clipboard-check text-2xl text-primary-500"></i>
                        </div>
                        <h3 class="text-2xl font-semibold mb-4 text-primary-400">Project Managers</h3>
                        <ul class="space-y-3 text-gray-300">
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary-500 mt-1 mr-3"></i>
                                <span>Full project planning and resource allocation</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary-500 mt-1 mr-3"></i>
                                <span>Task delegation and team management</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary-500 mt-1 mr-3"></i>
                                <span>Progress tracking and milestone monitoring</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary-500 mt-1 mr-3"></i>
                                <span>Deadline management and scheduling</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary-500 mt-1 mr-3"></i>
                                <span>Project-specific reporting and dashboards</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Team Member Role -->
                <div class="bg-black bg-opacity-60 rounded-xl p-8 border border-gray-800 relative overflow-hidden group hover:border-primary-500 transition duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-500 to-primary-300 opacity-0 group-hover:opacity-5 transition duration-300"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-primary-500 bg-opacity-20 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-user-friends text-2xl text-primary-500"></i>
                        </div>
                        <h3 class="text-2xl font-semibold mb-4 text-primary-400">Team Members</h3>
                        <ul class="space-y-3 text-gray-300">
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary-500 mt-1 mr-3"></i>
                                <span>Personal task view and management</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary-500 mt-1 mr-3"></i>
                                <span>Time tracking and progress updates</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary-500 mt-1 mr-3"></i>
                                <span>Collaborative document editing</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary-500 mt-1 mr-3"></i>
                                <span>Communication with team members</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-primary-500 mt-1 mr-3"></i>
                                <span>File sharing and asset management</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-20 bg-black relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-1/2 left-1/4 w-64 h-64 bg-primary-500 rounded-full blur-3xl"></div>
            <div class="absolute bottom-1/4 right-1/3 w-48 h-48 bg-blue-500 rounded-full blur-3xl"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto bg-gradient-to-br from-dark-900 to-black p-12 rounded-2xl border border-gray-800 shadow-xl">
                <div class="text-center mb-8">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">
                        Ready to <span class="text-primary-500">Catalyze</span> Your Team's Productivity?
                    </h2>
                    <p class="text-xl text-gray-300">
                        Join thousands of teams already using Catalyst to streamline their workflows.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/register" class="bg-primary-500 text-black px-8 py-4 rounded-lg text-lg font-semibold hover:bg-primary-400 transition">
                        Start Your Free Trial
                    </a>
                    <a href="/contact" class="bg-transparent border-2 border-primary-500 text-primary-500 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-primary-500 hover:text-black transition">
                        Schedule a Demo
                    </a>
                </div>

                <div class="mt-8 text-center text-gray-400">
                    <p>No credit card required. 14-day free trial. Cancel anytime.</p>
                </div>
            </div>
        </div>
    </section>
        <!-- Pricing Section -->
        <section id="pricing" class="py-12 md:py-16 relative">
            <div class="container mx-auto px-6">
                <div class="text-center max-w-2xl mx-auto mb-16">

                    <h2 class="text-3xl md:text-4xl font-bold mb-4 text-yellow-500">Plans for teams of all sizes</h2>
                    <p class="text-gray-300">Choose the perfect plan for your team's needs. All plans include core features.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-6">
                    <!-- Free Plan -->
                    <div class="bg-dark-800 p-6 rounded-xl shadow-lg flex flex-col h-full">
                        <h3 class="text-xl font-semibold text-primary-500">Free</h3>
                        <p class="text-gray-400">Perfect for small teams</p>
                        <p class="text-3xl font-bold mt-4">$0 <span class="text-sm text-gray-500">/month</span></p>
                        <ul class="mt-4 space-y-2 text-gray-300 flex-grow">
                            <li>✔ Up to 5 team members</li>
                            <li>✔ 5 boards</li>
                            <li>✔ Basic task tracking</li>
                            <li>✔ File sharing (100MB)</li>
                        </ul>
                        <a href="#" class="mt-auto bg-primary-500 text-black py-3 text-center rounded-lg font-semibold hover:bg-primary-400 transition">
                            Get Started
                        </a>
                    </div>

                    <!-- Pro Plan (Most Popular) -->
                    <div class="bg-dark-800 p-6 rounded-xl shadow-lg flex flex-col h-full border-2 border-primary-500">
                        <span class="bg-primary-500 text-black text-xs font-bold py-1 px-3 rounded-full self-start">Most Popular</span>
                        <h3 class="text-xl font-semibold text-primary-500 mt-2">Pro</h3>
                        <p class="text-gray-400">For growing teams</p>
                        <p class="text-3xl font-bold mt-2">$12 <span class="text-sm text-gray-500">/user/month</span></p>
                        <ul class="mt-4 mb-4 space-y-2 text-gray-300 flex-grow">
                            <li>✔ Unlimited team members</li>
                            <li>✔ Unlimited boards</li>
                            <li>✔ Advanced reporting</li>
                            <li>✔ File sharing (10GB)</li>
                            <li>✔ Custom workflows</li>
                            <li>✔ Guest access</li>
                        </ul>
                        <a href="#" class="mt-auto bg-primary-500 text-black py-3 text-center rounded-lg font-semibold hover:bg-primary-400 transition">
                            Try Pro Free
                        </a>
                    </div>

                    <!-- Enterprise Plan -->
                    <div class="bg-dark-800 p-6 rounded-xl shadow-lg flex flex-col h-full">
                        <h3 class="text-xl font-semibold text-primary-500">Enterprise</h3>
                        <p class="text-gray-400">For large organizations</p>
                        <p class="text-3xl font-bold mt-4">$20 <span class="text-sm text-gray-500">/user/month</span></p>
                        <ul class="mt-4 space-y-2 text-gray-300 flex-grow">
                            <li>✔ Everything in Pro</li>
                            <li>✔ SSO & advanced security</li>
                            <li>✔ Unlimited file storage</li>
                            <li>✔ 24/7 priority support</li>
                            <li>✔ Custom integrations</li>
                            <li>✔ Dedicated success manager</li>
                        </ul>
                        <a href="#" class="mt-auto bg-primary-500 text-black py-3 text-center rounded-lg font-semibold hover:bg-primary-400 transition">
                            Try Pro Free
                        </a>
                    </div>
                </div>

            </div>
        </section>
        <!-- Testimonials -->
    <section id="testimonials" class="py-12 bg-black">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-4 text-yellow-500">What our customers say</h2>
                <p class="text-gray-300">Teams of all sizes love using Catalyst to manage their projects</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700 hover:border-yellow-500 transition duration-300">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-500">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-300 mb-6">"Catalyst has transformed how our team collaborates. We've reduced our meeting time by 35% and shipped projects 40% faster."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-500 rounded-full mr-4 flex items-center justify-center">
                            <span class="text-black font-bold">SN</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-yellow-400">Sarah Nelson</h4>
                            <p class="text-gray-400 text-sm">Product Manager, Dropbox</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700 hover:border-yellow-500 transition duration-300">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-500">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-300 mb-6">"The customizable workflows in Catalyst allow us to adapt the tool to our specific needs rather than changing our process to fit the software."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-500 rounded-full mr-4 flex items-center justify-center">
                            <span class="text-black font-bold">JK</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-yellow-400">James Kim</h4>
                            <p class="text-gray-400 text-sm">CTO, MediTech</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700 hover:border-yellow-500 transition duration-300">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-500">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-300 mb-6">"We've tried numerous project management tools, but Catalyst is the only one our entire team has enthusiastically adopted. The interface is intuitive and the features are powerful."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-500 rounded-full mr-4 flex items-center justify-center">
                            <span class="text-black font-bold">ML</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-yellow-400">Maria Lopez</h4>
                            <p class="text-gray-400 text-sm">Director of Operations, Airbnb</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark-900 py-12 border-t border-gray-800">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-primary-500 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-project-diagram text-black text-xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-primary-500">Catalyst</h2>
                    </div>
                    <p class="text-gray-400 mb-6">
                        Boost productivity with seamless project management and smart workflows.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-primary-500 transition">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-primary-500 transition">
                            <i class="fab fa-linkedin text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-primary-500 transition">
                            <i class="fab fa-facebook text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-primary-500 transition">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-primary-400 mb-4">Product</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-primary-500 transition">Features</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary-500 transition">Pricing</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary-500 transition">Integrations</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary-500 transition">Roadmap</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary-500 transition">Release Notes</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-primary-400 mb-4">Resources</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-primary-500 transition">Documentation</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary-500 transition">Tutorials</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary-500 transition">Blog</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary-500 transition">Community</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary-500 transition">Support</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-primary-400 mb-4">Company</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-primary-500 transition">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary-500 transition">Careers</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary-500 transition">Contact</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary-500 transition">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-primary-500 transition">Terms of Service</a></li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-gray-800 text-center text-gray-500">
                <p>&copy; 2025 Catalyst. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile Menu Toggle - Updated for sidebar functionality
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const mobileOverlay = document.getElementById('mobile-menu-overlay');
            const closeSidebarButton = document.getElementById('close-sidebar');

            // Function to open the sidebar
            const openSidebar = () => {
                mobileSidebar.classList.remove('translate-x-full');
                mobileOverlay.classList.remove('hidden');
                setTimeout(() => {
                    mobileOverlay.classList.remove('opacity-0');
                }, 10);
                document.body.classList.add('overflow-hidden');
            };

            // Function to close the sidebar
            const closeSidebar = () => {
                mobileSidebar.classList.add('translate-x-full');
                mobileOverlay.classList.add('opacity-0');
                setTimeout(() => {
                    mobileOverlay.classList.add('hidden');
                }, 300); // Match this with your transition duration
                document.body.classList.remove('overflow-hidden');
            };

            // Event listeners for opening/closing sidebar
            mobileMenuButton.addEventListener('click', openSidebar);
            closeSidebarButton.addEventListener('click', closeSidebar);
            mobileOverlay.addEventListener('click', closeSidebar);

            // Close sidebar when clicking on links
            const sidebarLinks = mobileSidebar.querySelectorAll('a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', closeSidebar);
            });

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();

                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;

                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 80,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Animation on scroll
            const animateOnScroll = function() {
                const elements = document.querySelectorAll('.feature-card, .bg-dark-900');

                elements.forEach(element => {
                    const elementPosition = element.getBoundingClientRect().top;
                    const windowHeight = window.innerHeight;

                    if (elementPosition < windowHeight - 100) {
                        element.classList.add('opacity-100');
                        element.classList.remove('opacity-0', 'translate-y-10');
                    }
                });
            };

            // Apply initial styles for animation
            const elementsToAnimate = document.querySelectorAll('.feature-card, .bg-dark-900');
            elementsToAnimate.forEach(element => {
                element.classList.add('transition', 'duration-700', 'opacity-0', 'translate-y-10');
            });

            // Run animation check on load and scroll
            window.addEventListener('load', animateOnScroll);
            window.addEventListener('scroll', animateOnScroll);

            // Testimonial carousel
            let currentTestimonial = 0;
            const testimonials = document.querySelectorAll('.testimonial');
            const totalTestimonials = testimonials.length;
            const nextButton = document.getElementById('next-testimonial');
            const prevButton = document.getElementById('prev-testimonial');

            if (testimonials.length > 0 && nextButton && prevButton) {
                const showTestimonial = (index) => {
                    testimonials.forEach((testimonial, i) => {
                        testimonial.classList.add('hidden');
                        if (i === index) {
                            testimonial.classList.remove('hidden');
                        }
                    });
                };

                nextButton.addEventListener('click', () => {
                    currentTestimonial = (currentTestimonial + 1) % totalTestimonials;
                    showTestimonial(currentTestimonial);
                });

                prevButton.addEventListener('click', () => {
                    currentTestimonial = (currentTestimonial - 1 + totalTestimonials) % totalTestimonials;
                    showTestimonial(currentTestimonial);
                });

                // Initialize first testimonial
                showTestimonial(0);
            }

            // Add sticky header effect
            const header = document.querySelector('header');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 100) {
                    header.classList.add('shadow-lg');
                    header.classList.add('bg-opacity-95');
                } else {
                    header.classList.remove('shadow-lg');
                    header.classList.remove('bg-opacity-95');
                }
            });

            // Demo modal functionality
            const demoLink = document.querySelector('a[href="#demo"]');
            const modalClose = document.getElementById('modal-close');
            const demoModal = document.getElementById('demo-modal');

            if (demoLink && modalClose && demoModal) {
                demoLink.addEventListener('click', (e) => {
                    e.preventDefault();
                    demoModal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                });

                modalClose.addEventListener('click', () => {
                    demoModal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                });

                // Close modal when clicking outside content
                demoModal.addEventListener('click', (e) => {
                    if (e.target === demoModal) {
                        demoModal.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden');
                    }
                });
            }
        });
    </script>
</body>
</html>
