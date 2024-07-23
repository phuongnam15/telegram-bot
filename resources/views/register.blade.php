<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-md rounded-lg p-6 max-w-sm w-full">
        <div id="error-message" class="hidden text-red-500 mb-4 text-center text-sm"></div>
        <div id="success-message" class="hidden text-green-500 mb-4 text-center text-sm"></div>
        <form id="registerForm">
            @csrf
            <div class="mb-3">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" class="mt-1 block w-full px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                <input type="email" class="mt-1 block w-full px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" class="mt-1 block w-full px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="password" name="password" required>
            </div>
            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input type="password" class="mt-1 block w-full px-2 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="password_confirmation" name="password_confirmation" required>
            </div>
            <button type="submit" class="w-full py-2 px-3 bg-indigo-600 text-white font-semibold rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 text-sm">Register</button>
        </form>
        <div class="text-center mt-4">
            <a href="/login" class="text-indigo-500 hover:text-indigo-600 font-medium text-xs underline underline-offset-4">You already have an account</a>
        </div>
    </div>
    <script>
        document.getElementById('registerForm').addEventListener('submit', async function(event) {
            event.preventDefault();

            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const password_confirmation = document.getElementById('password_confirmation').value;
            const errorMessageElement = document.getElementById('error-message');
            const successMessageElement = document.getElementById('success-message');

            try {
                const response = await fetch('/api/admin/auth/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name,
                        email,
                        password,
                        password_confirmation
                    })
                });

                if (response.status === 200) {
                    successMessageElement.textContent = 'Registration successful';
                    successMessageElement.classList.remove('hidden');
                } else {
                    const errorData = await response.json();
                    errorMessageElement.textContent = errorData.msg_error;
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