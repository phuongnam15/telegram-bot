<!-- resources/views/components/navbar.blade.php -->

<nav class="bg-gray-700 py-4 h-lvh sticky top-0 left-0 z-10">
    <div class="flex flex-col h-full">
        <div class="flex-col flex flex-1">
            <a href="/" id="homeLink" class="rounded-s-2xl text-white font-medium py-2 px-5 hover:bg-[#fafbfb] transition-bg duration-200 hover:text-gray-700">Home</a>
            <a href="/group" id="groupLink" class="rounded-s-2xl text-white font-medium py-2 px-5 hover:bg-[#fafbfb] transition-bg duration-200 hover:text-gray-700">Group</a>
            <a href="/bot" id="botLink" class="rounded-s-2xl text-white font-medium py-2 px-5 hover:bg-[#fafbfb] transition-bg duration-200 hover:text-gray-700">Bot</a>
        </div>
        <div>
            <a href="#" id="logoutButton" class="text-white font-bold py-2 px-5 hover:bg-[#fafbfb] transition-bg duration-200 hover:text-gray-700">Logout</a>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const currentPath = window.location.pathname;
        const links = {
            '/': document.getElementById('homeLink'),
            '/group': document.getElementById('groupLink'),
            '/bot': document.getElementById('botLink')
        };

        if (links[currentPath]) {
            links[currentPath].classList.add('bg-[#fafbfb]', 'text-gray-700');
            links[currentPath].classList.remove('text-white');
        }
    });

    document.getElementById('logoutButton').addEventListener('click', () => {
        localStorage.removeItem('access_token');
        window.location.href = '/login';
    });
</script>