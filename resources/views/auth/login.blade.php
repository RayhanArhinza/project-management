<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Project Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        /* Tambahan CSS untuk animasi dan responsivitas */
        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        @media (max-width: 768px) {
            .grid-cols-2 {
                grid-template-columns: 1fr;
            }
            .left-column {
                padding: 2rem;
            }
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
            color: #6b7280;
        }
    </style>
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-4xl grid grid-cols-2 shadow-2xl rounded-2xl overflow-hidden">
        <!-- Left Column: Design Showcase (Redesigned) -->
        <div class="bg-gradient-to-br from-yellow-300 to-yellow-500 flex flex-col items-center justify-center p-12 text-center left-column">
            <div class="relative mb-8">
                <div class="w-32 h-32 bg-yellow-500 rounded-full mx-auto mb-6 flex items-center justify-center shadow-lg relative overflow-hidden">
                    <i class="fas fa-project-diagram text-black text-5xl z-10"></i>
                    <div class="absolute inset-0 bg-yellow-400 opacity-30 animate-pulse rounded-full"></div>
                </div>
                <h1 class="text-4xl font-bold text-black mb-4 tracking-tight">Catalyst</h1>
                <p class="text-black text-opacity-90 text-lg leading-relaxed max-w-xs">
                    Boost productivity with seamless collaboration and smart workflows.
                </p>
            </div>

            <div class="grid grid-cols-3 gap-4 text-yellow-500 w-full max-w-sm">
                <div class="bg-yellow-500 bg-opacity-20 p-4 rounded-xl text-center feature-card">
                    <i class="fas fa-chart-line text-3xl mb-2 text-black"></i>
                    <p class="text-sm font-medium text-black">Tracking Ticket</p>
                </div>
                <div class="bg-yellow-500 bg-opacity-20 p-4 rounded-xl text-center feature-card">
                    <i class="fas fa-users text-3xl mb-2 text-black"></i>
                    <p class="text-sm font-medium text-black">Team Collaboration</p>
                </div>
                <div class="bg-yellow-500 bg-opacity-20 p-4 rounded-xl text-center feature-card">
                    <i class="fas fa-clock text-3xl mb-2 text-black"></i>
                    <p class="text-sm font-medium text-black">Time Efficiency</p>
                </div>
            </div>
        </div>

        <!-- Right Column: Login Form -->
        <div class="bg-black flex items-center justify-center p-12">
            <div class="w-full max-w-md">
                <h2 class="text-3xl font-bold text-yellow-500 mb-6 text-center">Welcome Back</h2>
                <p class="text-yellow-300 text-center mb-8">Sign in to continue to your dashboard</p>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    @if ($errors->any())
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                            <strong class="font-bold">Oops! </strong>
                            <span class="block sm:inline">{{ $errors->first('email') ?: $errors->first('membership') }}</span>
                        </div>
                    @endif

                    <div class="space-y-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-yellow-500 mb-2">
                                <i class="fas fa-envelope mr-2 text-yellow-500"></i>Email Address
                            </label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                required
                                value="{{ old('email') }}"
                                class="w-full px-4 py-3 bg-transparent border border-yellow-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 transition duration-300 text-yellow-300"
                                placeholder="Enter your email"
                            >
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-yellow-500 mb-2">
                                <i class="fas fa-lock mr-2 text-yellow-500"></i>Password
                            </label>
                            <div class="password-container">
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    required
                                    class="w-full px-4 py-3 border border-yellow-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 transition duration-300 bg-black text-yellow-300"
                                    placeholder="Enter your password"
                                >
                                <span class="toggle-password" onclick="togglePasswordVisibility()">
                                    <i id="passwordToggleIcon" class="fas fa-eye text-yellow-500"></i>
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    id="remember"
                                    class="h-4 w-4 text-yellow-500 focus:ring-yellow-400 border-yellow-500 rounded"
                                >
                                <label for="remember" class="ml-2 block text-sm text-yellow-300">
                                    Remember me
                                </label>
                            </div>

                            <div class="text-sm">
                                <a href="#" class="font-medium text-yellow-500 hover:text-yellow-400">
                                    Forgot password?
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button
                            type="submit"
                            class="w-full bg-yellow-500 text-black py-3 rounded-lg hover:bg-yellow-600 transition duration-300 flex items-center justify-center space-x-2"
                        >
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Sign In</span>
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-yellow-300">
                        Don't have an account?
                        <a href="#" class="font-medium text-yellow-500 hover:text-yellow-400">
                            Sign up
                        </a>
                    </p>
                </div>
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
