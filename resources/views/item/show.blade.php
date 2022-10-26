<x-layout>
    <div class="flex justify-between m-5 h-14">
        <h1 class="text-4xl font-semibold">{{ $item->name }} ({{ $item->stock_count }} left)</h1>
        <div class="flex items-center space-x-4">
            @can('admin')
                <form action="/item/{{ $item->id }}" method="post"
                    onsubmit="return confirm('Do you really want to delete?')">
                    @csrf
                    @method('DELETE')
                    <button class="px-4 py-2 my-auto text-white bg-red-500 rounded hover:bg-black">
                        Delete
                    </button>
                </form>
            @endcan
            <a href="{{ url("/item/{$item->id}/edit") }}"
                class="px-4 py-2 my-auto text-white bg-blue-500 rounded hover:bg-black">
                Edit item
            </a>
        </div>
    </div>
    <div class="flex">
        <div class="ml-5 w-[600px]">
            <div class="mb-4">
                <p class="inline-block w-48 mb-2 text-gray-600">SKU</p>
                <p class="inline-block">{{ $item->sku }}</p>
            </div>
            <div class="mb-4">
                <p class="inline-block w-48 mb-2 text-gray-600">Description</p>
                <p class="inline-block">{{ $item->description }}</p>
            </div>
            <div class="mb-4">
                <p class="inline-block w-48 mb-2 text-gray-600">Purchase Price</p>
                <p class="inline-block">{{ $item->purchase_price }}</p>
            </div>
            <div class="mb-4">
                <p class="inline-block w-48 mb-2 text-gray-600">Selling Price</p>
                <p class="inline-block">{{ $item->selling_price }}</p>
            </div>
            <div class="mb-4">
                <p class="inline-block w-48 mb-2 text-gray-600">Minimum Stock</p>
                <p class="inline-block">{{ $item->minimum_stock }}</p>
            </div>
            <div class="mb-4">
                <p class="inline-block w-48 mb-2 text-gray-600">Lead Time</p>
                <p class="inline-block">{{ $item->lead_time }}</p>
            </div>
        </div>
        <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('storage/no-image.png') }}"
            class="object-contain bg-gray-200 h-72 w-72">
    </div>
</x-layout>
