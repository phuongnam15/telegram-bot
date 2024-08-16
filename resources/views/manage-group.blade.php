@extends("layouts.main")

@section("title", "Analytic Group")

@section("content")
<div class="container mx-auto mt-4">
    <div class="flex items-center gap-2 flex-1 border-b border-gray-200 pb-3">
        <img class="size-12 rounded-full" src="" alt="" id="groupAvatar">
        <div class="flex flex-col leading-5">
            <p class="">
                <span class="font-medium text-[15px] tracking-wide font-sans" id="groupName"></span>
            </p>
        </div>
    </div>
    <div class="my-5 border-b-[1px] border-solid border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500">
            <li class="me-2">
                <a href="#" class="inline-flex items-center justify-center px-3 py-[5px] border-b-2 border-transparent rounded-t-lg hover:border-gray-400 group" id="tab-analytic">
                    Analytics
                </a>
            </li>
            <li class="me-2">
                <a href="#" class="inline-flex items-center justify-center px-3 py-[5px] border-b-2 border-transparent rounded-t-lg hover:border-gray-400 group" id="tab-policy">
                    Policies
                </a>
            </li>
        </ul>
    </div>
    <div id="analytic" class="">
        <input type="text" readonly id="litepicker" class="border-gray-300 border outline-none rounded text-center text-[0.8rem] mb-1 focus:border-blue-300 py-1 font-bold font-popi text-gray-600 w-[10rem]">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-10">
            <div class="col-span-1">
                <h1 class="text-gray-600 text-[0.9rem] mb-2 font-popi font-bold">Messages</h1>
                <canvas id="messageChart"></canvas>
            </div>
            <div class="col-span-1">
                <h1 class="text-gray-600 text-[0.9rem] mb-2 font-popi font-bold">New users</h1>
                <canvas id="userJoin"></canvas>
            </div>
            <div class="col-span-1">
                <h1 class="text-gray-600 text-[0.9rem] mb-2 font-popi font-bold">Users left</h1>
                <canvas id="userLeft"></canvas>
            </div>
        </div>
    </div>
    <div id="policy" class="hidden font-popi text-sm">
        <form id="policyForm">
            <h1 class="text-lg font-bold font-serif text-gray-700 tracking-wide">Restrict Chat Members</h1>
            <div class="text-gray-600 leading-3 mt-4">
                <label class="flex items-center gap-1"><input class="size-5" type="checkbox" name="list_ban[]" id="can_send_messages" value="can_send_messages"> Cannot Send Messages</label><br>
                <label class="flex items-center gap-1"><input class="size-5" type="checkbox" name="list_ban[]" id="can_send_other_messages" value="can_send_other_messages"> Cannot Send Other Messages</label><br>
                <label class="flex items-center gap-1"><input class="size-5" type="checkbox" name="list_ban[]" id="can_add_web_page_previews" value="can_add_web_page_previews"> Cannot Add Web Page Previews</label><br>
                <label class="flex items-center gap-1"><input class="size-5" type="checkbox" name="list_ban[]" id="can_send_audios" value="can_send_audios"> Cannot Send Audios</label><br>
                <label class="flex items-center gap-1"><input class="size-5" type="checkbox" name="list_ban[]" id="can_send_documents" value="can_send_documents"> Cannot Send Documents</label><br>
                <label class="flex items-center gap-1"><input class="size-5" type="checkbox" name="list_ban[]" id="can_send_photos" value="can_send_photos"> Cannot Send Photos</label><br>
                <label class="flex items-center gap-1"><input class="size-5" type="checkbox" name="list_ban[]" id="can_send_videos" value="can_send_videos"> Cannot Send Videos</label><br>
                <label class="flex items-center gap-1"><input class="size-5" type="checkbox" name="list_ban[]" id="can_send_video_notes" value="can_send_video_notes"> Cannot Send Video Notes</label><br>
                <label class="flex items-center gap-1"><input class="size-5" type="checkbox" name="list_ban[]" id="can_send_voice_notes" value="can_send_voice_notes"> Cannot Send Voice Notes</label><br>
                <label class="flex items-center gap-1"><input class="size-5" type="checkbox" name="list_ban[]" id="can_send_polls" value="can_send_polls"> Cannot Send Polls</label><br>
                <label class="flex items-center gap-1"><input class="size-5" type="checkbox" name="list_ban[]" id="can_invite_users" value="can_invite_users"> Cannot Invite Users</label><br>
            </div>

            <div>
                <p class="text-sm">Restrict for</p>
                <div class="flex">
                    <input type="number" id="timeAmount" name="timeAmount" min="1" class="max-w-[7rem] text-gray-700 text-sm py-1 px-2 outline-none border border-gray-200" required>
                    <select id="timeUnit" name="timeUnit" class="max-w-[7rem] py-1 px-2 flex-1 border-t border-b border-r border-gray-200 outline-none text-sm text-gray-500" required>
                        <option value="s">seconds</option>
                        <option value="m">minutes</option>
                        <option value="h">hours</option>
                        <option value="d">days</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="flex items-center gap-1 mt-2 rounded-sm py-[0.5rem] px-3 bg-green-600 text-white hover:bg-gray-500"><i class="fa-solid fa-floppy-disk"></i>Save</button>
        </form>
    </div>
</div>
@endsection

@push("scripts")
<script>
    const groupId = window.location.pathname.split("/").pop();

    //ANALYTIC script
    const picker = new Litepicker({
        element: document.getElementById('litepicker'),
        plugins: ['ranges'],
        ranges: {
            position: 'right',
            // customRanges: {
            //     'Last 7 days': [new Date(new Date().setDate(new Date().getDate() - 7)), new Date()],
            //     'Last 4 weeks': [new Date(new Date().setDate(new Date().getDate() - 28)), new Date()],
            //     'Last 3 months': [new Date(new Date().setMonth(new Date().getMonth() - 3)), new Date()],
            // },
        },
        setup: (picker) => {
            picker.on('selected', async (date1, date2) => {
                const startAt = date1.format('YYYY-MM-DD');
                const endAt = date2.format('YYYY-MM-DD');
                await analyticMessage(startAt, endAt);
                await analyticUser(startAt, endAt);
            });
        },
        startDate: new Date(new Date().setDate(new Date().getDate() - 7)),
        endDate: new Date(new Date().setDate(new Date().getDate() + 7)),
        singleMode: false,
        format: 'MMM DD'
    });

    const analyticMessage = async (startAt, endAt) => {
        try {
            const response = await fetchClient(`/api/admin/group/analytic/message?group_id=${groupId}&start_at=${startAt}&end_at=${endAt}`, {
                method: "GET",
            });

            const data = response.data;

            const formattedData = formatData(startAt, endAt, data);

            updateChart('messageChart', formattedData);
        } catch (error) {
            console.error(error);
        }
    }
    const analyticUser = async (startAt, endAt) => {
        try {
            const responseJoin = await fetchClient(`/api/admin/group/analytic/user?group_id=${groupId}&start_at=${startAt}&end_at=${endAt}&type=join`, {
                method: "GET",
            });
            const responseLeft = await fetchClient(`/api/admin/group/analytic/user?group_id=${groupId}&start_at=${startAt}&end_at=${endAt}&type=left`, {
                method: "GET",
            });


            const dataJoin = Object.values(responseJoin.data);
            const dataLeft = responseLeft.data;

            const formattedDataJoin = formatData(startAt, endAt, dataJoin);
            const formattedDataLeft = formatData(startAt, endAt, dataLeft);

            updateChart('userJoin', formattedDataJoin);
            updateChart('userLeft', formattedDataLeft);
        } catch (error) {
            console.error(error);
        }
    }

    let messageChart;
    let userJoin;
    let userLeft;

    const updateChart = (nameChart, dataChart) => {

        switch (nameChart) {
            case 'messageChart':
                if (messageChart) {
                    messageChart.destroy();
                }
                break;
            case 'userJoin':
                if (userJoin) {
                    userJoin.destroy();
                }
                break;
            case 'userLeft':
                if (userLeft) {
                    userLeft.destroy();
                }
                break;
        }

        const ctx = document.getElementById(nameChart).getContext('2d');

        const data = {
            datasets: [{
                data: dataChart,
                borderColor: '#5097e7',
                borderWidth: 1,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: false,
                pointStyle: false,
                // pointRadius: 3
            }]
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return `Value: ${tooltipItem.raw.y}`;
                            }
                        }
                    },
                },
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'day',
                            tooltipFormat: 'MMM d',
                            displayFormats: {
                                day: 'MMM d'
                            }
                        },
                        ticks: {
                            callback: function(value, index, values) {
                                // const date = new Date(value);
                                // if (index === 0 || index === values.length - 1) {
                                //     return date.toLocaleDateString('en-US', {
                                //         month: 'short',
                                //         day: 'numeric'
                                //     });
                                // }
                                return '';
                            }
                        },
                        grid: {
                            display: false
                        },
                    },
                    y: {
                        ticks: {
                            callback: function(value, index, values) {
                                if (index === 0 || index === values.length - 1) {
                                    return value;
                                }
                                return '';
                            }
                        },
                        grid: {
                            display: false
                        },
                    }
                }
            }
        };

        switch (nameChart) {
            case 'messageChart':
                messageChart = new Chart(ctx, config);
                break;
            case 'userJoin':
                userJoin = new Chart(ctx, config);
                break;
            case 'userLeft':
                userLeft = new Chart(ctx, config);
                break;
        }
    }
    const formatData = (startAt, endAt, data) => {
        const formattedData = [];
        const start = new Date(startAt);
        const end = new Date(endAt);

        for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
            const dateStr = d.toISOString().split('T')[0];
            const dataForDate = data.find(item => item.created_at.split('T')[0] === dateStr);

            formattedData.push({
                x: dateStr + 'T00:00:00Z',
                y: dataForDate ? dataForDate.total : 0
            });
        }

        return formattedData;
    }
    const analyticScript = async () => {
        const defaultStartAt = new Date(new Date().setDate(new Date().getDate() - 7)).toISOString().split('T')[0];
        const defaultEndAt = new Date(new Date().setDate(new Date().getDate() + 7)).toISOString().split('T')[0];

        await analyticMessage(defaultStartAt, defaultEndAt);
        await analyticUser(defaultStartAt, defaultEndAt);
    }

    //POLICY script
    const policyScript = async () => {
        document.getElementById('policyForm').addEventListener('submit', async (event) => {
            event.preventDefault();

            const formData = new FormData();

            const allCheckboxes = document.querySelectorAll('input[name="list_ban[]"]');
            const listBan = {};
            allCheckboxes.forEach(checkbox => {
                listBan[checkbox.value] = !checkbox.checked;
            });

            const timeAmount = document.getElementById('timeAmount').value;
            const timeUnit = document.getElementById('timeUnit').value;
            const expiredTime = timeAmount + timeUnit;

            // console.log('List Ban:', listBan);
            // console.log('Expired Time:', expiredTime);

            formData.append('list_ban', JSON.stringify(listBan));
            formData.append('expired_time', expiredTime);
            formData.append('group_id', groupId);

            try {
                const response = await fetchClient(`/api/admin/group/policy`, {
                    method: "POST",
                    body: formData,
                });

                // console.log(response);

                showNotification('Saved', 'success');
            } catch (error) {
                console.error(error);
            }
        });
    }

    //////////////////////////////////////////
    const infoGroup = async () => {
        try {
            const response = await fetchClient(`/api/admin/group/${groupId}`, {
                method: "GET",
            });

            document.getElementById('groupName').innerText = response.title;
            document.getElementById('groupAvatar').src = response.avatar ?? "{{asset('assets/images/bot.png')}}";

            if(response.list_ban === null) {
                return;
            }

            const listBan = JSON.parse(response.list_ban);
            Object.keys(listBan).forEach(permission => {
                const checkbox = document.querySelector(`input[name="list_ban[]"][value="${permission}"]`);
                if (checkbox) {
                    checkbox.checked = !listBan[permission];
                }
            });
        } catch (error) {
            console.error(error);
        }
    }

    const showTab = async (tabId) => {
        const tabIds = ['analytic', 'policy'];
        tabIds.forEach(id => document.getElementById(id).classList.add('hidden'));
        document.getElementById(tabId).classList.remove('hidden');

        if (tabId === 'analytic') {
            analyticScript();
        } else if (tabId === 'policy') {
            policyScript();
        }

        document.querySelectorAll('a[id^="tab-"]').forEach(tabLink => {
            tabLink.classList.remove('bg-gray-500', 'text-white');
            tabLink.classList.add('hover:border-gray-400');
        });
        document.getElementById('tab-' + tabId).classList.add('bg-gray-500', 'text-white');
        document.getElementById('tab-' + tabId).classList.remove('hover:border-gray-400');
    }

    $(document).ready(async () => {
        $('#tab-analytic').on('click', async (e) => {
            e.preventDefault();
            await showTab('analytic');
        });
        $('#tab-policy').on('click', async (e) => {
            e.preventDefault();
            await showTab('policy');
        });

        await infoGroup();
        await showTab('policy');
    });
</script>
@endpush