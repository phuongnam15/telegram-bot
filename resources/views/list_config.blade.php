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
</head>

<body>
    <div class="container mt-5">
        <h1>Content List</h1>
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
        //list content
        $(document).ready(function() {
            fetch('api/admin/list')
                .then(response => response.json())
                .then(data => {
                    // console.log(data);
                    let contentHTML = '<table class="table"><thead><tr><th>ID</th><th>Content</th><th>Actions</th></tr></thead><tbody>';
                    data.forEach(content => {
                        contentHTML += `<tr><td>${content.id}</td><td>${content.content}</td><td><button class="btn btn-primary" onclick="showUsers(${content.id})">Send</button></td></tr>`;
                    });
                    contentHTML += '</tbody></table>';
                    document.getElementById('contentData').innerHTML = contentHTML;
                });
        });

        //list user
        function showUsers(contentId) {
            document.getElementById('contentId').value = contentId;
            fetch('/api/admin/users')
                .then(response => response.json())
                .then(data => {
                    let userListHTML = '';
                    data.forEach(user => {
                        userListHTML += `<label><input type="checkbox" name="user_ids[]" value="${user.telegram_id}"> ${user.name}</label><br>`;
                    });
                    document.getElementById('userList').innerHTML = userListHTML;
                    $('#userModal').modal('show');
                });
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
    </script>
</body>

</html>