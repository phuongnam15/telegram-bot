<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bot Management</title>
    <!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
</head>

<body>
    <div class="container mx-auto mt-5">
        <button class="btn btn-info mb-3" onclick="window.location.href='/'">
            &laquo Home
        </button>
        <h2 class="text-2xl font-bold mb-3">Bot Management</h2>
        <button class="bg-blue-500 text-white py-2 px-4 rounded mb-3" data-toggle="modal" data-target="#createBotModal">Tạo bot mới</button>
        <table class="min-w-full bg-white border border-gray-300 overflow-x-auto">
            <thead>
                <tr class="w-full bg-gray-200">
                    <th class="py-2 px-4 border-b">ID</th>
                    <th class="py-2 px-4 border-b">Token</th>
                    <th class="py-2 px-4 border-b">Name</th>
                    <th class="py-2 px-4 border-b">Status</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody id="botTableBody" class="w-full">
                <!-- Bots will be populated here by jQuery -->
            </tbody>
        </table>
    </div>

    <!-- Modal for creating new bot -->
    <div class="fixed z-10 inset-0 overflow-y-auto hidden" id="createBotModal">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full">
                <div class="bg-gray-200 px-3 py-1 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700 ml-4" data-dismiss="modal" aria-label="Close" onclick="document.getElementById('createBotModal').classList.add('hidden')">
                        <span aria-hidden="true" class="text-[20px]">&times;</span>
                    </button>
                </div>
                <div class="px-4 py-5 bg-white sm:p-6">
                    <form id="createBotForm">
                        <div class="mb-4">
                            <label for="botToken" class="text-sm font-medium text-gray-700">Token</label>
                            <input type="text" id="botToken" name="token" required class="border-[1px] border-solid border-gray-300 outline-none py-1 px-2 rounded w-full bg-gray-100 focus:ring-1 focus:ring-blue-300 focus:border-white">
                        </div>
                        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Tạo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(async () => {
            const fetchBots = async () => {
                try {
                    const response = await fetchClient('/api/admin/bot', {
                        method: 'GET'
                    });
                    $('#botTableBody').empty();
                    let activeButton;
                    let status;
                    response.forEach(function(bot) {
                        const activeButton = bot.status === "1" ? '' : `<button class="bg-blue-500 text-white py-1 px-2 rounded activate-btn" onClick="activeBot(${bot.id})">Active</button>`;
                        const status = bot.status === "1" ? '<span class="inline-block px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Bật</span>' : '<span class="inline-block px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded-full">Tắt</span>';
    
                        document.getElementById('botTableBody').insertAdjacentHTML('beforeend', `
                            <tr>
                                <td class="border px-4 py-2">${bot.id}</td>
                                <td class="border px-4 py-2">${bot.token}</td>
                                <td class="border px-4 py-2">${bot.name}</td>
                                <td class="border px-4 py-2">${status}</td>
                                <td class="border px-4 py-2 space-y-2">
                                    ${activeButton}
                                    <button class="bg-red-500 text-white py-1 px-2 rounded delete-btn" onClick="deleteBot(${bot.id})">Delete</button>
                                </td>
                            </tr>
                        `);
                    });
                } catch (e) {
                    console.log(e);
                }
            }

            await fetchBots();

            // Create new bot
            $('#createBotForm').on('submit', async (e) => {
                e.preventDefault();
                let token = $('#botToken').val();
                const formData = new FormData();
                formData.append('token', token);
                try {
                    const response = await fetchClient('/api/admin/bot', {
                        method: 'POST',
                        body: formData
                    });
                    $('#createBotModal').modal('hide');
                    await fetchBots();
                } catch (e) {
                    console.log(e);
                }
            });

            // Activate bot
            window.activeBot = async (id) => {
                try {
                    const response = await fetchClient(`/api/admin/bot/${id}`, {
                        method: 'POST'
                    });
                    await fetchBots();
                } catch (e) {
                    console.log(e);
                }
            }

            // Delete bot
            window.deleteBot = async (id) => {
                if (confirm('Are you sure you want to delete this bot?')) {
                    try {
                        const response = await fetchClient(`/api/admin/bot/${id}`, {
                            method: 'DELETE'
                        });
                        await fetchBots();
                    } catch (e) {
                        console.log(e);
                    }
                }
            }
        });
    </script>
</body>

</html>