<x-layout>
    @vite('resources/js/dashboard.js')
    <div class="m-5">
        <h1 class="text-3xl font-semibold">Dashboard</h1>
    </div>
    <div class="mx-5 mb-5">
        <div class="flex gap-4">
            <div id="dashboard-content-left">
                <div class="flex gap-5">
                    <div class="w-40 h-40 border rounded bg-sky-50 border-slate-400">
                        <p class="mt-4 font-medium text-center">Total items</p>
                        <p class="mt-2 text-6xl text-center">{{ $quantities[0] }}</p>
                        <p class="mt-4 text-sm text-center">Quantity</p>
                    </div>
                    <div class="w-40 h-40 border rounded bg-sky-50 border-slate-400">
                        <p class="mt-4 font-medium text-center">Total stock count</p>
                        <p class="mt-2 text-6xl text-center">{{ $quantities[1] }}</p>
                        <p class="mt-4 text-sm text-center">Quantity</p>
                    </div>
                    <div class="w-40 h-40 border rounded bg-sky-50 border-slate-400">
                        <p class="mt-4 font-medium text-center">Stock out (Week)</p>
                        <p class="mt-2 text-6xl text-center">{{ $quantities[2] }}</p>
                        <p class="mt-4 text-sm text-center">Quantity</p>
                    </div>
                    <div class="w-40 h-40 border rounded bg-sky-50 border-slate-400">
                        <p class="mt-4 font-medium text-center">Stock in (Week)</p>
                        <p class="mt-2 text-6xl text-center">{{ $quantities[3] ?? 0}}</p>
                        <p class="mt-4 text-sm text-center">Quantity</p>
                    </div>
                </div>
                <div class="w-full pb-2 mt-5 border rounded border-slate-400">
                    <p class="p-2 pl-4 text-lg bg-sky-50">Low stock count</p>
                    <hr class="border-slate-400">
                    <table class="w-full mt-2 table-auto">
                        <thead>
                            <tr>
                                <th class="pb-3 font-semibold">SKU</th>
                                <th class="pb-3 font-semibold">Name</th>
                                <th class="pb-3 font-semibold">Stock count</th>
                                <th class="pb-3 font-semibold">Minimum Quantity</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @foreach ($low_stock_count as $item)
                                <tr class="text-center">
                                    <td>{{ $item->sku }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->stock_count }}</td>
                                    <td>{{ $item->minimum_stock }}</td>
                                </tr>
                            @endforeach
                            @if ($low_stock_count->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center">All items has sufficient stock 👏</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="w-full pb-2 mt-5 border rounded border-slate-400">
                    <p class="p-2 pl-4 text-lg bg-sky-50">Forecast out of stock</p>
                    <hr class="border-slate-400">
                    <table class="w-full mt-2 table-auto">
                        <thead>
                            <tr>
                                <th class="pb-3 font-semibold">SKU</th>
                                <th class="pb-3 font-semibold">Name</th>
                                <th class="pb-3 font-semibold">Restock by</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @foreach ($forecast as $item)
                                <tr class="text-center">
                                    <td>{{ $item->sku }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->restockDate ? $item->restockDate->format('d/m/Y') : 'N/A' }}</td>
                                </tr>
                            @endforeach
                            @if ($forecast->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center">No items need restock 🤞</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="dashboard-content-right">
                <div class="w-[30rem] h-[30rem] border border-slate-400 rounded pb-2">
                    <div class="h-8"></div>
                    <div id="top-selling-pie-chart" class="w-full h-full"></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            var option;

            option = {
                title: {
                    text: 'Top selling item',
                    subtext: 'Past 7 days',
                    left: 'center'
                },
                tooltip: {
                    trigger: 'item'
                },
                series: [{
                    name: 'Item',
                    type: 'pie',
                    radius: '60%',
                    data: [
                        @foreach ($top_selling as $item)
                            {
                                value: {{ $item->demand }},
                                name: '{{ $item->name }}'
                            },
                        @endforeach
                    ],
                    emphasis: {
                        itemStyle: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }]
            };

            option && window.piechart.setOption(option);
        })
    </script>
</x-layout>
