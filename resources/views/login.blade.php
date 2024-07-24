<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-md rounded-lg p-6 max-w-sm w-full">
        <div id="error-message" class="hidden text-red-500 mb-4 text-center text-sm"></div>
        <form id="loginForm">
            @csrf
            <div class="mb-3">
                <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                <input type="email" class="mt-1 block w-full px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" class="mt-1 block w-full px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="password" name="password" required>
            </div>
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-900">Remember me</label>
                </div>
                <div class="text-sm">
                    <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Forgot your password?</a>
                </div>
            </div>
            <button type="submit" class="w-full py-2 px-3 bg-indigo-600 text-white font-semibold rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 text-sm">Login</button>
        </form>
        <div class="text-center mt-4">
            <a href="/register" class="text-indigo-500 hover:text-indigo-600 font-medium text-xs underline underline-offset-4">Create a new account</a>
        </div>
    </div>
    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(event) {
            event.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const remember = document.getElementById('remember').checked;
            const errorMessageElement = document.getElementById('error-message');

            try {
                const response = await fetch('/api/admin/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email,
                        password,
                    })
                });

                if (response.status === 200) {
                    const data = await response.json();
                    localStorage.setItem('access_token', data?.data?.token);
                    window.location.href = '/';
                } else {
                    const data = await response.json();
                    errorMessageElement.textContent = data.message;
                    errorMessageElement.classList.remove('hidden');
                }
            } catch (error) {
                console.error(error);
                errorMessageElement.textContent = error;
                errorMessageElement.classList.remove('hidden');
            }
        });
    </script>
</body>

</html>