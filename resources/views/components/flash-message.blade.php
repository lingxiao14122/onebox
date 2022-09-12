@if (session()->has('message'))
    <div id="flash-m" class="fixed top-1 left-1/2 transform -translate-x-1/2 rounded bg-blue-500 text-white py-2 transition-opacity opacity-100 duration-700">
        <p class="mx-4">
            {{ session('message') }}
        </p>
    </div>
    <script>
        setTimeout(() => {
            document.getElementById('flash-m').classList.add('opacity-0')
        }, 2000);
        setTimeout(() => {
            document.getElementById('flash-m').remove()
        }, 3000);
    </script>
@endif