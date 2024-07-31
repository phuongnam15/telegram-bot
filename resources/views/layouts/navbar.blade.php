<!-- resources/views/components/navbar.blade.php -->

<nav class="sticky top-0 z-10 w-lvw bg-[#f8f9fa] py-2 flex justify-center">
    <div class="container flex w-full items-center justify-between font-popi text-sm">
        <div class="flex gap-5">
            <img src="{{asset('/assets/images/telegram.png')}}" alt="" class="size-10">
            <a href="/"class="h-full w-full py-2 font-medium text-gray-500 hover:text-gray-600">
                <span>Home</span>
            </a>
            <!-- <a href="/group" class="h-full w-full py-2 font-medium text-gray-500 hover:text-gray-600">
                <span>Group</span>
            </a> -->
        </div>
        <div class="relative">
            <button id="userMenuButton" class="transition-bg py-2 text-gray-500 flex items-center">
                <span id="username"></span>
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div id="userMenu" class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg hidden overflow-hidden border-[1px] border-solid border-gray-300">
                <a href="#" id="logoutButton" class="block px-3 py-2 text-gray-800 hover:bg-gray-200 space-x-1"><i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></a>
            </div>
        </div>
    </div>
</nav>

<script>
    $(document).ready(async () => {
        try {
            const response = await fetchClient('/api/admin/me');
            document.getElementById('username').innerText = response.data.name;
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
</script>