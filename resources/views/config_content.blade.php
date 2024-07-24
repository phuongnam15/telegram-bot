<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo bài viết</title>
    <script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>

<body class="bg-gray-100">
    <div class="container mx-auto my-5 relative p-5 bg-white shadow rounded">
        <button class="absolute right-3 top-3 bg-blue-500 text-white px-4 py-2 rounded" id="watchList">Xem danh sách</button>
        <h2 class="text-2xl font-bold mb-5">Tạo bài viết mới</h2>
        <form action="{{ url('/api/posts') }}" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">Tên bài viết</label>
                <input type="text" class="form-input w-full border rounded py-2 px-3" id="name" name="name" required placeholder="vd: tuyen_nguoi_yeu">
            </div>
            <div class="mb-4">
                <label for="type" class="block text-gray-700 font-bold mb-2">Hình thức bài viết</label>
                <select class="form-select w-full border rounded py-2 px-3" id="type" name="type" required>
                    <option value="">-- Chọn hình thức bài viết --</option>
                    <option value="text">Text</option>
                    <option value="photo">Ảnh</option>
                    <option value="video">Video</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="kind" class="block text-gray-700 font-bold mb-2">Loại nội dung</label>
                <select class="form-select w-full border rounded py-2 px-3" id="kind" name="kind" required>
                    <option value="">-- Chọn loại nội dung --</option>
                    <option value="introduce">Tin nhắn Chào Mừng</option>
                    <option value="button">Click Button</option>
                    <option value="start">Start</option>
                    <option value="other">Khác</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="content" class="block text-gray-700 font-bold mb-2">Nội dung</label>
                <textarea class="form-input w-full border rounded py-2 px-3" id="content" name="content"></textarea>
            </div>

            <div class="mb-4">
                <label for="media" class="block text-gray-700 font-bold mb-2">Media (Ảnh/Video)</label>
                <input type="file" class="form-input w-full border rounded py-2 px-3" id="media" name="media">
                <img id="preview" src="#" alt="Media preview" class="mt-2 max-w-xs max-h-xs hidden">
                <video id="previewVideo" controls class="mt-2 max-w-xs max-h-xs hidden">
                    <source id="videoSource" src="#" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>

            <div class="mb-4">
                <label for="keyboardType" class="block text-gray-700 font-bold mb-2">Loại Button</label>
                <select class="form-select w-full border rounded py-2 px-3" id="keyboardType" name="keyboardType">
                    <option value="">-- Chọn loại Button --</option>
                    <option value="inline_keyboard">Đi kèm bài viết</option>
                    <option value="inline_keyboard_phone_number">Đi kèm bài viết (để lấy SĐT)</option>
                </select>
            </div>
            <div id="buttonsContainer" class="mb-4">
                <!-- Dynamic buttons will be added here -->
            </div>
            <button type="button" id="addButton" class="bg-blue-500 text-white px-4 py-2 rounded mb-4">Thêm Button</button>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Tạo</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        CKEDITOR.replace('content', {
            enterMode: CKEDITOR.ENTER_BR,
            shiftEnterMode: CKEDITOR.ENTER_P
        });

        //SHOW IMAGE OR VIDEO
        document.getElementById('media').addEventListener('change', function(event) {
            var file = event.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var preview = document.getElementById('preview');
                    var previewVideo = document.getElementById('previewVideo');
                    var videoSource = document.getElementById('videoSource');

                    if (file.type.startsWith('image/')) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                        previewVideo.classList.add('hidden');
                    } else if (file.type.startsWith('video/')) {
                        videoSource.src = e.target.result;
                        previewVideo.load(); // Nạp lại video sau khi thay đổi source
                        previewVideo.classList.remove('hidden');
                        preview.classList.add('hidden');
                    }


                };
                reader.readAsDataURL(file);
            }
        });

        $(document).ready(function() {
            $('#type').change(function() {
                var selectedType = $(this).val();
                if (selectedType === 'text') {
                    $('#media').closest('.mb-4').hide(); // Ẩn input media
                } else {
                    $('#media').closest('.mb-4').show(); // Hiện input media nếu không phải là 'text'
                }
            });

            // Trigger change event on page load in case the 'text' is pre-selected
            $('#type').trigger('change');

            //handle keyboard
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
                        <div class="button-group mb-2 flex gap-2">
                            <input type="text" name="buttons[][text]" placeholder="Nội dung" class="form-input w-full border rounded py-2 px-3">
                            <input type="text" name="buttons[][url]" placeholder="URL (tuỳ chọn)" class="form-input w-full border rounded py-2 px-3">
                            <input type="text" name="buttons[][callback_data]" placeholder="Dữ liệu gửi đi (tuỳ chọn)" class="form-input w-full border rounded py-2 px-3">
                            <button type="button" class="bg-red-500 text-white px-2 rounded remove-button">Xóa</button>
                        </div>
                    `);
                } else if (type === 'keyboard') {
                    $('#buttonsContainer').append(`
                        <div class="button-group mb-2 flex gap-2">
                            <input type="text" name="buttons[][text]" placeholder="Nội dung" class="form-input w-full border rounded py-2 px-3">
                            <select name="buttons[][action]" class="form-select w-full border rounded py-2 px-3">
                                <option value="">Chọn hành động</option>
                                <option value="contact">Yêu cầu liên hệ</option>
                                <option value="location">Yêu cầu vị trí</option>
                            </select>
                            <button type="button" class="bg-red-500 text-white px-2 rounded remove-button">Xóa</button>
                        </div>
                    `);
                } else if (type === 'inline_keyboard_phone_number') {
                    $('#buttonsContainer').append(`
                        <div class="button-group mb-2 flex gap-2">
                            <input type="text" name="buttons[][text]" placeholder="Nội dung" class="form-input w-full border rounded py-2 px-3">
                            <input type="text" name="buttons[][url]" placeholder="URL (tuỳ chọn)" class="form-input w-full border rounded py-2 px-3">
                            <input type="text" name="buttons[][callback_data]" value="get_phone_number" readOnly class="form-input w-full border rounded py-2 px-3">
                            <button type="button" class="bg-red-500 text-white px-2 rounded remove-button">Xóa</button>
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

        form.addEventListener('submit', async (e) => {
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
            const dataToSend = $('#keyboardType').val() === 'inline_keyboard' || $('#keyboardType').val() === 'inline_keyboard_phone_number' ? {
                inline_keyboard: keyboard
            } : {
                keyboard: keyboard
            };

            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }

            const formData = new FormData();
            formData.append('name', document.querySelector('input[name="name"]').value);
            formData.append('type', document.querySelector('select[name="type"]').value);
            formData.append('kind', document.querySelector('select[name="kind"]').value);
            formData.append('content', document.querySelector('textarea[name="content"]').value);
            formData.append('buttons', JSON.stringify(dataToSend));
            if (document.querySelector('input[name="media"]').files[0]) {
                formData.append('media', document.querySelector('input[name="media"]').files[0]);
            }

            try{
                const response = await fetchClient('/api/admin/config', { method: 'POST', body: formData });

                if(response.status === 200){
                    alert('Post created successfully!');
                }
                
            }catch (error) {
                console.error('Error:', error);
                alert('Error creating post');
            }
        });

        //remove button
        $(document).on('click', '.remove-button', function() {
            $(this).closest('.button-group').remove(); // Remove the button group from DOM
        });

        //watch list
        $('#watchList').click(function() {
            window.location.href = "{{ url('/') }}";
        });
    </script>
</body>

</html>