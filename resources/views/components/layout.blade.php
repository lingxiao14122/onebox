<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Onebox</title>
    @vite('resources/js/app.js')
</head>

<body>
    <nav class="flex justify-between items-center my-4">
        <a href="/" class="text-5xl ml-4">📦Onebox</a>
        <ul class="flex space-x-6 text-lg mr-6">
            <li>
                <a href="/" class="hover:bg-blue-500">Register</a>
            </li>
            <li>
                <a href="/" class="hover:bg-blue-500">Login</a>
            </li>
        </ul>
    </nav>
    <main>
        {{ $slot }}
    </main>
    <footer>

    </footer>
</body>

</html>
