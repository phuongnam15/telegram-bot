<!-- resources/views/components/navbar.blade.php -->

<nav class="bg-gray-800 p-4">
    <div class="container mx-auto flex justify-between items-center">
        <!-- Logo -->
        <div class="text-white font-bold">
            <a href="/">Brand</a>
        </div>

        <!-- Primary Nav -->
        <div class="hidden md:flex items-center space-x-4">
            <a href="#" class="text-gray-300 hover:text-white">Home</a>
            <a href="/group" class="text-gray-300 hover:text-white">Group</a>
            <a href="/bot" class="text-gray-300 hover:text-white">Bot</a>
        </div>

        <!-- Logout Button -->
        <div>
            <a href="#" id="logoutButton" class="text-gray-300 hover:text-white">Logout</a>
        </div>

        <!-- Mobile Button -->
        <div class="md:hidden flex items-center">
            <button class="mobile-menu-button text-gray-300 focus:outline-none">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <!-- <div class="mobile-menu hidden md:hidden">
        <a href="#" class="block py-2 px-4 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">Home</a>
        <a href="#" class="block py-2 px-4 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">About</a>
        <a href="#" class="block py-2 px-4 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">Services</a>
        <a href="#" class="block py-2 px-4 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">Contact</a>
        <a href="#" class="block py-2 px-4 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">Logout</a>
    </div> -->
</nav>

<script>
    const btn = document.querySelector('button.mobile-menu-button');
    const menu = document.querySelector('.mobile-menu');

    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });

    document.getElementById('logoutButton').addEventListener('click', () => {
        localStorage.removeItem('access_token');
        window.location.href = '/login';
    });
</script>