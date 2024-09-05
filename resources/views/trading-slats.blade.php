@extends("layouts.main")

@section("title", "Bot Coin - Trading Stats")

@section("content")
<div class="w-full h-full bg-red-50 p-4">
    <div id="analytic" class="">
        <input type="text" readonly id="litepicker" class="border-gray-300 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 border outline-none rounded text-center text-[0.8rem] mb-10  focus:border-blue-300 py-1 font-bold font-popi text-gray-600 w-[10rem]">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-10">
            <div class="col-span-1">
                <h1 class="text-gray-200 dark:text-gray-300 text-[0.9rem] mb-4 font-popi font-bold">Trading Command Slats</h1>
                <canvas id="tradingChart"></canvas>
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
                await tradingSlat(startAt, endAt);
            });
        },
        startDate: new Date(new Date().setDate(new Date().getDate() - 7)),
        endDate: new Date(new Date().setDate(new Date().getDate() + 7)),
        singleMode: false,
        format: 'MMM DD'
    });

    const tradingSlat = async (startAt, endAt) => {
        try {
            const response = await fetchClient(`/api/admin/bot/trade-command-slats?bot_id=${botId}&start_at=${startAt}&end_at=${endAt}`, {
                method: "GET",
            });

            console.log(response);
            const formattedData = formatData(startAt, endAt, response);
            console.log(formattedData, "formattedData");
            updateChart('tradingChart', formattedData);
        } catch (error) {
            console.error(error);
        }
    }

    const formatData = (startAt, endAt, data) => {
        const formattedData = Array.from({
            length: 2
        }, () => []);
        const start = new Date(startAt);
        const end = new Date(endAt);

        for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
            const dateStr = d.toISOString().split('T')[0];
            const dataForDate = data.find(item => item.created_at.split('T')[0] === dateStr);

            formattedData[0].push({
                x: dateStr + 'T00:00:00Z',
                y: dataForDate ? dataForDate.total_trading_command : 0
            });
            formattedData[1].push({
                x: dateStr + 'T00:00:00Z',
                y: dataForDate ? dataForDate.total_trading_failed : 0
            });
        }

        return formattedData;
    }

    let tradingChart;

    const updateChart = (nameChart, dataChart) => {

        switch (nameChart) {
            case 'tradingChart':
                if (tradingChart) {
                    tradingChart.destroy();
                }
                break;
        }

        const ctx = document.getElementById(nameChart).getContext('2d');

        const data = {
            datasets: [{
                    data: dataChart[0],
                    borderColor: '#5097e7',
                    borderWidth: 1,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: false,
                    pointStyle: false,
                },
                {
                    data: dataChart[1],
                    borderColor: '#d53434',
                    borderWidth: 1,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: false,
                    pointStyle: false,
                }
            ]
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
            case 'tradingChart':
                tradingChart = new Chart(ctx, config);
                break;
        }
    }

    $(document).ready(async () => {
        const defaultStartAt = new Date(new Date().setDate(new Date().getDate() - 7)).toISOString().split('T')[0];
        const defaultEndAt = new Date(new Date().setDate(new Date().getDate() + 7)).toISOString().split('T')[0];

        await tradingSlat(defaultStartAt, defaultEndAt);
    });
</script>
@endpush