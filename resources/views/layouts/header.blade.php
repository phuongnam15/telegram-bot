<header>
    <div class="relative flex items-center py-3 gap-2 justify-end px-5 border-b border-gray-700">
        <button id="botMenuButton" class="transition-bg py-2 text-gray-500 flex items-center">
            <span id="username_bot" class="font-popi"></span>
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div id="botMenu" class="absolute right-5 top-10 mt-2 w-[10rem] rounded-md shadow-lg hidden overflow-hidden border border-gray-700">
            <div class="py-2 px-3 text-sm text-white bg-gray-900 flex justify-between items-center">
                <select name="platform" id="platform" class="bg-gray-500 text-white outline-none py-1 flex-1">
                    <option value="bitget">BITGET</option>
                    <option value="binance">BINANCE</option>
                </select>
            </div>
        </div>
    </div>
</header>
<script>
    const botId = window.location.pathname.split('/').pop();

    $('#botMenuButton').click(function() {
        var botMenu = document.getElementById('botMenu');
        botMenu.classList.toggle('hidden');
    });

    let previousPlatform = $('#platform').val();
    $('#platform').change(async function() {
        const platform = $('#platform').val();
        try {
            const confirm = window.confirm('Are you sure you want to change the trading platform?');

            if (!confirm) {
                $('#platform').val(previousPlatform);
                return;
            }

            const formData = new FormData();
            formData.append('trading_platform', platform);

            const response = await fetchClient(`/api/admin/bot/update-platform/${botId}`, {
                method: 'POST',
                body: formData,
            });

            await getInfoBot();
            showNotification('updated success', 'success');
        } catch (e) {
            console.log(e);
        }
    });
    $(document).on('click', function(e) {
        var userMenu = document.getElementById('botMenu');
        var userMenuButton = document.getElementById('botMenuButton');
        if (!userMenuButton.contains(e.target) && !userMenu.contains(e.target)) {
            userMenu.classList.add('hidden');
        }
    });

    const getInfoBot = async () => {
        try {
            const response = await fetchClient(`/api/admin/bot/${botId}`, {
                method: 'GET',
            });
            $('#username_bot').text("@" + response.data.username);
            $('#platform').val(response.data.trading_platform);
        } catch (e) {
            console.log(e);
        }
    };
    
    $(document).ready(async () => {
        const botId = window.location.pathname.split('/').pop();
        document.getElementById('botUserLink').href = `/coin/${botId}`;

        await getInfoBot();
    });
</script>