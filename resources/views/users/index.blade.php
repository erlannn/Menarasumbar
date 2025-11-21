<x-main-layout>

    <div class="container mx-auto p-6">
        <h1 class="text-xl font-bold mb-4 text-black">Kelola Data Pengguna</h1>
        <div class="flex justify-end">
            <a href="{{ route('users.create') }}" class="bg-[#014D9B] text-white px-4 py-2 rounded-lg font-semibold">Tambah Data Pengguna</a>
        </div>

        @if(session('success'))
            <div class="bg-green-600 p-2 mt-4 rounded">{{ session('success') }}</div>
        @endif

        <table class="table-auto border-collapse border w-full mt-4">
            <thead>
                <tr class="bg-[#014D9B] text-white font-semibold">
                    <th class="border px-4 py-2">Nama</th>
                    <th class="border px-4 py-2">Username</th>
                    <th class="border px-4 py-2">Role</th>
                    <th class="border px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody class="text-black">
                @foreach($users as $user)
                <tr class="text-center">
                    <td class="border border-gray-300 px-4 py-2">{{ $user->name }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $user->username }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $user->getRoleNames()->first() }}</td>
                    <td class="border border-gray-300 px-4 py-2 flex justify-center space-x-2">
                        <a href="{{ route('users.edit',$user->id) }}" class="bg-green-600 text-white px-2 py-1 rounded">Edit</a>
                        <form action="{{ route('users.destroy',$user->id) }}" method="POST" onsubmit="return confirm('Yakin hapus user ini?')">
                            @csrf @method('DELETE')
                            <button class="bg-red-600 text-white px-2 py-1 rounded">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>

</x-main-layout>