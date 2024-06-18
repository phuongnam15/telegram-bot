<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
    <style>
        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <h2>Create a New Post</h2>
        <form action="{{ url('/api/posts') }}" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
            @csrf
            <div class="mb-3">
                <label for="type" class="form-label">Type of Post</label>
                <select class="form-select" id="type" name="type" required>
                    <option value="">-- Select Type --</option>
                    <option value="text">Text</option>
                    <option value="photo">Photo</option>
                    <option value="video">Video</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="kind" class="form-label">Kind of Post</label>
                <select class="form-select" id="kind" name="kind" required>
                    <option value="">-- Select Kind --</option>
                    <option value="introduce">Introduce</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" id="content" name="content"></textarea>
            </div>

            <div class="mb-3">
                <label for="media" class="form-label">Media (Photo/Video)</label>
                <input type="file" class="form-control" id="media" name="media">
            </div>

            <div class="mb-3">
                <label for="keyboardType" class="form-label">Keyboard Type</label>
                <select class="form-select" id="keyboardType" name="keyboardType">
                    <option value="">-- Select Keyboard Type --</option>
                    <option value="inline_keyboard">Inline Keyboard</option>
                    <option value="keyboard">Keyboard</option>
                </select>
            </div>
            <div id="buttonsContainer" class="mb-3">
                <!-- Dynamic buttons will be added here -->
            </div>
            <button type="button" id="addButton" class="btn btn-primary">Add Button</button>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        CKEDITOR.replace('content', {
            enterMode: CKEDITOR.ENTER_BR,
            shiftEnterMode: CKEDITOR.ENTER_P
        });


        $(document).ready(function() {
            $('#keyboardType').change(function() {
                updateButtonOptions();
            });

            $('#addButton').click(function() {
                addButtonFields();
            });
            let typeValue;

            function updateButtonOptions() {
                const type = $('#keyboardType').val();

                if (typeValue !== type) {
                    $('#buttonsContainer').empty(); // Clear previous inputs
                }

                typeValue = type;

                if (type === 'inline_keyboard') {
                    $('#buttonsContainer').append(`
                        <div class="button-group mb-2 flex">
                            <input type="text" name="buttons[][text]" placeholder="Button Text" class="form-control mb-1">
                            <input type="text" name="buttons[][url]" placeholder="URL (optional)" class="form-control mb-1">
                            <input type="text" name="buttons[][callback_data]" placeholder="Callback Data (optional)" class="form-control mb-1">
                            <input type="text" name="buttons[][switch_inline_query]" placeholder="Switch Inline Query (optional)" class="form-control mb-1">
                        </div>
                    `);
                } else if (type === 'keyboard') {
                    $('#buttonsContainer').append(`
                        <div class="button-group mb-2 flex">
                            <input type="text" name="buttons[][text]" placeholder="Button Text" class="form-control mb-1">
                            <select name="buttons[][action]" class="form-select mb-1">
                                <option value="">Select Action</option>
                                <option value="contact">Request Contact</option>
                                <option value="location">Request Location</option>
                            </select>
                        </div>
                    `);
                }
            }

            function addButtonFields() {
                // Function to add more button fields based on the type of keyboard selected
                updateButtonOptions();
            }
        });
        //submit
        const form = document.querySelector('form');

        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Ngăn chặn hành vi submit form mặc định
            const keyboard = [];

            // Lấy tất cả các nhóm nút
            document.querySelectorAll('.button-group').forEach(group => {
                const text = group.querySelector('[name="buttons[][text]"]').value;
                const action = group.querySelector('[name="buttons[][action]"]')?.value;
                const url = group.querySelector('[name="buttons[][url]"]')?.value;
                const callBackData = group.querySelector('[name="buttons[][callback_data]"]')?.value;
                const switchInlineQuery = group.querySelector('[name="buttons[][switch_inline_query]"]')?.value;

                let button = {
                    text: text
                };

                // Thêm các tùy chọn vào nút dựa trên loại action được chọn
                if (action === 'contact') {
                    button.request_contact = true;
                } else if (action === 'location') {
                    button.request_location = true;
                }

                if (url) {
                    button.url = url;
                }

                if (callBackData) {
                    button.callback_data = callBackData;
                }

                if (switchInlineQuery) {
                    button.switch_inline_query = switchInlineQuery;
                }

                // Đưa nút vào một hàng mới của keyboard
                keyboard.push([button]);
            });

            // Đóng gói dữ liệu nút thành đối tượng gửi đi
            const dataToSend = $('#keyboardType').val() === 'inline_keyboard' ? {
                inline_keyboard: keyboard
            } : {
                keyboard: keyboard
            };

            console.log('Data to send:', dataToSend);

            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }

            const formData = new FormData();
            formData.append('type', document.querySelector('select[name="type"]').value);
            formData.append('kind', document.querySelector('select[name="kind"]').value);
            formData.append('content', document.querySelector('textarea[name="content"]').value);
            if (document.querySelector('input[name="media"]').files[0])
                formData.append('media', document.querySelector('input[name="media"]').files[0]);

            // Thêm buttons đã định dạng
            formData.append('buttons', JSON.stringify(dataToSend));
            const url = "{{ url('/api/admin/config') }}"; // Đường dẫn tới API endpoint

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')  // CSRF token để bảo mật
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    alert('Post created successfully!');
                })
                .catch((error) => {
                    console.error('Error:', error);
                    alert('Error creating post');
                });
        });
    </script>
</body>

</html>