@extends("layouts.main")

@section("title", "Setting Bot Page")

@section("content")
    <div class="container mt-5 px-5">
        <h2 class="mb-3 text-[23px] font-bold text-gray-700">Bot Settings</h2>
        <div
            id="botDetails"
            class="mb-3 space-y-2 rounded bg-white p-4 font-sans shadow-md"
        >
            <!-- Bot details will be populated here -->
        </div>
        <div id="botCommands" class="space-y-2 rounded bg-white p-4 shadow-md">
            <button
                class="transform rounded border border-gray-500 bg-gray-500 px-4 py-1 font-medium text-white duration-200 hover:border-gray-600 hover:bg-gray-50 hover:text-gray-600"
            >
                New Command
            </button>
            <table class="w-full">
                <thead class="bg-gray-200">
                    <tr class="text-sm font-medium text-gray-600">
                        <td class="px-4 py-2 text-center">ID</td>
                        <td class="px-4 py-2 text-center">Command</td>
                        <td class="px-4 py-2 text-center">Content Name</td>
                        <td class="px-4 py-2 text-center">Created At</td>
                        <td class="px-4 py-2 text-center">Action</td>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b-[1px] border-solid border-gray-200">
                        <td class="px-4 py-2 text-center">1</td>
                        <td class="px-4 py-2 text-center">/start</td>
                        <td class="px-4 py-2 text-center">content_start</td>
                        <td class="px-4 py-2 text-center">
                            2024/03/15 10:05:11
                        </td>
                        <td class="px-4 py-2 text-center">
                            <button
                                class="rounded-md border-[1px] border-solid border-gray-500 px-3 py-1 text-gray-500"
                            >
                                update
                            </button>
                            <button
                                class="rounded-md border-[1px] border-solid border-gray-500 px-3 py-1 text-gray-500"
                            >
                                delete
                            </button>
                        </td>
                    </tr>
                    <tr class="border-b-[1px] border-solid border-gray-200">
                        <td class="px-4 py-2 text-center">1</td>
                        <td class="px-4 py-2 text-center">/start</td>
                        <td class="px-4 py-2 text-center">content_start</td>
                        <td class="px-4 py-2 text-center">
                            2024/03/15 10:05:11
                        </td>
                        <td class="px-4 py-2 text-center">
                            <button
                                class="rounded-md border-[1px] border-solid border-gray-500 px-3 py-1 text-gray-500"
                            >
                                update
                            </button>
                            <button
                                class="rounded-md border-[1px] border-solid border-gray-500 px-3 py-1 text-gray-500"
                            >
                                delete
                            </button>
                        </td>
                    </tr>
                    <tr class="border-b-[1px] border-solid border-gray-200">
                        <td class="px-4 py-2 text-center">1</td>
                        <td class="px-4 py-2 text-center">/start</td>
                        <td class="px-4 py-2 text-center">content_start</td>
                        <td class="px-4 py-2 text-center">
                            2024/03/15 10:05:11
                        </td>
                        <td class="px-4 py-2 text-center">
                            <button
                                class="rounded-md border-[1px] border-solid border-gray-500 px-3 py-1 text-gray-500"
                            >
                                update
                            </button>
                            <button
                                class="rounded-md border-[1px] border-solid border-gray-500 px-3 py-1 text-gray-500"
                            >
                                delete
                            </button>
                        </td>
                    </tr>
                    <tr class="border-b-[1px] border-solid border-gray-200">
                        <td class="px-4 py-2 text-center">1</td>
                        <td class="px-4 py-2 text-center">/start</td>
                        <td class="px-4 py-2 text-center">content_start</td>
                        <td class="px-4 py-2 text-center">
                            2024/03/15 10:05:11
                        </td>
                        <td class="px-4 py-2 text-center">
                            <button
                                class="rounded-md border-[1px] border-solid border-gray-500 px-3 py-1 text-gray-500"
                            >
                                update
                            </button>
                            <button
                                class="rounded-md border-[1px] border-solid border-gray-500 px-3 py-1 text-gray-500"
                            >
                                delete
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 text-center">1</td>
                        <td class="px-4 py-2 text-center">/start</td>
                        <td class="px-4 py-2 text-center">content_start</td>
                        <td class="px-4 py-2 text-center">
                            2024/03/15 10:05:11
                        </td>
                        <td class="px-4 py-2 text-center">
                            <button
                                class="rounded-md border-[1px] border-solid border-gray-500 px-3 py-1 text-gray-500"
                            >
                                update
                            </button>
                            <button
                                class="rounded-md border-[1px] border-solid border-gray-500 px-3 py-1 text-gray-500"
                            >
                                delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal for creating or updating schedule -->
    <div class="fixed inset-0 z-10 hidden overflow-y-auto" id="scheduleModal">
        <div class="flex min-h-screen items-center justify-center">
            <div
                class="w-full transform overflow-hidden rounded-lg bg-white shadow-xl transition-all sm:max-w-lg"
            >
                <div
                    class="bg-gray-200 px-3 py-1 sm:flex sm:flex-row-reverse sm:px-6"
                >
                    <button
                        type="button"
                        class="ml-4 text-gray-500 hover:text-gray-700 focus:text-gray-700 focus:outline-none"
                        aria-label="Close"
                        onclick="document.getElementById('scheduleModal').classList.add('hidden')"
                    >
                        <span aria-hidden="true" class="text-[20px]">
                            &times;
                        </span>
                    </button>
                </div>
                <div class="bg-white px-4 py-5 sm:p-6">
                    <form id="scheduleForm">
                        <input type="hidden" id="scheduleType" />
                        <input type="hidden" id="scheduleId" />
                        <input type="hidden" id="scheduleBotId" />
                        <div class="mb-4">
                            <label
                                for="scheduleStatus"
                                class="text-sm font-medium text-gray-700"
                            >
                                Status
                            </label>
                            <select
                                id="scheduleStatus"
                                name="status"
                                required
                                class="w-full rounded border-[1px] border-solid border-gray-300 bg-gray-100 px-2 py-1 outline-none focus:border-white focus:ring-1 focus:ring-blue-300"
                            >
                                <option value="on">On</option>
                                <option value="off">Off</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label
                                for="scheduleTime"
                                class="text-sm font-medium text-gray-700"
                            >
                                Delay Time (minutes)
                            </label>
                            <input
                                type="number"
                                id="scheduleTime"
                                name="delay_time"
                                required
                                class="w-full rounded border-[1px] border-solid border-gray-300 bg-gray-100 px-2 py-1 outline-none focus:border-white focus:ring-1 focus:ring-blue-300"
                            />
                        </div>
                        <button
                            type="submit"
                            class="rounded bg-blue-500 px-4 py-2 text-white"
                        >
                            Save
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
            const botId = window.location.pathname.split('/').pop();

            window.getDetailBot = async () => {
                try {
                    const response = await fetchClient(
                        `/api/admin/bot/${botId}`,
                        {
                            method: 'GET',
                        },
                    );
                    console.log(response);
                    const data = response.data;

                    // Hiển thị chi tiết bot lên trang
                    $('#botDetails').html(`
                        <div class="mb-3 border-[1px] rounded border-solid border-gray-300 p-3">
                            <p class="block text-sm font-medium text-gray-700"><strong>ID:</strong> ${data.id}</p>
                            <p class="block text-sm font-medium text-gray-700"><strong>Token:</strong> ${data.token}</p>
                            <p class="block text-sm font-medium text-gray-700"><strong>Name:</strong> ${data.name}</p>
                            <p class="block text-sm font-medium text-gray-700"><strong>Status:</strong> ${data.status === '1' ? 'Active' : 'Inactive'}</p>
                            <p class="block text-sm font-medium text-gray-700"><strong>Expire:</strong> ${data.expired_at ?? '--'}</p>
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
                                        class="self-end px-4 py-1 border font-medium rounded text-white bg-gray-500 border-gray-500 hover:bg-gray-50 hover:text-gray-600 hover:border-gray-600 transform duration-200"
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
                                        class="self-end px-4 py-1 border font-medium rounded text-white bg-gray-500 border-gray-500 hover:bg-gray-50 hover:text-gray-600 hover:border-gray-600 transform duration-200"
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
                                        class="self-end px-4 py-1 border font-medium rounded text-white bg-gray-500 border-gray-500 hover:bg-gray-50 hover:text-gray-600 hover:border-gray-600 transform duration-200"
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
            };
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

            const apiUrl =
                type === 'config'
                    ? `/api/admin/schedule`
                    : type === 'group_config'
                      ? `/api/admin/schedule-group`
                      : `/api/admin/schedule-delete`;
            const fullApiUrl = scheduleId ? `${apiUrl}/${scheduleId}` : apiUrl;

            try {
                const response = await fetchClient(fullApiUrl, {
                    method: 'POST',
                    body: formData,
                });
                $('#scheduleModal').addClass('hidden');
                // window.location.reload();
                await getDetailBot();
            } catch (e) {
                console.log(e);
            }
        });
    </script>
@endpush
