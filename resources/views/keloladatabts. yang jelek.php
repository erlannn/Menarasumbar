<x-main-layout>
    
    <div class="container mx-auto p-6">

        <div class="flex justify-between mb-4">
            <h1 class="text-2xl font-bold text-black">Kelola Data BTS</h1>
            <a href="{{ route('kelola-bts.create') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg font-semibold">Tambah data BTS</a>
        </div>

        <!-- ✅ Filter pakai form -->
        <form method="GET" action="{{ route('kelola-bts.index') }}" class="flex space-x-4 mb-6">
            <select name="kecamatan" class="border p-2 rounded-lg bg-gray-600 text-white font-semibold">
                <option value="">Pilih Kecamatan</option>
                @foreach($kecamatan as $k)
                    <option value="{{ $k }}" {{ request('kecamatan') == $k ? 'selected' : '' }}>{{ $k }}</option>
                @endforeach
            </select>

            <select name="operator" class="border p-2 bg-gray-600 text-white rounded-lg font-semibold">
                <option value="">Pilih Operator</option>
                @foreach($operator as $o)
                    <option value="{{ $o }}" {{ request('operator') == $o ? 'selected' : '' }}>{{ $o }}</option>
                @endforeach
            </select>

            <select name="tahun" class="border p-2 bg-gray-600 text-white rounded-lg font-semibold">
                <option value="">Pilih Tahun</option>
                @foreach($tahun as $t)
                    <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>

            <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg font-semibold w-[80px] flex justify-between">Search</button>

            <a href="{{ route('kelola-bts.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg font-semibold">Reset</a>

            @if (Route::has('login'))
                @auth
                    @if ((auth()->user()->role === 'superuser'))
                        <a href="{{ route('kelola-bts.export.pdf', request()->query()) }}" 
                        class="bg-gray-600 text-white px-4 py-2 rounded-lg w-[100px] flex justify-between font-semibold">Mencetak
                        </a>
                    @endif
                    @else

                @endauth
            @endif

        </form>

        @if(session('success'))
        <div class="bg-green-600 p-2 mb-4 rounded">
            {{ session('success') }}
        </div>
        @endif

        <table class="table-auto border-collapse border w-full text-md">
            <thead>
                <tr class="bg-gray-600 text-white">
                    <th class="border px-4 py-2">Nama BTS</th>
                    <th class="border px-4 py-2">Operator</th>
                    <th class="border px-4 py-2">Perangkat</th>
                    <th class="border px-4 py-2">Jenis Jaringan</th>
                    <th class="border px-4 py-2">Jangkauan Sinyal</th>
                    <th class="border px-4 py-2">Longitude</th>
                    <th class="border px-4 py-2">Latitude</th>
                    <th class="border px-4 py-2">Tahun Registrasi</th>
                    <th class="border px-4 py-2">Alamat</th>
                    <th class="border px-4 py-2">Kecamatan</th>
                    <th class="border px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody class="text-black text-md">
                @forelse($bts as $row)
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">{{ $row->nama_bts }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $row->operator }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $row->perangkat }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $row->jenis_jaringan }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $row->jangkauan_sinyal }} KM</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $row->Longitude }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $row->Latitude }}</td>
                        
                        <td class="border border-gray-300 px-4 py-2">
                            {{ \Carbon\Carbon::parse($row->Tahun_registrasi)->format('d-m-Y') }}
                        </td>                     
                        
                        <td class="border border-gray-300 px-4 py-2">{{ $row->alamat }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $row->kecamatan }}</td>
                        <td class="border border-gray-300 px-4 py-2 w-[140px]">
                            <a href="{{ route('kelola-bts.edit', $row->id) }}" class="bg-gray-600 text-white px-2 py-1 rounded">Edit</a>
                            <form action="{{ route('kelola-bts.destroy', $row->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="bg-gray-600 text-white px-2 py-1 rounded">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="11" class="text-center p-4">Belum ada data BTS</td></tr>
                @endforelse
            </tbody>
        </table>

        {{-- ✅ Pagination links tetap jalan --}}
        <div class="mt-4">
            {{ $bts->appends(request()->query())->links() }}
        </div>
    </div>

</x-main-layout>