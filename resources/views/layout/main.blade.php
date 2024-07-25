<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Default Title')</title>
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
</head>

<body>
    @include('components.navbar') <!-- Include Navbar -->

    <main class="container mx-auto py-4">
        @yield('content')
    </main>

    <footer>
        <!-- Footer content -->
    </footer>

    @stack('scripts')
</body>

</html>