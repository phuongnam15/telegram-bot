@extends("layouts.main")

@section("title", "Group Page")

@section("content")
    <div class="container mt-5 px-5">
        <h2 class="mb-4 text-[23px] font-bold text-gray-700">
            Group Management
        </h2>
        <button
            class="mb-3 rounded bg-gray-400 px-4 py-2 font-bold text-white"
            data-toggle="modal"
            data-target="#groupModal"
        >
            Add Group
        </button>
        <table
            class="min-w-full border border-gray-300 bg-white"
            id="groupTable"
        >
            <thead>
                <tr class="w-full bg-gray-200">
                    <th class="border-b px-4 py-2">Telegram ID</th>
                    <th class="border-b px-4 py-2">Name</th>
                    <th class="border-b px-4 py-2">Created At</th>
                    <th class="border-b px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Rows will be added by jQuery -->
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div
        class="fixed inset-0 z-10 hidden overflow-y-auto"
        id="groupModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="groupModalLabel"
        aria-hidden="true"
    >
        <div
            class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0"
        >
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span
                class="hidden sm:inline-block sm:h-screen sm:align-middle"
                aria-hidden="true"
            >
                &#8203;
            </span>
            <div
                class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle"
            >
                <div
                    class="flex flex-row-reverse bg-gray-200 px-3 py-2 sm:px-6"
                >
                    <button
                        type="button"
                        class="ml-4 text-gray-500 hover:text-gray-700 focus:text-gray-700 focus:outline-none"
                        data-dismiss="modal"
                    >
                        <span aria-hidden="true" class="text-[20px]">
                            &times;
                        </span>
                    </button>
                </div>
                <div class="bg-white px-4 pb-4 pt-3">
                    <div class="sm:flex sm:items-start">
                        <div class="w-full">
                            <form id="groupForm">
                                <div class="mb-4">
                                    <label
                                        for="telegram_id"
                                        class="text-gray-700"
                                    >
                                        ID
                                    </label>
                                    <input
                                        type="text"
                                        class="w-full rounded border-[1px] border-solid border-gray-300 bg-gray-100 px-2 py-1 outline-none focus:border-white focus:ring-1 focus:ring-blue-300"
                                        id="telegram_id"
                                        name="telegram_id"
                                        required
                                    />
                                </div>
                                <div class="mb-4">
                                    <select
                                        id="botList"
                                        class="text-sm outline-none"
                                    ></select>
                                </div>
                                <input type="hidden" id="group_id" />
                                <button
                                    type="submit"
                                    class="float-end rounded bg-blue-500 px-4 py-2 text-white"
                                >
                                    Save
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("scripts")
    <script>
        $(document).ready(function () {
            const fetchGroups = async () => {
                try {
                    const response = await fetchClient('/api/admin/group', {
                        method: 'GET',
                    });

                    $('#groupTable tbody').empty();
                    response.forEach(function (group) {
                        $('#groupTable tbody').append(`
                            <tr>
                                <td class="py-2 border text-center">${group.telegram_id}</td>
                                <td class="py-2 border text-center">${group.name}</td>
                                <td class="py-2 border text-center">${group.created_at}</td>
                                <td class="py-2 border text-center">
                                    <button class="bg-red-500 text-white px-2 py-1 rounded btn-delete" onClick="deleteGroup(${group.id})">Delete</button>
                                </td>
                            </tr>
                        `);
                    });
                } catch (error) {
                    console.log(error);
                }
            };
            const fetchBots = async () => {
                try {
                    const response = await fetchClient('/api/admin/bot');
                    let botListHTML = '';
                    response.forEach((bot) => {
                        botListHTML += `<option value="${bot.token}">${bot.name}</option>`;
                    });
                    document.getElementById('botList').innerHTML = botListHTML;
                } catch (error) {
                    console.error('Error:', error);
                }
            };

            fetchBots();
            fetchGroups();

            window.deleteGroup = async (id) => {
                if (confirm('Are you sure you want to delete this group?')) {
                    try {
                        const response = await fetchClient(
                            `/api/admin/group/${id}`,
                            {
                                method: 'DELETE',
                            },
                        );
                        fetchGroups();
                    } catch (error) {
                        console.log(error);
                    }
                }
            };

            $('#groupForm').on('submit', async (e) => {
                e.preventDefault();

                const formData = new FormData();
                formData.append('telegram_id', $('#telegram_id').val());
                formData.append('bot_token', $('#botList').val());

                try {
                    const response = await fetchClient('/api/admin/group', {
                        method: 'POST',
                        body: formData,
                    });

                    $('#groupModal').modal('hide');
                    fetchGroups();
                } catch (error) {
                    console.log(error);
                }
            });

            $('#groupModal').on('hidden.bs.modal', function () {
                $('#groupForm')[0].reset();
                $('#group_id').val('');
                $('#groupModalLabel').text('Add Group');
            });
        });
    </script>
@endpush
