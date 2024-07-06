<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content List</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="mt-5 mx-5" style="position: relative;">
        <!-- schedule delete message -->
        <div class="mb-3">
            <!-- <h5>Độ trễ xoá tin nhắn</h5> -->
            <form id="scheduleForm" class="d-flex" style="gap: 5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label style="font-size: 13px;" for="delay_time">Độ trễ xoá tin (phút)</label>
                    <input type="number" id="delay_time" class="form-control" name="delay_time">
                </div>
                <button type="button" class="btn btn-info" style="align-self: flex-end;" onclick="updateScheduleDeleteMesesage()">Cập nhật</button>
            </form>
        </div>

        <!-- schedule config -->
        <div class="mb-3">
            <!-- <h5>Lịch chạy của User</h5> -->
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
        </div>

        <!-- schedule group config -->
        <div class="mb-3">
            <!-- <h5>Lịch chạy của Group</h5> -->
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
        </div>

        <div style="position: absolute; right: 0px" class="d-flex">
            <div class="filter" style="width: 200px;">
                <select id="typeFilter" class="form-control">
                    <option value="">-- Hình thức --</option>
                    <option value="text">Text</option>
                    <option value="photo">Ảnh</option>
                    <option value="video">Video</option>
                </select>
            </div>
            <div class="filter ml-2" style="width: 200px;">
                <select id="kindFilter" class="form-control">
                    <option value="">-- Loại --</option>
                    <option value="Giới thiệu">Giới thiệu</option>
                    <option value="Click button">Click Button</option>
                    <option value="Start">Start</option>
                    <option value="Other">Khác</option>
                </select>
            </div>
            <button class="btn btn-info ml-2" id="manageGroup">Quản lí Group</button>
            <button class="btn btn-warning ml-2" id="manageBot">Quản lí Bot</button>
            <button class="btn btn-success ml-2" id="createNew">Tạo mới</button>
        </div>
        <h2>Content List</h2>
        <div id="contentData" class="mt-3"></div>
        <div id="pagination" class="mt-3"></div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">List Users</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/api/admin/send" method="post" id="sendUsersForm">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="content_id" id="contentId">
                        <div>
                            <label><input type="checkbox" id="selectAllUsers" /> Chọn tất cả</label>
                        </div>
                        <div class="form-group">
                            <input type="text" id="userSearch" class="form-control" placeholder="Tìm kiếm Telegram ID">
                        </div>
                        <div id="userList" style="font-size: 14px;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Send to Selected Users</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            //GET SCHEDULE CONFIG
            fetch('/api/admin/schedule')
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        alert(data.message);
                    } else {
                        document.getElementById('status').value = data.status;
                        document.getElementById('time').value = data.time;
                        document.getElementById('lastime').value = data.lastime;
                    }
                })
                .catch(error => console.error('Error fetching schedule config:', error));

            //GET SCHEDULE DELETE
            fetch('/api/admin/schedule-delete')
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        alert(data.message);
                    } else {
                        document.getElementById('delay_time').value = data.delay_time;
                    }
                })
                .catch(error => console.error('Error fetching schedule config:', error));

            //GET SCHEDULE GROUP CONFIG
            fetch('/api/admin/schedule-group')
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        alert(data.message);
                    } else {
                        document.getElementById('status-group').value = data.status;
                        document.getElementById('time-group').value = data.time;
                        document.getElementById('lastime-group').value = data.lastime;
                    }
                })
                .catch(error => console.error('Error fetching schedule config:', error));

            //FILTER TYPE or KIND
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

            //GET LIST CONTENT
            fetch('api/admin/list')
                .then(response => response.json())
                .then(data => {
                    renderContentList(data);
                });

            function renderContentList(data) {
                let contentHTML = `
                        <table class="table">
                            <thead style="color: white; background-color: #28a745;">
                                <tr>
                                    <th>ID</th>
                                    <th>Tên</th>
                                    <th>Nội dung</th>
                                    <th>Hình thữc</th>
                                    <th>Loại</th>
                                    <th>Media</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>`;
                data.data.forEach(content => {
                    let mediaHTML = '';
                    let buttonSetDefault = '';

                    if (content.type === 'photo') {

                        mediaHTML = `<img src="${content.media}" style="max-width: 200px; max-height: 200px;">`;

                    } else if (content.type === 'video') {

                        mediaHTML = `<video controls style="max-width: 200px; max-height: 200px;">
                                        <source src="${content.media}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>`;
                    }

                    if ((content.kind === 'introduce' || content.kind === 'start') && !content.is_default) {
                        buttonSetDefault = `<button class="btn btn-info" onclick="setDefault(${content.id})">Mặc định</button>`;
                    }

                    let typeBadge = '';
                    if (content.type === 'photo') {
                        typeBadge = '<span class="badge badge-primary">photo</span>';
                    } else if (content.type === 'video') {
                        typeBadge = '<span class="badge badge-warning">video</span>';
                    } else if (content.type === 'text') {
                        typeBadge = '<span class="badge badge-secondary">text</span>';
                    }

                    let kindBadge = '';
                    if (content.kind === 'introduce') {
                        kindBadge = '<span class="badge badge-success">Giới thiệu</span>';
                    } else if (content.kind === 'button') {
                        kindBadge = '<span class="badge badge-info">Click button</span>';
                    } else if (content.kind === 'start') {
                        kindBadge = '<span class="badge badge-primary">Start</span>';
                    } else {
                        kindBadge = '<span class="badge badge-warning">Other</span>';
                    }

                    contentHTML += `
                    <tr>
                        <td>${content.id}</td>
                        <td>${content.name + (content.is_default ? " <strong>(mặc định)</strong>" : "")}</td>
                        <td style="max-width: 600px; word-wrap: break-word; white-space: normal;">${content.content}</td>
                        <td>${typeBadge}</td>
                        <td>${kindBadge}</td>
                        <td>${mediaHTML}</td>
                        <td>
                        <button class="btn btn-primary" onclick="showUsers(${content.id})">Gửi</button>
                        <button class="btn btn-danger" onclick="deleteConfig(${content.id})">Xoá</button>
                        <button class="btn btn-warning" onclick="updateConfig(${content.id})">Sửa</button>
                        ${buttonSetDefault}
                        </td>
                        </tr>`;
                });
                contentHTML += '</tbody></table>';
                document.getElementById('contentData').innerHTML = contentHTML;

                // Render pagination
                let paginationHTML = '';
                for (let i = 1; i <= data.last_page; i++) {
                    paginationHTML += `<button class="btn btn-link" onclick="fetchPage(${i})">${i}</button>`;
                }
                document.getElementById('pagination').innerHTML = paginationHTML;
            }

            // Fetch specific page
            window.fetchPage = function(page) {
                fetch(`/api/admin/list?page=${page}`)
                    .then(response => response.json())
                    .then(data => {
                        renderContentList(data);
                    });
            };

            // UPDATE SCHEDULE DELETE
            window.updateScheduleDeleteMesesage = function() {
                const delayTime = document.getElementById('delay_time').value;

                const formData = new FormData();
                formData.append('delay_time', delayTime);

                fetch('/api/admin/schedule-delete', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert('Schedule updated successfully');
                    })
                    .catch(error => console.error('Error updating schedule config:', error));
            }

            // UPDATE SCHEDULE
            window.updateSchedule = function() {
                const status = document.getElementById('status').value;
                const time = document.getElementById('time').value;

                const formData = new FormData();
                formData.append('status', status);
                formData.append('time', time);

                fetch('/api/admin/schedule', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert('Schedule updated successfully');
                    })
                    .catch(error => console.error('Error updating schedule config:', error));
            }

            // UPDATE SCHEDULE GROUP
            window.updateScheduleGroup = function() {
                const status = document.getElementById('status-group').value;
                const time = document.getElementById('time-group').value;

                const formData = new FormData();
                formData.append('status', status);
                formData.append('time', time);

                fetch('/api/admin/schedule-group', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert('Schedule updated successfully');
                    })
                    .catch(error => console.error('Error updating schedule config:', error));
            }

            // LIST USER & GROUP
            window.showUsers = function(contentId) {
                document.getElementById('contentId').value = contentId;
                let userListHTML = '';
                //USER
                fetch('/api/admin/users')
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(user => {
                            userListHTML += `<label><input type="checkbox" class="user-checkbox" name="user_ids[]" value="${user.telegram_id}"> ${user.name} - ${user.telegram_id}</label><br>`;
                        });
                        document.getElementById('userList').innerHTML = userListHTML;
                        $('#userModal').modal('show');
                        attachSelectAllHandler();
                    });

                //GROUP
                fetch('/api/admin/group')
                    .then(response => response.json())
                    .then(data => {
                        // let userListHTML = '';
                        data.data.forEach(user => {
                            userListHTML += `<label><input type="checkbox" class="user-checkbox" name="user_ids[]" value="${user.telegram_id}"> ${user.telegram_id} - ${user.name}</label><br>`;
                        });
                        document.getElementById('userList').innerHTML = userListHTML;
                        $('#userModal').modal('show');
                        attachSelectAllHandler();
                    });
            }

            // Add search functionality
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

            // CHECK ALL USER
            function attachSelectAllHandler() {
                document.getElementById('selectAllUsers').addEventListener('click', function() {
                    const isChecked = this.checked;
                    const checkboxes = document.querySelectorAll('.user-checkbox');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = isChecked;
                    });
                });
            }

            window.deleteConfig = function(contentId) {
                const confirm = window.confirm('Are you sure to delete this config?');
                if (confirm) {
                    fetch(`/api/admin/delete/${contentId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Success:', data);
                            location.reload();
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                        });
                }
            }

            window.updateConfig = function(contentId) {
                location.href = `/update/${contentId}`;
            }

            //SEND
            document.getElementById('sendUsersForm').onsubmit = function(event) {
                event.preventDefault(); // Ngăn không cho form submit theo cách thông thường

                let contentId = document.getElementById('contentId').value;
                let checkboxes = document.querySelectorAll('#userList input[type="checkbox"]:checked');
                let telegramIds = Array.from(checkboxes).map(cb => cb.value);

                let formData = new FormData();
                formData.append('configId', contentId);
                telegramIds.forEach(id => formData.append('telegramIds[]', id));

                fetch('/api/admin/send', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        $('#userModal').modal('hide'); // Ẩn modal sau khi gửi thành công
                        alert(data.message);
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
            };

            //SET DEFAULT
            window.setDefault = function(contentId) {
                fetch(`/api/admin/set-default/${contentId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Success:', data);
                        location.reload();
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
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
        });
    </script>
    <script>
        // Create and style the button
        const button = document.createElement('button');
        button.id = 'getPassButton';
        button.innerText = 'Lấy Pass';
        button.style.position = 'relative';
        button.style.bottom = '50px';
        button.style.left = '50%';
        button.style.transform = 'translateX(-50%)';
        button.style.fontSize = '1em';
        button.style.padding = '5px 10px';
        button.style.backgroundColor = '#007BFF';
        button.style.color = '#FFF';
        button.style.border = 'none';
        button.style.borderRadius = '5px';
        button.style.cursor = 'pointer';

        // Create the timer and password display elements
        const timer = document.createElement('div');
        timer.id = 'timer';
        timer.style.display = 'none';
        timer.style.position = 'relative';
        timer.style.bottom = '50px';
        timer.style.left = '50%';
        timer.style.transform = 'translateX(-50%)';
        timer.style.fontSize = '1em';
        timer.style.padding = '5px 10px';
        timer.style.backgroundColor = '#FFC107';
        timer.style.color = '#fff';
        timer.style.borderRadius = '5px';
        timer.style.width = 'fit-content';

        const passwordDisplay = document.createElement('div');
        passwordDisplay.id = 'password';
        passwordDisplay.style.display = 'none';
        passwordDisplay.style.position = 'relative';
        passwordDisplay.style.bottom = '50px';
        passwordDisplay.style.left = '50%';
        passwordDisplay.style.transform = 'translateX(-50%)';
        passwordDisplay.style.fontSize = '1em';
        passwordDisplay.style.padding = '5px 10px';
        passwordDisplay.style.backgroundColor = '#28A745';
        passwordDisplay.style.color = '#FFF';
        passwordDisplay.style.borderRadius = '5px';
        passwordDisplay.style.width = 'fit-content';

        // Ensure elements are at the bottom of the page
        document.body.appendChild(button);
        document.body.appendChild(timer);
        document.body.appendChild(passwordDisplay);

        const passwords = [
            '2EoBhsV0', 'mpm40yL2', 'K1cb8ClT', 'NKajAdzz', '8CdGfMZZ', 'KFUrW0NB',
            'sF0V96aR', 'kxiu7DBD', 'QWcm0yR8', '4g8VizU4', 'dlWLQH2T', 'bs0fnoyl',
            'ltE8drdK', 'mgZIYw0N', 'DENq9cgr', 'NxGjAwLI', 'EjRh2qG5', 'PC96gSHw',
            'YP58iv4Y', '93WIbBAz', '1M0dn7ol', 'TdY0WTXW', 'xda8NirC', 'jBU3jR8f',
            'uIgTUIDv', 'ntPHsloN', 'xGsb4YeP', 'Xd0V4POS', '7mWUPRrB', 'FCbRnw5l',
            'WRrwAYk0', 'rIvzf1w2', '7bZT6dJ5', 't3vWPwai', 'VP8v4xpv', 'UDPBL4UL',
            'CJ77rcvI', 'C8HrV0qs', 'abTANkrZ', '397IYYev', 'pT99ewM1', '150Eha2n',
            'bjVWgCIB', 'hJwJJf6m', 'eXS974TV', '8YuXdMYl', 'IApR69hE', 'pCpUjC08',
            '0bdDwMhN', '3pVo5mtG', 'pmI5jrqU', 'fwYwnUg7', 'RC74QLuF', 'BhTBud28',
            'W0rKeh3X', 'WE7pznyf', 'gnzJPwGc', 'UG2QfGeU', 'dgzkR1A4', 'rqtp3JBC',
            'ebFmklmm', '6AsQVo5h', 'Lvtb6g4e', 'PnXAoPfC', 'njFzknQW', 'lxKMAgl7',
            'rP5YrGzz', 'yqUtO0GA', 'GgeVsRwi', 'bpG1USFq', 'nb6fecPu', 'KaxgaTpo',
            'Nyh4TfUz', 'ngLSsBI2', 'zsgf7YLI', 'SETB9Hqe', 'MWy3aL0a', 'RMrpnAzY',
            'qehWG0Qs', '1kX11mdJ', 'glrtXrJg', '5oGcrvJF', 'a8akQY5G', 'gIZiCh1P',
            'XnEM8bGa', '1TSDzLpT', 'uTmjzeRt', 'hHi8uXqr', 'shtrAHdk', 'fSiYkn98',
            'xTLO6mnx', 'gSFh3yXs', 'A0j9oO2V', 'ccSwUBjf', 'FQ67w7wZ', 'G5SM6iTq',
            'udgJTbWY', 'znntC6WS', '38QIw39s', 'GJ4izaNm'
        ];

        button.addEventListener('click', () => {
            button.style.display = 'none';
            timer.style.display = 'block';
            passwordDisplay.style.display = 'none';

            let countdown = 60;
            timer.innerText = "Chờ " + countdown + "s để lấy pass";

            const interval = setInterval(() => {
                countdown--;
                timer.innerText = "Chờ " + countdown + "s để lấy pass";

                if (countdown <= 0) {
                    clearInterval(interval);
                    timer.style.display = 'none';
                    passwordDisplay.style.display = 'block';
                    const randomPassword = passwords[Math.floor(Math.random() * passwords.length)];
                    passwordDisplay.innerText = randomPassword;
                }
            }, 1000);
        });
    </script>
</body>

</html>