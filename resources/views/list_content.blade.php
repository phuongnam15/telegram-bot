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
        <div class="mb-3">
            <form id="scheduleForm" class="d-flex" style="gap: 5px;">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label style="font-size: 13px;" for="status">Trạng thái</label>
                    <select id="status" class="form-control" name="status">
                        <option value="on">Bật</option>
                        <option value="off">Tắt</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0px;">
                    <label style="font-size: 13px;" for="time">Thời gian chạy (phút)</label>
                    <input type="number" id="time" class="form-control" name="time">
                </div>
                <div class="form-group" style="margin-bottom: 0px;">
                    <label style="font-size: 13px;" for="lastime">Lần cuối chạy</label>
                    <input type="text" id="lastime" class="form-control" name="lastime" readonly>
                </div>
                <button type="button" class="btn btn-primary" style="align-self: flex-end;" onclick="updateSchedule()">Cập nhật lịch chạy</button>
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
                    <option value="introduce">Giới thiệu</option>
                    <option value="other">Khác</option>
                </select>
            </div>
            <button class="btn btn-success ml-2" id="createNew">Tạo mới</button>
        </div>
        <h2>Content List</h2>
        <div id="contentData" class="mt-3"></div>
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
                        <div id="userList"></div>
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
                    console.log(data);
                    if (data.message) {
                        alert(data.message);
                    } else {
                        document.getElementById('status').value = data.status;
                        document.getElementById('time').value = data.time;
                        document.getElementById('lastime').value = data.lastime;
                    }
                })
                .catch(error => console.error('Error fetching schedule config:', error));

            //FILTER TYPE
            $('#typeFilter').change(function() {
                var selectedType = $(this).val();
                $('tbody tr').each(function() {
                    var rowType = $(this).find('td:nth-child(4)').text(); // Giả sử cột 'Hình thức' là cột thứ 4
                    if (selectedType === "" || rowType === selectedType) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            //KIND TYPE
            $('#kindFilter').change(function() {
                var selectedType = $(this).val();
                $('tbody tr').each(function() {
                    var rowType = $(this).find('td:nth-child(5)').text(); // Giả sử cột 'Loại' là cột thứ 5
                    if (selectedType === "" || rowType === selectedType) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            //GET LIST CONTENT
            fetch('api/admin/list')
                .then(response => response.json())
                .then(data => {
                    // console.log(data);
                    let contentHTML = `
                    <table class="table">
                    <thead>
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
                    data.forEach(content => {
                        contentHTML += `
                        <tr>
                            <td>${content.id}</td>
                            <td>${content.name}</td>
                            <td>${content.content}</td>
                            <td>${content.type}</td>
                            <td>${content.kind}</td>
                            <td>
                            <img src="${content.media}" style="max-width: 200px; max-height: 200px;">
                            </td>
                            <td>
                            <button class="btn btn-primary" onclick="showUsers(${content.id})">Gửi</button>
                            <button class="btn btn-danger" onclick="deleteConfig(${content.id})">Xoá</button>
                            <button class="btn btn-warning" onclick="updateConfig(${content.id})">Sửa</button>
                            </td>
                            </tr>`;
                    });
                    contentHTML += '</tbody></table>';
                    document.getElementById('contentData').innerHTML = contentHTML;
                });
        });

        //UPDATE SCHEDULE
        function updateSchedule() {
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

        //LIST USER
        function showUsers(contentId) {
            document.getElementById('contentId').value = contentId;
            fetch('/api/admin/users')
                .then(response => response.json())
                .then(data => {
                    let userListHTML = '';
                    data.forEach(user => {
                        userListHTML += `<label><input type="checkbox" class="user-checkbox" name="user_ids[]" value="${user.telegram_id}"> ${user.name}</label><br>`;
                    });
                    document.getElementById('userList').innerHTML = userListHTML;
                    $('#userModal').modal('show');
                    attachSelectAllHandler();
                });
        }
        //CHECK ALL USER
        function attachSelectAllHandler() {
            document.getElementById('selectAllUsers').addEventListener('click', function() {
                const isChecked = this.checked;
                const checkboxes = document.querySelectorAll('.user-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = isChecked;
                });
            });
        }

        function deleteConfig(contentId) {
            const confirm = window.confirm('Are you sure to delete this config?');
            if (confirm) {
                fetch(`/api/admin/delete/${contentId}`, {
                        method: 'DELETE'
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

        function updateConfig(contentId) {
            location.href = `/update/${contentId}`;
        }

        //send
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
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    $('#userModal').modal('hide'); // Ẩn modal sau khi gửi thành công
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        };
        $('#createNew').click(() => {
            location.href = '/config';
        });
    </script>
</body>

</html>