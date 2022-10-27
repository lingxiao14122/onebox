<x-layout>
    <div class="flex items-center justify-between m-5">
        <h1 class="text-3xl font-semibold">Edit Item</h1>
        <a href="{{ url('/item') }}" class="hover:text-blue-500 underline">Go back</a>
    </div>
    <form action="{{ url("/item/{$item->id}") }}" method="post" enctype="multipart/form-data" class="ml-5">
        @csrf
        @method('PUT')
        <div class="w-96">
            <div class="mb-0">
                <label for="name" class="inline-block text-lg mb-2">Name
                    <span class="text-red-600">*</span>
                </label>
                <input type="text" class="border border-gray-500 rounded p-2 mb-2 w-full" name="name"
                    value="{{ $item->name }}">
                @error('name')
                    <p class="text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-0">
                <label for="sku" class="inline-block text-lg mb-2">SKU
                    <span class="text-red-600">*</span>
                </label>
                <input type="text" class="border border-gray-500 rounded p-2 mb-2 w-full" name="sku"
                    value="{{ $item->sku }}">
                @error('sku')
                    <p class="text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-0">
                <label for="image" class="inline-block text-lg mb-2">Image</label>
                <input type="file" class="border border-gray-500 rounded p-2 mb-2 w-full" name="image">
                @error('image')
                    <p class="text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6">
                <label for="description" class="inline-block text-lg mb-2">Description</label>
                <textarea type="text" class="border border-gray-500 rounded p-2 mb-2 w-full" name="description"
                    value="">{{ $item->description }}</textarea>
                @error('description')
                    <p class="text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6">
                <hr class="border-b-2 border-gray-400">
            </div>
            <div class="mb-0">
                <label for="purchase_price" class="inline-block text-lg mb-2">Purchase Price</label>
                <input type="number" class="border border-gray-500 rounded p-2 mb-2 w-full" name="purchase_price"
                    value="{{ $item->purchase_price }}">
                @error('purchase_price')
                    <p class="text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-0">
                <label for="selling_price" class="inline-block text-lg mb-2">Selling Price</label>
                <input type="number" class="border border-gray-500 rounded p-2 mb-2 w-full" name="selling_price"
                    value="{{ $item->selling_price }}">
                @error('selling_price')
                    <p class="text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-0">
                <label for="minimum_stock" class="inline-block text-lg mb-2">Minimum Stock</label>
                <input type="number" class="border border-gray-500 rounded p-2 mb-2 w-full" name="minimum_stock"
                    value="{{ $item->minimum_stock }}">
                @error('minimum_stock')
                    <p class="text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6">
                <label for="lead_time" class="inline-block text-lg mb-2">Lead Time (Days)</label>
                <input type="number" class="border border-gray-500 rounded p-2 mb-2 w-full" name="lead_time"
                    value="{{ $item->lead_time }}">
                @error('lead_time')
                    <p class="text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6">
                <button type="submit" class="bg-blue-500 text-white rounded py-2 px-4 hover:bg-black">
                    Save
                </button>
            </div>
        </div>
    </form>
</x-layout>
