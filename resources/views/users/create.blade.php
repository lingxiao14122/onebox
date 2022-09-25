<x-layout>
    <div class="flex items-center justify-between m-5">
        <h1 class="text-3xl font-semibold">Create User</h1>
        <a href="{{ url('/item') }}" class="hover:text-blue-500 underline">back</a>
    </div>
    <form action="{{ url('/user') }}" method="post" class="ml-5">
        @csrf
        <div class="w-96">
            <div class="mb-6">
                <label for="name" class="inline-block text-lg mb-2">Name</label>
                <input type="text" class="border border-gray-500 rounded p-2 mb-2 w-full" name="name" value="{{ old('name') }}">
                @error('name')
                    <p class="text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6">
                <label for="email" class="inline-block text-lg mb-2">Email</label>
                <input type="email" class="border border-gray-500 rounded p-2 mb-2 w-full" name="email" value="{{ old('email') }}">
                @error('email')
                    <p class="text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6">
                <label for="role" class="inline-block text-lg mb-2">Role</label>
                <select name="role" class="border border-gray-500 rounded p-2 mb-2 w-full">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                @error('role')
                    <p class="text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6">
                <label for="password" class="inline-block text-lg mb-2">Password</label>
                <input type="password" class="border border-gray-500 rounded p-2 mb-2 w-full" name="password">
                @error('password')
                    <p class="text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6">
                <label for="password2" class="inline-block text-lg mb-2">Confirm Password</label>
                <input type="password" class="border border-gray-500 rounded p-2 mb-2 w-full" name="password_confirmation">
                @error('password_confirmation')
                    <p class="text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6">
                <button type="submit" class="bg-blue-500 text-white rounded py-2 px-4 hover:bg-black">
                    Save
                </button>
            </div>
        </div>
    </form>
</x-layout>
