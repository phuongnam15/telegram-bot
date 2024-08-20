@extends("layouts.blank")

@section("title", "Login")

@section("content")
<div class= "w-full max-w-sm rounded-lg bg-white p-6 shadow-md dark:bg-[#2d333b]">
    <div
        id="error-message"
        class="mb-4 hidden text-center text-sm text-red-500"></div>
    <form id="loginForm">
        @csrf
        <div class="mb-3">
            <label
                for="email"
                class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Email address
            </label>
            <input
                type="email"
                class="mt-1 block w-full rounded-md border border-gray-300 px-2 py-1 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm dark:bg-[#1c2128] dark:text-white dark:border-gray-700"
                id="email"
                name="email"
                required />
        </div>
        <div class="mb-3">
            <label
                for="password"
                class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Password
            </label>
            <input
                type="password"
                class="mt-1 block w-full rounded-md border border-gray-300 px-2 py-1 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm dark:bg-[#1c2128] dark:text-white dark:border-gray-700"
                id="password"
                name="password"
                required />
        </div>
        <div class="mb-4 flex items-center justify-between">
            <div class="flex items-center">
                <input
                    id="remember"
                    name="remember"
                    type="checkbox"
                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                <label
                    for="remember"
                    class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                    Remember me
                </label>
            </div>
            <div class="text-sm">
                <a
                    href="#"
                    class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                    Forgot your password?
                </a>
            </div>
        </div>
        <button
            type="submit"
            class="w-full rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            Login
        </button>
    </form>
    <div class="mt-4 text-center">
        <a
            href="/register"
            class="text-xs font-medium text-indigo-500 underline underline-offset-4 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300">
            Create a new account
        </a>
    </div>
</div>
@endsection
@push("scripts")
<script>
    document
        .getElementById('loginForm')
        .addEventListener('submit', async function(event) {
            event.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const remember =
                document.getElementById('remember').checked;
            const errorMessageElement =
                document.getElementById('error-message');

            try {
                const response = await fetch('/api/admin/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                    },
                    body: JSON.stringify({
                        email,
                        password,
                    }),
                });

                if (response.status === 200) {
                    const data = await response.json();
                    localStorage.setItem(
                        'access_token',
                        data?.data?.token,
                    );
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
@endpush