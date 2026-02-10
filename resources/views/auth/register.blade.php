<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Project Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        /* Consistent CSS for animations and responsiveness */
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
        <!-- Left Column: Design Showcase (Consistent with Login) -->
        <div class="bg-gradient-to-br from-yellow-300 to-yellow-500 flex flex-col items-center justify-center p-12 text-center left-column">
            <div class="relative mb-8">
                <div class="w-32 h-32 bg-yellow-500 rounded-full mx-auto mb-6 flex items-center justify-center shadow-lg relative overflow-hidden">
                    <i class="fas fa-project-diagram text-black text-5xl z-10"></i>
                    <div class="absolute inset-0 bg-yellow-400 opacity-30 animate-pulse rounded-full"></div>
                </div>
                <h1 class="text-4xl font-bold text-black mb-4 tracking-tight">Catalyst</h1>
                <p class="text-black text-opacity-90 text-lg leading-relaxed max-w-xs">
                    Join now to boost productivity with seamless collaboration and smart workflows.
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

        <!-- Right Column: Registration Form -->
        <div class="bg-black flex items-center justify-center p-12">
            <div class="w-full max-w-md">
                <h2 class="text-3xl font-bold text-yellow-500 mb-6 text-center">Create Your Account</h2>
                <p class="text-yellow-300 text-center mb-8">Sign up to start your journey with Catalyst</p>

                <form method="POST" action="{{ route('member.auth.register') }}" class="space-y-6">
                    @csrf

                    @if ($errors->any())
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                            <strong class="font-bold">Oops! </strong>
                            <span class="block sm:inline">{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <div class="space-y-4">
                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-yellow-500 mb-2">
                                <i class="fas fa-user mr-2 text-yellow-500"></i>Full Name
                            </label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                required
                                value="{{ old('name') }}"
                                class="w-full px-4 py-3 bg-transparent border border-yellow-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 transition duration-300 text-yellow-300"
                                placeholder="Enter your full name"
                            >
                            @error('name')
                                <span class="text-yellow-700 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email Field -->
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
                            @error('email')
                                <span class="text-yellow-700 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password Field -->
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
                                <span class="toggle-password" onclick="togglePasswordVisibility('password')">
                                    <i id="passwordToggleIcon" class="fas fa-eye text-yellow-500"></i>
                                </span>
                            </div>
                            @error('password')
                                <span class="text-yellow-700 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password Confirmation Field -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-yellow-500 mb-2">
                                <i class="fas fa-lock mr-2 text-yellow-500"></i>Confirm Password
                            </label>
                            <div class="password-container">
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    required
                                    class="w-full px-4 py-3 border border-yellow-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 transition duration-300 bg-black text-yellow-300"
                                    placeholder="Confirm your password"
                                >
                                <span class="toggle-password" onclick="togglePasswordVisibility('password_confirmation')">
                                    <i id="passwordConfirmationToggleIcon" class="fas fa-eye text-yellow-500"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button
                            type="submit"
                            class="w-full bg-yellow-500 text-black py-3 rounded-lg hover:bg-yellow-600 transition duration-300 flex items-center justify-center space-x-2"
                        >
                            <i class="fas fa-user-plus"></i>
                            <span>Sign Up</span>
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-yellow-300">
                        Already have an account?
                        <a href="{{ route('member.auth.login') }}" class="font-medium text-yellow-500 hover:text-yellow-400">
                            Sign in
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleIcon = document.getElementById(fieldId === 'password' ? 'passwordToggleIcon' : 'passwordConfirmationToggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
