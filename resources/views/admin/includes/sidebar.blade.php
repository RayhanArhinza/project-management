<!-- Sidebar -->
<div id="sidebar" class="sidebar flex flex-col h-screen overflow-hidden">
    <!-- Header Sidebar (Fixed) -->
    <div class="flex-shrink-0 flex items-center justify-between px-4 py-4">
        <div class="flex items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 rounded-md">
            <span id="logo-text" class="ml-3 font-bold text-xl text-white">Catalyst</span>
        </div>
    </div>

    <!-- Kontainer untuk konten yang bisa di-scroll -->
    <div class="flex-1 overflow-y-auto overflow-x-hidden py-2 scroll-smooth" style="scroll-behavior: smooth;">
        <div class="px-2 pb-4">
            {{-- Group 1: Dashboard --}}
            <h3 class="sidebar-heading text-gray-500 uppercase font-semibold text-xs px-4 mb-2">Dashboard</h3>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-1">
                <i class="fas fa-home text-lg yellow-text w-6"></i>
                <span class="sidebar-link-text ml-3">Dashboard</span>
            </a>

            {{-- Group 2: Management --}}
            <h3 class="sidebar-heading text-gray-500 uppercase font-semibold text-xs px-4 mb-2 mt-6">Membership</h3>
            <a href="{{ route('memberships.index') }}" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-1">
                <i class="fas fa-credit-card text-lg yellow-text w-6"></i>
                <span class="sidebar-link-text ml-3">Membership</span>
            </a>
            <a href="{{ route('members.index') }}" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-1">
                <i class="fas fa-users text-lg yellow-text w-6"></i>
                <span class="sidebar-link-text ml-3">Member</span>
            </a>
            <h3 class="sidebar-heading text-gray-500 uppercase font-semibold text-xs px-4 mb-2 mt-6">Admin</h3>

            <a href="{{ route('admins.index') }}" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-1">
                <i class="fas fa-user text-lg yellow-text w-6"></i>
                <span class="sidebar-link-text ml-3">Admin</span>
            </a>
        </div>
    </div>

    <!-- Tombol Logout di bagian bawah (Fixed) -->
    <div class="flex-shrink-0 px-6 py-4">
        <form action="{{ route('auth.logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-link flex items-center py-3 px-4 rounded-md w-full text-left">
                <i class="fas fa-sign-out-alt text-lg yellow-text w-6"></i>
                <span class="sidebar-link-text ml-3">Logout</span>
            </button>
        </form>
    </div>
</div>
