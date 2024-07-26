<!-- resources/views/setting-bot.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bot Settings</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
</head>

<body>
    <div class="container mx-auto mt-5">
        <button class="btn btn-info mb-3" onclick="window.location.href='/bot'">
            &laquo Bot
        </button>
        <h2 class="text-2xl font-bold mb-3">Bot Settings</h2>
        <div id="botDetails" class="bg-white p-4 rounded shadow-md space-y-2">
            <!-- Bot details will be populated here -->
        </div>
    </div>
    <!-- Modal for creating or updating schedule -->
    <div class="fixed z-10 inset-0 overflow-y-auto hidden" id="scheduleModal">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full">
                <div class="bg-gray-200 px-3 py-1 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700 ml-4" aria-label="Close" onclick="document.getElementById('scheduleModal').classList.add('hidden')">
                        <span aria-hidden="true" class="text-[20px]">&times;</span>
                    </button>
                </div>
                <div class="px-4 py-5 bg-white sm:p-6">
                    <form id="scheduleForm">
                        <input type="hidden" id="scheduleType">
                        <input type="hidden" id="scheduleId">
                        <input type="hidden" id="scheduleBotId">
                        <div class="mb-4">
                            <label for="scheduleStatus" class="text-sm font-medium text-gray-700">Status</label>
                            <select id="scheduleStatus" name="status" required class="border-[1px] border-solid border-gray-300 outline-none py-1 px-2 rounded w-full bg-gray-100 focus:ring-1 focus:ring-blue-300 focus:border-white">
                                <option value="on">On</option>
                                <option value="off">Off</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="scheduleTime" class="text-sm font-medium text-gray-700">Delay Time (minutes)</label>
                            <input type="number" id="scheduleTime" name="delay_time" required class="border-[1px] border-solid border-gray-300 outline-none py-1 px-2 rounded w-full bg-gray-100 focus:ring-1 focus:ring-blue-300 focus:border-white">
                        </div>
                        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(async () => {
            const botId = window.location.pathname.split('/').pop();

            window.getDetailBot = async () => {
                try {
                    const response = await fetchClient(`/api/admin/bot/${botId}`, {
                        method: 'GET'
                    });
                    console.log(response);
                    const data = response.data;
    
                    // Hiển thị chi tiết bot lên trang
                    $('#botDetails').html(`
                        <div class="mb-3 border-[1px] rounded border-solid border-gray-300 p-3">
                            <p class="block text-sm font-medium text-gray-700"><strong>ID:</strong> ${data.id}</p>
                            <p class="block text-sm font-medium text-gray-700"><strong>Token:</strong> ${data.token}</p>
                            <p class="block text-sm font-medium text-gray-700"><strong>Name:</strong> ${data.name}</p>
                            <p class="block text-sm font-medium text-gray-700"><strong>Status:</strong> ${data.status === "1" ? 'Active' : 'Inactive'}</p>
                            <p class="block text-sm font-medium text-gray-700"><strong>Expire:</strong> ${data.expired_at ?? "--"}</p>
                        </div>
                    `);
    
                    // Hiển thị các nút thiết lập schedule
                    if (data.schedule_delete_message) {
                        $('#botDetails').append(`
                            <div class="mt-5">
                                <form id="scheduleForm" class="flex space-x-4">
                                    <div class="flex-1 self-end">
                                        <label for="delay_time" class="block text-sm font-medium text-gray-700">Độ trễ xoá tin (phút)</label>
                                        <input 
                                            type="number" 
                                            id="delay_time" 
                                            name="delay_time" 
                                            class="mt-1 outline-none block w-full border-gray-300 border-[1px] rounded py-1 px-2 focus:border-white focus:ring-indigo-300 focus:ring" 
                                            value="${data.schedule_delete_message.delay_time}"
                                        />
                                    </div>
                                    <div class="flex-1 self-end">
                                        <label for="status" class="text-sm font-medium text-gray-700">Trạng thái</label>
                                        <select 
                                            id="status" 
                                            name="status" 
                                            class="mt-1 outline-none block w-full border-gray-300 border-[1px] rounded py-1 px-2 focus:border-white focus:ring-indigo-300 focus:ring"
                                        >
                                            <option value="on" ${data.schedule_delete_message.status === 'on' ? 'selected' : ''}>On</option>
                                            <option value="off" ${data.schedule_delete_message.status === 'off' ? 'selected' : ''}>Off</option>
                                        </select>
                                    </div>
                                    <button 
                                        type="button" 
                                        class="self-end px-4 py-1 border font-medium rounded text-white bg-indigo-500 border-indigo-500 hover:bg-indigo-600 hover:border-indigo-600"
                                        onclick="openScheduleModal('delete_message', ${botId}, ${JSON.stringify(data.schedule_delete_message).replace(/"/g, '&quot;')})"
                                    >
                                        Cập nhật
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
                             <div class="mb-5">
                                <form id="scheduleConfigForm" class="flex space-x-4">
                                    <div class="flex-1">
                                        <label for="config_delay_time" class="block text-sm font-medium text-gray-700">Lịch chạy user (phút)</label>
                                        <input 
                                            type="number" 
                                            id="config_delay_time" 
                                            name="delay_time" 
                                            class="mt-1 outline-none block w-full border-gray-300 border-[1px] rounded py-1 px-2 shadow-sm focus:border-white focus:ring-indigo-300 focus:ring" 
                                            value="${data.schedule_config.time}"
                                        />
                                    </div>
                                    <div class="flex-1">
                                        <label for="config_status" class="text-sm font-medium text-gray-700">Trạng thái</label>
                                        <select 
                                            id="config_status" 
                                            name="status" 
                                            class="mt-1 outline-none block w-full border-gray-300 border-[1px] rounded py-1 px-2 shadow-sm focus:border-white focus:ring-indigo-300 focus:ring"
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
                                            class="mt-1 block w-full border-gray-300 border-[1px] rounded py-1 px-2 shadow-sm bg-gray-100 text-gray-600" 
                                            value="${data.schedule_config.lastime}"
                                            readonly
                                        />
                                    </div>
                                    <button 
                                        type="button" 
                                        class="self-end px-4 py-1 border font-medium rounded text-white bg-indigo-500 border-indigo-500 hover:bg-indigo-600 hover:border-indigo-600"
                                        onclick="openScheduleModal('config', ${botId}, ${JSON.stringify(data.schedule_config).replace(/"/g, '&quot;')})"
                                    >
                                        Cập nhật
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
                            <div class="mb-5">
                                <form id="scheduleGroupConfigForm" class="flex space-x-4">
                                    <div class="flex-1">
                                        <label for="group_config_delay_time" class="block text-sm font-medium text-gray-700">Lịch chạy group (phút)</label>
                                        <input 
                                            type="number" 
                                            id="group_config_delay_time" 
                                            name="delay_time" 
                                            class="mt-1 outline-none block w-full border-gray-300 border-[1px] rounded py-1 px-2 shadow-sm focus:border-white focus:ring-indigo-300 focus:ring" 
                                            value="${data.schedule_group_config.time}"
                                        />
                                    </div>
                                    <div class="flex-1">
                                        <label for="group_config_status" class="text-sm font-medium text-gray-700">Trạng thái</label>
                                        <select 
                                            id="group_config_status" 
                                            name="status" 
                                            class="mt-1 outline-none block w-full border-gray-300 border-[1px] rounded py-1 px-2 shadow-sm focus:border-white focus:ring-indigo-300 focus:ring"
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
                                            class="mt-1 block w-full border-gray-300 border-[1px] rounded py-1 px-2 shadow-sm bg-gray-100 text-gray-600" 
                                            value="${data.schedule_group_config.lastime}"
                                            readonly
                                        />
                                    </div>
                                    <button 
                                        type="button" 
                                        class="self-end px-4 py-1 border font-medium rounded text-white bg-indigo-500 border-indigo-500 hover:bg-indigo-600 hover:border-indigo-600"
                                        onclick="openScheduleModal('group_config', ${botId}, ${JSON.stringify(data.schedule_group_config).replace(/"/g, '&quot;')})"
                                    >
                                        Cập nhật
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
            }
            await getDetailBot();
        });

        function openScheduleModal(type, botId, schedule = {}) {

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

        $('#scheduleForm').on('submit', async (e) => {
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

            const apiUrl = type === 'config' ? `/api/admin/schedule` : (type === 'group_config') ? `/api/admin/schedule-group` : `/api/admin/schedule-delete`;
            const fullApiUrl = scheduleId ? `${apiUrl}/${scheduleId}` : apiUrl;

            try {
                const response = await fetchClient(fullApiUrl, {
                    method: "POST",
                    body: formData
                });
                $('#scheduleModal').addClass('hidden');
                // window.location.reload();
                await getDetailBot();
            } catch (e) {
                console.log(e);
            }
        });
    </script>
</body>

</html>