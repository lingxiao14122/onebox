<x-layout>
    <div class="flex items-center justify-between m-5">
        <h1 class="text-3xl font-semibold">Users</h1>
        <a href="{{ url('/register') }}" class="px-4 py-2 my-auto text-white bg-blue-500 rounded hover:bg-black">Add
            User</a>
            <select class="px-4 py-2 border border-slate-400 rounded" name="pets" id="pet-select">
                <option value="">Averaging period (days)</option>
                <option value="30">30</option>
                <option value="60">60</option>
                <option value="90">90</option>
            </select>
    </div>
    <div class="mx-5">
        <div class="mb-8 overflow-hidden border rounded border-slate-400">
            <table class="w-full text-sm border-collapse table-auto">
                <thead class="border-b-[0.5px] border-slate-400">
                    <tr class="border-b-[0.5px] border-slate-400">
                        <th class="font-medium py-3 border-r-[0.5px] border-slate-400" colspan="4">Item info</th>
                        <th class="font-medium py-3 border-r-[0.5px] border-slate-400" colspan="4">Demand reference</th>
                        <th class="font-medium py-3 border-r-[0.5px] border-slate-400" colspan="2">Forecast</th>
                    </tr>
                    <tr>
                        <th class="font-medium py-3 px-1">Image</th>
                        <th class="font-medium py-3 px-1">Sku</th>
                        <th class="font-medium py-3 px-1">Stock Count</th>
                        <th class="font-medium py-3 px-1">Minimum</th>
                        <th class="font-medium py-3 px-1">Lead time (days)</th>
                        <th class="font-medium py-3 px-1">Demand/Day</th>
                        <th class="font-medium py-3 px-1">Total Demand</th>
                        <th class="font-medium py-3 px-1">Anticipated Demand</th>
                        <th class="font-medium py-3 px-1">Days left</th>
                        <th class="font-medium py-3 px-1">Out of stock date</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <tr class="tr-border-except-last hover:bg-slate-100">
                        <td class="py-3 px-1 flex justify-center">
                            <img class="h-16" src="{{ asset('storage/no-image.png') }}" alt="">
                        </td>
                        <td class="py-3 px-1 text-center">LOGI-MINIKEYS</td>
                        <td class="py-3 px-1 text-center">50</td>
                        <td class="py-3 px-1 text-center">10</td>
                        <td class="py-3 px-1 text-center">12</td>
                        <td class="py-3 px-1 text-center">1.2</td>
                        <td class="py-3 px-1 text-center">22</td>
                        <td class="py-3 px-1 text-center">25</td>
                        <td class="py-3 px-1 text-center">12</td>
                        <td class="py-3 px-1 text-center">10/26/2022</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-layout>
