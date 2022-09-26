<x-layout>
    @if ($items->isEmpty())
        <h1 class="text-xl text-center mt-5 mb-6">The are no items. Please
            <a href="{{ url('/item/create') }}" class="hover:text-blue-500 underline">create an item</a>
        </h1>
    @else
        <div class="h-14 m-5 my-2 flex justify-between items-center">
            <h1 class="text-3xl font-semibold">Browse item</h1>
            <a href="{{ url('/item/create') }}" class="bg-blue-500 text-white rounded py-2 px-4 my-auto hover:bg-black">
                New item
            </a>
        </div>
        <div class="overflow-hidden mb-8 mx-5 border border-slate-400 rounded pt-4">
            <table class="border-collapse table-fixed w-full text-sm">
                <thead class="border-b-[0.5px] border-slate-400">
                    <tr>
                        <th class="font-medium p-4 pl-8 pt-0 pb-3 text-left w-36">Image</th>
                        <th class="font-medium p-4 pt-0 pb-3 text-left w-1/2">Name</th>
                        <th class="font-medium p-4 pr-8 pt-0 text-left">SKU</th>
                        <th class="font-medium p-4 pr-8 pt-0 ">Stock count</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($items as $item)
                        <tr class="cursor-pointer hover:bg-gray-100" onclick='window.location="{{ url("/item/{$item->id}") }}"'>
                            <td class="p-3 pl-3 text-slate-500">
                                <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('storage/no-image.png') }}"
                                    class="h-20 w-20 bg-gray-200 object-contain">
                            </td>
                            <td class="p-3">{{ $item->name }}</td>
                            <td class="p-3">{{ $item->sku }}</td>
                            <td class="p-3 text-lg font-bold text-center">{{ $item->stock_count }}</td>
                        </tr>
                    @endforeach
            </table>
        </div>
    @endif
</x-layout>
