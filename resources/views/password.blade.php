<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Password Management</title>
        <link
            href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
            rel="stylesheet"
        />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </head>

    <body>
        <div class="container mt-5">
            <button
                class="btn btn-info mb-3"
                onclick="window.location.href='/'"
            >
                &laquo Home
            </button>
            <h2>Password Management</h2>
            <div class="mb-3">
                <h4>
                    Tổng số password:
                    <span id="totalPhones">0</span>
                </h4>
            </div>

            <!-- Password Table -->
            <div class="table-responsive">
                <table class="table-bordered table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Password</th>
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

        <script>
            $(document).ready(function () {
                // Fetch total phones on page load
                fetchTotalPhones();

                function fetchTotalPhones(page = 1) {
                    $.ajax({
                        url: `/api/admin/password?page=${page}`,
                        method: 'GET',
                        success: function (data) {
                            $('#totalPhones').text(data.data.total);

                            $('#phoneTableBody').empty();
                            data.data.data.forEach(function (password) {
                                $('#phoneTableBody').append(`
                                <tr>
                                    <td>${password.id}</td>
                                    <td>${password.password}</td>
                                </tr>
                            `);
                            });

                            updatePagination(data.data);
                        },
                    });
                }

                // Update pagination
                function updatePagination(data) {
                    $('#pagination').empty();

                    if (data.prev_page_url) {
                        $('#pagination').append(
                            `<li class="page-item"><a class="page-link" href="#" data-page="${data.current_page - 1}">&laquo; Previous</a></li>`,
                        );
                    }

                    if (data.current_page > 2) {
                        $('#pagination').append(
                            `<li class="page-item"><a class="page-link" href="#" data-page="1">First</a></li>`,
                        );
                        if (data.current_page > 3) {
                            $('#pagination').append(
                                '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>',
                            );
                        }
                    }

                    data.links.forEach(function (link, index) {
                        if (index === 0 || index === data.links.length - 1)
                            return;

                        // Hiển thị trang hiện tại và các trang liền trước và liền sau
                        if (
                            index === data.current_page - 1 ||
                            index === data.current_page ||
                            index === data.current_page + 1
                        ) {
                            $('#pagination').append(
                                `<li class="page-item ${link.active ? 'active' : ''}"><a class="page-link" href="#" data-page="${link.label}">${link.label}</a></li>`,
                            );
                        }

                        // Hiển thị dấu "..." nếu có các trang trước đó
                        if (index === 1 && data.current_page > 3) {
                            $('#pagination').append(
                                '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>',
                            );
                        }

                        // Hiển thị dấu "..." nếu có các trang sau đó
                        if (
                            index === data.links.length - 2 &&
                            data.current_page < data.last_page - 2
                        ) {
                            $('#pagination').append(
                                '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>',
                            );
                        }
                    });

                    if (data.current_page < data.last_page - 1) {
                        if (data.current_page < data.last_page - 2) {
                            $('#pagination').append(
                                '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>',
                            );
                        }
                        $('#pagination').append(
                            `<li class="page-item"><a class="page-link" href="#" data-page="${data.last_page}">Last</a></li>`,
                        );
                    }

                    if (data.next_page_url) {
                        $('#pagination').append(
                            `<li class="page-item"><a class="page-link" href="#" data-page="${data.current_page + 1}">Next &raquo;</a></li>`,
                        );
                    }
                }

                // Handle pagination click
                $(document).on('click', '.page-link', function (e) {
                    e.preventDefault();
                    let page = $(this).data('page');
                    fetchTotalPhones(page);
                });
            });
        </script>
    </body>
</html>
