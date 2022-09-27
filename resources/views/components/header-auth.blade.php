<header class="h-14 border-b border-gray-400">
    <ul class="flex justify-end items-center space-x-6 text-lg mr-6 h-full">
        <li class="relative">
            <button class="hover:fill-blue-500 py-2" id="bell">
                <i class="flex justify-center items-center bell-shaking">
                    <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path
                            d="M256 32V49.88C328.5 61.39 384 124.2 384 200V233.4C384 278.8 399.5 322.9 427.8 358.4L442.7 377C448.5 384.2 449.6 394.1 445.6 402.4C441.6 410.7 433.2 416 424 416H24C14.77 416 6.365 410.7 2.369 402.4C-1.628 394.1-.504 384.2 5.26 377L20.17 358.4C48.54 322.9 64 278.8 64 233.4V200C64 124.2 119.5 61.39 192 49.88V32C192 14.33 206.3 0 224 0C241.7 0 256 14.33 256 32V32zM216 96C158.6 96 112 142.6 112 200V233.4C112 281.3 98.12 328 72.31 368H375.7C349.9 328 336 281.3 336 233.4V200C336 142.6 289.4 96 232 96H216zM288 448C288 464.1 281.3 481.3 269.3 493.3C257.3 505.3 240.1 512 224 512C207 512 190.7 505.3 178.7 493.3C166.7 481.3 160 464.1 160 448H288z" />
                    </svg>
                </i>
            </button>
            <div
                class="absolute bg-white max-h-52 min-w-[25rem] right-0 top-[100%] border rounded border-slate-300 overflow-auto notification-box shadow-md opacity-0">
                <div class="overflow-clip notification-content">
                    @for ($i = 0; $i < 10; $i++)
                        <div class="notification-item h-12 mx-6 my-2">
                            <p class="text-slate-700 font-semibold text-base mb-1">Logitech MX Mouse reach minimum! 3 left</p>
                            <p class="text-slate-500 font-semibold text-sm">27-9-2022 5:00PM</p>
                        </div>
                    @endfor
                </div>
            </div>
        </li>
        <li class="py-2">
            <a href="{{ url('/logout') }}" class="hover:text-blue-500 hover:fill-blue-500">
                <div class="flex">
                    <i class="flex justify-center items-center mr-1">
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
@vite('resources/js/notification.js')