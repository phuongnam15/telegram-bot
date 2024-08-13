@extends("layouts.main")

@section("title", "Bot Coin - User")

@section("content")
<div class="container mt-4">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400" id="listUserTable">
        <thead class="text-xs text-gray-700 uppercase bg-[#2a2d35] dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Name
                </th>
                <th scope="col" class="px-6 py-3 text-center">
                    Status
                </th>
                <th scope="col" class="px-6 py-3 text-center">
                    Risk Tolerance
                </th>
                <th scope="col" class="px-6 py-3 text-center">
                    Expired at
                </th>
                <th scope="col" class="px-6 py-3 text-center">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<input type="text" id="pivotId" class="hidden">
<!-- Update User Modal -->
<div id="updateUserModal" tabindex="-1" class="fixed inset-0 z-50 hidden overflow-y-auto overflow-x-hidden bg-gray-800 bg-opacity-75">
    <div class="relative w-full max-w-md mx-auto mt-20">
        <div class="bg-[#2a2d35] text-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-gray-700">
                <h3 class="text-xl font-medium">Update User Information</h3>
            </div>
            <div class="px-6 py-4">
                <form id="updateUserForm">
                    <div class="mb-4">
                        <label for="riskTolerance" class="block mb-2 text-sm">Risk Tolerance</label> 
                        <input type="text" id="riskTolerance" name="riskTolerance" class="w-full px-3 py-2 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="apiKey" class="block mb-2 text-sm">API key</label>
                        <input type="text" id="apiKey" name="apiKey" class="w-full px-3 py-2 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="secretKey" class="block mb-2 text-sm">Secret key</label>
                        <input type="text" id="secretKey" name="secretKey" class="w-full px-3 py-2 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="passphrase" class="block mb-2 text-sm">Passphrase</label>
                        <input type="text" id="passphrase" name="passphrase" class="w-full px-3 py-2 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" class="px-4 py-2 bg-gray-600 rounded-lg hover:bg-gray-700" onclick="closeUpdateUserModal()">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 rounded-lg hover:bg-blue-700">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Activate User Modal -->
<div id="activateUserModal" tabindex="-1" class="fixed inset-0 z-50 hidden overflow-y-auto overflow-x-hidden bg-gray-800 bg-opacity-75">
    <div class="relative w-full max-w-md mx-auto mt-20">
        <div class="bg-[#2a2d35] text-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-gray-700">
                <h3 class="text-xl font-medium">Activate User</h3>
            </div>
            <div class="px-6 py-4">
                <form id="activateUserForm">
                    <div class="mb-4">
                        <label for="activationMonths" class="block mb-2 text-sm">Number of Months</label>
                        <input type="number" id="activationMonths" name="months" min="1" max="12" class="w-full px-3 py-2 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" class="px-4 py-2 bg-gray-600 rounded-lg hover:bg-gray-700" onclick="closeActivateUserModal()">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 rounded-lg hover:bg-green-700">Activate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push("scripts")
<script>
    const botId = window.location.pathname.split('/').pop();
    const getListUser = async () => {
        try {
            const response = await fetchClient(`/api/admin/bot/user?bot_id=${botId}`, {
                method: 'GET',
            });

            const data = response.data;

            console.log(data);

            $('#listUserTable tbody').empty();

            data.forEach(user => {
                $('#listUserTable tbody').append(`
                    <tr class="bg-[#1e2026] border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                            <img class="w-10 h-10 rounded-full" src="${user.avatar}" alt="Jese image">
                            <div class="ps-3">
                                <div class="text-base font-semibold">${user.firstname + user.lastname}</div>
                                <div class="font-normal text-gray-500">@${user.username}</div>
                            </div>
                        </th>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center">
                                <div class="h-2.5 w-2.5 rounded-full ${user.pivot.is_actived ? "bg-green-500" : "bg-red-500"} me-2"></div> ${user.pivot.is_actived ? "Active" : "Inactive"}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">${user.pivot.risk_tolerance ?? ""}</td>
                        <td class="px-6 py-4 text-center">${user.pivot.expired_at ? formatDate(user.pivot.expired_at) : ""}</td>
                        <td class="px-6 py-4 space-x-2 text-center">
                            <a href="#" class="font-medium text-blue-600 hover:underline" onclick="openUpdateUserModal('${user.pivot.id}', '${user.pivot.risk_tolerance ?? ""}', '${user.pivot.api_key ?? ""}', '${user.pivot.secret_key ?? ""}', '${user.pivot.passphrase ?? ""}')">Edit</a>
                            ${user.pivot.is_actived === "0" ? 
                                `<a href="#" class="font-medium text-green-600 hover:underline" onclick="openActivateUserModal('${user.pivot.id}')">Active</a>`
                            : 
                                "" 
                            }
                        </td>
                    </tr>`)
            });

        } catch (error) {
            console.log(error);
        }
    }


    const openUpdateUserModal = (pivotId, riskTolerance, apiKey, secretKey, passphrase) => {
        $('#pivotId').val(pivotId);
        $('#updateUserModal #riskTolerance').val(riskTolerance);
        $('#updateUserModal #apiKey').val(apiKey);
        $('#updateUserModal #secretKey').val(secretKey);
        $('#updateUserModal #passphrase').val(passphrase);
        $('#updateUserModal').removeClass('hidden');
    };

    const closeUpdateUserModal = () => {
        $('#updateUserModal').addClass('hidden');
    };

    const openActivateUserModal = (pivotId) => {
        $('#pivotId').val(pivotId);
        $('#activateUserModal').removeClass('hidden');
    };

    const closeActivateUserModal = () => {
        $('#activateUserModal').addClass('hidden');
    };

    $('#updateUserForm').on('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append('risk_tolerance', $('#riskTolerance').val());
        formData.append('api_key', $('#apiKey').val());
        formData.append('secret_key', $('#secretKey').val());
        formData.append('passphrase', $('#passphrase').val());
        formData.append('id', $('#pivotId').val());

        try{
            const response = await fetchClient(`/api/admin/bot/user`, {
                method: 'POST',
                body: formData
            });

            closeUpdateUserModal();
            showNotification('Update user successfully', 'success');
            await getListUser();
        } catch (error) {
            console.log(error);
        }
    });

    $('#activateUserForm').on('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append('id', $('#pivotId').val());
        formData.append('months', $('#activationMonths').val());

        try{
            const response = await fetchClient(`/api/admin/bot/user/active`, {
                method: 'POST',
                body: formData
            });

            closeActivateUserModal();
            showNotification('Activate user successfully', 'success');
            await getListUser();
        } catch (error) {
            console.log(error);
        }

    });


    $(document).ready(async () => {
        await getListUser();
    });
</script>
@endpush