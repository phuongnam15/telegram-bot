<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phone Management</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h2>Phone Management</h2>
        <div class="mb-3">
            <h4>Tổng số phone: <span id="totalPhones">0</span></h4>
        </div>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#createPhoneModal">Thêm phone mới</button>

        <!-- Phone Table -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Phone Number</th>
                    </tr>
                </thead>
                <tbody id="phoneTableBody">
                    <!-- Phones will be populated here by jQuery -->
                </tbody>
            </table>
        </div>
        <nav>
            <ul class="pagination" id="pagination">
                <!-- Pagination links will be populated here by jQuery -->
            </ul>
        </nav>
    </div>

    <!-- Modal for adding new phones -->
    <div class="modal fade" id="createPhoneModal" tabindex="-1" role="dialog" aria-labelledby="createPhoneModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createPhoneModalLabel">Thêm phone mới</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createPhoneForm">
                        <div class="form-group">
                            <label for="phoneNumbers">Nhập số điện thoại (mỗi dòng một số, tối đa 500 dòng):</label>
                            <textarea class="form-control" id="phoneNumbers" rows="10" maxlength="5000" required></textarea>
                            <small class="form-text text-muted">Bạn đã nhập <span id="phoneCount">0</span> dòng.</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Lưu phone</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Fetch total phones on page load
            fetchTotalPhones();

            function fetchTotalPhones(page = 1) {
                $.ajax({
                    url: `/api/admin/phone?page=${page}`,
                    method: 'GET',
                    success: function(data) {
                        $('#totalPhones').text(data.data.total);

                        $('#phoneTableBody').empty();
                        data.data.data.forEach(function(phone) {
                            $('#phoneTableBody').append(`
                                <tr>
                                    <td>${phone.id}</td>
                                    <td>${phone.phone_number}</td>
                                </tr>
                            `);
                        });

                        updatePagination(data.data);
                    }
                });
            }

            // Update pagination
            function updatePagination(data) {
                $('#pagination').empty();

                if (data.prev_page_url) {
                    $('#pagination').append(`<li class="page-item"><a class="page-link" href="#" data-page="${data.current_page - 1}">&laquo; Previous</a></li>`);
                }

                if (data.current_page > 2) {
                    $('#pagination').append(`<li class="page-item"><a class="page-link" href="#" data-page="1">First</a></li>`);
                    if (data.current_page > 3) {
                        $('#pagination').append('<li class="page-item disabled"><a class="page-link" href="#">...</a></li>');
                    }
                }

                data.links.forEach(function(link, index) {
                    if (index === 0 || index === data.links.length - 1) return;

                    // Hiển thị trang hiện tại và các trang liền trước và liền sau
                    if ((index === data.current_page - 1) ||
                        (index === data.current_page) ||
                        (index === data.current_page + 1)) {
                        $('#pagination').append(`<li class="page-item ${link.active ? 'active' : ''}"><a class="page-link" href="#" data-page="${link.label}">${link.label}</a></li>`);
                    }

                    // Hiển thị dấu "..." nếu có các trang trước đó
                    if (index === 1 && data.current_page > 3) {
                        $('#pagination').append('<li class="page-item disabled"><a class="page-link" href="#">...</a></li>');
                    }

                    // Hiển thị dấu "..." nếu có các trang sau đó
                    if (index === data.links.length - 2 && data.current_page < data.last_page - 2) {
                        $('#pagination').append('<li class="page-item disabled"><a class="page-link" href="#">...</a></li>');
                    }
                });

                if (data.current_page < data.last_page - 1) {
                    if (data.current_page < data.last_page - 2) {
                        $('#pagination').append('<li class="page-item disabled"><a class="page-link" href="#">...</a></li>');
                    }
                    $('#pagination').append(`<li class="page-item"><a class="page-link" href="#" data-page="${data.last_page}">Last</a></li>`);
                }

                if (data.next_page_url) {
                    $('#pagination').append(`<li class="page-item"><a class="page-link" href="#" data-page="${data.current_page + 1}">Next &raquo;</a></li>`);
                }
            }


            // Handle pagination click
            $(document).on('click', '.page-link', function(e) {
                e.preventDefault();
                let page = $(this).data('page');
                fetchTotalPhones(page);
            });

            // Update phone count on textarea input
            $('#phoneNumbers').on('input', function() {
                let lineCount = $(this).val().split('\n').length;
                if (lineCount > 500) {
                    $(this).val($(this).val().split('\n').slice(0, 500).join('\n'));
                    lineCount = 500;
                }
                $('#phoneCount').text(lineCount);
            });

            // Save new phones
            $('#createPhoneForm').on('submit', function(e) {
                e.preventDefault();
                let phoneNumbers = $('#phoneNumbers').val().split('\n').filter(Boolean);
                $.ajax({
                    url: '/api/admin/phone',
                    method: 'POST',
                    data: {
                        phones: phoneNumbers
                    },
                    success: function(data) {

                        let message = data.data.message;

                        if (data.data.invalid_phones && data.data.invalid_phones.length > 0) {
                            message += '\n\nInvalid phones:\n' + data.data.invalid_phones.join('\n');
                        }

                        alert(message);

                        $('#createPhoneModal').modal('hide');
                        fetchTotalPhones();
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred: ' + xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>

</html>