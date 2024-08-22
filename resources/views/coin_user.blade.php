@extends("layouts.main")

@section("title", "Bot Coin - User")

@section("content")
<div class="container mt-4">
    <table class="w-full text-sm text-left rtl:text-right" id="listUserTable">
        <thead class="text-xs text-gray-300 uppercase bg-[#2a2d35]">
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
    <div id="pagination" class="mt-3"></div>
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
                        <input placeholder="enter your risk level eg: 10" type="text" id="riskTolerance" name="riskTolerance" class="text-sm font-popi w-full p-3 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="apiKey" class="block mb-2 text-sm">API key</label>
                        <input placeholder="your api key eg: bg_e955d8b656ty56gyH97c5b4c4d283e83" type="text" id="apiKey" name="apiKey" class="text-sm font-popi w-full p-3 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="secretKey" class="block mb-2 text-sm">Secret key</label>
                        <input placeholder="your secret key eg: cec36ffa45990dfb50d853ee8faf18e8640f32f75af8ed2b0" type="text" id="secretKey" name="secretKey" class="text-sm font-popi w-full p-3 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="passphrase" class="block mb-2 text-sm">Passphrase</label>
                        <input placeholder="your passphrase eg: 12345678" type="text" id="passphrase" name="passphrase" class="text-sm font-popi w-full p-3 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
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
                        <input placeholder="enter the duration in months eg: 12" type="number" id="activationMonths" name="months" min="1" max="12" class="text-sm font-popi w-full p-3 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
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
    const getListUser = async () => {
        try {
            const response = await fetchClient(`/api/admin/bot/user?bot_id=${botId}`, {
                method: 'GET',
            });

            const data = response.data;

            // console.log(response);

            renderUserList(data);

        } catch (error) {
            console.log(error);
        }
    }

    const renderUserList = (data) => {
        try {
            $('#listUserTable tbody').empty();

            data.data.forEach(user => {
                $('#listUserTable tbody').append(`
                    <tr class="bg-[#1e2026] border-b border-gray-700 hover:bg-gray-900 text-gray-400">
                        <th scope="row" class="flex items-center px-6 py-4 whitespace-nowrap">
                            <img class="w-10 h-10 rounded-full" src="${user.avatar ?? "{{ asset('assets/images/profile-account.png') }}"}" alt="Jese image">
                            <div class="ps-3">
                                <div class="font-semibold">${user.firstname + user.lastname}</div>
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

            // Render pagination
            let paginationHTML = '';

            if (data.last_page > 1) {
                for (let i = 1; i <= data.last_page; i++) {
                    paginationHTML += `<button class="bg-[#2a2d35] hover:bg-[#2d313a] text-gray-200 text-sm py-1 px-3 rounded mr-1" onclick="fetchPage(${i})">${i}</button>`;
                }
                document.getElementById('pagination').innerHTML = paginationHTML;
            }
        } catch (error) {
            console.log(error);
        }
    }

    const fetchPage = async (page) => {
        try {
            const response = await fetchClient(
                `/api/admin/bot/user?bot_id=${botId}&page=${page}`,
            );

            renderUserList(response.data);
        } catch (error) {
            console.error(error);
        }
    };

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

        try {
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

        try {
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