<nav class="w-[220px] border-r border-gray-400">
    <div class="flex flex-col ml-5 text-lg">
        <a href="/" class="py-6 text-4xl font-semibold">📦Onebox</a>
    </div>
    <div class="flex flex-col text-lg" x-data="{ topen: localStorage.getItem('ttopen') === 'true'  }" x-init="$watch('topen', (val) => localStorage.setItem('ttopen', val))">
        <a href="{{ url('/item') }}"
            class="px-3 py-2 mx-4 my-1 rounded-md hover:text-blue-700 hover:bg-slate-50">Items</a>
        <button x-on:click="topen = !topen"
            class="px-3 py-2 mx-4 my-1 rounded-md cursor-pointer hover:text-blue-700 hover:fill-blue-700 hover:bg-slate-50">
            <div class="flex justify-between align-middle">
                <span>Transaction</span>
                <x-icon.angle-down width="24" height="24"></x-icon.angle-down>
            </div>
        </button>
        <div class="flex flex-col ml-4 text-slate-700" x-show="topen" x-transition>
            <a href="{{ url('/transaction') }}"
                class="px-3 py-1 mx-4 rounded-md hover:text-blue-700 hover:bg-slate-50">Transactions</a>
            <a href="{{ url('/transaction/in') }}"
                class="px-3 py-1 mx-4 rounded-md hover:text-blue-700 hover:bg-slate-50">Stock In</a>
            <a href="{{ url('/transaction/out') }}"
                class="px-3 py-1 mx-4 rounded-md hover:text-blue-700 hover:bg-slate-50">Stock Out</a>
            <a href="{{ url('/transaction/audit') }}"
                class="px-3 py-1 mx-4 rounded-md hover:text-blue-700 hover:bg-slate-50">Stock Audit</a>
        </div>
        @can('admin')
            <a href="{{ url('/user') }}" class="px-3 py-2 mx-4 my-1 rounded-md hover:text-blue-700 hover:bg-slate-50">Users
                & Roles</a>
            <a href="{{ url('/integration') }}" class="px-3 py-2 mx-4 my-1 rounded-md hover:text-blue-700 hover:bg-slate-50">
                Integration
            </a>
        @endcan
    </div>
</nav>
