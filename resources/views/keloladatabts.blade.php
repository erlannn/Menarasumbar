<x-main-layout>
    
    <div class="container mx-auto p-6">

        <div class="flex justify-between mb-4">
            <h1 class="text-2xl font-bold text-black">Kelola Data BTS</h1>
            <a href="{{ route('kelola-bts.create') }}" class="px-4 py-2 bg-[#014D9B] text-white rounded-lg font-semibold">Tambah data BTS</a>
        </div>

        <!-- ✅ Filter pakai form -->
        <form method="GET" action="{{ route('kelola-bts.index') }}" class="flex space-x-4 mb-6">
            <select name="kecamatan" class="border p-2 rounded-lg bg-[#014D9B] text-white font-semibold">
                <option value="">Pilih Kecamatan</option>
                @foreach($kecamatan as $k)
                    <option value="{{ $k }}" {{ request('kecamatan') == $k ? 'selected' : '' }}>{{ $k }}</option>
                @endforeach
            </select>

            <select name="operator" class="border p-2 bg-[#014D9B] text-white rounded-lg font-semibold">
                <option value="">Pilih Operator</option>
                @foreach($operator as $o)
                    <option value="{{ $o }}" {{ request('operator') == $o ? 'selected' : '' }}>{{ $o }}</option>
                @endforeach
            </select>

            <select name="tahun_registrasi" class="border p-2 bg-[#014D9B] text-white rounded-lg font-semibold">
                <option value="">Pilih Tahun</option>
                @foreach($tahun_registrasi as $t)
                    <option value="{{ $t }}" {{ request('tahun_registrasi') == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>

            
            <button type="submit" class="bg-[#014D9B] text-white px-4 py-2 rounded-lg font-semibold w-[140px] flex justify-between">Cari Data<img src="img/icon-cari.png" alt="Icon cari" class="w-[35px]"></button>

            <a href="{{ route('kelola-bts.index') }}" class="bg-red-500 text-white h-[43px] px-4 py-2 rounded-lg font-semibold">Reset Pencarian</a>

            @if (Route::has('login'))
                @auth
                    @if ((auth()->user()->hasRole('superuser')))
                        <a href="{{ route('kelola-bts.export.pdf', request()->query()) }}" 
                        class="bg-green-600 text-white px-4 py-2 rounded-lg w-[145px] flex justify-between font-semibold">Cetak PDF
                        <img src="img/download.png" alt="icon unduh" class="w-[25px] pl-1">
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

        <table class="table-auto border-collapse border w-full text-sm">
            <thead>
                <tr class="bg-[#014D9B] text-white justify-center">
                    <th class="border">Nama BTS</th>
                    <th class="border">Operator</th>
                    <th class="border">Perangkat</th>
                    <th class="border">Jenis Jaringan</th>
                    <th class="border">Jangkauan Sinyal</th>
                    <th class="border">Longitude</th>
                    <th class="border">Latitude</th>
                    <th class="border px-4 py-2">Tahun Registrasi</th>
                    <th class="border px-4 py-2">Tahun Berakhir</th>
                    <th class="border">Sisa Waktu Kadaluarsa</th>
                    <th class="border">Alamat</th>
                    <th class="border">Kecamatan</th>
                    <th class="border">Action</th>
                </tr>
            </thead>
            <tbody class="text-black text-xs text-center">
                @forelse($bts as $row)
                    <tr>
                        <td class="border border-gray-300">{{ $row->nama_bts }}</td>
                        <td class="border border-gray-300">{{ $row->operator }}</td>
                        <td class="border border-gray-300">{{ $row->perangkat }}</td>
                        <td class="border border-gray-300">{{ $row->jenis_jaringan }}</td>
                        <td class="border border-gray-300">{{ $row->jangkauan_sinyal }} KM</td>
                        <td class="border border-gray-300 px-2">{{ $row->Longitude }}</td>
                        <td class="border border-gray-300 px-1">{{ $row->Latitude }}</td>
                        
                        <td class="border border-gray-300">
                            {{ \Carbon\Carbon::parse($row->Tahun_registrasi)->format('d-m-Y') }}
                        </td>
                        <td class="border border-gray-300">
                            {{ \Carbon\Carbon::parse($row->Tahun_berakhir)->format('d-m-Y') }}
                        </td>
                        <td class=" text-center w-[165px]">
                            @if($row->sisa_waktu === 'Kadaluarsa')
                                <span class="bg-red-500 border-gray-300 text-white px-2 py-1 rounded">
                                    {{ $row->sisa_waktu }}
                                </span>
                            @else
                                <span class="bg-green-500 border-gray-300 text-white px-2 py-1 rounded">
                                    {{ $row->sisa_waktu }}
                                </span>
                            @endif
                        </td>                        
                        
                        <td class="border border-gray-300 px-4 py-2">{{ $row->alamat }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $row->kecamatan }}</td>
                        <td class="border border-gray-300 px-4 py-2 w-[140px]">
                            <a href="{{ route('kelola-bts.edit', $row->id) }}" class="bg-green-600 text-white px-2 py-1 rounded">Edit</a>
                            <form action="{{ route('kelola-bts.destroy', $row->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-600 text-white px-2 py-1 rounded">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="11" class="text-center p-4">Tidak ada data BTS</td></tr>
                @endforelse
            </tbody>
        </table>

        {{-- ✅ Pagination links tetap jalan --}}
        <div class="mt-4">
            {{ $bts->appends(request()->query())->links() }}
        </div>
    </div>

</x-main-layout>