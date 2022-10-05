<x-layout>
    <x-card class="max-w-lg mx-auto mt-24">
        <header class="text-center">
            <h2 class="mb-1 text-2xl font-bold uppercase">
                Login
            </h2>
        </header>

        <form action="{{ url('/users/login')}}" method="post">
            @csrf
            <div class="mb-6">
                <label for="email" class="inline-block mb-2 text-lg">Email</label>
                <input type="email" class="w-full p-2 mb-2 border border-gray-500 rounded" name="email" value="{{ old('email') }}">
                @error('email')
                    <p class="mt-1 text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6">
                <label for="password" class="inline-block mb-2 text-lg">Password</label>
                <input type="password" class="w-full p-2 mb-2 border border-gray-500 rounded" name="password">
                @error('password')
                    <p class="mt-1 text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6">
                <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-black">
                    Sign In
                </button>
            </div>

            <div class="mt-8">
                <p>
                    No account? Register
                    <a href="/register" class="text-blue-500">here</a>
                </p>
            </div>
        </form>
    </x-card>
</x-layout>
