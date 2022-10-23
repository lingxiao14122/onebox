@vite('resources/js/notification.js')
<header class="border-b border-gray-400 h-14">
    <ul class="flex items-center justify-end h-full mr-6 space-x-6 text-lg">
        <li>
            <div id="bell-parent" class="relative hover:fill-blue-500" x-data="{ open: false, bellring: false }" x-on:ring="bellring = true">
                <button 
                    x-on:click="open = !open; bellring = false" 
                    x-bind:class="bellring && 'bell-shaking' " 
                    class="flex items-center justify-center w-10 h-10">
                    <x-icon.bell width="24" height="24"></x-icon.bell>
                </button>
                <div x-show="open" x-transition
                    class="absolute bg-white right-0 top-[100%] overflow-y-scroll min-w-[24rem]
                        max-h-52 border rounded border-slate-300 notification-box shadow-md"
                    style="display: none;">
                    <div class="mx-4 mb-4" id="notification-content">
                        @foreach (auth()->user()->notifications as $no)
                            <div class="my-2 notification-item">
                                <p class="mb-1 text-sm font-semibold text-slate-700">
                                    {{ $no->data['name'] }} reach minimum, <br>{{ $no->data['stock_count'] }} quantity
                                    left
                                </p>
                                <p class="text-sm font-semibold text-slate-500">
                                    {{ $no->created_at->format('m/d/y g:i A') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </li>
        <li class="py-2">
            <a href="{{ url('/logout') }}" class="hover:text-blue-500 hover:fill-blue-500">
                <div class="flex">
                    <i class="flex items-center justify-center mr-1">
                        <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                            <path
                                d="M534.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L434.7 224 224 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l210.7 0-73.4 73.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l128-128zM192 96c17.7 0 32-14.3 32-32s-14.3-32-32-32l-64 0c-53 0-96 43-96 96l0 256c0 53 43 96 96 96l64 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-64 0c-17.7 0-32-14.3-32-32l0-256c0-17.7 14.3-32 32-32l64 0z" />
                        </svg>
                    </i>
                    <span>Logout</span>
                </div>
            </a>
        </li>
    </ul>
</header>
