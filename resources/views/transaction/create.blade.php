<x-layout>
    <div class="flex items-center justify-between m-5">
        <h1 class="text-3xl font-semibold">Record Transaction</h1>
        <a href="{{ url('/transaction') }}" class="underline hover:text-blue-500">Go back</a>
    </div>
    <form action="{{ url('/transaction/create') }}" method="get" class="mx-5">
        @csrf
        <div class="w-96">
            <div class="mb-0">
                <label for="transaction_type" class="block mb-2 text-lg">Transaction type
                    <span class="text-red-600">*</span>
                </label>
                <select name="transaction_type" class="w-full p-2 mb-2 border border-gray-500 rounded">
                    <option value="in">Stock In</option>
                    <option value="out">Stock Out</option>
                    <option value="audit">Stock Audit</option>
                </select>
            </div>
        </div>
        @vite('resources/js/selectProduct.js')
        <div class="w-[700px]">
            <label for="products" class="block mb-2 text-lg">Select product
                <span class="text-red-600">*</span>
            </label>
            {{-- <div class="pt-4 mb-8 overflow-hidden border rounded border-slate-400">
                <table id="table-select-product" class="w-full text-sm border-collapse table-fixed">
                    <thead class="border-b-[0.5px] border-slate-400">
                        <tr>
                            <th class="p-4 pt-0 pb-3 pl-8 font-medium text-left">Product</th>
                            <th class="p-4 pt-0 pb-3 font-medium text-left">Count</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <tr class="cursor-pointer hover:bg-slate-100 h-11">
                            <td class="pl-3 h-11">
                                <input class="w-full h-full focus:outline-none" type="text" value="logo">
                            </td>
                            <td class="pl-3 h-11">
                                <input class="w-full h-full focus:border-0" type="text" value="logo">
                            </td>
                        </tr>
                </table>
            </div> --}}
            <div class="w-full mb-8 border rounded border-slate-400">
                <div class="flex py-4 pl-6 font-medium">
                    <h5 class="basis-1/2">Product</h5>
                    <h5 class="basis-1/2">Count</h5>
                </div>
                <div class="relative flex items-center h-14">
                    <input class="relative h-12 pl-6 mr-4 basis-1/2" type="text" value="logi" onclick="selectProductClicked(this)">
                    <input class="mr-4 basis-1/2" type="number" value="5">
                    {{-- <div class="absolute w-full top-[60px] max-h-60 overflow-auto">
                        <div class="flex justify-between mb-2 bg-slate-100 hover:bg-slate-200">
                            <div class="flex flex-col p-2 pl-4">
                                <p class="text-sm">MX Keys Logitech keyboard</p>
                                <p class="text-sm">LOGI-MXKEYS</p>
                            </div>
                            <p class="pt-2 pr-4">5 pcs</p>
                        </div>
                        <div class="flex justify-between mb-2 bg-slate-100 hover:bg-slate-200">
                            <div class="flex flex-col p-2 pl-4">
                                <p class="text-sm">MX Keys Logitech keyboard</p>
                                <p class="text-sm">LOGI-MXKEYS</p>
                            </div>
                            <p class="pt-2 pr-4">5 pcs</p>
                        </div>
                        <div class="flex justify-between mb-2 bg-slate-100 hover:bg-slate-200">
                            <div class="flex flex-col p-2 pl-4">
                                <p class="text-sm">MX Keys Logitech keyboard</p>
                                <p class="text-sm">LOGI-MXKEYS</p>
                            </div>
                            <p class="pt-2 pr-4">5 pcs</p>
                        </div>
                        <div class="flex justify-between mb-2 bg-slate-100 hover:bg-slate-200">
                            <div class="flex flex-col p-2 pl-4">
                                <p class="text-sm">MX Keys Logitech keyboard</p>
                                <p class="text-sm">LOGI-MXKEYS</p>
                            </div>
                            <p class="pt-2 pr-4">5 pcs</p>
                        </div>
                        <div class="flex justify-between mb-2 bg-slate-100 hover:bg-slate-200">
                            <div class="flex flex-col p-2 pl-4">
                                <p class="text-sm">MX Keys Logitech keyboard</p>
                                <p class="text-sm">LOGI-MXKEYS</p>
                            </div>
                            <p class="pt-2 pr-4">5 pcs</p>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </form>
</x-layout>
