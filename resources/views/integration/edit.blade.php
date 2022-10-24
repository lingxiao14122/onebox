<x-layout>
    <div class="flex items-center m-5 justify-between">
        <h1 class="text-3xl font-semibold">Integration Setting</h1>
        <a href="{{ url('/integration/sync/lazada') }}"
            class="bg-blue-500 text-white rounded py-2 px-4 my-auto hover:bg-black">
            Sync now
        </a>
    </div>
    <div class="mx-5">
        <div class="flex items-center">
            <input
                x-data="{ loading: false }"
                x-on:click="clickCheckbox"
            {{ $in->is_sync_enabled ? 'checked' : '' }} id="checkbox" type="checkbox"
                class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 ">
            <label for="checkbox" class="ml-2 text-sm font-medium text-gray-900">Sync stock count</label>
        </div>
    </div>
    <script>
        async function clickCheckbox(e) {
            const changingChecked = e.target.checked
            e.target.checked = !e.target.checked
            let res = await axios.put('/integration/lazada', { checked: changingChecked })
            if (res.status != 200) {
                return
            }
            if (changingChecked === res.data.is_sync_enabled) {
                e.target.checked = changingChecked
                flashMessage(`Sync stock count ${changingChecked ? 'enabled' : 'disabled'}`)
            }
        }
    </script>
</x-layout>
