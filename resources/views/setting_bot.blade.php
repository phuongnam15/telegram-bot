@extends("layouts.main")

@section("title", "Setting Bot Page")

@section("content")
<div class="container mt-5">
    <div id="botDetails" class="space-y-1 bg-white font-sans mb-5">
        <!-- Bot details will be populated here -->
    </div>
    <input type="text" id="botToken" class="hidden">
    <input type="text" id="botStatus" class="hidden">
    <div class="mb-5 border-b-[1px] border-solid border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500">
            <li class="me-2">
                <a href="#" onclick="showTab('command'); return false;" class="inline-flex items-center justify-center px-3 py-[5px] border-b-2 border-transparent rounded-t-lg hover:border-gray-400 group" id="tab-command">
                    Command
                </a>
            </li>
            <li class="me-2">
                <a href="#" onclick="showTab('testTab'); return false;" class="inline-flex items-center justify-center px-3 py-[5px] border-b-2 border-transparent rounded-t-lg hover:border-gray-400 group" id="tab-testTab">
                    Test tab
                </a>
            </li>
        </ul>
    </div>

    <!-- command tab list -->
    <div id="command" class="hidden">
        <div id="botCommands" class="space-y-2 rounded bg-white">
            <table class="w-full shadow">
                <thead class="border-b-[1px] border-solid border-gray-300">
                    <tr class="text-sm font-medium text-gray-600">
                        <td class="px-4 py-2 text-center">Command</td>
                        <td class="px-4 py-2 text-center">Content Name</td>
                        <td class="px-4 py-2 text-center">Created At</td>
                        <td class="px-4 py-2 text-center">Action</td>
                    </tr>
                </thead>
                <tbody id="botCommandsBody"></tbody>
            </table>
            <button class="transform mb-1 rounded border border-gray-500 bg-gray-500 w-full py-[5px] text-sm font-popi text-white duration-200 hover:border-gray-500 hover:bg-gray-50 hover:text-gray-500" data-toggle="modal" data-target="#newCommandModal">
                New command
            </button>
        </div>

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
                                        <label for="commandInput" class="text-gray-700 font-mono text-sm">
                                            Command
                                        </label>
                                        <input type="text" class="w-full rounded border-[1px] border-solid border-gray-300 bg-gray-100 px-2 py-1 outline-none focus:border-white focus:ring-1 focus:ring-blue-300" id="commandInput" name="commandInput" required placeholder="ex: /start" />
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
    </div>

    <!-- content tab list -->
    <div id="content" class="hidden">
        <div class="relative container">
            <div class="flex gap-2">
                <div id="contentData" class="w-full overflow-x-auto shadow"></div>
                <div class="text-left">
                    <div>
                        <button type="button" class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50" id="menu-button">
                            <svg class="h-3 w-3 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    <div id="menu-content" class="max-h-0 overflow-auto absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-black focus:outline-none">
                        <div class="p-2">
                            <select id="kindFilter" class="hover:bg-gray-50 cursor-pointer form-control w-full px-4 py-2 outline-none text-sm text-gray-700 border-b border-gray-200">
                                <option value="" class="hidden">Kind</option>
                                <option value="Giới thiệu">Introduce</option>
                                <option value="Click button">Click Button</option>
                                <option value="Start">Start</option>
                                <option value="Other">Other</option>
                            </select>
                            <select id="typeFilter" class="hover:bg-gray-50 cursor-pointer form-control w-full px-4 py-2 outline-none text-sm text-gray-700">
                                <option value="" class="hidden">Type</option>
                                <option value="text">Text</option>
                                <option value="photo">Image</option>
                                <option value="video">Video</option>
                            </select>
                            <button class="w-full px-4 py-2 outline-none text-sm text-gray-700 bg-gray-200" id="createNew">
                                New content
                            </button>
                        </div>
                    </div>
                </div>
                <script>
                    $('#menu-button').on('click', function() {
                        $('#menu-content').toggleClass('max-h-0');
                    });

                    $(document).on('click', function(event) {
                        if (!$(event.target).closest('#menu-content, #menu-button').length) {
                            $('#menu-content').addClass('max-h-0');
                        }
                    });
                </script>
            </div>
            <div id="pagination" class="mt-3"></div>
        </div>

        <!-- Modal -->
        <div class="fixed inset-0 z-10 hidden overflow-y-auto" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
            <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <!-- This element is to trick the browser into centering the modal contents. -->
                <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">
                    &#8203;
                </span>
                <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left">
                                <h5 class="text-lg font-medium leading-6 text-gray-900" id="userModalLabel">
                                    List Users
                                </h5>
                                <div class="mt-2">
                                    <!-- <form id="sendUsersForm"> -->
                                    <div class="mb-4">
                                        <input type="hidden" name="content_id" id="contentId" />
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" id="selectAllUsers" class="mr-2" />
                                            Chọn tất cả
                                        </label>
                                    </div>
                                    <div class="mb-4">
                                        <input type="text" id="userSearch" class="form-control w-full rounded border border-gray-300 p-2" placeholder="Tìm kiếm Telegram ID" />
                                    </div>
                                    <div id="userList" class="text-sm"></div>
                                    <!-- </form> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 bg-gray-50 px-4 py-3">
                        <button type="button" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" data-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="rounded bg-blue-500 px-4 py-2 text-white" id="sendContent">
                            Send
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- group tab list -->
    <div id="group" class="hidden">
        <div class="container mt-5">
            <table class="min-w-full shadow rounded" id="groupTable">
                <thead class="text-sm text-gray-600 font-mono border-b-[1px] border-solid border-gray-300">
                    <tr class="w-full">
                        <th class="px-4 py-2">Infomation</th>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Created At</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 text-center">
                    <!-- Rows will be added by jQuery -->
                    <tr class="bg-gray-100">
                        <td class="flex items-center gap-2 flex-1 hover:bg-[#ced3dc] p-4">
                            <img class="size-12 rounded-full" src="https://th.bing.com/th/id/R.ac76a296e880e51f549d9d25865a2e0a?rik=K%2fWgSkaj2gB79Q&riu=http%3a%2f%2fimages4.fanpop.com%2fimage%2fphotos%2f22500000%2fkakashi-sensei-kakashi-22519264-1024-768.jpg&ehk=%2bv5GD7jfWuVq051pFdp%2f4Rs8Rxgnx0VSjNPzcLuXvC4%3d&risl=&pid=ImgRaw&r=0" alt="">
                            <div class="flex flex-col leading-5">
                                <p class="text-left">
                                    <span class="font-medium">test</span>
                                </p>
                                <p class="text-gray-500 flex items-center gap-1">
                                    <span class="text-sm">@uzimaki</span>
                                    <i class="fa-brands fa-telegram"></i>
                                </p>
                            </div>
                        </td>
                        <td class="p-4">8375687358</td>
                        <td class="p-4">24/10/37 34:23:12</td>
                        <td class="p-4">24/10/37 34:23:12</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="testTab" class="hidden">
        <p>Test tab</p>
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
                    <label for="monthQty" class="text-sm text-gray-700">
                        Select num of months
                    </label>
                    <select id="monthQty" class="w-full text-sm rounded border-[1px] border-solid border-gray-300 bg-gray-100 px-2 py-1 outline-none focus:border-white focus:ring-1 focus:ring-blue-300">
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

        window.commandScript = async () => {
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
            const listContentName = async () => {
                try {
                    const response = await fetchClient(`/api/admin/list?bot_id=${botId}`, {
                        method: "GET",
                    });

                    response.data.forEach(element => {
                        $("#contentList").append(`<option value="${element.id}">${element.id} - ${element.name}</option>`);
                    });
                } catch (error) {
                    console.log(error)
                }
            }
            window.deleteCommand = async (commandId) => {
                try {
                    if (!confirm("Are you sure to delete this command?")) {
                        return;
                    }
                    const response = await fetchClient(`/api/admin/command/${commandId}`, {
                        method: "DELETE",
                    });

                    await listCommand();
                } catch (error) {
                    console.log(error)
                }
            }

            await listCommand();
            await listContentName();

            $('#commandForm').on('submit', async (e) => {
                e.preventDefault();

                const formData = new FormData();
                formData.append('bot_id', botId);
                formData.append('command', $('#commandInput').val());
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
        }

        window.contentScript = async () => {
            function filterTable() {
                var selectedType = $('#typeFilter').val();
                var selectedKind = $('#kindFilter').val();

                $('#listContentBody tr').each(function() {
                    var rowType = $(this).find('td:nth-child(3)').text(); // Giả sử cột 'Hình thức' là cột thứ 4
                    var rowKind = $(this).find('td:nth-child(4)').text(); // Giả sử cột 'Loại' là cột thứ 5

                    var typeMatch =
                        selectedType === '' || rowType === selectedType;
                    var kindMatch =
                        selectedKind === '' || rowKind === selectedKind;

                    if (typeMatch && kindMatch) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }
            $('#typeFilter, #kindFilter').change(filterTable);

            //get list content
            const fetchContent = async () => {
                try {
                    const response = await fetchClient(`/api/admin/list?bot_id=${botId}`, {
                        method: "GET",
                    });
                    if (response) {
                        renderContentList(response);
                    }
                } catch (error) {
                    console.error(error);
                }
            }

            await fetchContent();

            function renderContentList(data) {
                let contentHTML = `
                            <table class="min-w-full bg-white overflow-hidden">
                                <thead class="text-gray-600 border-b-[1px] border-solid border-gray-300 font-mono text-[14px]">
                                    <tr>
                                        <th class="py-2 px-4">Name</th>
                                        <th class="py-2 px-4">Content</th>
                                        <th class="py-2 px-4">Type</th>
                                        <th class="py-2 px-4">Kind</th>
                                        <th class="py-2 px-4">Media</th>
                                        <th class="py-2 px-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="listContentBody">`;

                // if (data.data.length === 0) {
                //     document.getElementById('thinkOutOfTheBox').classList.remove('hidden');
                // }

                data.data.forEach((content, index) => {
                    let mediaHTML = '';
                    let buttonSetDefault = '';

                    if (content.type === 'photo') {
                        mediaHTML = `<img src="${content.media}" class="max-w-[200px] max-h-[200px] mx-auto rounded">`;
                    } else if (content.type === 'video') {
                        mediaHTML = `<video controls class="max-w-[200px] max-h-[200px] mx-auto rounded">
                                                <source src="${content.media}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>`;
                    }

                    if (
                        (content.kind === 'introduce' ||
                            content.kind === 'start') &&
                        !content.is_default
                    ) {
                        buttonSetDefault = `<button class="bg-blue-500 text-white text-xs py-1 px-2 rounded" onclick="setDefault(${content.id})">Default</button>`;
                    }

                    let typeBadge = '';
                    if (content.type === 'photo') {
                        typeBadge =
                            '<span class="inline-block px-2 py-1 text-xs text-blue-800 bg-blue-100 rounded-full">photo</span>';
                    } else if (content.type === 'video') {
                        typeBadge =
                            '<span class="inline-block px-2 py-1 text-xs text-yellow-800 bg-yellow-100 rounded-full">video</span>';
                    } else if (content.type === 'text') {
                        typeBadge =
                            '<span class="inline-block px-2 py-1 text-xs text-gray-800 bg-gray-100 rounded-full">text</span>';
                    }

                    let kindBadge = '';
                    if (content.kind === 'introduce') {
                        kindBadge =
                            '<span class="inline-block px-2 py-1 text-xs text-green-800 bg-green-100 rounded-full">Giới thiệu</span>';
                    } else if (content.kind === 'button') {
                        kindBadge =
                            '<span class="inline-block px-2 py-1 text-xs text-blue-800 bg-blue-100 rounded-full">Click button</span>';
                    } else if (content.kind === 'start') {
                        kindBadge =
                            '<span class="inline-block px-2 py-1 text-xs text-purple-800 bg-purple-100 rounded-full">Start</span>';
                    } else {
                        kindBadge =
                            '<span class="inline-block px-2 py-1 text-xs text-yellow-800 bg-yellow-100 rounded-full">Other</span>';
                    }

                    contentHTML += `
                                <tr class="${index !== data.data.length - 1 ? 'border-b-[1px] border-solid border-gray-100' : ''}">
                                    <td class="px-4 py-2 text-center text-gray-600 text-sm">${content.name + (content.is_default ? ' <strong>(mặc định)</strong>' : '')}</td>
                                    <td class="px-4 py-2 max-w-[600px] min-w-[400px] break-words font-sans text-gray-600 text-sm">${content.content}</td>
                                    <td class="px-4 py-2 text-center">${typeBadge}</td>
                                    <td class="px-4 py-2 text-center">${kindBadge}</td>
                                    <td class="px-4 py-2">${mediaHTML}</td>
                                    <td class="px-4 py-2 text-center space-y-1">
                                        <button class="bg-blue-500 text-white py-1 px-2 rounded text-xs font-medium" onclick="showUsers(${content.id})">Gửi</button>
                                        <button class="bg-red-500 text-white py-1 px-2 rounded text-xs font-medium" onclick="deleteConfig(${content.id})">Xoá</button>
                                        <button class="bg-yellow-500 text-white py-1 px-2 rounded text-xs font-medium" onclick="updateConfig(${content.id})">Sửa</button>
                                        ${buttonSetDefault}
                                    </td>
                                </tr>`;
                });
                contentHTML += '</tbody></table>';
                document.getElementById('contentData').innerHTML =
                    contentHTML;

                // Render pagination
                let paginationHTML = '';

                if (data.last_page > 1) {
                    for (let i = 1; i <= data.last_page; i++) {
                        paginationHTML += `<button class="bg-gray-200 text-gray-500 text-sm py-1 px-3 rounded mr-1" onclick="fetchPage(${i})">${i}</button>`;
                    }
                    document.getElementById('pagination').innerHTML = paginationHTML;
                }

            }

            // Fetch specific page
            window.fetchPage = async (page) => {
                try {
                    const response = await fetchClient(
                        `/api/admin/list?page=${page}`,
                    );
                    if (response) {
                        renderContentList(response);
                    }
                } catch (error) {
                    console.error(error);
                }
            };


            // // LIST USER & GROUP
            window.showUsers = async (contentId) => {
                document.getElementById('contentId').value = contentId;
                let userListHTML = '';
                //USER
                try {
                    const response = await fetchClient(`/api/admin/users?bot_id=${botId}`);

                    response.data.forEach((user) => {
                        userListHTML += `<label><input type="checkbox" class="user-checkbox" name="user_ids[]" value="${user.telegram_id}"> ${user.name} - ${user.telegram_id}</label><br>`;
                    });

                    document.getElementById('userList').innerHTML =
                        userListHTML;

                    $('#userModal').modal('show');

                    attachSelectAllHandler();
                } catch (error) {
                    console.log(error);
                }

                //GROUP
                try {
                    const response = await fetchClient(`/api/admin/group?bot_id=${botId}`);

                    response.data.forEach((user) => {
                        userListHTML += `<label><input type="checkbox" class="user-checkbox" name="user_ids[]" value="${user.telegram_id}"> ${user.telegram_id} - ${user.name}</label><br>`;
                    });

                    document.getElementById('userList').innerHTML = userListHTML;

                    $('#userModal').modal('show');

                    attachSelectAllHandler();
                } catch (error) {
                    console.log(error);
                }
            };

            // // Add search functionality
            document
                .getElementById('userSearch')
                .addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    document
                        .querySelectorAll('#userList label')
                        .forEach((label) => {
                            const telegramId = label.textContent.toLowerCase();
                            if (telegramId.includes(searchTerm)) {
                                label.style.display = 'inline-block';
                            } else {
                                label.style.display = 'none';
                            }
                        });
                });

            // // CHECK ALL USER
            function attachSelectAllHandler() {
                document
                    .getElementById('selectAllUsers')
                    .addEventListener('click', function() {
                        const isChecked = this.checked;
                        const checkboxes =
                            document.querySelectorAll('.user-checkbox');
                        checkboxes.forEach((checkbox) => {
                            checkbox.checked = isChecked;
                        });
                    });
            }

            window.deleteConfig = async (contentId) => {
                const confirm = window.confirm(
                    'Are you sure to delete this config?',
                );
                if (confirm) {
                    try {
                        const response = await fetchClient(
                            `/api/admin/delete/${contentId}`, {
                                method: 'DELETE',
                            },
                        );

                        await fetchContent();
                    } catch (error) {
                        console.error('Error:', error);
                    }
                }
            };

            window.updateConfig = function(contentId) {
                location.href = `/update/${contentId}`;
            };

            //Send
            document
                .getElementById('sendContent')
                .addEventListener('click', async () => {
                    let botStatus = document.getElementById('botStatus').value;

                    if (botStatus !== '1') {
                        alert('BOT IS INACTIVE');
                        return;
                    }

                    let botToken = document.getElementById('botToken').value;
                    let contentId = document.getElementById('contentId').value;
                    let telegramIds = Array.from(
                        document.querySelectorAll(
                            '#userList input[type="checkbox"]:checked',
                        ),
                    ).map((cb) => cb.value);

                    if (telegramIds.length === 0) {
                        showNotification('Please select at least one user or group', 'warning');
                        return;
                    }

                    const formData = new FormData();
                    formData.append('botToken', botToken);
                    formData.append('configId', contentId);
                    telegramIds.forEach((id) =>
                        formData.append('telegramIds[]', id),
                    );

                    try {
                        const response = await fetchClient(`/api/admin/send`, {
                            method: 'POST',
                            body: formData,
                        });

                        $('#userModal').modal('hide');
                        showNotification(response.message, 'success');
                    } catch (error) {
                        console.error('Error:', error);
                    }
                });

            // //SET DEFAULT
            window.setDefault = async (contentId) => {
                try {
                    const response = await fetchClient(
                        `/api/admin/set-default/${contentId}`, {
                            method: 'POST',
                        },
                    );

                    console.log('Success:', response);
                    // location.reload();
                } catch (error) {
                    console.error('Error:', error);
                }
            };

            $('#createNew').click(() => {
                location.href = `/config/${botId}`;
            });
            $('#manageGroup').click(() => {
                location.href = '/group';
            });
            $('#manageBot').click(() => {
                location.href = '/';
            });
            $('#managePhone').click(() => {
                location.href = '/phone';
            });
            $('#managePass').click(() => {
                location.href = '/password';
            });
        }

        window.groupScript = async () => {
            const listGroup = async () => {
                $('#groupTable tbody').html('');

                try {
                    const response = await fetchClient(`/api/admin/group?bot_id=${botId}`);

                    response.data.forEach((group) => {
                        $('#groupTable tbody').append(`
                            <tr>
                                <td class="flex items-center justify-center gap-2 py-4">
                                    <img class="size-12 rounded-full" src="${group.avatar}" alt="">
                                    <div class="flex flex-col leading-5">
                                        <p class="text-left">
                                            <span class="font-medium">${group?.title}</span>
                                        </p>
                                        <p class="text-gray-500 flex items-center gap-1">
                                            <span class="text-sm">@${group.name}</span>
                                            <i class="fa-brands fa-telegram"></i>
                                        </p>
                                    </div>
                                </td>
                                <td class="py-4">8375687358</td>
                                <td class="py-4">${formatDate(group.created_at)}</td>
                                <td class="py-4"></td>
                            </tr>
                        `);
                    });
                } catch (error) {
                    console.log(error);
                }
            }
            await listGroup();
        }

        window.testScript = async () => {
            console.log('Test tab');
        }
        
        window.showTab = async (tabId) => {
            const tabIds = ['command', 'content', 'group', 'testTab'];
            tabIds.forEach(function(id) {
                document.getElementById(id).classList.add('hidden');
            });
            document.getElementById(tabId).classList.remove('hidden');
            if (tabId === 'content') {
                await contentScript();
            } else if (tabId === 'command') {
                await commandScript();
            } else if (tabId === 'group') {
                await groupScript();
            }else if (tabId === 'testTab') {
                await testScript();
            }

            // Đổi class của tablist để thể hiện tab được chọn
            document.querySelectorAll('a[id^="tab-"]').forEach(function(tabLink) {
                tabLink.classList.remove('bg-gray-500', 'text-white');
                tabLink.classList.add('hover:border-gray-400');
            });
            document.getElementById('tab-' + tabId).classList.add('bg-gray-500', 'text-white');
            document.getElementById('tab-' + tabId).classList.remove('hover:border-gray-400');
        }
        await showTab('command');

        window.getDetailBot = async () => {
            try {
                const response = await fetchClient(
                    `/api/admin/bot/${botId}`, {
                        method: 'GET',
                    },
                );

                const data = response.data;
                document.getElementById('botToken').value = data.token;
                document.getElementById('botStatus').value = data.status;

                // Hiển thị chi tiết bot lên trang
                $('#botDetails').html(`
                        <div class="mb-3 border-[1px] rounded border-solid border-gray-300 px-3 pt-3 pb-1 relative">
                            <img src="${data.avatar ?? "{{ asset('assets/images/bot.png') }}"}" 
                                class="size-24 rounded-full absolute top-[50%] -translate-y-1/2 -translate-x-1/2 right-0" />
                            <p class="block text-sm font-medium text-gray-600"><strong>Token:</strong> ${data.token}</p>
                            <p class="block text-sm font-medium text-gray-600"><strong>Username:</strong> @${data.username}</p>
                            <p class="block text-sm font-medium text-gray-600"><strong>Firstname:</strong> ${data.firstname}</p>
                            <p class="block text-sm font-medium text-gray-600"><strong>Status:</strong> ${data.status === '1' ? 'Active' : 'Inactive'}</p>
                            <p class="block text-sm font-medium text-gray-600"><strong>Expire:</strong> ${data.expired_at ?? '--'}</p>
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
                                        <label for="delay_time" class="block text-xs font-medium text-gray-700">Độ trễ xoá tin (phút)</label>
                                        <input
                                            type="number"
                                            id="delay_time"
                                            name="delay_time"
                                            class="text-sm mt-1 outline-none block w-full border-gray-300 border-[1px] rounded py-1 px-2 focus:border-white focus:ring-green-300 focus:ring"
                                            value="${data.schedule_delete_message.delay_time}"
                                        />
                                    </div>
                                    <div class="flex-1 flex flex-col">
                                        <label for="status" class="text-xs font-medium text-gray-700">Trạng thái</label>
                                        <select
                                            id="status"
                                            name="status"
                                            class="text-sm mt-1 flex-1 outline-none block w-full border-gray-300 border-[1px] rounded py-1 px-2 focus:border-white focus:ring-green-300 focus:ring"
                                        >
                                            <option value="on" ${data.schedule_delete_message.status === 'on' ? 'selected' : ''}>On</option>
                                            <option value="off" ${data.schedule_delete_message.status === 'off' ? 'selected' : ''}>Off</option>
                                        </select>
                                    </div>
                                    <button
                                        type="button"
                                        class="text-sm self-end px-2 py-2 border font-medium rounded-full text-gray-300 hover:bg-gray-50 hover:text-gray-500 hover:border-gray-500 transform duration-200"
                                        onclick="openScheduleModal('delete_message', ${botId}, ${JSON.stringify(data.schedule_delete_message).replace(/"/g, '&quot;')})"
                                    >
                                        <i class="fa-solid fa-wrench"></i>
                                    </button>
                                </form>
                            </div>
                        `);
                } else {
                    $('#botDetails').append(`
                        <div class="bg-[#f8f9fa] border-[1px] border-solid border-[#e5e7eb] rounded py-1 pl-7 space-x-5 text-gray-700 flex items-center hover:bg-[#ced3dc] cursor-pointer" onclick="openScheduleModal('delete_message', ${botId})">
                            <i class="fa-solid fa-plus text-[12px]"></i>
                            <span class="text-[12px] font-medium">Create schedule auto delete message</span>
                        </div>
                    `);
                }

                if (data.schedule_config) {
                    $('#botDetails').append(`
                             <div class="">
                                <form id="scheduleConfigForm" class="flex space-x-4 mb-0">
                                    <div class="flex-1">
                                        <label for="config_delay_time" class="block text-xs font-medium text-gray-700">Lịch chạy user (phút)</label>
                                        <input
                                            type="number"
                                            id="config_delay_time"
                                            name="delay_time"
                                            class="text-sm mt-1 outline-none block w-full border-gray-300 border-[1px] rounded py-1 px-2 shadow-sm focus:border-white focus:ring-green-300 focus:ring"
                                            value="${data.schedule_config.time}"
                                        />
                                    </div>
                                    <div class="flex-1 flex flex-col">
                                        <label for="config_status" class="text-xs font-medium text-gray-700">Trạng thái</label>
                                        <select
                                            id="config_status"
                                            name="status"
                                            class="text-sm mt-1 flex-1 outline-none block w-full border-gray-300 border-[1px] rounded py-1 px-2 shadow-sm focus:border-white focus:ring-green-300 focus:ring"
                                        >
                                            <option value="on" ${data.schedule_config.status === 'on' ? 'selected' : ''}>On</option>
                                            <option value="off" ${data.schedule_config.status === 'off' ? 'selected' : ''}>Off</option>
                                        </select>
                                    </div>
                                    <div class="flex-1">
                                        <label for="config_lastime" class="text-xs font-medium text-gray-700">Lần cuối chạy</label>
                                        <input
                                            type="text"
                                            id="config_lastime"
                                            name="lastime"
                                            class="text-sm outline-none mt-1 block w-full border-gray-300 border-[1px] rounded py-1 px-2 shadow-sm bg-gray-100 text-gray-600"
                                            value="${data.schedule_config.lastime}"
                                            readonly
                                        />
                                    </div>
                                    <button
                                        type="button"
                                        class="text-sm self-end px-2 py-2 border font-medium rounded-full text-gray-300 hover:bg-gray-50 hover:text-gray-500 hover:border-gray-500 transform duration-200"
                                        onclick="openScheduleModal('config', ${botId}, ${JSON.stringify(data.schedule_config).replace(/"/g, '&quot;')})"
                                    >
                                        <i class="fa-solid fa-wrench"></i>
                                    </button>
                                </form>
                            </div>
                        `);
                } else {
                    $('#botDetails').append(`
                        <div class="bg-[#f8f9fa] border-[1px] border-solid border-[#e5e7eb] rounded py-1 pl-7 space-x-5 text-gray-700 flex items-center hover:bg-[#ced3dc] cursor-pointer" onclick="openScheduleModal('config', ${botId})">
                            <i class="fa-solid fa-plus text-[12px]"></i>
                            <span class="text-[12px] font-medium">Create schedule auto for user</span>
                        </div>
                    `);
                }

                if (data.schedule_group_config) {
                    $('#botDetails').append(`
                            <div>
                                <form id="scheduleGroupConfigForm" class="flex space-x-4 mb-0">
                                    <div class="flex-1">
                                        <label for="group_config_delay_time" class="block text-xs font-medium text-gray-700">Lịch chạy group (phút)</label>
                                        <input
                                            type="number"
                                            id="group_config_delay_time"
                                            name="delay_time"
                                            class="text-sm mt-1 outline-none block w-full border-gray-300 border-[1px] rounded py-1 px-2 shadow-sm focus:border-white focus:ring-green-300 focus:ring"
                                            value="${data.schedule_group_config.time}"
                                        />
                                    </div>
                                    <div class="flex-1 flex flex-col">
                                        <label for="group_config_status" class="text-xs font-medium text-gray-700">Trạng thái</label>
                                        <select
                                            id="group_config_status"
                                            name="status"
                                            class="text-sm mt-1 flex-1 outline-none block w-full border-gray-300 border-[1px] rounded py-1 px-2 shadow-sm focus:border-white focus:ring-green-300 focus:ring"
                                        >
                                            <option value="on" ${data.schedule_group_config.status === 'on' ? 'selected' : ''}>On</option>
                                            <option value="off" ${data.schedule_group_config.status === 'off' ? 'selected' : ''}>Off</option>
                                        </select>
                                    </div>
                                    <div class="flex-1">
                                        <label for="group_config_lastime" class="text-xs font-medium text-gray-700">Lần cuối chạy</label>
                                        <input
                                            type="text"
                                            id="group_config_lastime"
                                            name="lastime"
                                            class="text-sm outline-none mt-1 block w-full border-gray-300 border-[1px] rounded py-1 px-2 shadow-sm bg-gray-100 text-gray-600"
                                            value="${data.schedule_group_config.lastime}"
                                            readonly
                                        />
                                    </div>
                                    <button
                                        type="button"
                                        class="text-sm self-end px-2 py-2 border font-medium rounded-full text-gray-300 hover:bg-gray-50 hover:text-gray-500 hover:border-gray-500 transform duration-200"
                                        onclick="openScheduleModal('group_config', ${botId}, ${JSON.stringify(data.schedule_group_config).replace(/"/g, '&quot;')})"
                                    >
                                        <i class="fa-solid fa-wrench"></i>
                                    </button>
                                </form>
                            </div>
                        `);
                } else {
                    $('#botDetails').append(`
                        <div class="bg-[#f8f9fa] border-[1px] border-solid border-[#e5e7eb] rounded py-1 pl-7 space-x-5 text-gray-700 flex items-center hover:bg-[#ced3dc] cursor-pointer" onclick="openScheduleModal('group_config', ${botId})">
                            <i class="fa-solid fa-plus text-[12px]"></i>
                            <span class="text-[12px] font-medium">Create schedule auto for group</span>
                        </div>
                    `);
                }
            } catch (e) {
                console.log(e);
            }
        };

        await getDetailBot();

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
                    await getDetailBot();
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
                        window.location.href = '/';
                    } catch (e) {
                        console.log(e);
                    }
                }
            };
        }
    });
</script>
@endpush