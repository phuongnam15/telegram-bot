<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bot Management</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Bot Management</h2>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#createBotModal">Tạo bot mới</button>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Token</th>
                    <th>Name</th>
                    <th>Status</th>
                    <!-- <th>Created At</th> -->
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="botTableBody">
                <!-- Bots will be populated here by jQuery -->
            </tbody>
        </table>
    </div>

    <!-- Modal for creating new bot -->
    <div class="modal fade" id="createBotModal" tabindex="-1" role="dialog" aria-labelledby="createBotModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createBotModalLabel">Tạo bot mới</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createBotForm">
                        <div class="form-group">
                            <label for="botToken">Token</label>
                            <input type="text" class="form-control" id="botToken" name="token" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Bot</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Fetch bots on page load
            fetchBots();

            // Fetch bots function
            function fetchBots() {
                $.ajax({
                    url: '/api/admin/bot',
                    method: 'GET',
                    success: function(data) {
                        $('#botTableBody').empty();
                        let activeButton;
                        let status;
                        data.data.forEach(function(bot) {
                            activeButton = bot.status === "1" ? '' : `<button class="btn btn-info activate-btn" data-id="${bot.id}">Active</button>`;
                            status = bot.status === "1" ? '<span class="badge badge-success">Bật</span>' : '<span class="badge badge-secondary">Tắt</span>';
                            
                            $('#botTableBody').append(`
                                <tr>
                                    <td>${bot.id}</td>
                                    <td>${bot.token}</td>
                                    <td>${bot.name}</td>
                                    <td>${status}</td>
                                    <td>
                                        ${activeButton}
                                        <button class="btn btn-danger delete-btn" data-id="${bot.id}">Delete</button>
                                    </td>
                                </tr>
                            `);
                        });
                    }
                });
            }

            // Create new bot
            $('#createBotForm').on('submit', function(e) {
                e.preventDefault();
                let token = $('#botToken').val();
                $.ajax({
                    url: '/api/admin/bot',
                    method: 'POST',
                    data: { token: token },
                    success: function() {
                        $('#createBotModal').modal('hide');
                        fetchBots();
                    }
                });
            });

            // Activate bot
            $(document).on('click', '.activate-btn', function() {
                let botId = $(this).data('id');
                $.ajax({
                    url: `/api/admin/bot/${botId}`,
                    method: 'POST',
                    success: function() {
                        fetchBots();
                    }
                });
            });

            // Delete bot
            $(document).on('click', '.delete-btn', function() {
                let botId = $(this).data('id');
                if (confirm('Are you sure you want to delete this bot?')) {
                    $.ajax({
                        url: `/api/admin/bot/${botId}`,
                        method: 'DELETE',
                        success: function() {
                            fetchBots();
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
