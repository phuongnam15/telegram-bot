@extends("layouts.main")

@section("title", "Config Content")

@section("content")
<div class="container relative mx-auto rounded bg-white p-5">
    <form action="{{ url("/api/posts") }}" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
        @csrf
        <div class="flex gap-2 items-stretch h-[50px]">
            <div class="mb-4 flex-1">
                <input type="text" class="text-gray-500 text-sm h-full form-input w-full rounded border outline-none pl-3" id="name" name="name" required placeholder="name" />
            </div>
            <div class="mb-4">
                <select class="px-2 h-full form-select w-full rounded border text-sm text-gray-500 outline-none" id="type" name="type" required>
                    <option value="" class="hidden">type content</option>
                    <option value="text">Text</option>
                    <option value="photo">Image</option>
                    <option value="video">Video</option>
                </select>
            </div>
    
            <div class="mb-4">
                <select class="px-2 h-full form-select w-full rounded border text-sm text-gray-500 outline-none" id="kind" name="kind" required>
                    <option value="" class="hidden">topic content</option>
                    <option value="introduce">Welcome</option>
                    <option value="button">Click Button</option>
                    <option value="start">Start</option>
                    <option value="other">Other</option>
                </select>
            </div>
        </div>

        <div class="mb-4">
            <textarea class="form-input w-full rounded border px-3 py-2" id="content" name="content"></textarea>
        </div>

        <div class="mb-4">
            <label for="media" class="font-popi mb-4 text-gray-500 text-xs py-1 px-2 border-[1px] border-solid border-gray-200 rounded-lg">
                <input type="file" class="form-input w-full rounded border px-3 py-2 hidden" id="media" name="media" />
                <i class="fa-solid fa-image"></i>
                Upload media
            </label>
            <img id="preview" src="#" alt="Media preview" class="max-h-xs mt-2 hidden max-w-xs rounded" />
            <video id="previewVideo" controls class="max-h-xs mt-2 hidden max-w-xs rounded">
                <source id="videoSource" src="#" type="video/mp4" />
                Your browser does not support the video tag.
            </video>
        </div>

        <div class="mb-4">
            <select class="form-select w-full rounded border px-3 py-2 text-sm text-gray-500 outline-none" id="keyboardType" name="keyboardType">
                <option value="" class="hidden">type button</option>
                <option value="inline_keyboard">Inline keyboard</option>
                <option value="keyboard">Keyboard</option>
                
            </select>
        </div>
        <div id="buttonsContainer" class="mb-4">
            <!-- Dynamic buttons will be added here -->
        </div>
        <button type="button" id="addButton" class="mb-4 rounded  py-2 border border-gray-500 px-2 text-sm">
            More button
        </button>
        <button type="submit" class="rounded bg-gray-600 px-2 border border-gray-600 py-2 text-white text-sm">
            Create
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

    $(document).ready(function() {
        const botId = window.location.pathname.split('/').pop();

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
                            <input type="text" name="buttons[][text]" placeholder="text" class="form-input w-full border rounded py-1 px-3 text-sm outline-none">
                            <input type="text" name="buttons[][url]" placeholder="url (optional)" class="form-input w-full border rounded py-1 px-3 text-sm outline-none">
                            <input type="text" name="buttons[][callback_data]" placeholder="callback data (optional)" class="form-input w-full border rounded py-1 px-3 text-sm outline-none">
                            <button type="button" class="text-red-500 px-2 rounded remove-button"><i class="fa-solid fa-trash-can"></i></button>
                        </div>
                    `);
            } else if (type === 'keyboard') {
                $('#buttonsContainer').append(`
                        <div class="button-group mb-2 flex gap-2">
                            <input type="text" name="buttons[][text]" placeholder="text" class="form-input w-full border rounded py-1 px-3 outline-none text-sm text-gray-500">
                            <select name="buttons[][action]" class="form-select w-full border rounded py-1 px-3 outline-none text-sm text-gray-500">
                                <option value="" class="hidden">select action</option>
                                <option value="contact">request contact</option>
                                <option value="location">request location</option>
                            </select>
                            <button type="button" class="text-red-500 px-2 rounded remove-button"><i class="fa-solid fa-trash-can"></i></button>
                        </div>
                    `);
            } else if (type === 'inline_keyboard_phone_number') {
                $('#buttonsContainer').append(`
                        <div class="button-group mb-2 flex gap-2">
                            <input type="text" name="buttons[][text]" placeholder="Nội dung" class="form-input w-full border rounded py-2 px-3">
                            <input type="text" name="buttons[][url]" placeholder="URL (tuỳ chọn)" class="form-input w-full border rounded py-2 px-3">
                            <input type="text" name="buttons[][callback_data]" value="get_phone_number" readOnly class="form-input w-full border rounded py-2 px-3">
                            <button type="button" class="text-red-500 px-2 rounded remove-button"><i class="fa-solid fa-trash-can"></i></button>
                        </div>
                    `);
            }
        }

        function addButtonFields() {
            // Function to add more button fields based on the type of keyboard selected
            updateButtonOptions();
        }
        //submit
        const form = document.querySelector('form');

        form.addEventListener('submit', async (e) => {
            e.preventDefault(); // Ngăn chặn hành vi submit form mặc định
            const keyboard = [];

            // Lấy tất cả các nhóm nút
            document.querySelectorAll('.button-group').forEach((group) => {
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
                $('#keyboardType').val() === 'inline_keyboard_phone_number' ? {
                    inline_keyboard: keyboard,
                } : {
                    keyboard: keyboard,
                };

            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }

            const formData = new FormData();
            formData.append('bot_id', botId);
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
                document.querySelector('textarea[name="content"]').value,
            );
            formData.append('buttons', JSON.stringify(dataToSend));
            if (document.querySelector('input[name="media"]').files[0]) {
                formData.append(
                    'media',
                    document.querySelector('input[name="media"]').files[0],
                );
            }

            try {
                const response = await fetchClient('/api/admin/config', {
                    method: 'POST',
                    body: formData,
                });

                showNotification('Post created successfully', 'success');
            } catch (error) {
                console.error('Error:', error);
                showNotification(error, 'error');
            }
        });

        //remove button
        $(document).on('click', '.remove-button', function() {
            $(this).closest('.button-group').remove(); // Remove the button group from DOM
        });

        //watch list
        $('#watchList').click(function() {
            window.location.href = '{{ url("/") }}';
        });
    });
</script>
@endpush