<x-layout>
    @vite('resources/js/selectProduct.js')
    <div class="flex items-center justify-between m-5">
        <h1 class="text-3xl font-semibold">Record Transaction</h1>
        <a href="{{ url('/transaction') }}" class="underline hover:text-blue-500">Go back</a>
    </div>
    <form action="{{ url('/transaction') }}" method="post" class="mx-5">
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
        <x-select-product></x-select-product>
    </form>
</x-layout>
