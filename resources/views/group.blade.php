<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Group Management</h2>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#groupModal">Add Group</button>
        <table class="table table-bordered" id="groupTable">
            <thead>
                <tr>
                    <th>Telegram ID</th>
                    <th>Name</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Rows will be added by jQuery -->
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="groupModal" tabindex="-1" role="dialog" aria-labelledby="groupModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="groupModalLabel">Add Group</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="groupForm">
                        <div class="form-group">
                            <label for="telegram_id">Telegram ID</label>
                            <input type="text" class="form-control" id="telegram_id" name="telegram_id" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <input type="hidden" id="group_id">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="groupModal" tabindex="-1" role="dialog" aria-labelledby="groupModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="groupModalLabel">Edit Group</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="groupForm">
                        <div class="form-group">
                            <label for="telegram_id">Telegram ID</label>
                            <input type="text" class="form-control" id="telegram_id" name="telegram_id" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <input type="hidden" id="group_id">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            fetchGroups();

            function fetchGroups() {
                $.ajax({
                    url: '/api/admin/group',
                    method: 'GET',
                    success: function(data) {
                        $('#groupTable tbody').empty();
                        data.data.forEach(function(group) {
                            $('#groupTable tbody').append(`
                            <tr>
                                <td>${group.telegram_id}</td>
                                <td>${group.name}</td>
                                <td>${group.created_at}</td>
                                <td>${group.updated_at}</td>
                                <td>
                                    <button class="btn btn-info btn-edit" data-id="${group.id}">Edit</button>
                                    <button class="btn btn-danger btn-delete" data-id="${group.id}">Delete</button>
                                </td>
                            </tr>
                        `);
                        });
                    }
                });
            }

            $('#groupForm').submit(function(e) {
                e.preventDefault();
                let groupId = $('#group_id').val();
                let url = '/api/admin/group';
                let method = 'POST';

                if (groupId) {
                    url += '/' + groupId;
                    method = 'PUT';
                }

                $.ajax({
                    url: url,
                    method: method,
                    data: $(this).serialize(),
                    success: function() {
                        $('#groupModal').modal('hide');
                        $('#groupForm')[0].reset();
                        fetchGroups();
                    }
                });
            });

            $(document).on('click', '.btn-edit', function() {
                let groupId = $(this).data('id');

                $.ajax({
                    url: '/api/admin/group/' + groupId,
                    method: 'GET',
                    success: function(data) {
                        $('#telegram_id').val(data.telegram_id);
                        $('#name').val(data.name);
                        $('#group_id').val(data.id);
                        $('#groupModalLabel').text('Edit Group');
                        $('#groupModal').modal('show');
                    }
                });
            });

            $(document).on('click', '.btn-delete', function() {
                let groupId = $(this).data('id');

                if (confirm('Are you sure to delete this group?')) {
                    $.ajax({
                        url: '/api/admin/group/' + groupId,
                        method: 'DELETE',
                        success: function() {
                            fetchGroups();
                        }
                    });
                }
            });

            $('#groupModal').on('hidden.bs.modal', function() {
                $('#groupForm')[0].reset();
                $('#group_id').val('');
                $('#groupModalLabel').text('Add Group');
            });
        });
    </script>
</body>

</html>