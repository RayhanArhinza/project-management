<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Project Management</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.tailwindcss.com/3.4.1"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        .feature-badge {
            transition: transform 0.3s ease, background-color 0.3s ease;
        }
        .feature-badge:hover {
            transform: translateY(-3px);
            background-color: rgba(6, 78, 59, 0.9);
        }
        .password-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #047857;
        }
        @media (max-width: 768px) {
            .grid-cols-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-4xl grid grid-cols-2 shadow-2xl rounded-2xl overflow-hidden">
        <!-- Left Column: Admin Login Form -->
        <div class="bg-gray-800 flex items-center justify-center p-12">
            <div class="w-full max-w-md">
                <div class="text-center mb-8">
                    <div class="inline-block p-4 rounded-full bg-emerald-700 mb-4">
                        <i class="fas fa-user-shield text-4xl text-white"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-emerald-500 mb-2">Admin Portal</h2>
                    <p class="text-gray-400">Access your administrator dashboard</p>
                </div>

                <!-- Perhatikan perubahan route action pada form -->
                <form method="POST" action="{{ route('auth.login.submit') }}" class="space-y-6">
                    @csrf

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                            <strong class="font-bold">Authentication Error: </strong>
                            <span class="block sm:inline">{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <div class="space-y-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-emerald-400 mb-2">
                                <i class="fas fa-envelope mr-2"></i>Admin Email
                            </label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                required
                                value="{{ old('email') }}"
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition duration-300 text-white"
                                placeholder="admin@catalyst.com"
                            >
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-emerald-400 mb-2">
                                <i class="fas fa-lock mr-2"></i>Admin Password
                            </label>
                            <div class="password-container">
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    required
                                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition duration-300 text-white"
                                    placeholder="Enter secure password"
                                >
                                <span class="toggle-password" onclick="togglePasswordVisibility()">
                                    <i id="passwordToggleIcon" class="fas fa-eye text-emerald-500"></i>
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    id="remember"
                                    class="h-4 w-4 text-emerald-500 focus:ring-emerald-400 border-gray-600 rounded"
                                >
                                <label for="remember" class="ml-2 block text-sm text-gray-400">
                                    Stay signed in
                                </label>
                            </div>

                            <div class="text-sm">
                                <a href="#" class="font-medium text-emerald-500 hover:text-emerald-400">
                                    Reset credentials
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button
                            type="submit"
                            class="w-full bg-emerald-600 text-white py-3 rounded-lg hover:bg-emerald-700 transition duration-300 flex items-center justify-center space-x-2 font-medium"
                        >
                            <i class="fas fa-shield-alt mr-2"></i>
                            <span>Secure Login</span>
                        </button>
                    </div>
                </form>

                <div class="mt-6 pt-4 border-t border-gray-700 text-center">
                    <p class="text-sm text-gray-400">
                        Need assistance? Contact
                        <a href="#" class="font-medium text-emerald-500 hover:text-emerald-400">
                            IT Support
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Column: System Information -->
        <div class="bg-gradient-to-br from-emerald-800 to-emerald-900 flex flex-col justify-center p-12">
            <div class="mb-8 text-center">
                <h1 class="text-4xl font-bold text-white mb-3">Catalyst</h1>
                <div class="w-24 h-1 bg-emerald-500 mx-auto mb-6"></div>
                <p class="text-emerald-100 text-lg">
                    Administrative Control Panel
                </p>
            </div>

            <div class="space-y-6 mb-8">
                <div class="bg-emerald-800 bg-opacity-50 p-4 rounded-lg shadow-lg">
                    <div class="flex items-center mb-3">
                        <div class="bg-emerald-700 p-2 rounded-lg mr-4">
                            <i class="fas fa-tasks text-white"></i>
                        </div>
                        <h3 class="text-white font-semibold">System Management</h3>
                    </div>
                    <p class="text-emerald-100 text-sm">
                        Configure and manage all system settings, user permissions and platform services.
                    </p>
                </div>

                <div class="bg-emerald-800 bg-opacity-50 p-4 rounded-lg shadow-lg">
                    <div class="flex items-center mb-3">
                        <div class="bg-emerald-700 p-2 rounded-lg mr-4">
                            <i class="fas fa-chart-line text-white"></i>
                        </div>
                        <h3 class="text-white font-semibold">Analytics Dashboard</h3>
                    </div>
                    <p class="text-emerald-100 text-sm">
                        Access real-time insights, usage statistics and performance metrics.
                    </p>
                </div>

                <div class="bg-emerald-800 bg-opacity-50 p-4 rounded-lg shadow-lg">
                    <div class="flex items-center mb-3">
                        <div class="bg-emerald-700 p-2 rounded-lg mr-4">
                            <i class="fas fa-user-cog text-white"></i>
                        </div>
                        <h3 class="text-white font-semibold">User Administration</h3>
                    </div>
                    <p class="text-emerald-100 text-sm">
                        Manage user accounts, teams, and role-based permissions.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap justify-center gap-2 mt-4">
                <span class="inline-block px-3 py-1 bg-emerald-800 rounded-full text-xs text-white feature-badge">
                    <i class="fas fa-lock mr-1"></i> Secure Access
                </span>
                <span class="inline-block px-3 py-1 bg-emerald-800 rounded-full text-xs text-white feature-badge">
                    <i class="fas fa-history mr-1"></i> Audit Logs
                </span>
                <span class="inline-block px-3 py-1 bg-emerald-800 rounded-full text-xs text-white feature-badge">
                    <i class="fas fa-sync mr-1"></i> System Updates
                </span>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const passwordToggleIcon = document.getElementById('passwordToggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordToggleIcon.classList.remove('fa-eye');
                passwordToggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordToggleIcon.classList.remove('fa-eye-slash');
                passwordToggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
