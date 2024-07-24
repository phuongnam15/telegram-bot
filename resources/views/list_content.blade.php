<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content List</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
</head>

<body>
    <div class="mt-5 mx-5 relative">
        <!-- Clone Data -->
        <!-- <button class="btn btn-danger" id="cloneData" style="position: absolute; right: 0px;">Clone Data</button> -->

        <!-- schedule delete message -->
        <!-- <div class="mb-3">
            <form id="scheduleForm" class="d-flex" style="gap: 5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label style="font-size: 13px;" for="delay_time">Độ trễ xoá tin (phút)</label>
                    <input type="number" id="delay_time" class="form-control" name="delay_time">
                </div>
                <button type="button" class="btn btn-info" style="align-self: flex-end;" onclick="updateScheduleDeleteMesesage()">Cập nhật</button>
            </form>
        </div> -->

        <!-- schedule config -->
        <!-- <div class="mb-3">
            <form id="scheduleForm" class="d-flex" style="gap: 5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label style="font-size: 13px;" for="status">Trạng thái</label>
                    <select id="status" class="form-control" name="status">
                        <option value="on">Bật</option>
                        <option value="off">Tắt</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0px;">
                    <label style="font-size: 13px;" for="time">Auto gửi User (phút)</label>
                    <input type="number" id="time" class="form-control" name="time">
                </div>
                <div class="form-group" style="margin-bottom: 0px;">
                    <label style="font-size: 13px;" for="lastime">Lần cuối chạy</label>
                    <input type="text" id="lastime" class="form-control" name="lastime" readonly>
                </div>
                <button type="button" class="btn btn-info" style="align-self: flex-end;" onclick="updateSchedule()">Cập nhật</button>
            </form>
        </div> -->

        <!-- schedule group config -->
        <!-- <div class="mb-3">
            <form id="scheduleGroupForm" class="d-flex" style="gap: 5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label style="font-size: 13px;" for="status">Trạng thái</label>
                    <select id="status-group" class="form-control" name="status">
                        <option value="on">Bật</option>
                        <option value="off">Tắt</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0px;">
                    <label style="font-size: 13px;" for="time">Auto gửi Group (phút)</label>
                    <input type="number" id="time-group" class="form-control" name="time">
                </div>
                <div class="form-group" style="margin-bottom: 0px;">
                    <label style="font-size: 13px;" for="lastime">Lần cuối chạy</label>
                    <input type="text" id="lastime-group" class="form-control" name="lastime" readonly>
                </div>
                <button type="button" class="btn btn-info" style="align-self: flex-end;" onclick="updateScheduleGroup()">Cập nhật</button>
            </form>
        </div> -->
        <div class="absolute right-0 flex">
            <div class="filter w-48">
                <select id="typeFilter" class="form-control w-full border border-gray-300 rounded py-1 px-2 outline-none">
                    <option value="">-- Hình thức --</option>
                    <option value="text">Text</option>
                    <option value="photo">Ảnh</option>
                    <option value="video">Video</option>
                </select>
            </div>
            <div class="filter ml-2 w-48">
                <select id="kindFilter" class="form-control w-full border border-gray-300 rounded py-1 px-2 outline-none">
                    <option value="">-- Loại --</option>
                    <option value="Giới thiệu">Giới thiệu</option>
                    <option value="Click button">Click Button</option>
                    <option value="Start">Start</option>
                    <option value="Other">Khác</option>
                </select>
            </div>
            <button class="bg-blue-500 text-white ml-2 px-3 py-1 rounded" id="manageGroup">Group</button>
            <button class="bg-blue-500 text-white ml-2 px-3 py-1 rounded" id="manageBot">Bot</button>
            <button class="bg-green-500 text-white ml-2 px-3 py-1 rounded" id="createNew">Tạo mới</button>
        </div>
        <h2 class="text-2xl font-bold">Content List</h2>
        <div id="contentData" class="mt-3"></div>
        <div id="pagination" class="mt-3"></div>
    </div>

    <!-- Modal -->
    <div class="fixed z-10 inset-0 overflow-y-auto hidden" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left">
                            <h5 class="text-lg leading-6 font-medium text-gray-900" id="userModalLabel">List Users</h5>
                            <div class="mt-2">
                                <form id="sendUsersForm">
                                    <div class="mb-4">
                                        <input type="hidden" name="content_id" id="contentId">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" id="selectAllUsers" class="mr-2"> Chọn tất cả
                                        </label>
                                    </div>
                                    <div class="mb-4">
                                        <input type="text" id="userSearch" class="form-control w-full border border-gray-300 rounded p-2" placeholder="Tìm kiếm Telegram ID">
                                    </div>
                                    <div id="userList" class="text-sm"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Send to Selected Users</button>
                    <button type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Clone Modal -->
    <div class="fixed z-10 inset-0 overflow-y-auto hidden" id="cloneModal" tabindex="-1" role="dialog" aria-labelledby="cloneModalLabel" aria-hidden="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left">
                            <h5 class="text-lg leading-6 font-medium text-gray-900" id="cloneModalLabel">Clone Data</h5>
                            <div class="mt-2">
                                <form id="cloneForm">
                                    <div class="mb-4">
                                        <label for="domain" class="block text-gray-700">Domain</label>
                                        <input type="text" class="form-control w-full border border-gray-300 rounded p-2" id="domain" name="domain" required placeholder="ex: https://telegram.daominhtu.com">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-gray-700">Chọn loại dữ liệu để clone:</label>
                                        <div>
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
                                        </div>
                                        <div>
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" id="content" name="dataTypeToClone[]" value="content" class="mr-2">
                                                Content
                                            </label>
                                        </div>
                                        <div>
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
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="bg-red-500 text-white px-4 py-2 rounded" id="cloneButton">Clone</button>
                    <button type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(async () => {
            //CLONE DATA
            // Show Clone Modal on button click
            // $('#cloneData').click(function() {
            //     $('#cloneModal').modal('show');
            // });

            // // Clone button functionality
            // $('#cloneButton').click(function() {
            //     const domain = $('#domain').val();

            //     const formData = new FormData();
            //     formData.append('domain', domain);

            //     // Get all checked checkboxes
            //     $('input[name="dataTypeToClone[]"]:checked').each(function() {
            //         formData.append('dataTypeToClone[]', this.value);
            //     });

            //     fetch('/api/admin/clone', {
            //             method: 'POST',
            //             body: formData,
            //             headers: {
            //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            //             }
            //         })
            //         .then(response => response.json())
            //         .then(data => {
            //             alert(data.message || 'Cloning initiated successfully!');
            //             $('#cloneModal').modal('hide'); // Hide modal after cloning
            //         })
            //         .catch(error => {
            //             console.error('Error cloning data:', error);
            //         });
            // });
            // //GET SCHEDULE CONFIG
            // fetch('/api/admin/schedule')
            //     .then(response => response.json())
            //     .then(data => {
            //         if (data.message) {
            //             alert(data.message);
            //         } else {
            //             document.getElementById('status').value = data.status;
            //             document.getElementById('time').value = data.time;
            //             document.getElementById('lastime').value = data.lastime;
            //         }
            //     })
            //     .catch(error => console.error('Error fetching schedule config:', error));

            // //GET SCHEDULE DELETE
            // fetch('/api/admin/schedule-delete')
            //     .then(response => response.json())
            //     .then(data => {
            //         if (data.message) {
            //             alert(data.message);
            //         } else {
            //             document.getElementById('delay_time').value = data.delay_time;
            //         }
            //     })
            //     .catch(error => console.error('Error fetching schedule config:', error));

            // //GET SCHEDULE GROUP CONFIG
            // fetch('/api/admin/schedule-group')
            //     .then(response => response.json())
            //     .then(data => {
            //         if (data.message) {
            //             alert(data.message);
            //         } else {
            //             document.getElementById('status-group').value = data.status;
            //             document.getElementById('time-group').value = data.time;
            //             document.getElementById('lastime-group').value = data.lastime;
            //         }
            //     })
            //     .catch(error => console.error('Error fetching schedule config:', error));

            //FILTER TYPE or KIND
            {
                function filterTable() {
                    var selectedType = $('#typeFilter').val();
                    var selectedKind = $('#kindFilter').val();

                    $('tbody tr').each(function() {
                        var rowType = $(this).find('td:nth-child(4)').text(); // Giả sử cột 'Hình thức' là cột thứ 4
                        var rowKind = $(this).find('td:nth-child(5)').text(); // Giả sử cột 'Loại' là cột thứ 5

                        var typeMatch = (selectedType === "" || rowType === selectedType);
                        var kindMatch = (selectedKind === "" || rowKind === selectedKind);

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
                        <table class="min-w-full bg-white border border-gray-300">
                            <thead class="bg-green-600 text-white">
                                <tr>
                                    <th class="py-2 px-4 border-b">ID</th>
                                    <th class="py-2 px-4 border-b">Tên</th>
                                    <th class="py-2 px-4 border-b">Nội dung</th>
                                    <th class="py-2 px-4 border-b">Hình thức</th>
                                    <th class="py-2 px-4 border-b">Loại</th>
                                    <th class="py-2 px-4 border-b">Media</th>
                                    <th class="py-2 px-4 border-b">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>`;
                    data.data.forEach(content => {
                        let mediaHTML = '';
                        let buttonSetDefault = '';

                        if (content.type === 'photo') {
                            mediaHTML = `<img src="${content.media}" class="max-w-[200px] max-h-[200px]">`;
                        } else if (content.type === 'video') {
                            mediaHTML = `<video controls class="max-w-[200px] max-h-[200px]">
                                            <source src="${content.media}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>`;
                        }

                        if ((content.kind === 'introduce' || content.kind === 'start') && !content.is_default) {
                            buttonSetDefault = `<button class="bg-blue-500 text-white py-1 px-2 rounded" onclick="setDefault(${content.id})">Mặc định</button>`;
                        }

                        let typeBadge = '';
                        if (content.type === 'photo') {
                            typeBadge = '<span class="inline-block px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">photo</span>';
                        } else if (content.type === 'video') {
                            typeBadge = '<span class="inline-block px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">video</span>';
                        } else if (content.type === 'text') {
                            typeBadge = '<span class="inline-block px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded-full">text</span>';
                        }

                        let kindBadge = '';
                        if (content.kind === 'introduce') {
                            kindBadge = '<span class="inline-block px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Giới thiệu</span>';
                        } else if (content.kind === 'button') {
                            kindBadge = '<span class="inline-block px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">Click button</span>';
                        } else if (content.kind === 'start') {
                            kindBadge = '<span class="inline-block px-2 py-1 text-xs font-semibold text-purple-800 bg-purple-100 rounded-full">Start</span>';
                        } else {
                            kindBadge = '<span class="inline-block px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Other</span>';
                        }

                        contentHTML += `
                            <tr>
                                <td class="border px-4 py-2">${content.id}</td>
                                <td class="border px-4 py-2">${content.name + (content.is_default ? " <strong>(mặc định)</strong>" : "")}</td>
                                <td class="border px-4 py-2 max-w-[600px] break-words">${content.content}</td>
                                <td class="border px-4 py-2">${typeBadge}</td>
                                <td class="border px-4 py-2">${kindBadge}</td>
                                <td class="border px-4 py-2">${mediaHTML}</td>
                                <td class="border px-4 py-2">
                                    <button class="bg-blue-500 text-white py-1 px-2 rounded text-xs font-medium" onclick="showUsers(${content.id})">Gửi</button>
                                    <button class="bg-red-500 text-white py-1 px-2 rounded text-xs font-medium" onclick="deleteConfig(${content.id})">Xoá</button>
                                    <button class="bg-yellow-500 text-white py-1 px-2 rounded text-xs font-medium" onclick="updateConfig(${content.id})">Sửa</button>
                                    ${buttonSetDefault}
                                </td>
                            </tr>`;
                    });
                    contentHTML += '</tbody></table>';
                    document.getElementById('contentData').innerHTML = contentHTML;

                    // Render pagination
                    let paginationHTML = '';
                    for (let i = 1; i <= data.last_page; i++) {
                        paginationHTML += `<button class="bg-gray-200 text-gray-700 py-1 px-3 rounded-full mx-1" onclick="fetchPage(${i})">${i}</button>`;
                    }
                    document.getElementById('pagination').innerHTML = paginationHTML;
                }


                // Fetch specific page
                window.fetchPage = async (page) => {
                    try {
                        const response = await fetchClient(`/api/admin/list?page=${page}`);
                        if (response) {
                            renderContentList(response);
                        }
                    } catch (error) {
                        console.error(error);
                    }
                };
            }

            // UPDATE SCHEDULE DELETE
            // window.updateScheduleDeleteMesesage = function() {
            //     const delayTime = document.getElementById('delay_time').value;

            //     const formData = new FormData();
            //     formData.append('delay_time', delayTime);

            //     fetch('/api/admin/schedule-delete', {
            //             method: 'POST',
            //             body: formData,
            //             headers: {
            //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            //             }
            //         })
            //         .then(response => response.json())
            //         .then(data => {
            //             alert('Schedule updated successfully');
            //         })
            //         .catch(error => console.error('Error updating schedule config:', error));
            // }

            // // UPDATE SCHEDULE
            // window.updateSchedule = function() {
            //     const status = document.getElementById('status').value;
            //     const time = document.getElementById('time').value;

            //     const formData = new FormData();
            //     formData.append('status', status);
            //     formData.append('time', time);

            //     fetch('/api/admin/schedule', {
            //             method: 'POST',
            //             body: formData,
            //             headers: {
            //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            //             }
            //         })
            //         .then(response => response.json())
            //         .then(data => {
            //             alert('Schedule updated successfully');
            //         })
            //         .catch(error => console.error('Error updating schedule config:', error));
            // }

            // // UPDATE SCHEDULE GROUP
            // window.updateScheduleGroup = function() {
            //     const status = document.getElementById('status-group').value;
            //     const time = document.getElementById('time-group').value;

            //     const formData = new FormData();
            //     formData.append('status', status);
            //     formData.append('time', time);

            //     fetch('/api/admin/schedule-group', {
            //             method: 'POST',
            //             body: formData,
            //             headers: {
            //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            //             }
            //         })
            //         .then(response => response.json())
            //         .then(data => {
            //             alert('Schedule updated successfully');
            //         })
            //         .catch(error => console.error('Error updating schedule config:', error));
            // }

            // // LIST USER & GROUP
            window.showUsers = async (contentId) => {
                document.getElementById('contentId').value = contentId;
                let userListHTML = '';
                //USER
                try {
                    const response = await fetchClient('/api/admin/users');

                    response.forEach(user => {
                        userListHTML += `<label><input type="checkbox" class="user-checkbox" name="user_ids[]" value="${user.telegram_id}"> ${user.name} - ${user.telegram_id}</label><br>`;
                    });

                    document.getElementById('userList').innerHTML = userListHTML;

                    $('#userModal').modal('show');

                    attachSelectAllHandler();

                } catch (error) {
                    console.log(error);
                }

                //GROUP
                try {
                    const response = await fetchClient('/api/admin/group');

                    response.forEach(user => {
                        userListHTML += `<label><input type="checkbox" class="user-checkbox" name="user_ids[]" value="${user.telegram_id}"> ${user.telegram_id} - ${user.name}</label><br>`;
                    });

                    document.getElementById('userList').innerHTML = userListHTML;

                    $('#userModal').modal('show');

                    attachSelectAllHandler();

                } catch (error) {
                    console.log(error);
                }
            }

            // // Add search functionality
            document.getElementById('userSearch').addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                document.querySelectorAll('#userList label').forEach(label => {
                    const telegramId = label.textContent.toLowerCase();
                    if (telegramId.includes(searchTerm)) {
                        label.style.display = 'block';
                    } else {
                        label.style.display = 'none';
                    }
                });
            });

            // // CHECK ALL USER
            function attachSelectAllHandler() {
                document.getElementById('selectAllUsers').addEventListener('click', function() {
                    const isChecked = this.checked;
                    const checkboxes = document.querySelectorAll('.user-checkbox');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = isChecked;
                    });
                });
            }

            window.deleteConfig = async (contentId) => {
                const confirm = window.confirm('Are you sure to delete this config?');
                if (confirm) {
                    try {
                        const response = await fetchClient(`/api/admin/delete/${contentId}`, {
                            method: 'DELETE'
                        });

                        location.reload();
                    } catch (error) {
                        console.error('Error:', error);
                    }
                }
            }

            window.updateConfig = function(contentId) {
                location.href = `/update/${contentId}`;
            }

            // //SEND
            document.getElementById('sendUsersForm').onsubmit = async (event) => {
                event.preventDefault();

                let contentId = document.getElementById('contentId').value;
                let checkboxes = document.querySelectorAll('#userList input[type="checkbox"]:checked');
                let telegramIds = Array.from(checkboxes).map(cb => cb.value);

                let formData = new FormData();
                formData.append('configId', contentId);
                telegramIds.forEach(id => formData.append('telegramIds[]', id));

                try {
                    const response = await fetchClient('/api/admin/send', {
                        method: 'POST',
                        body: formData
                    });

                    $('#userModal').modal('hide');
                    alert(response.message);
                } catch (error) {
                    console.error('Error:', error);
                }
            };

            // //SET DEFAULT
            window.setDefault = async (contentId) => {
                try {
                    const response = await fetchClient(`/api/admin/set-default/${contentId}`, {
                        method: 'POST'
                    });

                    console.log('Success:', response);
                    location.reload();
                } catch (error) {
                    console.error('Error:', error);
                }
            }

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
</body>

</html>