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


        <!-- Notifications -->
        <div class="relative">
            <button id="notif-btn" class="text-gray-600 hover:text-gray-800 mt-2">
                <i class="fas fa-bell text-2xl text-white"></i>
                @php
                    $userNotifications = auth()->user()->notifications;
                @endphp
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center mt-2">
                    {{ $userNotifications->count() }}
                </span>
            </button>


            <!-- Notifications Dropdown -->
            <div id="notif-menu" class="hidden absolute right-0 mt-2 w-80 bg-white shadow-2xl rounded-xl overflow-hidden border border-gray-100">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-800">Notifications</h2>
                    <span class="text-sm text-gray-500 bg-gray-200 px-2 py-1 rounded-full">
                        {{ $userNotifications->count() }}
                    </span>
                </div>

                @forelse($userNotifications as $notification)
                    <div class="px-4 py-3 hover:bg-gray-50 transition-colors duration-200 group">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="text-blue-600 fas fa-bell"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-semibold text-gray-800 truncate">
                                    {{ $notification->title }}
                                </h3>
                                <p class="text-xs text-gray-500 mt-1">
                                    Project: {{ optional($notification->project)->name ?? $notification->project_id }}
                                </p>
                                <p class="text-xs text-gray-600 mt-1">
                                    {{ Str::limit($notification->description, 50) }}
                                </p>
                                <!-- Pada bagian tampilan due date, ubah kode berikut: -->

                                <div class="mt-2 flex items-center justify-between">
                                    <span class="text-xs {{ Carbon\Carbon::parse($notification->due_date)->startOfDay() <= now()->startOfDay() ? 'text-red-600 font-bold' : 'text-gray-500' }}">
                                        Due: {{ $notification->due_date->format('d M Y H:i') }}
                                    </span>
                                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="delete-notif text-xs text-red-500 hover:text-red-700 opacity-0 group-hover:opacity-100 transition-opacity duration-200 hover:underline focus:outline-none" data-id="{{ $notification->id }}">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-6 text-center text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.58V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.579c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <p class="text-sm">Tidak ada notifikasi</p>
                    </div>
                @endforelse
            </div>
        </div>
        <!-- User Profile -->
        <div class="relative flex items-center">
            <div id="user-profile-trigger" class="flex items-center cursor-pointer">
                @if(Auth::user()->profile && Auth::user()->profile->avatar)
                    <img src="{{ asset('avatars/' . Auth::user()->profile->avatar) }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 mr-2 rounded-full object-cover">
                @else
                    <div class="w-8 h-8 mr-2 flex items-center justify-center rounded-full bg-gray-500 text-white font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) . (strpos(Auth::user()->name, ' ') ? strtoupper(substr(explode(' ', Auth::user()->name)[1], 0, 1)) : '') }}
                    </div>
                @endif
                <span class="mr-2 hidden md:block">{{ Auth::user()->name }}</span>
            </div>


            <!-- User Profile Dropdown Menu -->
            <div id="user-profile-menu" class="hidden absolute right-0 top-full mt-2 w-48 bg-white shadow-lg rounded-lg border border-gray-200 z-50">
                <ul class="py-1">
                    <li>
                        <a href="{{ route('landingpage') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-desktop  mr-2"></i>Landing Page
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user mr-2"></i>Edit Profile
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Notifications Script -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const notifBtn = document.getElementById("notif-btn");
    const notifMenu = document.getElementById("notif-menu");

    notifBtn.addEventListener("click", function (event) {
        event.stopPropagation(); // Prevent event bubbling
        notifMenu.classList.toggle("hidden");
    });

    // Close notifications dropdown when clicking outside
    document.addEventListener("click", function (event) {
        if (!notifMenu.contains(event.target) && !notifBtn.contains(event.target)) {
            notifMenu.classList.add("hidden");
        }
    });

    // User Profile Dropdown
    const userProfileTrigger = document.getElementById("user-profile-trigger");
    const userProfileMenu = document.getElementById("user-profile-menu");

    userProfileTrigger.addEventListener("click", function (event) {
        event.stopPropagation(); // Prevent event bubbling
        userProfileMenu.classList.toggle("hidden");
    });

    // Close user profile dropdown when clicking outside
    document.addEventListener("click", function (event) {
        if (!userProfileMenu.contains(event.target) && !userProfileTrigger.contains(event.target)) {
            userProfileMenu.classList.add("hidden");
        }
    });
});
</script>

<!-- jQuery for Notification Deletion -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Delete single notification
    $(".delete-notif").click(function() {
        var notifId = $(this).data("id");
        var notifItem = $(this).closest(".group");

        $.ajax({
            url: "/notifications/" + notifId,
            type: "POST",
            data: {
                _method: "DELETE",
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                notifItem.fadeOut(300, function() {
                    $(this).remove();
                });
            }
        });
    });
});
</script>
