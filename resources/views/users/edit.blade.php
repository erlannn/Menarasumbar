<x-main-layout>

    <div class="container mx-auto p-6">
        <div class="bg-[#001F3F] text-white">
            <h1 class="text-2xl font-bold mb-4 flex justify-center pt-4">Form Edit Data pengguna</h1>

            <form method="POST" action="{{ route('users.update',$user->id) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="flex justify-center">
                    <div class="mr-2">
                        <h2 class="font-semibold text-lg">Nama pengguna</h2>
                        <input type="text" name="name" value="{{ $user->name }}" class="border p-2 w-[500px] mb-2">
                        @error('name')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror


                        <h2 class="font-semibold text-lg">Username</h2>
                        <input type="username" name="username" value="{{ $user->username }}" class="border p-2 w-[500px] mb-2">
                        @error('username')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror


                        <h2 class="font-semibold text-lg">Password</h2>
                        <input type="password" name="password" placeholder="Ganti Password (opsional)" class="border p-2 w-[500px] mb-2">

                        <h2 class="font-semibold text-lg">Konfirmasi Password</h2>
                        <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" class="border p-2 w-[500px] mb-2">
                        @error('password')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror


                        <h2 class="font-semibold text-lg">Pilih Role</h2>
                        <select name="role" class="border p-2 w-full">
                            @foreach($roles as $role)
                                <option value="{{ $role }}" 
                                    {{ $user->roles->pluck('name')->first() == $role ? 'selected' : '' }}>
                                    {{ $role }}  
                                </option>
                            @endforeach
                        </select>

                        @error('role')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror

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
