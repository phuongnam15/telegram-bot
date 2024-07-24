<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Management</title>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
</head>

<body>
    <div class="container mx-auto mt-5">
        <button class="mb-3 text-black" onclick="window.location.href='/'">
            &laquo Home
        </button>
        <h2 class="mb-4 text-2xl font-bold">Group Management</h2>
        <button class="bg-blue-500 text-white py-2 px-4 rounded mb-3" data-toggle="modal" data-target="#groupModal">Add Group</button>
        <table class="min-w-full bg-white border border-gray-300" id="groupTable">
            <thead>
                <tr class="w-full bg-gray-200">
                    <th class="py-2 px-4 border-b">Telegram ID</th>
                    <th class="py-2 px-4 border-b">Name</th>
                    <th class="py-2 px-4 border-b">Created At</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Rows will be added by jQuery -->
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="fixed z-10 inset-0 overflow-y-auto hidden" id="groupModal" tabindex="-1" role="dialog" aria-labelledby="groupModalLabel" aria-hidden="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-gray-200 px-3 py-2 sm:px-6 flex flex-row-reverse">
                    <button type="button" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700 ml-4" data-dismiss="modal">
                        <span aria-hidden="true" class="text-[20px]">&times;</span>
                    </button>
                </div>
                <div class="bg-white px-4 pt-3 pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="w-full">
                            <form id="groupForm">
                                <div class="mb-4">
                                    <label for="telegram_id" class="text-gray-700">ID</label>
                                    <input type="text" class="border-[1px] border-solid border-gray-300 outline-none py-1 px-2 rounded w-full bg-gray-100 focus:ring-1 focus:ring-blue-300 focus:border-white" id="telegram_id" name="telegram_id" required>
                                </div>
                                <input type="hidden" id="group_id">
                                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            const fetchGroups = async () => {
                try {
                    const response = await fetchClient('/api/admin/group', {
                        method: 'GET'
                    });

                    $('#groupTable tbody').empty();
                    response.forEach(function(group) {
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
            }

            fetchGroups();

            window.deleteGroup = async (id) => {
                if (confirm('Are you sure you want to delete this group?')) {
                    try {
                        const response = await fetchClient(`/api/admin/group/${id}`, {
                            method: 'DELETE'
                        });
                        fetchGroups();
                    } catch (error) {
                        console.log(error);
                    }
                }
            }

            $('#groupModal').on('hidden.bs.modal', function() {
                $('#groupForm')[0].reset();
                $('#group_id').val('');
                $('#groupModalLabel').text('Add Group');
            });
        });
    </script>
</body>

</html>