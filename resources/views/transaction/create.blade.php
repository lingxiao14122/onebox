<x-layout>
    @vite('resources/js/selectProduct.js')
    <div class="flex items-center justify-between m-5">
        <h1 class="text-3xl font-semibold">Record Transaction</h1>
        <a href="{{ url('/transaction') }}" class="underline hover:text-blue-500">Go back</a>
    </div>
    <form action="{{ url('/transaction/create') }}" method="get" class="mx-5">
        @csrf
        <div class="w-96">
            <div class="mb-4">
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
        <div class="w-[700px]">
            <label for="products" class="block mb-2 text-lg">Select product
                <span class="text-red-600">*</span>
            </label>
            <div class="w-full mb-[240px] border rounded border-slate-400">
                <div class="flex py-4 pl-6 font-medium">
                    <h5 class="basis-1/2">Product</h5>
                    <h5 class="basis-1/2">Count</h5>
                </div>
                <div class="relative flex items-center h-14">
                    <input class="h-12 pl-6 mr-4 basis-1/2" type="text" value="" placeholder="Select a product" onclick="chooseProduct(this)">
                    <input type="hidden" name="items[]" type="number">
                    <input class="basis-1/2 max-w-[200px] h-8" type="number" value="" name="itemsCount[] required">
                </div>
            </div>
        </div>
    </form>
</x-layout>
