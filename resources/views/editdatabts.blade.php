<x-main-layout>

    <div class="container mx-auto p-6">
        <div class="bg-[#001F3F] text-white">
            <h1 class="text-xl font-bold mb-4 text-white flex justify-center pt-4">Form Edit Data BTS</h1>

            @if ($errors->any())
            <div class="bg-red-500 text-white p-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>⚠️ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <form action="{{ route('kelola-bts.update', $bts->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="flex justify-center mb-4">
                    <div class="mr-4">
                        <h2 class="font-semibold text-lg">Nama BTS</h2>
                        <input type="text" name="nama_BTS"  value="{{ $bts->nama_BTS }}" class="border p-2 w-[500px] mb-2 required">
    
                        <h2 class="font-semibold text-lg">Longitude</h2>
                        <input type="text" name="Longitude"  value="{{ $bts->Longitude }}" class="border p-2 w-[500px] mb-2 required">
    
                        <h2 class="font-semibold text-lg">Latitude</h2>
                        <input type="text" name="Latitude"value="{{ $bts->Latitude }}" class="border p-2 w-[500px] mb-2 required">
    
                        <h2 class="font-semibold text-lg">Tahun Registrasi</h2>
                        <input type="date" name="Tahun_registrasi" value="{{ $bts->Tahun_registrasi }}" class="border p-2 w-[500px] mb-2 required">

                        <h2 class="font-semibold text-lg">Tahun Berakhir</h2>
                        <input type="date" name="Tahun_berakhir" value="{{ $bts->Tahun_berakhir }}" class="border p-2 w-[500px] mb-2 required">
                    </div>
    
                    <div class="mt-1">
                        <h2 class="font-semibold text-lg">Alamat BTS</h2>
                        <input type="text" name="alamat" value="{{ $bts->alamat }}" class="border p-2 w-[500px] mb-2 required">
    
                        <h2 class="font-semibold text-lg">Kode Operator</h2>
                        <select name="Kode_operator" class="border p-2 w-[500px] mb-2 required">
                            @foreach($operator as $op)
                            <option value="{{ $op->Kode_operator }}" {{ $bts->Kode_operator == $op->Kode_operator ? 'selected' : '' }}>
                                {{ $op->Operator }}
                            </option>
                            @endforeach
                        </select>
    
                        <h2 class="font-semibold text-lg">Kode Perangkat Jaringan</h2>
                        <select name="Kode_perangkat_jaringan" class="border p-2 w-[500px] mb-2 required">
                            @foreach($perangkat as $pj)
                            <option value="{{ $pj->Kode_perangkat_jaringan }}" {{ $bts->Kode_perangkat_jaringan == $pj->Kode_perangkat_jaringan ? 'selected' : '' }}>
                                {{ $pj->Perangkat }} ({{ $pj->Jenis_jaringan }})
                            </option>
                            @endforeach
                        </select>
    
                        <h2 class="font-semibold text-lg">Kode kecamatan</h2>
                        <select name="Kode_kecamatan" class="border p-2 w-[500px] mb-2 required">
                            @foreach($kecamatan as $k)
                            <option value="{{ $k->Kode_kecamatan }}" {{ $bts->Kode_kecamatan == $k->Kode_kecamatan ? 'selected' : '' }}>
                                {{ $k->Nama_kecamatan }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-center space-x-2 pb-6">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Simpan</button>
                    <a href="{{ route('kelola-bts.index') }}" class="bg-red-600 text-white px-4 py-2 rounded">Kembali</a>
                </div>
            </form>
        </div>
    </div>

</x-main-layout>