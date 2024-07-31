@extends("layouts.main")

@section("title", "Update Content")

@section("content")
<div class="container relative mx-auto rounded bg-white p-5 shadow">
    <h2 class="mb-5 text-2xl font-bold text-gray-700">Update post</h2>
    <form action="{{ url("/api/posts") }}" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
        @csrf
        <div class="mb-4">
            <label for="name" class="mb-2 block font-bold text-gray-700">
                Name
            </label>
            <input type="text" class="form-input w-full rounded border px-3 py-2" id="name" name="name" required placeholder="vd: tuyen_nguoi_yeu" />
        </div>
        <div class="mb-4">
            <label for="type" class="mb-2 block font-bold text-gray-700">
                Type
            </label>
            <select class="form-select w-full rounded border px-3 py-2" id="type" name="type" required>
                <option value="">-- Select type --</option>
                <option value="text">Text</option>
                <option value="photo">Ảnh</option>
                <option value="video">Video</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="kind" class="mb-2 block font-bold text-gray-700">
                Kind
            </label>
            <select class="form-select w-full rounded border px-3 py-2" id="kind" name="kind" required>
                <option value="">-- Select kind --</option>
                <option value="introduce">Tin nhắn Chào Mừng</option>
                <option value="button">Click Button</option>
                <option value="start">Start</option>
                <option value="other">Khác</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="content" class="mb-2 block font-bold text-gray-700">
                Content
            </label>
            <textarea class="form-input w-full rounded border px-3 py-2" id="content" name="content"></textarea>
        </div>

        <div class="mb-4">
            <label for="media" class="mb-2 block font-bold text-gray-700">
                Media (Image/Video)
            </label>
            <input type="file" class="form-input w-full rounded border px-3 py-2" id="media" name="media" />
            <img id="preview" src="#" alt="Media preview" class="max-h-xs mt-2 hidden max-w-xs" />
            <video id="previewVideo" controls class="max-h-xs mt-2 hidden max-w-xs">
                <source id="videoSource" src="#" type="video/mp4" />
                Your browser does not support the video tag.
            </video>
        </div>

        <div class="mb-4">
            <label for="keyboardType" class="mb-2 block font-bold text-gray-700">
                Type button
            </label>
            <select class="form-select w-full rounded border px-3 py-2" id="keyboardType" name="keyboardType">
                <option value="">-- Select type button --</option>
                <option value="inline_keyboard">Đi kèm bài viết</option>
                <option value="inline_keyboard_phone_number">
                    Đi kèm bài viết (để lấy SĐT)
                </option>
            </select>
        </div>
        <div id="buttonsContainer" class="mb-4">
            <!-- Dynamic buttons will be added here -->
        </div>
        <button type="button" id="addButton" class="mb-4 rounded bg-blue-500 px-4 py-2 text-white">
            More button
        </button>
        <button type="submit" class="rounded bg-green-500 px-4 py-2 text-white">
            Update
        </button>
    </form>
</div>
@endsection
@push("scripts")
<script>
    CKEDITOR.replace('content', {
        enterMode: CKEDITOR.ENTER_BR,
        shiftEnterMode: CKEDITOR.ENTER_P,
    });

    //SHOW IMAGE OR VIDEO
    document
        .getElementById('media')
        .addEventListener('change', function(event) {
            var file = event.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var preview = document.getElementById('preview');
                    var previewVideo =
                        document.getElementById('previewVideo');
                    var videoSource =
                        document.getElementById('videoSource');

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

    $(document).ready(async () => {
        //CHECK TYPE TO SHOW MEDIA INPUT
        $('#type').change(function() {
            var selectedType = $(this).val();
            if (selectedType === 'text') {
                $('#media').closest('.mb-3').addClass('d-none');
            } else {
                $('#media').closest('.mb-3').removeClass('d-none');
            }
        });

        $('#keyboardType').change(function() {
            updateButtonOptions2();
        });

        $('#addButton').click(function() {
            addButtonFields();
        });

        let typeValue;

        //ADD BUTTON WITH DATA
        function updateButtonOptions(buttonsData) {
            const type = $('#keyboardType').val();

            $('#buttonsContainer').empty(); // Clear previous inputs

            typeValue = type;

            if (type === 'inline_keyboard') {
                buttonsData.inline_keyboard.forEach((button) => {
                    $('#buttonsContainer').append(`
                            <div class="button-group mb-2 flex gap-2">
                                <input type="text" name="buttons[][text]" placeholder="Nội dung" class="form-input w-full border rounded py-2 px-3" value="${button[0].text}">
                                <input type="text" name="buttons[][url]" placeholder="URL (tuỳ chọn)" class="form-input w-full border rounded py-2 px-3" value="${button[0].url || ''}">
                                <input type="text" name="buttons[][callback_data]" placeholder="Dữ liệu gửi đi (tuỳ chọn)" class="form-input w-full border rounded py-2 px-3" value="${button[0].callback_data || ''}">
                                <button type="button" class="bg-red-500 text-white px-2 rounded remove-button">Xóa</button>
                            </div>
                        `);
                });
            } else if (type === 'keyboard') {
                buttonsData.keyboard.forEach((button) => {
                    $('#buttonsContainer').append(`
                            <div class="button-group mb-2 flex gap-2">
                                <input type="text" name="buttons[][text]" placeholder="Nội dung" class="form-input w-full border rounded py-2 px-3" value="${button[0].text}>
                                <select name="buttons[][action]" class="form-select w-full border rounded py-2 px-3">
                                    <option value="">Chọn hành động</option>
                                    <option value="contact" ${button[0].action === 'contact' ? 'selected' : ''}>Yêu cầu liên hệ</option>
                                    <option value="location" ${button[0].action === 'location' ? 'selected' : ''}>Yêu cầu vị trí</option>
                                </select>
                                <button type="button" class="bg-red-500 text-white px-2 rounded remove-button">Xóa</button>
                            </div>
                        `);
                });
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

        //ADD BLANK BUTTON
        function updateButtonOptions2() {
            const type = $('#keyboardType').val();

            if (typeValue !== type) {
                console.log('Type changed');
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

        //ADD BUTTON
        function addButtonFields() {
            updateButtonOptions2();
        }

        //GET CONTENT BY ID
        var pathArray = window.location.pathname.split('/');
        var id = pathArray[pathArray.length - 1];
      
        try {
            const response = await fetchClient(
                `/api/admin/detail/${id}`,
            );

            $('#name').val(response.name);
            $('#type').val(response.type);
            $('#kind').val(response.kind);

            if (CKEDITOR.instances['content']) {
                CKEDITOR.instances['content'].setData(response.content);
            }
            if (response.type !== 'text') {
                $('#preview').attr('src', response.media);
                $('#preview').closest('.mb-3').removeClass('d-none');
                $('#preview').show();
            }

            if (JSON.parse(response.buttons)?.inline_keyboard) {
                $('#keyboardType').val('inline_keyboard');
            } else {
                $('#keyboardType').val('keyboard');
            }

            updateButtonOptions(JSON.parse(response.buttons));
        } catch (error) {
            console.error('Error:', error);
        }

        //UPDATE CONTENT
        const form = document.querySelector('form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault(); // Ngăn chặn hành vi submit form mặc định
            const keyboard = [];

            // Lấy tất cả các nhóm nút
            document
                .querySelectorAll('.button-group')
                .forEach((group) => {
                    const text = group.querySelector(
                        '[name="buttons[][text]"]',
                    ).value;
                    const action = group.querySelector(
                        '[name="buttons[][action]"]',
                    )?.value;
                    const url = group.querySelector(
                        '[name="buttons[][url]"]',
                    )?.value;
                    const callBackData = group.querySelector(
                        '[name="buttons[][callback_data]"]',
                    )?.value;
                    const switchInlineQuery = group.querySelector(
                        '[name="buttons[][switch_inline_query]"]',
                    )?.value;

                    let button = {
                        text: text,
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
            const dataToSend =
                $('#keyboardType').val() === 'inline_keyboard' ||
                $('#keyboardType').val() ===
                'inline_keyboard_phone_number' ?
                {
                    inline_keyboard: keyboard,
                } :
                {
                    keyboard: keyboard,
                };

            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }

            const formData = new FormData();
            formData.append(
                'name',
                document.querySelector('input[name="name"]').value,
            );
            formData.append(
                'type',
                document.querySelector('select[name="type"]').value,
            );
            formData.append(
                'kind',
                document.querySelector('select[name="kind"]').value,
            );
            formData.append(
                'content',
                document.querySelector('textarea[name="content"]')
                .value,
            );
            formData.append('buttons', JSON.stringify(dataToSend));
            if (
                document.querySelector('input[name="media"]').files[0]
            ) {
                formData.append(
                    'media',
                    document.querySelector('input[name="media"]')
                    .files[0],
                );
            }

            try {
                const response = await fetchClient(
                    `/api/admin/update/${id}`, {
                        method: 'POST',
                        body: formData,
                    },
                );

                showNotification('Post updated successfully', 'success');
            } catch (error) {
                console.error('Error:', error);
            }
        });
    });

    //watch list
    $('#watchList').click(function() {
        window.location.href = '{{ url("/") }}';
    });

    //REMOVE BUTTON
    $(document).on('click', '.remove-button', function() {
        $(this).closest('.button-group').remove(); // Remove the button group from DOM
    });
</script>
@endpush