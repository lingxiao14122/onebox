<x-layout>
    <div class="flex items-center justify-between m-5">
        <h1 class="text-3xl font-semibold">Quantitative forecasting</h1>
        <div class="flex items-center gap-2">
            <p>Averaging period (days)</p>
            <form action="{{ url('forecast') }}" method="get">
                <select class="px-3 py-2 border border-slate-400 rounded" name="period" onchange="submit()">
                    <option {{ $period == 30 ? 'selected' : '' }} value="30">30</option>
                    <option {{ $period == 60 ? 'selected' : '' }} value="60">60</option>
                    <option {{ $period == 90 ? 'selected' : '' }} value="90">90</option>
                </select>
            </form>
        </div>
    </div>
    <div class="mx-5">
        <div class="mb-8 overflow-hidden border rounded border-slate-400">
            <table class="w-full text-sm border-collapse table-auto">
                <thead class="border-b-[0.5px] border-slate-400">
                    <tr class="border-b-[0.5px] border-slate-400">
                        <th class="font-medium py-3 border-r-[0.5px] border-slate-400" colspan="4">Item info</th>
                        <th class="font-medium py-3 border-r-[0.5px] border-slate-400" colspan="3">Demand reference
                        </th>
                        <th class="font-medium py-3 border-r-[0.5px] border-slate-400" colspan="3">Forecast</th>
                    </tr>
                    <tr>
                        <th class="font-medium py-3 px-1">Image</th>
                        <th class="font-medium py-3 px-1">Sku</th>
                        <th class="font-medium py-3 px-1">Stock Count</th>
                        <th class="font-medium py-3 px-1">Minimum</th>
                        <th class="font-medium py-3 px-1">Lead time (days)</th>
                        <th class="font-medium py-3 px-1">Demand/Day</th>
                        <th class="font-medium py-3 px-1">Total Demand</th>
                        <th class="font-medium py-3 px-1 relative group">Days left
                            <span
                                class="absolute hidden group-hover:flex -translate-x-1/4 -translate-y-full w-[13rem] -top-2 bg-white border px-2 py-1 rounded-lg border-slate-400 text-center text-sm ">
                                Stock Count / (Demand/Day)</span>
                        </th>
                        <th class="font-medium py-3 px-1">Out of stock</th>
                        <th class="font-medium py-3 px-1">Restock by</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($items as $item)
                        @php
                            if ($item->restockDate) {
                                $color_text_red = Carbon\Carbon::parse("$item->restockDate") <= Carbon\Carbon::now();
                            }
                        @endphp
                        <tr
                            class="tr-border-except-last hover:bg-slate-100 {{ $color_text_red ? 'text-red-500' : '' }}">
                            <td class="py-2 px-1 flex justify-center">
                                <img class="h-16 w-16 object-contain"
                                    src="{{ $item->image ? asset('storage/' . $item->image) : asset('storage/no-image.png') }}"
                                    alt="">
                            </td>
                            <td class="py-2 px-1 text-center">{{ $item->sku }}</td>
                            <td class="py-2 px-1 text-center">{{ $item->stock_count }}</td>
                            <td class="py-2 px-1 text-center">{{ $item->minimum_stock }}</td>
                            <td class="py-2 px-1 text-center">{{ $item->lead_time ?? 'N/A' }}</td>
                            <td class="py-2 px-1 text-center">{{ $item->demandPerDay ?? 'N/A' }}</td>
                            <td class="py-2 px-1 text-center">{{ $item->demandCount ?? 'N/A' }}</td>
                            <td class="py-2 px-1 text-center">{{ $item->daysLeft ?? 'N/A' }}</td>
                            <td class="py-2 px-1 text-center">{{ $item->outOfStock ?? 'N/A' }}</td>
                            <td class="py-2 px-1 text-center">
                                {{ $item->restockDate ? $item->restockDate->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layout>
