<nav class="w-[220px] border-r border-gray-400">
    <div class="flex flex-col ml-5 text-lg">
        <a href="/" class="text-4xl py-6">📦Onebox</a>
    </div>
    <div class="flex flex-col ml-5 text-lg">
        <a href="{{ url('/item') }}" class="mb-5 hover:text-blue-500">Items</a>
        <a href="{{ url('/transaction') }}" class="mb-5 hover:text-blue-500">Transaction</a>
        @can('admin')
            <a href="{{ url('/user') }}" class="mb-5 hover:text-blue-500">Users & Roles</a>
        @endcan
    </div>
</nav>
