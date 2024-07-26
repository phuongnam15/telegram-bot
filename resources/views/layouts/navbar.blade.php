<!-- resources/views/components/navbar.blade.php -->

<nav class="bg-[#795548] py-4 h-lvh sticky top-0 left-0 z-10">
    <div class="flex flex-col h-full">
        <div class="flex-col flex flex-1">
            <a href="/" id="homeLink" class="text-white font-medium py-2 px-5 hover:bg-white transition-bg duration-200 hover:text-[#795548]">Home</a>
            <a href="/group" id="groupLink" class="text-white font-medium py-2 px-5 hover:bg-white transition-bg duration-200 hover:text-[#795548]">Group</a>
            <a href="/bot" id="botLink" class="text-white font-medium py-2 px-5 hover:bg-white transition-bg duration-200 hover:text-[#795548]">Bot</a>
        </div>
        <div>
            <a href="#" id="logoutButton" class="text-white font-bold py-2 px-5 hover:bg-white transition-bg duration-200 hover:text-[#795548]">Logout</a>
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
            links[currentPath].classList.add('bg-white', 'text-[#795548]');
            links[currentPath].classList.remove('text-white');
        }
    });

    document.getElementById('logoutButton').addEventListener('click', () => {
        localStorage.removeItem('access_token');
        window.location.href = '/login';
    });
</script>