<x-layout>
    <div class="flex items-center justify-between m-5">
        <h1 class="text-3xl font-semibold">Transactions</h1>
        <a href="{{ url('/transaction/create') }}" class="bg-blue-500 text-white rounded py-2 px-4 my-auto hover:bg-black">Record
            transaction</a>
    </div>
    <div class="mx-5">
        @if ($transactions->isEmpty())
        <h1 class="text-xl text-center mt-5 mb-6">No transaction
            <a href="{{ url('/transaction/create') }}" class="hover:text-blue-500 underline">create an item</a>
        </h1>
        @else
            @foreach ($transactions as $transaction)
                <p>{{ $transaction->id }}, {{ $transaction->user->name }}</p>
                @foreach ($transaction->items as $item)
                    <p>{{ $item->id }} {{ $item->name }}</p>
                @endforeach
            @endforeach
        @endif
    </div>
</x-layout>
