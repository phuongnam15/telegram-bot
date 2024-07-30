@extends("layouts.main")

@section("title", "Bot Page")

@section("content")
<!-- <div class="container mt-5">
        <h2 class="mb-3 text-[23px] font-bold text-gray-700">Bot Management</h2>
        <button
            class="mb-3 rounded bg-gray-400 px-3 py-2 text-sm text-white"
            data-toggle="modal"
            data-target="#createBotModal"
        >
            New bot
        </button>
        <table
            class="min-w-full overflow-x-auto border border-gray-300 bg-white"
        >
            <thead>
                <tr class="w-full bg-gray-200">
                    <th class="border-b px-4 py-2">ID</th>
                    <th class="border-b px-4 py-2">Token</th>
                    <th class="border-b px-4 py-2">Name</th>
                    <th class="border-b px-4 py-2">Status</th>
                    <th class="border-b px-4 py-2">Expire</th>
                    <th class="border-b px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="botTableBody" class="w-full">
            </tbody>
        </table>
    </div> -->
<div class="container h-8 mt-3 space-y-2">
    <div class="flex flex-col gap-2" id="botList"></div>
    <div class="bg-[#e5e9f4] border-[1px] border-solid border-[#e5e7eb] rounded-md py-[7px] pl-7 space-x-5 text-gray-700 flex items-center hover:bg-[#ced3dc] cursor-pointer" data-toggle="modal" data-target="#createBotModal">
        <i class="fa-solid fa-plus"></i>
        <span class="test-sm font-medium">Add new bot</span>
    </div>
</div>

<!-- Modal for creating new bot -->
<div class="fixed inset-0 z-10 overflow-y-auto hidden" id="createBotModal">
    <div class="flex min-h-screen items-center justify-center">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="w-full transform overflow-hidden rounded-lg bg-white shadow-xl transition-all sm:max-w-lg">
            <div class="bg-gray-200 px-3 py-1 sm:flex sm:flex-row-reverse sm:px-6">
                <button type="button" class="ml-4 text-gray-500 hover:text-gray-700 focus:text-gray-700 focus:outline-none" data-dismiss="modal" aria-label="Close" onclick="document.getElementById('createBotModal').classList.add('hidden')">
                    <span aria-hidden="true" class="text-[20px]">
                        &times;
                    </span>
                </button>
            </div>
            <div class="bg-white px-4 pt-3 py-1">
                <form id="createBotForm">
                    <div class="mb-4">
                        <label for="botToken" class="text-sm font-mono text-gray-700">
                            Token
                        </label>
                        <input type="text" id="botToken" name="token" required class="w-full rounded border-[1px] border-solid border-gray-300 bg-gray-100 px-2 py-1 outline-none focus:border-white focus:ring-1 focus:ring-blue-300" />
                    </div>
                    <button type="submit" class="rounded bg-blue-500 px-4 py-2 text-white">
                        Tạo
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for activating bot -->
<div class="fixed inset-0 z-10 hidden overflow-y-auto" id="activateBotModal">
    <div class="flex min-h-screen items-center justify-center">
        <div class="w-full transform overflow-hidden rounded-lg bg-white shadow-xl transition-all sm:max-w-lg">
            <div class="bg-gray-200 px-3 py-1 sm:flex sm:flex-row-reverse sm:px-6">
                <button type="button" class="ml-4 text-gray-500 hover:text-gray-700 focus:text-gray-700 focus:outline-none" data-dismiss="modal" aria-label="Close" onclick="document.getElementById('activateBotModal').classList.add('hidden')">
                    <span aria-hidden="true" class="text-[20px]">
                        &times;
                    </span>
                </button>
            </div>
            <div class="bg-white px-4 py-5 sm:p-6">
                <div class="mb-4">
                    <label for="monthQty" class="text-sm font-medium text-gray-700">
                        Select Number of Months
                    </label>
                    <select id="monthQty" class="w-full rounded border-[1px] border-solid border-gray-300 bg-gray-100 px-2 py-1 outline-none focus:border-white focus:ring-1 focus:ring-blue-300">
                        <option value="1" data-price="10">
                            1 Month - $10
                        </option>
                        <option value="3" data-price="27">
                            3 Months - $27
                        </option>
                        <option value="6" data-price="48">
                            6 Months - $48
                        </option>
                        <option value="12" data-price="90">
                            12 Months - $90
                        </option>
                    </select>
                </div>
                <button id="activateBotButton" class="text-md rounded bg-blue-500 px-3 py-1 text-white">
                    Activate
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push("scripts")
<script>
    $(document).ready(async () => {
        const fetchBots = async () => {
            try {
                const response = await fetchClient('/api/admin/bot', {
                    method: 'GET',
                });
                $('#botList').empty();
                response.forEach((bot) => {
                    $('#botList').append(`
                        <div class="cursor-pointer border-[1px] border-solid border-[#e5e7eb] rounded-md overflow-hidden bg-[#f8f8f8] flex items-center" id="botItem" data-id="${bot.id}">
                            <div class="flex items-center gap-2 flex-1 hover:bg-[#ced3dc] py-2 px-3">
                                <img class="size-12 rounded-full" src="https://th.bing.com/th/id/R.ac76a296e880e51f549d9d25865a2e0a?rik=K%2fWgSkaj2gB79Q&riu=http%3a%2f%2fimages4.fanpop.com%2fimage%2fphotos%2f22500000%2fkakashi-sensei-kakashi-22519264-1024-768.jpg&ehk=%2bv5GD7jfWuVq051pFdp%2f4Rs8Rxgnx0VSjNPzcLuXvC4%3d&risl=&pid=ImgRaw&r=0" alt="">
                                <div class="flex flex-col leading-5">
                                    <p class="">
                                        <span class="font-medium">${bot.firstname}</span>
                                        ${bot.status === '1' ? 
                                            `<span class="py-[3px] px-2 bg-green-400 text-white rounded-full text-[12px]">actived</span>`
                                        : 
                                            ``
                                        }
                                    </p>
                                    <p class="text-gray-500 flex items-center gap-1">
                                        <span class="text-sm">@${bot.username}</span>
                                        <i class="fa-brands fa-telegram"></i>
                                    </p>
                                </div>
                            </div>
                            <i class="fa-solid fa-ellipsis-vertical px-5"></i>
                        </div>
                    `);
                });
            } catch (e) {
                console.log(e);
            }
        };

        await fetchBots();

        $('#createBotForm').on('submit', async (e) => {
            e.preventDefault();
            let token = $('#botToken').val();
            const formData = new FormData();
            formData.append('token', token);
            try {
                const response = await fetchClient('/api/admin/bot', {
                    method: 'POST',
                    body: formData,
                });
                $('#createBotModal').modal('hide');
                await fetchBots();
            } catch (e) {
                console.log(e);
            }
        });


        $('#botItem').on('click', function() {
            const botId = $(this).data('id');
            window.location.href = `/setting-bot/${botId}`;
        });

        {
            window.openActivateBotModal = (id) => {
                $('#activateBotModal').removeClass('hidden');
                $('#activateBotModal').data('botId', id);
            };

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
                    const response = await fetchClient(
                        `/api/admin/bot/${botId}`, {
                            method: 'POST',
                            body: formData,
                        },
                    );
                    $('#activateBotModal').addClass('hidden');
                    // await fetchBots();
                } catch (e) {
                    console.log(e);
                }
            });

            window.deleteBot = async (id) => {
                if (confirm('Are you sure you want to delete this bot?')) {
                    try {
                        const response = await fetchClient(
                            `/api/admin/bot/${id}`, {
                                method: 'DELETE',
                            },
                        );
                        // await fetchBots();
                    } catch (e) {
                        console.log(e);
                    }
                }
            };
        }
    });
</script>
@endpush