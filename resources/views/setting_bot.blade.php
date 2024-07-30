@extends("layouts.main")

@section("title", "Setting Bot Page")

@section("content")
<div class="container mt-5">
    <!-- <h2 class="mb-3 text-[23px] font-bold text-gray-700">Bot Settings</h2> -->
    <div id="botDetails" class="mb-5 space-y-3 bg-white font-sans border-b-[1px] border-solid border-gray-200 pb-5">
        <!-- Bot details will be populated here -->
    </div>
    <div id="botCommands" class="space-y-2 rounded bg-white">
        <button class="transform rounded border border-gray-400 bg-gray-400 px-4 py-[7px] text-sm font-popi text-white duration-200 hover:border-gray-500 hover:bg-gray-50 hover:text-gray-500" data-toggle="modal" data-target="#newCommandModal">
            New command
        </button>
        <table class="w-full">
            <thead class="bg-gray-200">
                <tr class="text-sm font-medium text-gray-600">
                    <td class="px-4 py-2 text-center">Command</td>
                    <td class="px-4 py-2 text-center">Content Name</td>
                    <td class="px-4 py-2 text-center">Created At</td>
                    <td class="px-4 py-2 text-center">Action</td>
                </tr>
            </thead>
            <tbody id="botCommandsBody"></tbody>
        </table>
    </div>
</div>
<!-- Modal for creating or updating schedule -->
<div class="fixed inset-0 z-10 overflow-y-auto hidden" id="scheduleModal">
    <div class="flex min-h-screen items-center justify-center">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="w-full transform overflow-hidden rounded-lg bg-white shadow-xl transition-all sm:max-w-lg">
            <div class="bg-gray-200 px-3 py-1 sm:flex sm:flex-row-reverse sm:px-6">
                <button type="button" class="ml-4 text-gray-500 hover:text-gray-700 focus:text-gray-700 focus:outline-none" aria-label="Close" onclick="document.getElementById('scheduleModal').classList.add('hidden')">
                    <span aria-hidden="true" class="text-[20px]">
                        &times;
                    </span>
                </button>
            </div>
            <div class="bg-white px-4 py-2 pt-4 pb-1">
                <form id="scheduleUpdateForm">
                    <input type="hidden" id="scheduleType" />
                    <input type="hidden" id="scheduleId" />
                    <input type="hidden" id="scheduleBotId" />
                    <div class="mb-4">
                        <label for="scheduleStatus" class="text-sm font-mono tracking-tighter text-gray-700">
                            Status
                        </label>
                        <select id="scheduleStatus" name="status" required class="w-full rounded border-[1px] border-solid border-gray-300 bg-gray-100 px-2 py-1 outline-none focus:border-white focus:ring-1 focus:ring-blue-300">
                            <option value="on">On</option>
                            <option value="off">Off</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="scheduleTime" class="text-sm font-mono tracking-tighter text-gray-700">
                            Delay Time (minutes)
                        </label>
                        <input type="number" id="scheduleTime" name="delay_time" required class="w-full rounded border-[1px] border-solid border-gray-300 bg-gray-100 px-2 py-1 outline-none focus:border-white focus:ring-1 focus:ring-blue-300" />
                    </div>
                    <button type="submit" class="rounded bg-blue-500 px-4 py-2 text-white">
                        Save
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal to set new command -->
<div class="fixed inset-0 z-10 hidden overflow-y-auto" id="newCommandModal" tabindex="-1" role="dialog" aria-labelledby="newCommandModalLabel" aria-hidden="true">
    <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">
            &#8203;
        </span>
        <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
            <div class="flex flex-row-reverse bg-gray-200 px-3 py-2 sm:px-6">
                <button type="button" class="ml-4 text-gray-500 hover:text-gray-700 focus:text-gray-700 focus:outline-none" data-dismiss="modal">
                    <span aria-hidden="true" class="text-[20px]">
                        &times;
                    </span>
                </button>
            </div>
            <div class="bg-white px-4 pb-4 pt-3">
                <div class="sm:flex sm:items-start">
                    <div class="w-full">
                        <form id="commandForm">
                            <div class="mb-4">
                                <label for="command" class="text-gray-700 font-mono text-sm">
                                    Command
                                </label>
                                <input type="text" class="w-full rounded border-[1px] border-solid border-gray-300 bg-gray-100 px-2 py-1 outline-none focus:border-white focus:ring-1 focus:ring-blue-300" id="command" name="command" required placeholder="ex: /start" />
                            </div>
                            <div class="mb-4">
                                <select id="contentList" class="text-sm outline-none">
                                    <option value="">-----content----</option>
                                </select>
                            </div>
                            <button type="submit" class="float-end rounded bg-blue-500 px-4 py-2 text-white">
                                Save
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for activating bot -->
<div class="fixed inset-0 z-10 hidden overflow-y-auto" id="activateBotModal">
    <div class="flex min-h-screen items-center justify-center">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
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
        const botId = window.location.pathname.split('/').pop();

        window.getDetailBot = async () => {
            try {
                const response = await fetchClient(
                    `/api/admin/bot/${botId}`, {
                        method: 'GET',
                    },
                );

                const data = response.data;

                // Hiển thị chi tiết bot lên trang
                $('#botDetails').html(`
                        <div class="mb-3 border-[1px] rounded border-solid border-gray-300 px-3 pt-3 pb-1 relative">
                            <img src="https://th.bing.com/th/id/R.ac76a296e880e51f549d9d25865a2e0a?rik=K%2fWgSkaj2gB79Q&riu=http%3a%2f%2fimages4.fanpop.com%2fimage%2fphotos%2f22500000%2fkakashi-sensei-kakashi-22519264-1024-768.jpg&ehk=%2bv5GD7jfWuVq051pFdp%2f4Rs8Rxgnx0VSjNPzcLuXvC4%3d&risl=&pid=ImgRaw&r=0" 
                                class="size-24 rounded-full absolute top-[50%] -translate-y-1/2 -translate-x-1/2 right-0" />
                            <p class="block text-sm font-medium text-gray-700"><strong>Token:</strong> ${data.token}</p>
                            <p class="block text-sm font-medium text-gray-700"><strong>Username:</strong> @${data.username}</p>
                            <p class="block text-sm font-medium text-gray-700"><strong>Firstname:</strong> ${data.firstname}</p>
                            <p class="block text-sm font-medium text-gray-700"><strong>Status:</strong> ${data.status === '1' ? 'Active' : 'Inactive'}</p>
                            <p class="block text-sm font-medium text-gray-700"><strong>Expire:</strong> ${data.expired_at ?? '--'}</p>
                            <div class="mt-2">
                                ${data.status === '0' ? 
                                    `<button class="text-green-500 text-[14px] italic hover:underline activate-btn" onClick="openActivateBotModal(${data.id})">active</button>
                                    <span>/</span>`
                                : 
                                    ''
                                }
                                <button class="text-red-500 text-[14px] italic hover:underline" onClick="deleteBot(${data.id})">delete</button>
                            </div>
                        </div>
                    `);

                // Hiển thị các nút thiết lập schedule
                if (data.schedule_delete_message) {
                    $('#botDetails').append(`
                            <div class="">
                                <form id="scheduleForm" class="flex space-x-4 mb-0">
                                    <div class="flex-1 self-end">
                                        <label for="delay_time" class="block text-sm font-medium text-gray-700">Độ trễ xoá tin (phút)</label>
                                        <input
                                            type="number"
                                            id="delay_time"
                                            name="delay_time"
                                            class="mt-1 outline-none block w-full border-gray-300 border-[1px] rounded py-1 px-2 focus:border-white focus:ring-green-300 focus:ring"
                                            value="${data.schedule_delete_message.delay_time}"
                                        />
                                    </div>
                                    <div class="flex-1 flex flex-col">
                                        <label for="status" class="text-sm font-medium text-gray-700">Trạng thái</label>
                                        <select
                                            id="status"
                                            name="status"
                                            class="mt-1 flex-1 outline-none block w-full border-gray-300 border-[1px] rounded py-1 px-2 focus:border-white focus:ring-green-300 focus:ring"
                                        >
                                            <option value="on" ${data.schedule_delete_message.status === 'on' ? 'selected' : ''}>On</option>
                                            <option value="off" ${data.schedule_delete_message.status === 'off' ? 'selected' : ''}>Off</option>
                                        </select>
                                    </div>
                                    <button
                                        type="button"
                                        class="self-end px-3 py-2 border font-medium rounded text-white bg-gray-400 border-gray-400 hover:bg-gray-50 hover:text-gray-500 hover:border-gray-500 transform duration-200"
                                        onclick="openScheduleModal('delete_message', ${botId}, ${JSON.stringify(data.schedule_delete_message).replace(/"/g, '&quot;')})"
                                    >
                                        <i class="fa-solid fa-wrench"></i>
                                    </button>
                                </form>
                            </div>
                        `);
                } else {
                    $('#botDetails').append(`
                            <button class="bg-blue-500 text-white py-2 px-4 rounded mt-2" onclick="openScheduleModal('config', ${botId})">Create Schedule Delete Message</button>
                        `);
                }

                if (data.schedule_config) {
                    $('#botDetails').append(`
                             <div class="">
                                <form id="scheduleConfigForm" class="flex space-x-4 mb-0">
                                    <div class="flex-1">
                                        <label for="config_delay_time" class="block text-sm font-medium text-gray-700">Lịch chạy user (phút)</label>
                                        <input
                                            type="number"
                                            id="config_delay_time"
                                            name="delay_time"
                                            class="mt-1 outline-none block w-full border-gray-300 border-[1px] rounded py-1 px-2 shadow-sm focus:border-white focus:ring-green-300 focus:ring"
                                            value="${data.schedule_config.time}"
                                        />
                                    </div>
                                    <div class="flex-1 flex flex-col">
                                        <label for="config_status" class="text-sm font-medium text-gray-700">Trạng thái</label>
                                        <select
                                            id="config_status"
                                            name="status"
                                            class="mt-1 flex-1 outline-none block w-full border-gray-300 border-[1px] rounded py-1 px-2 shadow-sm focus:border-white focus:ring-green-300 focus:ring"
                                        >
                                            <option value="on" ${data.schedule_config.status === 'on' ? 'selected' : ''}>On</option>
                                            <option value="off" ${data.schedule_config.status === 'off' ? 'selected' : ''}>Off</option>
                                        </select>
                                    </div>
                                    <div class="flex-1">
                                        <label for="config_lastime" class="text-sm font-medium text-gray-700">Lần cuối chạy</label>
                                        <input
                                            type="text"
                                            id="config_lastime"
                                            name="lastime"
                                            class="outline-none mt-1 block w-full border-gray-300 border-[1px] rounded py-1 px-2 shadow-sm bg-gray-100 text-gray-600"
                                            value="${data.schedule_config.lastime}"
                                            readonly
                                        />
                                    </div>
                                    <button
                                        type="button"
                                        class="self-end px-3 py-2 border font-medium rounded text-white bg-gray-400 border-gray-400 hover:bg-gray-50 hover:text-gray-500 hover:border-gray-500 transform duration-200"
                                        onclick="openScheduleModal('config', ${botId}, ${JSON.stringify(data.schedule_config).replace(/"/g, '&quot;')})"
                                    >
                                        <i class="fa-solid fa-wrench"></i>
                                    </button>
                                </form>
                            </div>
                        `);
                } else {
                    $('#botDetails').append(`
                            <button class="bg-green-500 text-white py-1 px-3 rounded mt-2" onclick="openScheduleModal('config', ${botId})">User Config</button>
                        `);
                }

                if (data.schedule_group_config) {
                    $('#botDetails').append(`
                            <div>
                                <form id="scheduleGroupConfigForm" class="flex space-x-4 mb-0">
                                    <div class="flex-1">
                                        <label for="group_config_delay_time" class="block text-sm font-medium text-gray-700">Lịch chạy group (phút)</label>
                                        <input
                                            type="number"
                                            id="group_config_delay_time"
                                            name="delay_time"
                                            class="mt-1 outline-none block w-full border-gray-300 border-[1px] rounded py-1 px-2 shadow-sm focus:border-white focus:ring-green-300 focus:ring"
                                            value="${data.schedule_group_config.time}"
                                        />
                                    </div>
                                    <div class="flex-1 flex flex-col">
                                        <label for="group_config_status" class="text-sm font-medium text-gray-700">Trạng thái</label>
                                        <select
                                            id="group_config_status"
                                            name="status"
                                            class="mt-1 flex-1 outline-none block w-full border-gray-300 border-[1px] rounded py-1 px-2 shadow-sm focus:border-white focus:ring-green-300 focus:ring"
                                        >
                                            <option value="on" ${data.schedule_group_config.status === 'on' ? 'selected' : ''}>On</option>
                                            <option value="off" ${data.schedule_group_config.status === 'off' ? 'selected' : ''}>Off</option>
                                        </select>
                                    </div>
                                    <div class="flex-1">
                                        <label for="group_config_lastime" class="text-sm font-medium text-gray-700">Lần cuối chạy</label>
                                        <input
                                            type="text"
                                            id="group_config_lastime"
                                            name="lastime"
                                            class="outline-none mt-1 block w-full border-gray-300 border-[1px] rounded py-1 px-2 shadow-sm bg-gray-100 text-gray-600"
                                            value="${data.schedule_group_config.lastime}"
                                            readonly
                                        />
                                    </div>
                                    <button
                                        type="button"
                                        class="self-end px-3 py-2 border font-medium rounded text-white bg-gray-400 border-gray-400 hover:bg-gray-50 hover:text-gray-500 hover:border-gray-500 transform duration-200"
                                        onclick="openScheduleModal('group_config', ${botId}, ${JSON.stringify(data.schedule_group_config).replace(/"/g, '&quot;')})"
                                    >
                                        <i class="fa-solid fa-wrench"></i>
                                    </button>
                                </form>
                            </div>
                        `);
                } else {
                    $('#botDetails').append(`
                            <button class="bg-green-500 text-white py-1 px-3 rounded mt-2" onclick="openScheduleModal('group_config', ${botId})">Group Config</button>
                        `);
                }
            } catch (e) {
                console.log(e);
            }
        };

        const listCommand = async () => {
            try {
                $("#botCommandsBody").html("");

                const formData = new FormData();

                formData.append('bot_id', botId);

                const response = await fetchClient(`/api/admin/command?bot_id=${botId}`, {
                    method: "GET",
                });

                response.data.forEach(element => {
                    $("#botCommandsBody").append(`
                        <tr class="border-b-[1px] border-solid border-gray-200">
                            <td class="px-4 py-2 text-center">${element.command.command}</td>
                            <td class="px-4 py-2 text-center">${element.content.name}</td>
                            <td class="px-4 py-2 text-center">${formatDate(element.created_at)}</td>
                            <td class="px-4 py-2 text-center">
                                <button class="rounded-md border-[1px] border-solid border-gray-500 px-3 py-1 text-gray-500" onClick="deleteCommand(${element.id})">
                                    delete
                                </button>
                            </td>
                        </tr>
                    `);
                });

            } catch (error) {
                console.log(error)
            }
        }

        try {
            const response = await fetchClient("/api/admin/list/", {
                method: "GET",
            });

            response.data.forEach(element => {
                $("#contentList").append(`
                    <option value="${element.id}">${element.id} - ${element.name}</option>
                `);
            });
        } catch (error) {
            console.log(error)
        }

        await getDetailBot();
        await listCommand();

        window.deleteCommand = async (commandId) => {
            try {
                if (!confirm("Are you sure to delete this command?")) {
                    return;
                }
                const response = await fetchClient(`/api/admin/command/${commandId}`, {
                    method: "DELETE",
                });

                console.log(response);
                await listCommand();
            } catch (error) {
                console.log(error)
            }
        }

        window.openScheduleModal = (type, botId, schedule = {}) => {
            $('#scheduleModal').removeClass('hidden');
            $('#scheduleType').val(type);
            $('#scheduleBotId').val(botId);

            if (Object.keys(schedule).length > 0) {
                $('#scheduleId').val(schedule.id);
                $('#scheduleStatus').val(schedule.status);
                $('#scheduleTime').val(schedule.delay_time ?? schedule.time);
            } else {
                $('#scheduleId').val('');
                $('#scheduleStatus').val('on');
                $('#scheduleTime').val('');
            }
        }

        $('#scheduleUpdateForm').on('submit', async (e) => {
            e.preventDefault();

            const type = $('#scheduleType').val();
            const botId = $('#scheduleBotId').val();
            const scheduleId = $('#scheduleId').val();
            const status = $('#scheduleStatus').val();
            const time = $('#scheduleTime').val();

            const formData = new FormData();
            formData.append('status', status);
            formData.append('time', time);
            formData.append('bot_id', botId);
            formData.append('id', scheduleId);

            const apiUrl =
                type === 'config' ?
                `/api/admin/schedule` :
                type === 'group_config' ?
                `/api/admin/schedule-group` :
                `/api/admin/schedule-delete`;
            const fullApiUrl = scheduleId ? `${apiUrl}/${scheduleId}` : apiUrl;

            try {
                const response = await fetchClient(fullApiUrl, {
                    method: 'POST',
                    body: formData,
                });

                $('#scheduleModal').addClass('hidden');
                await getDetailBot();
            } catch (e) {
                console.log(e);
            }
        });

        $('#commandForm').on('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData();
            formData.append('bot_id', botId);
            formData.append('command', $('#command').val());
            formData.append('content_id', $('#contentList').val());

            try {
                const response = await fetchClient('/api/admin/command', {
                    method: 'POST',
                    body: formData,
                });

                $('#newCommandModal').modal('hide');
                await listCommand();
            } catch (error) {
                console.log(error);
            }
        });

        $('#newCommandModal').on('hidden.bs.modal', function() {
            $('#commandForm')[0].reset();
            $('#contentList').val('');
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
                    location.reload();
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
                        window.location.href = '/bot';
                    } catch (e) {
                        console.log(e);
                    }
                }
            };
        }
    });
</script>
@endpush