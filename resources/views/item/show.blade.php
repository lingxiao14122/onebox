<x-layout>
    <div class="h-14 m-5 flex justify-between">
        <h1 class="text-4xl font-semibold">{{ $item->name }}     ({{ $item->stock_count }} left)</h1>
        <div class="flex items-center space-x-4">
            <form action="/item/{{ $item->id }}" method="post" onsubmit="return confirm('Do you really want to delete?')">
                @csrf
                @method('DELETE')
                <button class="bg-red-500 text-white rounded py-2 px-4 my-auto hover:bg-black">
                    Delete
                </button>
            </form>
            <a href="{{ url("/item/{$item->id}/edit") }}" class="bg-blue-500 text-white rounded py-2 px-4 my-auto hover:bg-black">
                Edit item
            </a>
        </div>
    </div>
    <div class="flex">
        <div class="ml-5 w-[600px]">
            <div class="mb-4">
                <p class="inline-block mb-2 w-48 text-gray-600">SKU</p>
                <p class="inline-block">{{ $item->sku }}</p>
            </div>
            <div class="mb-4">
                <p class="inline-block mb-2 w-48 text-gray-600">Description</p>
                <p class="inline-block">{{ $item->description }}</p>
            </div>
            <div class="mb-4">
                <p class="inline-block mb-2 w-48 text-gray-600">Purchase Price</p>
                <p class="inline-block">{{ $item->purchase_price }}</p>
            </div>
            <div class="mb-4">
                <p class="inline-block mb-2 w-48 text-gray-600">Selling Price</p>
                <p class="inline-block">{{ $item->selling_price }}</p>
            </div>
            <div class="mb-4">
                <p class="inline-block mb-2 w-48 text-gray-600">Minimum Stock</p>
                <p class="inline-block">{{ $item->minimum_stock }}</p>
            </div>
        </div>
        <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('storage/no-image.png') }}"
            class="h-72 w-72 bg-gray-200 object-contain">
    </div>
</x-layout>
