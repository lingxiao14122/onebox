<x-layout>
    <div class="flex items-center m-5 justify-between">
        <h1 class="text-3xl font-semibold">Integration Setting</h1>
        <a href="{{ url('/integration/sync/lazada') }}" class="bg-blue-500 text-white rounded py-2 px-4 my-auto hover:bg-black">
            Sync now
        </a>
    </div>
    <div class="mx-5">
        <div class="flex items-center">
            <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 ">
            <label for="checked-checkbox" class="ml-2 text-sm font-medium text-gray-900">Sync stock count</label>
        </div>
    </div>
</x-layout>
<script>
    
</script>