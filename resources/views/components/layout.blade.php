<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Onebox</title>
    @vite('resources/js/app.js')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    @auth
        <style>
            html, body {
                width: 100%;
                height: 100%;
            }
        </style>
        <div class="flex h-full">
            <x-sidebar/>
            <main class="flex-1 overflow-y-auto">
                <header class="h-14 border-b border-gray-400">
                    <ul class="flex justify-end items-center space-x-6 text-lg mr-6 h-full">
                        <li>
                            <form class="inline" method="POST" action="/logout">
                                @csrf
                                <button type="submit" class="hover:text-blue-500">
                                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </header>
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
