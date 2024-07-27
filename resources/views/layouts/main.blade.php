<html lang="en">

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
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @vite("resources/js/app.js")
    @vite("resources/css/app.css")
    <style>
        .parent:hover {
            background-color: white;
            border-radius: 0 1rem 1rem 0;
            /* rounded-s-2xl */
        }

        .parent:hover a:hover {
            background-color: #fafbfb;
            color: gray;
        }
    </style>
</head>

<body class="font-ubuntu">
    <div class="flex bg-[#fafbfb]">
        @include("layouts.navbar")

        <main class="w-full p-2">
            @yield("content")
        </main>
    </div>

    @stack("scripts")
</body>

</html>