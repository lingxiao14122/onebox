<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Onebox</title>
    @vite('resources/js/app.js')
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
        <nav class="flex justify-between items-center my-4">
            <a href="/" class="text-5xl ml-4">📦Onebox</a>
            <ul class="flex space-x-6 text-lg mr-6">
                <li>
                    <a href="/login" class="hover:text-blue-500">Login</a>
                </li>
                <li>
                    <a href="/register" class="hover:text-blue-500">Register</a>
                </li>
            </ul>
        </nav>
        <main>
            {{ $slot }}
        </main>
    @endauth
</body>

</html>
