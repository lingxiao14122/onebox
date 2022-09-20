<x-layout>
    <div class="flex items-center justify-between m-5">
        <h1 class="text-3xl font-semibold">Transactions</h1>
        <a href="{{ url('/transaction/create') }}"
            class="px-4 py-2 my-auto text-white bg-blue-500 rounded hover:bg-black">Record
            transaction</a>
    </div>
    <div class="mx-5">
        @if ($transactions->isEmpty())
            <h1 class="mt-5 mb-6 text-xl text-center">No transaction
                <a href="{{ url('/transaction/create') }}" class="underline hover:text-blue-500">create an item</a>
            </h1>
        @else
            <div class="pt-4 mb-8 overflow-hidden border rounded border-slate-400">
                <table class="w-full text-sm border-collapse table-fixed">
                    <thead class="border-b-[0.5px] border-slate-400">
                        <tr>
                            <th class="w-48 p-4 pt-0 pb-3 font-medium text-left">Transaction ID</th>
                            <th class="p-4 pt-0 pb-3 font-medium text-left">User</th>
                            <th class="p-4 pt-0 pr-8 font-medium text-left">Items</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach ($transactions as $transaction)
                            <tr class="tr-border-except-last">
                                <td class="p-3 pl-3 text-slate-500">{{ $transaction->id }}</td>
                                <td class="p-3 text-slate-500">{{ $transaction->user->name }}</td>
                                <td class="p-3 text-slate-500">
                                    @foreach ($transaction->items as $item)
                                        {{ $item->name }}<br>
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                </table>
            </div>
        @endif
    </div>
</x-layout>
