<html lang="en" class="">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield("title", "Default Title")</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Readex+Pro:wght@160..700&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/plugins/ranges.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-ubuntu dark:bg-[#1a222d]">
    <div id="toast-element" class="fixed z-40 top-6 -right-full font-popi space-y-2 transition-all duration-500">
        <div class="max-w-xs bg-white border border-gray-200 rounded-xl shadow-lg dark:bg-neutral-800 dark:border-neutral-700" role="alert" tabindex="-1" aria-labelledby="hs-toast-success-example-label">
            <div class="flex p-4">
                <div class="shrink-0" id="toast-icon">
                    <!-- icon svg toast -->
                </div>
                <div class="ms-3">
                    <p id="toast-message" class="text-sm text-gray-700 dark:text-neutral-400 mr-4">
                        <!-- toast message -->
                    </p>
                </div>
                <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-success" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-2 h-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div class="" id="root">
        @if (Request::is('coin*'))
        @include("layouts.header")
        @else
        @include("layouts.navbar")
        @endif

        <main class="w-full" id="main">
            @if (Request::is('coin*'))
            @include("layouts.coin-sidebar")
            @endif
            <div class="w-full flex justify-center">
                @yield("content")
            </div>
        </main>
    </div>

    @stack("scripts")
</body>
<script>
    const path = window.location.pathname;
    const rootElement = document.getElementById('root');
    const mainElement = document.querySelector('main');

    if (path.startsWith('/coin')) {
        rootElement.classList.add('bg-[#161a1e]');
        main.classList.add('flex');
    } else {
        rootElement.classList.remove('bg-[#161a1e]');
        main.classList.remove('flex');
    }
</script>

</html>