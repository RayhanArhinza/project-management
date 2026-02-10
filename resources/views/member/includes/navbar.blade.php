<!-- Navbar -->
<div id="navbar" class="navbar px-6 shadow-md flex items-center justify-between">
    <!-- Sidebar Toggle Button (Desktop) -->
    <button id="toggle-sidebar-btn" class="text-white hover:text-gray-500 lg:block hidden">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Mobile Menu Button -->
    <div class="flex items-center">
        <button id="mobile-menu-btn" class="mobile-menu-btn text-white hover:text-gray-500 mr-4 lg:hidden block">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Right Side Navigation -->
    <div class="flex items-center space-x-4">
        <!-- Search Input -->
        <div class="relative">
            <input type="text" placeholder="Search..." class="bg-gray-100 px-4 py-2 text-gray-800 rounded-md text-sm w-40 md:w-60 focus:outline-none focus:ring-2 focus:ring-gray-300">
            <i class="fas fa-search absolute right-3 top-2.5 text-gray-500"></i>
        </div>

        <!-- Admin Profile -->
        <div class="relative flex items-center">
            <div id="user-profile-trigger" class="flex items-center cursor-pointer">
                <div class="w-8 h-8 mr-2 flex items-center justify-center rounded-full bg-gray-500 text-white font-bold">
                    {{ Auth::guard('member')->user() ? substr(Auth::guard('member')->user()->name, 0, 2) : 'A' }}
                </div>
                <span class="mr-2 hidden md:block">{{ Auth::guard('member')->user() ? Auth::guard('member')->user()->name : 'member' }}</span>
            </div>

            <!-- User Profile Dropdown Menu -->
            <div id="user-profile-menu" class="hidden absolute right-0 top-full mt-2 w-48 bg-white shadow-lg rounded-lg border border-gray-200 z-50">
                <ul class="py-1">
                    <li>
                        <a href="/" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-desktop mr-2"></i>Landing Page
                        </a>
                    </li>
                    {{-- <li>
                        <a href="{{ route('member.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user mr-2"></i>Edit Profile
                        </a>
                    </li> --}}
                    <li>
                        <form action="{{ route('auth.logout') }}" method="POST" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- JS Dropdown -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const userProfileTrigger = document.getElementById("user-profile-trigger");
    const userProfileMenu = document.getElementById("user-profile-menu");

    userProfileTrigger.addEventListener("click", function (event) {
        event.stopPropagation();
        userProfileMenu.classList.toggle("hidden");
    });

    document.addEventListener("click", function (event) {
        if (!userProfileMenu.contains(event.target) && !userProfileTrigger.contains(event.target)) {
            userProfileMenu.classList.add("hidden");
        }
    });
});
</script>
