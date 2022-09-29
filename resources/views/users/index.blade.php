<x-layout>
    <div class="flex items-center justify-between m-5">
        <h1 class="text-3xl font-semibold">Users</h1>
        <a href="{{ url('/register') }}" class="px-4 py-2 my-auto text-white bg-blue-500 rounded hover:bg-black">Add
            User</a>
    </div>
    <div class="mx-5">
        @if ($users->isEmpty())
            <h1 class="text-xl text-center mt-5 mb-6">No users</h1>
        @else
            <div class="pt-4 mb-8 overflow-hidden border rounded border-slate-400">
                <table class="w-full text-sm border-collapse table-fixed">
                    <thead class="border-b-[0.5px] border-slate-400">
                        <tr>
                            <th class="font-medium p-4 pl-8 pt-0 pb-3 text-left">Name</th>
                            <th class="font-medium p-4 pt-0 pb-3 text-left">Email</th>
                            <th class="font-medium p-4 pr-8 pt-0 text-left">Role</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach ($users as $user)
                            <tr class="tr-border-except-last hover:bg-slate-100 cursor-pointer" onclick='window.location="{{ url("/user/{$user->id}") }}"'>
                                <td class="p-3 pl-8">{{ $user->name }}</td>
                                <td class="p-3">{{ $user->email }}</td>
                                <td class="p-3">{{ $user->is_admin ? 'Admin' : 'User' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-layout>
