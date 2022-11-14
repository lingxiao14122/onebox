<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Onebox</title>
    @vite('resources/js/app.js')
    <script>
        @auth
            window.userId = {{ auth()->user()->id }}
        @endauth
    </script>
</head>

<body>
    <x-flash-message></x-flash-message>
    @auth
        <div class="flex h-full">
            <x-sidebar />
            <main class="flex-1 overflow-y-auto">
                <x-header-auth></x-header-auth>
                <div id="layout-slot-wrap" class="">
                    {{ $slot }}
                </div>
            </main>
        </div>
    @else
        <nav class="flex items-center justify-between my-4">
            <a href="/" class="ml-4 text-5xl">📦Onebox</a>
            <ul class="flex mr-6 space-x-6 text-lg">
            </ul>
        </nav>
        <main>
            {{ $slot }}
        </main>
    @endauth
</body>

</html>
