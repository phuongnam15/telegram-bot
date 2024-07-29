@extends("layouts.main")

@section("title", "Home Page")

@section("content")
    <div class="relative mx-5 mt-5">
        <div class="absolute right-0 flex">
            <div class="flex w-48 filter">
                <select
                    id="typeFilter"
                    class="form-control w-full rounded border border-gray-300 px-2 outline-none"
                >
                    <option value="">-- Type --</option>
                    <option value="text">Text</option>
                    <option value="photo">Ảnh</option>
                    <option value="video">Video</option>
                </select>
            </div>
            <div class="ml-2 flex w-48 filter">
                <select
                    id="kindFilter"
                    class="form-control w-full rounded border border-gray-300 px-2 outline-none"
                >
                    <option value="">-- Kind --</option>
                    <option value="Giới thiệu">Giới thiệu</option>
                    <option value="Click button">Click Button</option>
                    <option value="Start">Start</option>
                    <option value="Other">Khác</option>
                </select>
            </div>
            <button
                class="ml-2 rounded border border-gray-900 bg-gray-900 p-1 px-4 text-white transition-all duration-200 hover:bg-gray-50 hover:text-gray-700"
                id="createNew"
            >
                Create
            </button>
        </div>
        <button
            class="rounded-lg border-2 border-solid border-gray-800 px-4 py-2 font-bold text-gray-800"
            id="cloneData"
        >
            Clone Data
        </button>
        <!-- <h2 class="text-2xl font-bold">Content List</h2> -->
        <div
            id="contentData"
            class="mt-3 w-full overflow-x-auto rounded-md shadow"
        ></div>
        <div id="pagination" class="mt-3"></div>
        <div class="w-full mt-[13%] text-center hidden" id="thinkOutOfTheBox">
            <img src="{{asset('assets/images/think-out-of-the-box.png')}}" alt="" class="w-[230px] mx-auto">
            <p class="mt-3 font-bold text-2xl text-gray-800">There are no posts to show.</p>
        </div>
    </div>

    <!-- Modal -->
    <div
        class="fixed inset-0 z-10 hidden overflow-y-auto"
        id="userModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="userModalLabel"
        aria-hidden="true"
    >
        <div
            class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0"
        >
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <!-- This element is to trick the browser into centering the modal contents. -->
            <span
                class="hidden sm:inline-block sm:h-screen sm:align-middle"
                aria-hidden="true"
            >
                &#8203;
            </span>
            <div
                class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle"
            >
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left">
                            <h5
                                class="text-lg font-medium leading-6 text-gray-900"
                                id="userModalLabel"
                            >
                                List Users
                            </h5>
                            <div class="mt-2">
                                <!-- <form id="sendUsersForm"> -->
                                <div class="mb-4">
                                    <input
                                        type="hidden"
                                        name="content_id"
                                        id="contentId"
                                    />
                                    <label class="inline-flex items-center">
                                        <input
                                            type="checkbox"
                                            id="selectAllUsers"
                                            class="mr-2"
                                        />
                                        Chọn tất cả
                                    </label>
                                </div>
                                <div class="mb-4">
                                    <input
                                        type="text"
                                        id="userSearch"
                                        class="form-control w-full rounded border border-gray-300 p-2"
                                        placeholder="Tìm kiếm Telegram ID"
                                    />
                                </div>
                                <div id="userList" class="text-sm"></div>
                                <!-- </form> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-2 bg-gray-50 px-4 py-3">
                    <button
                        type="button"
                        class="rounded-md border border-gray-300 bg-white px-4 py-2 text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        data-dismiss="modal"
                    >
                        Close
                    </button>
                    <button
                        type="submit"
                        class="rounded bg-blue-500 px-4 py-2 text-white"
                        id="nextToBots"
                    >
                        Next to select bot
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bot Selection Modal -->
    <div
        class="fixed inset-0 z-10 hidden overflow-y-auto"
        id="botModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="botModalLabel"
        aria-hidden="true"
    >
        <div
            class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0"
        >
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span
                class="hidden sm:inline-block sm:h-screen sm:align-middle"
                aria-hidden="true"
            >
                &#8203;
            </span>
            <div
                class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle"
            >
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left">
                            <h5
                                class="text-lg font-medium leading-6 text-gray-900"
                                id="botModalLabel"
                            >
                                Select Bot
                            </h5>
                            <div class="mt-2">
                                <form id="selectBotForm">
                                    <select
                                        id="botList"
                                        class="text-sm"
                                    ></select>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-2 bg-gray-50 px-4 py-3">
                    <button
                        type="button"
                        class="rounded-md border border-gray-300 bg-white px-4 py-2 text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        data-dismiss="modal"
                    >
                        Close
                    </button>
                    <button
                        type="submit"
                        class="rounded bg-blue-500 px-4 py-2 text-white"
                        id="sendContent"
                    >
                        Send
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Clone Modal -->
    <div
        class="fixed inset-0 z-10 hidden overflow-y-auto"
        id="cloneModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="cloneModalLabel"
        aria-hidden="true"
    >
        <div
            class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0"
        >
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <!-- This element is to trick the browser into centering the modal contents. -->
            <span
                class="hidden sm:inline-block sm:h-screen sm:align-middle"
                aria-hidden="true"
            >
                &#8203;
            </span>
            <div
                class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle"
            >
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mt-3 w-full text-center sm:mt-0 sm:text-left"
                        >
                            <div class="mt-2">
                                <form id="cloneForm">
                                    <div class="mb-4">
                                        <label
                                            for="domain"
                                            class="block text-gray-700"
                                        >
                                            Domain
                                        </label>
                                        <input
                                            type="text"
                                            class="w-full rounded border border-gray-300 p-2 outline-none"
                                            id="domain"
                                            name="domain"
                                            required
                                            placeholder="ex: https://telegram.daominhtu.com"
                                        />
                                    </div>
                                    <div class="mb-4">
                                        <label
                                            class="mb-2 block text-[14px] text-gray-700 underline"
                                        >
                                            Chọn loại dữ liệu để clone
                                        </label>
                                        <!-- <div>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" id="user" name="dataTypeToClone[]" value="user" class="mr-2">
                                            User
                                        </label>
                                    </div>
                                    <div>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" id="password" name="dataTypeToClone[]" value="password" class="mr-2">
                                            Password
                                        </label>
                                    </div>
                                    <div>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" id="phone" name="dataTypeToClone[]" value="phone" class="mr-2">
                                            Phone
                                        </label>
                                    </div> -->
                                        <div>
                                            <label
                                                class="inline-flex items-center text-sm"
                                            >
                                                <input
                                                    type="checkbox"
                                                    id="content"
                                                    name="dataTypeToClone[]"
                                                    value="content"
                                                    class="mr-2"
                                                />
                                                Content
                                            </label>
                                        </div>
                                        <!-- <div>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" id="schedule_user" name="dataTypeToClone[]" value="schedule_user" class="mr-2">
                                            Schedule User
                                        </label>
                                    </div>
                                    <div>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" id="schedule_group" name="dataTypeToClone[]" value="schedule_group" class="mr-2">
                                            Schedule Group
                                        </label>
                                    </div>
                                    <div>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" id="schedule_delete" name="dataTypeToClone[]" value="schedule_delete" class="mr-2">
                                            Schedule Delete
                                        </label>
                                    </div>
                                    <div>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" id="bot" name="dataTypeToClone[]" value="bot" class="mr-2">
                                            Bot
                                        </label>
                                    </div>
                                    <div>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" id="group" name="dataTypeToClone[]" value="group" class="mr-2">
                                            Group
                                        </label>
                                    </div> -->
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-row-reverse gap-1 bg-gray-50 px-4 py-3">
                    <button
                        type="button"
                        class="rounded bg-gray-700 px-4 py-[5px] text-white"
                        id="cloneButton"
                    >
                        Clone
                    </button>
                    <button
                        type="button"
                        class="rounded border border-gray-300 bg-white px-4 py-[5px] text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-1"
                        data-dismiss="modal"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("scripts")
    <script>
        $(document).ready(async () => {
            //FILTER TYPE or KIND
            {
                function filterTable() {
                    var selectedType = $('#typeFilter').val();
                    var selectedKind = $('#kindFilter').val();

                    $('tbody tr').each(function () {
                        var rowType = $(this).find('td:nth-child(4)').text(); // Giả sử cột 'Hình thức' là cột thứ 4
                        var rowKind = $(this).find('td:nth-child(5)').text(); // Giả sử cột 'Loại' là cột thứ 5

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
            }

            //get list content
            {
                try {
                    const response = await fetchClient('/api/admin/list');
                    if (response) {
                        renderContentList(response);
                    }
                } catch (error) {
                    console.error(error);
                }

                function renderContentList(data) {
                    let contentHTML = `
                            <table class="min-w-full bg-white rounded-md overflow-hidden">
                                <thead class="bg-gray-500 text-white">
                                    <tr>
                                        <th class="py-3 px-4 text-sm">ID</th>
                                        <th class="py-3 px-4 text-sm">Name</th>
                                        <th class="py-3 px-4 text-sm">Content</th>
                                        <th class="py-3 px-4 text-sm">Type</th>
                                        <th class="py-3 px-4 text-sm">Kind</th>
                                        <th class="py-3 px-4 text-sm">Media</th>
                                        <th class="py-3 px-4 text-sm">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                    if (data.data.length === 0) {
                        document.getElementById('thinkOutOfTheBox').classList.remove('hidden');
                    }

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
                            buttonSetDefault = `<button class="bg-blue-500 text-white py-1 px-2 rounded" onclick="setDefault(${content.id})">Mặc định</button>`;
                        }

                        let typeBadge = '';
                        if (content.type === 'photo') {
                            typeBadge =
                                '<span class="inline-block px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">photo</span>';
                        } else if (content.type === 'video') {
                            typeBadge =
                                '<span class="inline-block px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">video</span>';
                        } else if (content.type === 'text') {
                            typeBadge =
                                '<span class="inline-block px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded-full">text</span>';
                        }

                        let kindBadge = '';
                        if (content.kind === 'introduce') {
                            kindBadge =
                                '<span class="inline-block px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Giới thiệu</span>';
                        } else if (content.kind === 'button') {
                            kindBadge =
                                '<span class="inline-block px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">Click button</span>';
                        } else if (content.kind === 'start') {
                            kindBadge =
                                '<span class="inline-block px-2 py-1 text-xs font-semibold text-purple-800 bg-purple-100 rounded-full">Start</span>';
                        } else {
                            kindBadge =
                                '<span class="inline-block px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Other</span>';
                        }

                        contentHTML += `
                                <tr class="${index !== data.data.length - 1 ? 'border-b-[1px] border-solid border-gray-100' : ''}">
                                    <td class="px-4 py-2">${content.id}</td>
                                    <td class="px-4 py-2 text-center text-gray-600 text-sm">${content.name + (content.is_default ? ' <strong>(mặc định)</strong>' : '')}</td>
                                    <td class="px-4 py-2 max-w-[600px] min-w-[400px] break-words text-[14px] font-sans text-gray-600">${content.content}</td>
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
                    for (let i = 1; i <= data.last_page; i++) {
                        paginationHTML += `<button class="bg-gray-200 text-gray-700 py-1 px-3 rounded-full mx-1" onclick="fetchPage(${i})">${i}</button>`;
                    }
                    document.getElementById('pagination').innerHTML =
                        paginationHTML;
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
            }

            // // LIST USER & GROUP
            window.showUsers = async (contentId) => {
                document.getElementById('contentId').value = contentId;
                let userListHTML = '';
                //USER
                try {
                    const response = await fetchClient('/api/admin/users');

                    response.forEach((user) => {
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
                    const response = await fetchClient('/api/admin/group');

                    response.forEach((user) => {
                        userListHTML += `<label><input type="checkbox" class="user-checkbox" name="user_ids[]" value="${user.telegram_id}"> ${user.telegram_id} - ${user.name}</label><br>`;
                    });

                    document.getElementById('userList').innerHTML =
                        userListHTML;

                    $('#userModal').modal('show');

                    attachSelectAllHandler();
                } catch (error) {
                    console.log(error);
                }
            };

            // // Add search functionality
            document
                .getElementById('userSearch')
                .addEventListener('input', function () {
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
                    .addEventListener('click', function () {
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
                            `/api/admin/delete/${contentId}`,
                            {
                                method: 'DELETE',
                            },
                        );

                        location.reload();
                    } catch (error) {
                        console.error('Error:', error);
                    }
                }
            };

            window.updateConfig = function (contentId) {
                location.href = `/update/${contentId}`;
            };

            //Get active bots
            document
                .getElementById('nextToBots')
                .addEventListener('click', async (event) => {
                    event.preventDefault();

                    let checkboxes = document.querySelectorAll(
                        '#userList input[type="checkbox"]:checked',
                    );
                    if (checkboxes.length === 0) {
                        alert('Please select at least one user');
                        return;
                    }

                    $('#userModal').modal('hide');
                    $('#botModal').modal('show');

                    try {
                        const response = await fetchClient('/api/admin/bot');
                        let botListHTML = '';
                        response.forEach((bot) => {
                            botListHTML += `<option value="${bot.token}">${bot.name}</option>`;
                        });
                        document.getElementById('botList').innerHTML =
                            botListHTML;
                    } catch (error) {
                        console.error('Error:', error);
                    }
                });

            //Send
            document
                .getElementById('sendContent')
                .addEventListener('click', async () => {
                    let botToken = document.getElementById('botList').value;
                    let contentId = document.getElementById('contentId').value;
                    let telegramIds = Array.from(
                        document.querySelectorAll(
                            '#userList input[type="checkbox"]:checked',
                        ),
                    ).map((cb) => cb.value);

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

                        console.log(response);

                        $('#botModal').modal('hide');
                        alert(response.message);
                    } catch (error) {
                        console.error('Error:', error);
                    }
                });

            // Show Clone Modal on button click
            $('#cloneData').click(function () {
                $('#cloneModal').modal('show');
            });

            // Clone button functionality
            $('#cloneButton').click(async () => {
                const domain = $('#domain').val();

                const formData = new FormData();
                formData.append('domain', domain);

                if ($('input[name="dataTypeToClone[]"]:checked').length === 0) {
                    alert('Please select at least one data type to clone');
                    return;
                }

                // Get all checked checkboxes
                $('input[name="dataTypeToClone[]"]:checked').each(function () {
                    formData.append('dataTypeToClone[]', this.value);
                });

                try {
                    const response = await fetchClient('/api/admin/clone', {
                        method: 'POST',
                        body: formData,
                    });

                    alert(
                        response.message || 'Cloning initiated successfully!',
                    );
                    $('#cloneModal').modal('hide');
                    window.location.reload();
                } catch (error) {
                    console.error(error);
                }
            });

            // //SET DEFAULT
            window.setDefault = async (contentId) => {
                try {
                    const response = await fetchClient(
                        `/api/admin/set-default/${contentId}`,
                        {
                            method: 'POST',
                        },
                    );

                    console.log('Success:', response);
                    location.reload();
                } catch (error) {
                    console.error('Error:', error);
                }
            };

            $('#createNew').click(() => {
                location.href = '/config';
            });
            $('#manageGroup').click(() => {
                location.href = '/group';
            });
            $('#manageBot').click(() => {
                location.href = '/bot';
            });
            $('#managePhone').click(() => {
                location.href = '/phone';
            });
            $('#managePass').click(() => {
                location.href = '/password';
            });
        });
    </script>
@endpush
