<x-main-layout>

    <div class="container mx-auto p-6">
        <div class="bg-[#001F3F] text-white">
            <h1 class="text-xl font-bold mb-4 text-white flex justify-center pt-4">Form Pengisian Data BTS</h1>
            
            @if ($errors->any())
            <div class="bg-red-500 text-white p-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>⚠️ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif


            <form action="{{ route('kelola-bts.store') }}" method="POST">
                @csrf
                <div class="flex justify-center mb-4">
                    <div class="mr-4">
                        <h2 class="font-semibold text-lg">Nama BTS</h2>
                        <input type="text" name="nama_BTS" placeholder="Masukkan Nama BTS" class="border p-2 w-[500px] mb-2 required">
    
                        <h2 class="font-semibold text-lg">Longitude</h2>
                        <input type="text" name="Longitude" placeholder="Masukkan titik Longitude (contoh 100.34066)" class="border p-2 w-[500px] mb-2 required">
    
                        <h2 class="font-semibold text-lg">Latitude</h2>
                        <input type="text" name="Latitude" placeholder="Masukkan titik Latitude (contoh -0.86274)" class="border p-2 w-[500px] mb-2 required">
    
                        <h2 class="font-semibold text-lg">Tahun Registrasi</h2>
                        <input type="date" name="Tahun_registrasi" class="border p-2 w-[500px] mb-2 required">

                        <h2 class="font-semibold text-lg">Tahun berakhir</h2>
                        <input type="date" name="Tahun_berakhir" class="border p-2 w-[500px] mb-2 required">
                    </div>
    
                    <div class="mt-1">
                        <h2 class="font-semibold text-lg">Alamat BTS</h2>
                        <input type="text" name="alamat" placeholder="Masukkan Alamat" class="border p-2 w-[500px] mb-2 required">

                        <h2 class="font-semibold text-lg">Pilih kecamatan</h2>
                        <select name="Kode_kecamatan" class="border p-2 w-[500px] mb-2 required">
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach($kecamatan as $k)
                                <option value="{{ $k->Kode_kecamatan }}">{{ $k->Nama_kecamatan }}</option>
                            @endforeach
                        </select>
    
                        <h2 class="font-semibold text-lg">Pilih Operator</h2>
                        <select name="Kode_operator" class="border p-2 w-[500px] mb-2 required">
                            <option value="">-- Pilih Operator --</option>
                            @foreach($operator as $op)
                                <option value="{{ $op->Kode_operator }}">{{ $op->Operator }}</option>
                            @endforeach
                        </select>
    
                        <h2 class="font-semibold text-lg">Pilih Perangkat Jaringan Yang Digunakan</h2>
                        <select name="Kode_perangkat_jaringan" class="border p-2 w-[500px] mb-2 required">
                            <option value="">-- Pilih Perangkat --</option>
                            @foreach($perangkat as $pj)
                                <option value="{{ $pj->Kode_perangkat_jaringan }}">{{ $pj->Perangkat }} ({{ $pj->Jenis_jaringan }})</option>
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