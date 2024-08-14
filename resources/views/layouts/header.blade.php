<header>
    <div class="flex items-center py-3 gap-2 justify-end px-5 border-b border-gray-700">
        <p class="text-[#eaecef] font-popi" id="botName"></p>
        <img class="size-14 rounded-full" src="" alt="" id="botAvatar">
    </div>
</header>
<script>
    $(document).ready(async () => {
        const botId = window.location.pathname.split('/').pop();
        document.getElementById('botUserLink').href = `/coin/${botId}`;

        const getInfoBot = async () => {
            try {
                const response = await fetchClient(`/api/admin/bot/${botId}`, {
                    method: 'GET',
                });
                $('#botAvatar').attr('src', response.data.avatar ?? "{{ asset('assets/images/bot.png') }}");
                $('#botName').text("@" + response.data.username);
            } catch (e) {
                console.log(e);
            }
        };

        await getInfoBot();
    });
</script>