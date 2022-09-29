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
                <a href="{{ url('/transaction/create') }}" class="underline hover:text-blue-500">create a transaction</a>
            </h1>
        @else
            <div class="pt-4 mb-8 overflow-hidden border rounded border-slate-400">
                <table class="w-full text-sm border-collapse table-fixed">
                    <thead class="border-b-[0.5px] border-slate-400">
                        <tr>
                            <th class="w-48 p-3 pt-0 pb-3 font-medium text-left">Timestamp</th>
                            <th class="w-48 p-3 pt-0 pb-3 font-medium text-left">Transaction type</th>
                            <th class="p-3 pt-0 pr-8 font-medium text-left max-w-[30rem] w-[30rem]">Items</th>
                            <th class="p-3 pt-0 pb-3 font-medium text-left">Created By</th>
                            <th class="w-48 p-3 pt-0 pb-3 font-medium text-left">Transaction ID</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach ($transactions as $transaction)
                            <tr class="tr-border-except-last">
                                <td class="p-3">{{ $transaction->user->created_at->format('d/m/y H:i:A') }}</td>
                                <td class="p-3">{{ $transaction->type }}</td>
                                <td class="p-3">
                                    @foreach ($transaction->items as $item)
                                        @php
                                        if ($item->pivot->from_count > $item->pivot->to_count)
                                        {
                                            $arrow_color = "fill-red-500";
                                        } else {
                                            $arrow_color = "fill-green-500";
                                        }
                                        @endphp
                                        <div class="flex max-w-[25rem] items-center justify-between">
                                            <p class="mr-2 overflow-hidden">{{ $item->name }}</p>
                                            <p class="flex items-center w-8 space-x-1">
                                                <span class="font-semibold">{{ $item->pivot->from_count }}</span>
                                                <x-icon.arrow-right class="w-auto h-3 {{ $arrow_color }}">
                                                </x-icon.arrow-right>
                                                <span class="font-semibold">{{ $item->pivot->to_count }}</span>
                                            </p>
                                        </div>
                                    @endforeach
                                </td>
                                <td class="p-3">{{ $transaction->user->name }}</td>
                                <td class="p-3">{{ $transaction->id }}</td>
                            </tr>
                        @endforeach
                </table>
            </div>
        @endif
    </div>
</x-layout>
