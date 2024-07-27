<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Register</title>
        @vite("resources/css/app.css")
    </head>

    <body class="flex min-h-screen items-center justify-center bg-gray-100">
        <div class="w-full max-w-sm rounded-lg bg-white p-6 shadow-md">
            <div
                id="error-message"
                class="mb-4 hidden text-center text-sm text-red-500"
            ></div>
            <div
                id="success-message"
                class="mb-4 hidden text-center text-sm text-green-500"
            ></div>
            <form id="registerForm">
                @csrf
                <div class="mb-3">
                    <label
                        for="name"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Name
                    </label>
                    <input
                        type="text"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-2 py-1 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                        id="name"
                        name="name"
                        required
                    />
                </div>
                <div class="mb-3">
                    <label
                        for="email"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Email address
                    </label>
                    <input
                        type="email"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-2 py-1 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                        id="email"
                        name="email"
                        required
                    />
                </div>
                <div class="mb-3">
                    <label
                        for="password"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Password
                    </label>
                    <input
                        type="password"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-2 py-1 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                        id="password"
                        name="password"
                        required
                    />
                </div>
                <div class="mb-4">
                    <label
                        for="password_confirmation"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Confirm Password
                    </label>
                    <input
                        type="password"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-2 py-1 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                    />
                </div>
                <button
                    type="submit"
                    class="w-full rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Register
                </button>
            </form>
            <div class="mt-4 text-center">
                <a
                    href="/login"
                    class="text-xs font-medium text-indigo-500 underline underline-offset-4 hover:text-indigo-600"
                >
                    You already have an account
                </a>
            </div>
        </div>
        <script>
            document
                .getElementById('registerForm')
                .addEventListener('submit', async function (event) {
                    event.preventDefault();

                    const name = document.getElementById('name').value;
                    const email = document.getElementById('email').value;
                    const password = document.getElementById('password').value;
                    const password_confirmation = document.getElementById(
                        'password_confirmation',
                    ).value;
                    const errorMessageElement =
                        document.getElementById('error-message');
                    const successMessageElement =
                        document.getElementById('success-message');

                    try {
                        const response = await fetch(
                            '/api/admin/auth/register',
                            {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    Accept: 'application/json',
                                },
                                body: JSON.stringify({
                                    name,
                                    email,
                                    password,
                                    password_confirmation,
                                }),
                            },
                        );

                        if (response.status === 200) {
                            successMessageElement.textContent =
                                'Registration successful';
                            successMessageElement.classList.remove('hidden');
                        } else {
                            const errorData = await response.json();
                            errorMessageElement.textContent =
                                errorData.msg_error;
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
