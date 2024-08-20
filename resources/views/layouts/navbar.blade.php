<!-- resources/views/components/navbar.blade.php -->

<nav class="sticky top-0 z-10 w-lvw bg-[#f8f9fa] py-2 flex justify-center dark:bg-[#2d333b]">
    <div class="container flex w-full items-center justify-between font-popi text-sm">
        <div class="flex gap-5 items-center">
            <img src="{{asset('/assets/images/telegram.png')}}" alt="" class="size-7">
            <a href="/" class="h-full w-full py-2 font-medium text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300">
                <span>Home</span>
            </a>
            <!-- <a href="/group" class="h-full w-full py-2 font-medium text-gray-500 hover:text-gray-600">
                <span>Group</span>
            </a> -->
        </div>
        <div class="flex items-center gap-3">
            <button
                class="w-10 h-5 rounded-full bg-white dark:bg-gray-700 flex items-center transition duration-300 focus:outline-none shadow"
                onclick="toggleTheme()">
                <div
                    id="switch-toggle"
                    class="w-6 h-6 relative rounded-full transition duration-500 transform bg-yellow-500 -translate-x-2 p-1 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </button>
            <div class="relative">
                <button id="userMenuButton" class="transition-bg py-2 text-gray-500 flex items-center dark:text-gray-400 dark:hover:text-gray-300">
                    <span id="username"></span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="userMenu" class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg hidden overflow-hidden border border-gray-300 dark:border-gray-700 dark:bg-[#2d333b]">
                    <a href="#" id="profileButton" class="block px-3 py-2 text-gray-800 dark:text-gray-400 dark:hover:bg-gray-800 hover:bg-gray-200 space-x-1 border-b border-gray-200 dark:border-gray-600"><i class="fa-solid fa-user"></i><span>Profile</span></a>
                    <a href="#" id="logoutButton" class="block px-3 py-2 text-gray-800 dark:text-gray-400 dark:hover:bg-gray-800 hover:bg-gray-200 space-x-1"><i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></a>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    $(document).ready(async () => {
        try {
            const response = await fetchClient('/api/admin/me');
            document.getElementById('username').innerText = response.data.name;

            if (document.getElementById('profile_name') && document.getElementById('profile_email') && document.getElementById('profile_telegram_id')) {
                document.getElementById('profile_name').value = response.data.name;
                document.getElementById('profile_email').value = response.data.email;
                document.getElementById('profile_telegram_id').value = response.data.telegram_id ?? '';
            }

        } catch (error) {
            console.log(error);
        }

        document.getElementById('userMenuButton').addEventListener('click', function() {
            var userMenu = document.getElementById('userMenu');
            userMenu.classList.toggle('hidden');
        });

        window.addEventListener('click', function(e) {
            var userMenu = document.getElementById('userMenu');
            var userMenuButton = document.getElementById('userMenuButton');
            if (!userMenuButton.contains(e.target) && !userMenu.contains(e.target)) {
                userMenu.classList.add('hidden');
            }
        });
    });

    document.getElementById('logoutButton').addEventListener('click', () => {
        localStorage.removeItem('access_token');
        window.location.href = '/login';
    });
    document.getElementById('profileButton').addEventListener('click', () => {
        window.location.href = '/profile';
    });
</script>
<script>
    const switchToggle = document.querySelector('#switch-toggle');
    let isDarkmode = localStorage.getItem('isDarkmode') === 'true' ? true : false;

    const darkIcon = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>`

    const lightIcon = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>`

    function toggleTheme() {
        isDarkmode = !isDarkmode
        localStorage.setItem('isDarkmode', isDarkmode)
        switchTheme()
    }

    function switchTheme() {
        if (isDarkmode) {
            document.documentElement.classList.add('dark');
            switchToggle.classList.remove('bg-yellow-500', '-translate-x-2')
            switchToggle.classList.add('bg-gray-600', 'translate-x-full')
            setTimeout(() => {
                switchToggle.innerHTML = darkIcon
            }, 250);
        } else {
            document.documentElement.classList.remove('dark');
            switchToggle.classList.add('bg-yellow-500', '-translate-x-2')
            switchToggle.classList.remove('bg-gray-600', 'translate-x-full')
            setTimeout(() => {
                switchToggle.innerHTML = lightIcon
            }, 250);
        }
    }

    switchTheme()
</script>