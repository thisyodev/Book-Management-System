<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Book Manager')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Slide animation */
        .slide-enter {
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
        }

        .slide-enter-active {
            transform: translateX(0%);
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-white transition-colors duration-300">

    <!-- Navbar -->
    <nav
        class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 fixed top-0 w-full z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                <!-- Logo -->
                <a href="{{ url('/') }}"
                    class="flex items-center gap-2 text-xl font-bold text-blue-600 dark:text-blue-400">
                    📚 <span class="hidden sm:inline">Book Manager</span>
                </a>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center gap-6">
                    <button id="toggleTheme" class="text-xl" title="Toggle Dark Mode">🌓</button>

                    @auth
                        <a href="{{ route('books.index') }}" class="hover:text-blue-500 dark:hover:text-blue-400">Books</a>

                        <!-- Dropdown -->
                        <div class="relative">
                            <button id="avatarToggle"
                                class="hover:text-blue-500 dark:hover:text-blue-400 focus:outline-none">
                                👤 {{ Auth::user()->name }}
                            </button>
                            <div id="avatarDropdown"
                                class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded shadow-lg hidden z-50">
                                <a href="{{ route('books.index') }}"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">📚 Books</a>
                                <form method="POST" action="{{ url('/logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-red-600 dark:text-red-400">🚪
                                        Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ url('/login') }}" class="hover:text-blue-500 dark:hover:text-blue-400">Login</a>
                        <a href="{{ url('/register') }}" class="hover:text-blue-500 dark:hover:text-blue-400">Register</a>
                    @endauth
                </div>

                <!-- Mobile Hamburger -->
                <button id="navToggle" class="md:hidden text-gray-700 dark:text-gray-200 focus:outline-none"
                    aria-label="Toggle navigation">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Slide Menu Overlay -->
    <div id="mobileMenu"
        class="fixed top-0 right-0 w-64 h-full bg-white dark:bg-gray-800 shadow-lg transform translate-x-full transition-transform duration-300 z-40">
        <div class="p-4 space-y-4">
            <button id="closeMenu" class="text-gray-600 dark:text-gray-300 float-right">✖</button>
            <div class="pt-8 clear-both space-y-3">
                <button id="toggleThemeMobile" class="text-xl">🌓 Toggle Theme</button>

                @auth
                    <p class="text-lg font-semibold">👤 {{ Auth::user()->name }}</p>
                    <a href="{{ route('books.index') }}" class="block text-blue-500 hover:underline">📚 Books</a>
                    <form method="POST" action="{{ url('/logout') }}">
                        @csrf
                        <button type="submit" class="text-red-600 hover:underline">🚪 Logout</button>
                    </form>
                @else
                    <a href="{{ url('/login') }}" class="block text-blue-500 hover:underline">Login</a>
                    <a href="{{ url('/register') }}" class="block text-blue-500 hover:underline">Register</a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Overlay Background -->
    <div id="menuOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-30"></div>

    <!-- Main Content -->
    <main class="pt-24 px-4 max-w-5xl mx-auto">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-10 text-center text-sm text-gray-500 dark:text-gray-400">
        &copy; {{ date('Y') }} Book Manager. Built with ❤️ Laravel & TailwindCSS.
    </footer>

    <!-- Scripts -->
    <script>
        const navToggle = document.getElementById('navToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        const menuOverlay = document.getElementById('menuOverlay');
        const closeMenu = document.getElementById('closeMenu');
        const toggleTheme = document.getElementById('toggleTheme');
        const toggleThemeMobile = document.getElementById('toggleThemeMobile');

        // Mobile menu toggle
        navToggle?.addEventListener('click', () => {
            mobileMenu.classList.remove('translate-x-full');
            menuOverlay.classList.remove('hidden');
        });

        closeMenu?.addEventListener('click', () => {
            mobileMenu.classList.add('translate-x-full');
            menuOverlay.classList.add('hidden');
        });

        menuOverlay?.addEventListener('click', () => {
            mobileMenu.classList.add('translate-x-full');
            menuOverlay.classList.add('hidden');
        });

        // Dark Mode toggle
        const toggleDark = () => {
            document.documentElement.classList.toggle('dark');
        };

        toggleTheme?.addEventListener('click', toggleDark);
        toggleThemeMobile?.addEventListener('click', toggleDark);

        // 👤 Avatar Dropdown toggle
        const avatarToggle = document.getElementById('avatarToggle');
        const avatarDropdown = document.getElementById('avatarDropdown');

        avatarToggle?.addEventListener('click', (e) => {
            e.stopPropagation(); // prevent bubbling
            avatarDropdown?.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!avatarDropdown?.contains(e.target) && !avatarToggle?.contains(e.target)) {
                avatarDropdown?.classList.add('hidden');
            }
        });
    </script>
</body>

</html>
