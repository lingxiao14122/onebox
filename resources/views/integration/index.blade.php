<x-layout style="width: 100%">
    <x-flash-message></x-flash-message>
    <div class="flex items-center m-5">
        <h1 class="text-3xl font-semibold">Integration</h1>
    </div>
    <div class="flex gap-4 mx-5">
        @if ($lazada->isEmpty())
            <div class="w-60 border border-gray-500 rounded">
                <img class="object-scale-down m-auto h-32 w-52"
                    src="https://1000logos.net/wp-content/uploads/2022/01/Lazada-Logo.png">
                <hr class="border-slate-500">
                <div class="flex p-4 items-baseline gap-4">
                    <p class="text-2xl font-semibold">Lazada</p>
                    <a class="text-lg text-blue-500 hover:underline"
                        href="{{ url('/integration/auth/lazada') }}">Authorize</a>
                </div>
            </div>
        @else
            <div class="w-60 border border-gray-500 rounded">
                <a href="{{ url('/integration/lazada') }}">
                    <img class="object-scale-down m-auto h-32 w-52"
                        src="https://1000logos.net/wp-content/uploads/2022/01/Lazada-Logo.png">
                    <hr class="border-slate-500">
                    <div class="flex p-4 items-baseline gap-4">
                        <p class="text-2xl font-semibold">Lazada</p>
                        <p class="text-lg text-green-500">Active</p>
                    </div>
                </a>
            </div>
        @endif
        <div class="relative border border-gray-500 rounded w-60">
            <div class="absolute w-full">
                <img class="object-scale-down h-32 m-auto w-52"
                    src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/0e/Shopify_logo_2018.svg/1024px-Shopify_logo_2018.svg.png">
                <hr class="border-slate-500">
                <div class="flex items-baseline gap-2 p-4">
                    <p class="text-2xl font-semibold">Shopify</p>
                    {{-- <a class="text-lg text-blue-500 hover:underline" href="{{ url('/integration/auth/lazada') }}">Authorize</a> --}}
                </div>
            </div>
            <div class="absolute text-3xl rotate-12 translate-x-5 translate-y-[5rem]">Coming soon</div>
        </div>
    </div>
</x-layout>
