<x-main-layout>

    <div class="container mx-auto p-6">
        <div class="bg-[#001F3F] text-white">
            <h1 class="text-2xl font-bold mb-4 flex justify-center pt-4">Form Tambah Data pengguna</h1>

            @if ($errors->any())
            <div class="bg-red-500 text-white p-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>⚠️ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('users.store') }}" class="space-y-4">
                @csrf
                <div class="flex justify-center">
                    <div class="mr-2">
                        <h2 class="font-semibold text-lg">Nama pengguna</h2>
                        <input type="text" name="name" placeholder="Masukkan Nama" class="border p-2 w-[500px] mb-2" required>

                        <h2 class="font-semibold text-lg">Username</h2>
                        <input type="text" name="username" placeholder="Masukkan Username" class="border p-2 w-[500px] mb-2" required>

                        <h2 class="font-semibold text-lg">Password</h2>
                        <input type="password" name="password" placeholder="Masukkan Password" class="border p-2 w-[500px] mb-2" required>

                        <h2 class="font-semibold text-lg">Konfirmasi Password</h2>
                        <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" class="border p-2 w-[500px] mb-2" required>

                        <h2 class="font-semibold text-lg">Pilih Role</h2>
                        <select name="role" class="border p-2 w-[500px] mb-2" required>
                            <option value="">-- Pilih Roles --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role }}">{{ $role }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-center pb-4">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg m-1">Simpan</button>
                    <a href="{{ route('users.index') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg m-1">Kembali</a>
                </div>
            </form>
        </div>
    </div>

</x-main-layout>