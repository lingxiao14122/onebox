<x-layout>
    @vite('resources/js/selectProduct.js')
    <div class="flex items-center justify-between m-5">
        <h1 class="text-3xl font-semibold">Record Transaction</h1>
        <a href="{{ url('/transaction') }}" class="underline hover:text-blue-500">Go back</a>
    </div>
    <form action="{{ url('/transaction') }}" method="post" class="mx-5">
        @csrf
        <div class="w-96">
            <div class="mb-4">
                <label for="transaction_type" class="block mb-2 text-lg">Transaction type
                    <span class="text-red-600">*</span>
                </label>
                <select name="transaction_type" class="w-full p-2 mb-2 border border-gray-500 rounded">
                    <option value="in">Stock In</option>
                    <option value="out">Stock Out</option>
                    <option value="audit">Stock Audit</option>
                </select>
            </div>
        </div>
        <div class="w-[700px]">
            <label for="products" class="block mb-2 text-lg">Select product
                <span class="text-red-600">*</span>
            </label>
            @error('item_ids')
                <p class="mt-1 mb-2 text-red-500">{{ $message }}</p>
            @enderror
            @error('item_quantities')
                <p class="mt-1 mb-2 text-red-500">{{ $message }}</p>
            @enderror
            <div id="selectProductDiv" class="w-full mb-6 border rounded border-slate-400">
                <div class="flex py-4 pl-6 font-medium">
                    <h5 class="basis-1/2">Product</h5>
                    <h5 class="basis-1/2">Quantity</h5>
                </div>
                <div data-role="row" class="relative flex items-center h-14">
                    <input class="h-12 pl-6 mr-4 basis-1/2" type="text" value="" placeholder="Select a product"
                        onclick="chooseProduct(this)">
                    <input type="hidden" name="item_ids[]" type="number">
                    <input class="basis-1/2 max-w-[200px] h-8" type="number" min="1" name="item_quantities[]"
                        required />
                    <div class="mx-4 cursor-pointer" onclick="chooseProductRowDelete(this)">
                        <?xml version="1.0" encoding="UTF-8"?><svg width="24" height="24" viewBox="0 0 48 48"
                            fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 11L40 11" stroke="#333" stroke-width="4" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M18 5L30 5" stroke="#333" stroke-width="4" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M12 17H36V40C36 41.6569 34.6569 43 33 43H15C13.3431 43 12 41.6569 12 40V17Z"
                                fill="none" stroke="#333" stroke-width="4" stroke-linejoin="round" />
                            <path d="M20 25L28 33" stroke="#333" stroke-width="4" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M28 25L20 33" stroke="#333" stroke-width="4" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function clickSubmitBtn(event) {
                let selectProductDiv = document.getElementById('selectProductDiv');
                let lastRow = selectProductDiv.lastElementChild
                // if one field exist dont remove last element
                if (selectProductDiv.childElementCount <= 2 && (lastRow.children[1].value.length == 0 && lastRow.children[2].value.length == 0)) {
                    alert('Please select at least one product')
                    return false
                } 
                // if last row not used
                if (lastRow.children[1].value.length == 0 && lastRow.children[2].value.length == 0) {
                    lastRow.remove()
                    document.getElementById('real-submit-btn').click() // help block ui when required validation not pass
                }
            }
            window.clickSubmitBtn = clickSubmitBtn
        </script>
        <div class="mb-6">
            {{-- dispatch submit event --}}
            <button class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-black"
                onclick="clickSubmitBtn(event);">
                Save
            </button>
            {{-- receive submit event --}}
            <button id="real-submit-btn" type="submit" class="hidden">
                Save
            </button>
        </div>
    </form>
</x-layout>
