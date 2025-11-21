<x-main-layout>
    <div class="absolute w-full">
        <div class="flex flex-col justify-center items-center mt-28">
            <h1 class="text-4xl font-bold text-white">Silahkan untuk memilih kecamatan!</h1>
            <p class=" text-lg text-white mb-12 text-justify w-[500px] mt-2">Pada halaman berikut, kami menyajikan berbagai informasi mengenai BTS. Anda dapat mengetahui jumlah BTS di suatu kecamatan, luas jangkauan jaringan, dan fitur penandaan lokasi. Silakan pilih kecamatan yang informasinya ingin Anda ketahui.</p>

            <!-- Dropdown custom -->
            <div x-data="{ open: false }" class="relative w-[400px]">
                <!-- Tombol -->
                <button @click="open = !open"
                    class="flex justify-between items-center w-full px-4 py-2 bg-[#001F3F] text-white text-xl rounded-md focus:outline-none">
                    Pilih kecamatan
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- List kecamatan -->
                <div x-show="open" @click.away="open = false"
                    class="absolute mt-1 w-full bg-[#001F3F] rounded-md shadow-lg z-10 max-h-[200px] overflow-y-auto">
                    @foreach ($kecamatans as $kecamatan)
                        <a href="{{ route('petalanjutan', ['kode_kecamatan' => $kecamatan->Kode_kecamatan]) }}"
                            class="block px-4 py-2 text-white hover:bg-[#014D9B] border-b border-gray-700">
                            {{ $kecamatan->Nama_kecamatan }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div>
        <img src="img/padang.png" alt="gambarpeta" class="w-full h-full">
    </div>
</x-main-layout>
