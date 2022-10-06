<x-layout>
    @vite('resources/js/selectProduct.js')
    <div class="flex items-center justify-between m-5">
        <h1 class="text-3xl font-semibold">Record Transaction</h1>
        <a href="{{ url('/transaction') }}" class="underline hover:text-blue-500">Go transactions</a>
    </div>
    <form action="{{ url('/transaction') }}" method="post" class="mx-5">
        @csrf
        <div class="w-96">
            <div class="mb-4">
                <label for="transaction_type" class="block mb-2 text-lg">Transaction type</label>
                <input class="w-full p-2 mb-2 border border-gray-500 rounded" value="Stock Out" readonly>
                <input name="transaction_type" class="" value="out" hidden>
            </div>
        </div>
        <x-select-product></x-select-product>
    </form>
</x-layout>
