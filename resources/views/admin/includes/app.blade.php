<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Admin Dashboard</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/css/style.css', 'resources/js/app.js'])
    @endif
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="text-gray-200 ">
    <div id="overlay" class="overlay hidden fixed inset-0 bg-black opacity-50"></div>

    @include('admin.includes.sidebar')

    <div class="flex flex-col min-h-screen">
        @include('admin.includes.navbar')

        <div id="main-content" class="main-content p-6 bg-primary-black">
            @yield('content')
        </div>
    </div>
    <script>
        const sidebar = document.getElementById('sidebar');
        const navbar = document.getElementById('navbar');
        const mainContent = document.getElementById('main-content');
        const toggleSidebarBtn = document.getElementById('toggle-sidebar-btn');
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const overlay = document.getElementById('overlay');
        const logoText = document.getElementById('logo-text');
        const sidebarLinkTexts = document.querySelectorAll('.sidebar-link-text');
        // Tambahkan seleksi untuk heading (h3)
        const sidebarHeadings = document.querySelectorAll('.sidebar-heading');

        // Toggle sidebar pada desktop
        toggleSidebarBtn.addEventListener('click', () => {
        sidebar.classList.toggle('sidebar-collapsed');
        navbar.classList.toggle('navbar-collapsed');
        mainContent.classList.toggle('main-content-collapsed');

        // Tampilkan atau sembunyikan teks di sidebar
        if (sidebar.classList.contains('sidebar-collapsed')) {
            logoText.classList.add('hidden');
            sidebarLinkTexts.forEach(text => text.classList.add('opacity-0'));
            // Sembunyikan heading h3 menggunakan Tailwind
            sidebarHeadings.forEach(heading => heading.classList.add('hidden'));
        } else {
            logoText.classList.remove('hidden');
            sidebarLinkTexts.forEach(text => text.classList.remove('opacity-0'));
            // Tampilkan heading h3 kembali
            sidebarHeadings.forEach(heading => heading.classList.remove('hidden'));
        }
        });


            // Toggle sidebar pada mobile
            mobileMenuBtn.addEventListener('click', (event) => {
              event.stopPropagation(); // Mencegah event click menyebar ke document
              sidebar.classList.toggle('mobile-open');
              // Tampilkan overlay jika sidebar terbuka
              if (sidebar.classList.contains('mobile-open')) {
                overlay.classList.remove('hidden');
              } else {
                overlay.classList.add('hidden');
              }
            });

            // Menutup sidebar ketika klik di luar area sidebar atau pada overlay (pada mobile)
            document.addEventListener('click', function(event) {
              if (window.innerWidth < 768 && sidebar.classList.contains('mobile-open')) {
                if (!sidebar.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                  sidebar.classList.remove('mobile-open');
                  overlay.classList.add('hidden');
                }
              }
            });

            // Menutup sidebar ketika overlay diklik
            overlay.addEventListener('click', () => {
              if (window.innerWidth < 768 && sidebar.classList.contains('mobile-open')) {
                sidebar.classList.remove('mobile-open');
                overlay.classList.add('hidden');
              }
            });

            // Menangani klik pada link sidebar
            const sidebarLinks = document.querySelectorAll('.sidebar-link');
            sidebarLinks.forEach(link => {
              link.addEventListener('click', () => {
                sidebarLinks.forEach(l => l.classList.remove('active'));
                link.classList.add('active');

                // Otomatis menutup sidebar pada mobile setelah klik link
                if (window.innerWidth < 768) {
                  sidebar.classList.remove('mobile-open');
                  overlay.classList.add('hidden');
                }
              });
            });


            document.addEventListener('DOMContentLoaded', function() {
            const dropdowns = document.querySelectorAll('.sidebar-link:has(.fa-chevron-down)');

            dropdowns.forEach(dropdown => {
                // Get the associated submenu and chevron icon
                const submenu = dropdown.nextElementSibling;
                const chevron = dropdown.querySelector('.fa-chevron-down');

                // Ensure submenus are closed by default
                if (submenu) {
                    submenu.style.display = 'none';
                }

                dropdown.addEventListener('click', function() {
                    const submenu = this.nextElementSibling;
                    const chevron = this.querySelector('.fa-chevron-down, .fa-chevron-up');

                    // Close all other submenus
                    dropdowns.forEach(otherDropdown => {
                        if (otherDropdown !== dropdown) {
                            const otherSubmenu = otherDropdown.nextElementSibling;
                            const otherChevron = otherDropdown.querySelector('.fa-chevron-down, .fa-chevron-up');
                            if (otherSubmenu) {
                                otherSubmenu.style.display = 'none';
                                // Make sure to restore the down chevron
                                if (otherChevron) {
                                    otherChevron.classList.remove('fa-chevron-up');
                                    otherChevron.classList.add('fa-chevron-down');
                                }
                            }
                        }
                    });

                    // Toggle current submenu
                    if (submenu) {
                        if (submenu.style.display === 'none' || !submenu.style.display) {
                            submenu.style.display = 'block';
                            // Change to up chevron
                            if (chevron) {
                                chevron.classList.remove('fa-chevron-down');
                                chevron.classList.add('fa-chevron-up');
                            }
                        } else {
                            submenu.style.display = 'none';
                            // Change back to down chevron
                            if (chevron) {
                                chevron.classList.remove('fa-chevron-up');
                                chevron.classList.add('fa-chevron-down');
                            }
                        }
                    }
                });
            });

            // Optional: Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.sidebar-link')) {
                    dropdowns.forEach(dropdown => {
                        const submenu = dropdown.nextElementSibling;
                        const chevron = dropdown.querySelector('.fa-chevron-down, .fa-chevron-up');
                        if (submenu) {
                            submenu.style.display = 'none';
                            // Restore down chevron
                            if (chevron) {
                                chevron.classList.remove('fa-chevron-up');
                                chevron.classList.add('fa-chevron-down');
                            }
                        }
                    });
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Get current URL path
            const currentPath = window.location.pathname;

            // Define path to menu item mapping
            const pathMapping = {
                '/dashboard': 'Dashboard',
                '/tasklists': 'List',
                '/projects': 'Project',
                '/project_members': 'Project Members',
                '/tasks': 'Task',
                '/tickets': 'Ticket',
                '/boards': 'Board',
                '/users': 'User',
                '/roles': 'Role',
                // Add more mappings as needed
            };

            // Find all sidebar links
            const sidebarLinks = document.querySelectorAll('.sidebar-link');

            // Remove any existing active classes
            sidebarLinks.forEach(link => {
                link.classList.remove('active');
            });

            // Check which path matches the current URL
            for (const path in pathMapping) {
                if (currentPath.startsWith(path)) {
                    // Find the corresponding sidebar link
                    sidebarLinks.forEach(link => {
                        const linkText = link.textContent.trim();
                        if (linkText === pathMapping[path]) {
                            // Add active class to the link
                            link.classList.add('active');

                            // If this is a submenu parent, show its submenu
                            const submenu = link.nextElementSibling;
                            if (submenu && submenu.classList.contains('submenu')) {
                                submenu.style.display = 'block';
                                // Update chevron icon
                                const chevron = link.querySelector('.fa-chevron-down');
                                if (chevron) {
                                    chevron.classList.remove('fa-chevron-down');
                                    chevron.classList.add('fa-chevron-up');
                                }
                            }

                            // If this is a submenu item, show its parent menu
                            if (link.closest('.submenu')) {
                                const parentMenu = link.closest('.submenu').previousElementSibling;
                                if (parentMenu) {
                                    parentMenu.classList.add('active');
                                    link.closest('.submenu').style.display = 'block';

                                    // Update parent menu chevron
                                    const parentChevron = parentMenu.querySelector('.fa-chevron-down');
                                    if (parentChevron) {
                                        parentChevron.classList.remove('fa-chevron-down');
                                        parentChevron.classList.add('fa-chevron-up');
                                    }
                                }
                            }
                        }
                    });

                    // For submenu items, check by href
                    document.querySelectorAll('.submenu a').forEach(submenuItem => {
                        const href = submenuItem.getAttribute('href');
                        if (href && currentPath === href) {
                            submenuItem.classList.add('active');

                            // Show and highlight parent menu
                            const parentMenu = submenuItem.closest('.submenu').previousElementSibling;
                            if (parentMenu) {
                                parentMenu.classList.add('active');
                                submenuItem.closest('.submenu').style.display = 'block';

                                // Update chevron
                                const chevron = parentMenu.querySelector('.fa-chevron-down');
                                if (chevron) {
                                    chevron.classList.remove('fa-chevron-down');
                                    chevron.classList.add('fa-chevron-up');
                                }
                            }
                        }
                    });

                    break;
                }
            }

            // Add CSS for active items if it doesn't exist
            if (!document.getElementById('active-sidebar-styles')) {
                const styleElement = document.createElement('style');
                styleElement.id = 'active-sidebar-styles';
                styleElement.textContent = `
                    .sidebar-link.active,
                    .submenu a.active {
                        background-color: rgba(253, 224, 71, 0.1) !important;
                        color: #fbbf24 !important;
                        font-weight: 500 !important;
                        border-left: 3px solid #fbbf24 !important;
                    }
                `;
                document.head.appendChild(styleElement);
            }
        });
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        </script>
</body>
</html>
