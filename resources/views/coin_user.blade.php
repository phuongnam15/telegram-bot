@extends("layouts.main")

@section("title", "Bot Coin - User")

@section("content")
<div class="container mt-4">
    <input type="text" id="keyword" class="bg-[#1e2026] border border-gray-700 text-sm float-end mb-1 pl-2 py-1 outline-none text-gray-300 font-popi" placeholder="search name">
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
                    Platform
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
<input type="text" id="userId" class="hidden">
<!-- Update User Risk Modal -->
<div id="updateUserModal" tabindex="-1" class="fixed inset-0 z-50 hidden overflow-y-auto overflow-x-hidden bg-gray-800 bg-opacity-75">
    <div class="relative w-full max-w-md mx-auto mt-20">
        <div class="bg-[#2a2d35] text-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-gray-700">
                <h3 class="text-xl font-medium">Update User Risk Tolerance</h3>
            </div>
            <div class="px-6 py-4">
                <form id="updateUserForm">
                    <div class="mb-4">
                        <label for="riskTolerance" class="block mb-2 text-sm">Risk Tolerance</label>
                        <input placeholder="enter your risk level eg: 10" type="text" id="riskTolerance" name="riskTolerance" class="text-sm font-popi w-full p-3 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
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

<!-- Update User Key Modal -->
<div id="updateUserKeyModal" tabindex="-1" class="fixed hidden inset-0 z-50 overflow-y-auto overflow-x-hidden bg-gray-800 bg-opacity-75">
    <div class="relative w-full max-w-md mx-auto mt-20">
        <div class="bg-[#2a2d35] text-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-gray-700">
                <h3 class="text-xl font-medium">Update User Key</h3>
            </div>
            <div class="px-6 py-4">
                <form id="updateUserKeyForm">
                    <div class="mb-4">
                        <label for="apiKey" class="block mb-2 text-sm">API key</label>
                        <input placeholder="your api key eg: bg_e955d8b656ty56gyH97c5b4c4d283e83" type="text" id="apiKey" name="apiKey" class="text-sm font-popi w-full p-3 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="secretKey" class="block mb-2 text-sm">Secret key</label>
                        <input placeholder="your secret key eg: cec36ffa45990dfb50d853ee8faf18e8640f32f75af8ed2b0" type="text" id="secretKey" name="secretKey" class="text-sm font-popi w-full p-3 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4 hidden" id="passphraseField">
                        <label for="passphrase" class="block mb-2 text-sm">Passphrase</label>
                        <input placeholder="your passphrase eg: 12345678" type="text" id="passphrase" name="passphrase" class="text-sm font-popi w-full p-3 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="platform" class="block mb-2 text-sm">Platform</label>
                        <select name="platform" id="platform" class="text-sm font-popi w-full p-3 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="bitget">BITGET</option>
                            <option value="bingx">BINGX</option>
                            <option value="binance">BINANCE</option>
                            <option value="bybit">BYBIT</option>
                            <option value="okx">OKX</option>
                            <option value="mexc">MEXC</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" class="px-4 py-2 bg-gray-600 rounded-lg hover:bg-gray-700" onclick="closeUpdateUserKeyModal()">Cancel</button>
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
    const getListUser = async (keyword = "") => {
        try {
            const response = await fetchClient(`/api/admin/bot/user?bot_id=${botId}&keyword=${keyword}`, {
                method: 'GET',
            });

            const data = response.data;

            // console.log(response);

            renderUserList(data, keyword);

        } catch (error) {
            console.log(error);
        }
    }

    const renderUserList = (data, keyword = "") => {
        try {
            $('#listUserTable tbody').empty();
            $('#pagination').empty();

            data.data.forEach((user, index) => {
                let apiKey = "";
                let secretKey = "";
                let passphrase = "";
                let keys = JSON.stringify(user.keys).replace(/"/g, '&quot;');
                if (user.keys.length > 0) {
                    var matchingKey = user.keys.find(function(key) {
                        return key.platform === user.pivot.trading_platform;
                    });
                    if (matchingKey) {
                        apiKey = matchingKey.api_key;
                        secretKey = matchingKey.secret_key;
                        passphrase = matchingKey.passphrase;
                    }
                }
                $('#listUserTable tbody').append(`
                    <tr class="bg-[#1e2026] ${index === (data.data.length - 1) ? '' : 'border-b border-gray-700'} hover:bg-gray-900 text-gray-400">
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
                        <td class="px-6 py-4 text-center">
                            <select class="outline-none p-2 bg-gray-800 text-white rounded-lg platform-select" data-user-id="${user.id}" data-original-platform="${user.pivot.trading_platform}">
                                <option value="bitget" ${user.pivot.trading_platform === "bitget" ? "selected" : ""}>BITGET</option>
                                <option value="bingx" ${user.pivot.trading_platform === "bingx" ? "selected" : ""}>BINGX</option>
                                <option value="binance" ${user.pivot.trading_platform === "binance" ? "selected" : ""}>BINANCE</option>
                                <option value="bybit" ${user.pivot.trading_platform === "bybit" ? "selected" : ""}>BYBIT</option>
                                <option value="okx" ${user.pivot.trading_platform === "okx" ? "selected" : ""}>OKX</option>
                                <option value="mexc" ${user.pivot.trading_platform === "mexc" ? "selected" : ""}>MEXC</option>
                            </select>
                        </td>
                        <td class="px-6 py-4 text-center">${user.pivot.risk_tolerance ?? ""}</td>
                        <td class="px-6 py-4 text-center">${user.pivot.expired_at ? formatDate(user.pivot.expired_at) : ""}</td>
                        <td class="px-6 py-4 space-x-2 text-center">
                            <a href="#" class="font-medium text-yellow-600 hover:underline" onclick="openUpdateUserModal('${user.pivot.id}', '${user.pivot.risk_tolerance ?? ""}')">Risk</a>
                            <a href="#" class="font-medium text-blue-600 hover:underline" onclick="openUpdateUserKeyModal('${user.id}', '${apiKey}', '${secretKey}', '${passphrase}', '${user.pivot.trading_platform}', '${keys}')">Key</a>
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
                    const isActive = (i === data.current_page) ? 'bg-[#4a4f58]' : 'bg-[#2a2d35]';
                    paginationHTML += `<button class="${isActive} hover:bg-[#2d313a] text-gray-200 text-sm py-1 px-3 rounded mr-1" onclick="fetchPage(${i}, ${keyword})">${i}</button>`;
                }
                document.getElementById('pagination').innerHTML = paginationHTML;
            }
        } catch (error) {
            console.log(error);
        }
    }

    const fetchPage = async (page, keyword = "") => {
        try {
            const response = await fetchClient(
                `/api/admin/bot/user?bot_id=${botId}&page=${page}&keyword=${keyword}`, {
                    method: 'GET',
                }
            );

            renderUserList(response.data);
        } catch (error) {
            console.error(error);
        }
    };

    const openUpdateUserModal = (pivotId, riskTolerance, apiKey, secretKey, passphrase) => {
        $('#pivotId').val(pivotId);
        $('#updateUserModal #riskTolerance').val(riskTolerance);
        $('#updateUserModal').removeClass('hidden');
    };

    const closeUpdateUserModal = () => {
        $('#updateUserModal').addClass('hidden');
    };

    let currentUserKeys = [];
    const openUpdateUserKeyModal = (userId, apiKey, secretKey, passphrase, platform, keys) => {
        if (['bitget', 'okx'].includes(platform)) {
            if ($('#passphraseField').hasClass('hidden')) {
                $('#passphraseField').removeClass('hidden');
            }
        } else {
            if (!$('#passphraseField').hasClass('hidden')) {
                $('#passphraseField').addClass('hidden');
            }
        }

        currentUserKeys = JSON.parse(keys);

        $('#userId').val(userId);
        $('#updateUserKeyModal #apiKey').val(apiKey);
        $('#updateUserKeyModal #secretKey').val(secretKey);
        $('#updateUserKeyModal #passphrase').val(passphrase);
        $('#updateUserKeyModal #platform').val(platform);
        $('#updateUserKeyModal').removeClass('hidden');

        $('#platform').on('change', function() {
            const selectedPlatform = $(this).val();

            if (['bitget', 'okx'].includes(selectedPlatform)) {
                if ($('#passphraseField').hasClass('hidden')) {
                    $('#passphraseField').removeClass('hidden');
                }
            } else {
                if (!$('#passphraseField').hasClass('hidden')) {
                    $('#passphraseField').addClass('hidden');
                }
            }

            const matchingKey = currentUserKeys.find(key => key.platform === selectedPlatform);

            if (matchingKey) {
                $('#apiKey').val(matchingKey.api_key);
                $('#secretKey').val(matchingKey.secret_key);
                $('#passphrase').val(matchingKey.passphrase);
            } else {
                $('#apiKey').val('');
                $('#secretKey').val('');
                $('#passphrase').val('');
            }
        });
    };

    const closeUpdateUserKeyModal = () => {
        $('#updateUserKeyModal').addClass('hidden');
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

    $('#updateUserKeyForm').on('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append('api_key', $('#apiKey').val());
        formData.append('secret_key', $('#secretKey').val());
        formData.append('passphrase', $('#passphrase').val());
        formData.append('platform', $('#platform').val());
        formData.append('user_id', $('#userId').val());

        try {
            const response = await fetchClient(`/api/admin/bot/user/keys`, {
                method: 'POST',
                body: formData
            });

            closeUpdateUserKeyModal();
            showNotification('Update user keys successfully', 'success');
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

    $(document).on('change', '.platform-select', async function() {
        const selectElement = $(this);
        const userId = selectElement.data('user-id');
        const originalPlatform = selectElement.data('original-platform');
        const newPlatform = selectElement.val();

        const confirm = window.confirm('Are you sure you want to change the trading platform?');

        if (!confirm) {
            selectElement.val(originalPlatform);
            return;
        }

        const formData = new FormData();
        formData.append('user_id', userId);
        formData.append('trading_platform', newPlatform);
        formData.append('bot_id', botId);

        try {
            await fetchClient(`/api/admin/bot/user/update-platform`, {
                method: 'POST',
                body: formData
            });

            selectElement.data('original-platform', newPlatform);

            showNotification('Platform updated successfully', 'success');
            await getListUser();
        } catch (error) {
            selectElement.val(originalPlatform);
            console.error(error);
            showNotification('Failed to update platform', 'error');
        }
    });


    $(document).ready(async () => {
        await getListUser();

        const debouncedGetListUser = debounce(
            function() {
                const keyword = $('#keyword').val();
                getListUser(keyword);
            },
            500
        );

        $('#keyword').on('input', debouncedGetListUser);
    });
</script>
@endpush