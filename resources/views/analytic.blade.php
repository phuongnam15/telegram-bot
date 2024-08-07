@extends("layouts.main")

@section("title", "Analytic Group")

@section("content")
<div class="container mx-auto mt-4">
    <div class="flex flex-col gap-5">
        <div class="flex items-center gap-2 flex-1 border-b border-gray-200 pb-3">
            <img class="size-12 rounded-full" src="" alt="" id="groupAvatar">
            <div class="flex flex-col leading-5">
                <p class="">
                    <span class="font-medium text-[15px] tracking-wide font-sans" id="groupName"></span>
                </p>
            </div>
        </div>
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
</div>
@endsection

@push("scripts")
<script>
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
    const groupId = window.location.pathname.split("/").pop();

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
    const infoGroup = async () => {
        try {
            const response = await fetchClient(`/api/admin/group/${groupId}`, {
                method: "GET",
            });

            document.getElementById('groupName').innerText = response.title;
            document.getElementById('groupAvatar').src = response.avatar ?? "{{asset('assets/images/bot.png')}}";
        } catch (error) {
            console.error(error);
        }
    }

    $(document).ready(async () => {
        const defaultStartAt = new Date(new Date().setDate(new Date().getDate() - 7)).toISOString().split('T')[0];
        const defaultEndAt = new Date(new Date().setDate(new Date().getDate() + 7)).toISOString().split('T')[0];

        await analyticMessage(defaultStartAt, defaultEndAt);
        await analyticUser(defaultStartAt, defaultEndAt);
        await infoGroup();
    });
</script>
@endpush