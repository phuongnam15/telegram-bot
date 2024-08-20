@extends("layouts.main")

@section("title", "Bot Page")

@section("content")
<div class="container h-8 mt-3 space-y-2">
    <div class="flex flex-col gap-2" id="botList"></div>
    <div class="bg-gray-300 dark:bg-gray-800 text-[0.8rem] border border-gray-300 dark:border-gray-600 rounded-md py-[0.4rem] font-popi pl-7 space-x-5 text-gray-700 dark:text-gray-300 flex items-center hover:bg-white dark:hover:bg-gray-700 transition-all duration-200 cursor-pointer" data-toggle="modal" data-target="#createBotModal">
        <i class="fa-solid fa-plus"></i>
        <span class="test-sm font-medium">Add new bot</span>
    </div>
</div>

<!-- Modal for creating new bot -->
<div class="fixed inset-0 z-10 overflow-y-auto hidden" id="createBotModal">
    <div class="flex min-h-screen items-center justify-center">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
        </div>
        <div class="w-full transform overflow-hidden rounded bg-white dark:bg-gray-800 shadow-xl transition-all sm:max-w-lg">
            <div class="flex flex-row-reverse pr-3 border-b border-gray-100 dark:border-gray-700">
                <button type="button" class="text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-100 focus:text-gray-700 dark:focus:text-gray-100 focus:outline-none" data-dismiss="modal" aria-label="Close" onclick="document.getElementById('createBotModal').classList.add('hidden')">
                    <span aria-hidden="true" class="text-xl">
                        &times;
                    </span>
                </button>
            </div>
            <div class="bg-white dark:bg-gray-800 px-4 pt-3 py-1">
                <form id="createBotForm">
                    <div class="mb-4">
                        <label for="botToken" class="text-sm font-mono text-gray-700 dark:text-gray-300">
                            Token
                        </label>
                        <input type="text" id="botToken" name="token" required class="w-full rounded border-[1px] border-solid border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 px-2 py-1 outline-none focus:border-white focus:ring-1 focus:ring-[#6c799a] dark:focus:border-gray-100 dark:focus:ring-gray-600" />
                    </div>
                    <button type="submit" class="rounded bg-[#6c799a] px-4 py-1 text-white dark:bg-[#4a536b]">
                        Tạo
                    </button>
                </form>
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
                        <div class="relative cursor-pointer border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition-all duration-150 rounded-md overflow-hidden bg-[#f8f8f8] dark:bg-[#1a1a1a] flex items-center" onClick="showDetailBot(${bot.id})">
                            <span class="size-2 top-1 left-1 rounded-full absolute border border-gray-300 dark:border-gray-600 ${bot.status === '1' ? 'bg-green-400' : 'bg-red-400'}"></span>
                            <div class="flex items-center gap-2 flex-1 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-150 py-2 px-3">
                                <img class="size-12 rounded-full" src="${bot.avatar ?? "{{ asset('assets/images/bot.png') }}"}" alt="">
                                <div class="flex flex-col leading-5">
                                    <p class="">
                                        <span class="font-medium text-[15px] tracking-wide font-sans text-gray-900 dark:text-gray-100">${bot.firstname}</span>
                                    </p>
                                    <p class="text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                        <span class="text-sm">@${bot.username}</span>
                                        <i class="fa-brands fa-telegram"></i>
                                    </p>
                                </div>
                            </div>
                            <i class="fa-solid fa-ellipsis-vertical px-5 dark:text-gray-400"></i>
                        </div>
                    `);
                });
            } catch (e) {
                console.log(e);
            }
        };

        await fetchBots();

        window.showDetailBot = (botId) => {
            window.location.href = `/setting-bot/${botId}`;
        };

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
                showNotification('Bot created successfully', 'success');
            } catch (e) {
                console.log(e);
            }
        });
    });
</script>
@endpush