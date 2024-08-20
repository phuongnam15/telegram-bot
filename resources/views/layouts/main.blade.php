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
    <div id="notification" class="hidden fixed left-[50%] -translate-x-[50%] z-20 top-10 rounded-md text-white py-1 px-5 w-fitmx-auto">
        <p class="text-sm"></p>
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