<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Login - Catalyst</title>
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
        <!-- Left Column: Design Showcase -->
        <div class="bg-gradient-to-br from-emerald-400 to-emerald-600 flex flex-col items-center justify-center p-12 text-center left-column">
            <div class="relative mb-8">
                <div class="w-32 h-32 bg-emerald-500 rounded-full mx-auto mb-6 flex items-center justify-center shadow-lg relative overflow-hidden">
                    <i class="fas fa-project-diagram text-white text-5xl z-10"></i>
                    <div class="absolute inset-0 bg-emerald-400 opacity-30 animate-pulse rounded-full"></div>
                </div>
                <h1 class="text-4xl font-bold text-white mb-4 tracking-tight">Catalyst</h1>
                <p class="text-white text-opacity-90 text-lg leading-relaxed max-w-xs">
                    Empower your team with seamless project management and collaboration.
                </p>
            </div>

            <div class="grid grid-cols-3 gap-4 text-emerald-100 w-full max-w-sm">
                <div class="bg-white bg-opacity-20 p-4 rounded-xl text-center feature-card">
                    <i class="fas fa-chart-line text-3xl mb-2 text-black"></i>
                    <p class="text-sm font-medium text-black">Tracking Ticket</p>
                </div>
                <div class="bg-white bg-opacity-20 p-4 rounded-xl text-center feature-card">
                    <i class="fas fa-users text-3xl mb-2 text-black"></i>
                    <p class="text-sm font-medium text-black">Team Collaboration</p>
                </div>
                <div class="bg-white bg-opacity-20 p-4 rounded-xl text-center feature-card">
                    <i class="fas fa-clock text-3xl mb-2 text-black"></i>
                    <p class="text-sm font-medium text-black">Time Efficiency</p>
                </div>
            </div>
        </div>

        <!-- Right Column: Login Form -->
        <div class="bg-black flex items-center justify-center p-12">
            <div class="w-full max-w-md">
                <h2 class="text-3xl font-bold text-emerald-400 mb-6 text-center">Member Login</h2>
                <p class="text-emerald-300 text-center mb-8">Access your Catalyst membership dashboard</p>

                <form method="POST" action="{{ route('member.auth.login') }}" class="space-y-6">
                    @csrf

                    @if ($errors->any())
                        <div class="bg-red-900 bg-opacity-50 border border-red-400 text-red-300 px-4 py-3 rounded-lg relative mb-4" role="alert">
                            <strong class="font-bold">Error! </strong>
                            <span class="block sm:inline">{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <div class="space-y-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-emerald-400 mb-2">
                                <i class="fas fa-envelope mr-2 text-emerald-400"></i>Email Address
                            </label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                required
                                value="{{ old('email') }}"
                                class="w-full px-4 py-3 bg-transparent border border-emerald-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-400 transition duration-300 text-emerald-300 placeholder-gray-500"
                                placeholder="Enter your email"
                            >
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-emerald-400 mb-2">
                                <i class="fas fa-lock mr-2 text-emerald-400"></i>Password
                            </label>
                            <div class="password-container">
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    required
                                    class="w-full px-4 py-3 border border-emerald-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-400 transition duration-300 bg-black text-emerald-300 placeholder-gray-500"
                                    placeholder="Enter your password"
                                >
                                <span class="toggle-password" onclick="togglePasswordVisibility()">
                                    <i id="passwordToggleIcon" class="fas fa-eye text-emerald-400"></i>
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    id="remember"
                                    class="h-4 w-4 text-emerald-400 focus:ring-emerald-400 border-emerald-400 rounded bg-transparent"
                                >
                                <label for="remember" class="ml-2 block text-sm text-emerald-300">
                                    Remember me
                                </label>
                            </div>

                            <div class="text-sm">
                                <a href="#" class="font-medium text-emerald-400 hover:text-emerald-300">
                                    Forgot password?
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button
                            type="submit"
                            class="w-full bg-emerald-500 text-white py-3 rounded-lg hover:bg-emerald-600 transition duration-300 flex items-center justify-center space-x-2 shadow-lg"
                        >
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Sign In</span>
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-emerald-300">
                        Don't have a membership?
                        <a href="{{ route('member.auth.register') }}" class="font-medium text-emerald-400 hover:text-emerald-300">
                            Join Now
                        </a>
                    </p>
                </div>

                <div class="mt-4 text-center">
                    <a href="{{ url('/') }}" class="text-sm text-gray-400 hover:text-gray-300">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Back to Homepage
                    </a>
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
