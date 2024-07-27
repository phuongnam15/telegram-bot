<!-- resources/views/components/navbar.blade.php -->

<nav class="sticky left-0 top-0 z-10 h-lvh bg-gray-700 py-4">
    <div class="flex h-full flex-col">
        <div class="flex flex-1 flex-col">
            <div class="flex w-full">
                <a href="/" id="homeLink" class="h-full w-full py-2 pl-5 font-medium text-white hover:bg-gray-600">
                <i class="fa-solid fa-house mr-4"></i> <span>Home</span>
                </a>
            </div>
            <div class="flex w-full">
                <a href="/group" id="groupLink" class="h-full w-full py-2 pl-5 font-medium text-white hover:bg-gray-600">
                <i class="fa-solid fa-user-group mr-4"></i> <span>Group</span>
                </a>
            </div>
            <div class="flex w-full">
                <a href="/bot" id="botLink" class="h-full w-full px-[60px] py-2 pl-5 font-medium text-white hover:bg-gray-600">
                <i class="fa-solid fa-robot mr-4"></i> <span>Bot</span>
                </a>
            </div>
        </div>
        <div>
            <a href="#" id="logoutButton" class="transition-bg px-[60px] py-2 font-bold text-white duration-200 hover:bg-[#fafbfb] hover:text-gray-700">
                Logout
            </a>
        </div>
    </div>
</nav>

<script>
    //div cha của 2 element a trên và dưới sẽ thêm bg-white và rounded-s-2xl, còn phần tử a ở dưới sẽ thêm rounded-tr-2xl còn ở trên sẽ thêm rounded-br-2xl
    document.addEventListener('DOMContentLoaded', () => {
        const currentPath = window.location.pathname;
        const links = {
            '/': document.getElementById('homeLink'),
            '/group': document.getElementById('groupLink'),
            '/bot': document.getElementById('botLink'),
        };

        if (links[currentPath]) {
            const currentLink = links[currentPath];
            currentLink.classList.add(
                'bg-[#fafbfb]',
                'text-gray-700',
                'rounded-s-2xl',
            );
            currentLink.classList.remove('text-white', 'hover:bg-gray-600');

            // Get keys as an array
            const paths = Object.keys(links);

            // Find the index of the current path
            const currentIndex = paths.indexOf(currentPath);

            // Add rounded corners to the parent elements before and after the current path element
            if (currentIndex > 0) {
                const previousLink = links[paths[currentIndex - 1]];
                if (previousLink && previousLink.parentElement) {
                    previousLink.parentElement.classList.add('bg-[#fafbfb]', 'rounded-s-2xl');
                    previousLink.classList.add('rounded-br-2xl', 'bg-gray-700');
                }
            }

            if (currentIndex < paths.length - 1) {
                const nextLink = links[paths[currentIndex + 1]];
                if (nextLink && nextLink.parentElement) {
                    nextLink.parentElement.classList.add('bg-[#fafbfb]', 'rounded-s-2xl');
                    nextLink.classList.add('rounded-tr-2xl', 'bg-gray-700');
                }
            }
        }
    });

    document.getElementById('logoutButton').addEventListener('click', () => {
        localStorage.removeItem('access_token');
        window.location.href = '/login';
    });
</script>