@extends('layouts.main')

@section('title', 'Bot Page')

@section('content')
<div class="container px-5 mt-5">
    <h2 class="text-2xl font-bold mb-3">Bot Management</h2>
    <button class="bg-blue-500 text-white py-2 px-3 rounded mb-3 text-sm" data-toggle="modal" data-target="#createBotModal">Tạo bot mới</button>
    <table class="min-w-full bg-white border border-gray-300 overflow-x-auto border-separate border-spacing-0">
        <thead>
            <tr class="w-full bg-gray-200">
                <th class="py-2 px-4 border-b">ID</th>
                <th class="py-2 px-4 border-b">Token</th>
                <th class="py-2 px-4 border-b">Name</th>
                <th class="py-2 px-4 border-b">Status</th>
                <th class="py-2 px-4 border-b">Expire</th>
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

<!-- Modal for activating bot -->
<div class="fixed z-10 inset-0 overflow-y-auto hidden" id="activateBotModal">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full">
            <div class="bg-gray-200 px-3 py-1 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700 ml-4" data-dismiss="modal" aria-label="Close" onclick="document.getElementById('activateBotModal').classList.add('hidden')">
                    <span aria-hidden="true" class="text-[20px]">&times;</span>
                </button>
            </div>
            <div class="px-4 py-5 bg-white sm:p-6">
                <div class="mb-4">
                    <label for="monthQty" class="text-sm font-medium text-gray-700">Select Number of Months</label>
                    <select id="monthQty" class="border-[1px] border-solid border-gray-300 outline-none py-1 px-2 rounded w-full bg-gray-100 focus:ring-1 focus:ring-blue-300 focus:border-white">
                        <option value="1" data-price="10">1 Month - $10</option>
                        <option value="3" data-price="27">3 Months - $27</option>
                        <option value="6" data-price="48">6 Months - $48</option>
                        <option value="12" data-price="90">12 Months - $90</option>
                    </select>
                </div>
                <button id="activateBotButton" class="bg-blue-500 text-white py-1 px-3 rounded text-md">Activate</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(async () => {
        const fetchBots = async () => {
            try {
                const response = await fetchClient('/api/admin/bot', {
                    method: 'GET'
                });
                $('#botTableBody').empty();
                response.forEach(function(bot) {
                    const activeButton = bot.status === "1" ? '' : `<button class="flex-1 bg-blue-500 text-white py-1 px-2 rounded activate-btn text-[13px]" onClick="openActivateBotModal(${bot.id})">Activate</button>`;
                    const status = bot.status === "1" ? '<span class="inline-block px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Bật</span>' : '<span class="inline-block px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded-full">Tắt</span>';

                    document.getElementById('botTableBody').insertAdjacentHTML('beforeend', `
                            <tr>
                                <td class="border px-4 py-2 text-gray-500 font-medium text-sm text-center">${bot.id}</td>
                                <td class="border px-4 py-2 text-gray-500 text-sm text-center">${bot.token}</td>
                                <td class="border px-4 py-2 text-gray-500 text-sm text-center">${bot.name}</td>
                                <td class="border px-4 py-2 text-center">${status}</td>
                                <td class="border px-4 py-2 text-gray-500 font-medium text-sm text-center">${bot.expired_at ?? "--"}</td>
                                <td class="border px-4 py-2 space-y-1 text-center flex flex-col">
                                    ${activeButton}
                                    <button class="bg-red-500 text-white py-1 rounded delete-btn text-[13px]" onClick="deleteBot(${bot.id})">Delete</button>
                                    <button class="bg-green-500 text-white py-1 rounded setting-btn text-[13px]" data-id="${bot.id}">Setting</button>
                                </td>
                            </tr>
                        `);
                });
            } catch (e) {
                console.log(e);
            }
        }

        await fetchBots();

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

        window.openActivateBotModal = (id) => {
            $('#activateBotModal').removeClass('hidden');
            $('#activateBotModal').data('botId', id);
        }

        $('#monthQty').on('change', function() {
            const price = $(this).find(':selected').data('price');
            $('#totalPrice').text(price);
        });

        $('#activateBotButton').on('click', async () => {
            const botId = $('#activateBotModal').data('botId');
            const monthQty = $('#monthQty').val();
            const formData = new FormData();
            formData.append('month_qty', monthQty);

            try {
                const response = await fetchClient(`/api/admin/bot/${botId}`, {
                    method: 'POST',
                    body: formData
                });
                $('#activateBotModal').addClass('hidden');
                await fetchBots();
            } catch (e) {
                console.log(e);
            }
        });

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
        $('.setting-btn').on('click', function() {
            const botId = $(this).data('id');
            window.location.href = `/setting-bot/${botId}`;
        });
    });
</script>
@endpush