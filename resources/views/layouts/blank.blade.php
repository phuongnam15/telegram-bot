<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield("title", "Default Title")</title>
    @vite("resources/css/app.css")
</head>

<body class="flex min-h-screen items-center justify-center bg-gray-100 dark:bg-[#1c2128]">
    <main class="w-full flex justify-center">
        @yield("content")
    </main>

    @stack("scripts")
</body>
<script>
    const isDarkmode = localStorage.getItem('isDarkmode');
    if (isDarkmode === 'true') {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
</script>

</html>