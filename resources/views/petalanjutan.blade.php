<x-main-layout>
    <div class="flex justify-between">
        <div class=" rounded-md mt-7 ml-3  w-[300px] h-[530px] bg-[#001F3F]">
            @if($kecamatan->Nama_kecamatan === 'padang')
                <h1 class=" font-semibold text-lg mt-5 ml-5" > Nama wilayah:
                    {{ $kecamatan->Nama_kecamatan }}
                </h1>
            @else
                <h1 class=" font-semibold text-lg mt-5 ml-5" > Nama Kecamatan:
                    {{ $kecamatan->Nama_kecamatan }}
                </h1>
            @endif

            <p class="mt-1 ml-5 mr-4 text-justify">
                @if($kecamatan->Nama_kecamatan === 'Padang')

                @else
                Kode kecamatan: {{ $kecamatan->Kode_kecamatan }} <br><br>
                @endif
                @foreach ($operatorCountsReadable as $operator => $count)
                    {{ $operator }}: {{ $count }} BTS <br>
                @endforeach

                <p class="text-white mt-3 ml-5 mr-4 text-justify">Klik tombol untuk mengetahui lokasi saat ini!</p>
                <button onclick="getLocation()" class="text-white bg-[#014D9B] w-[130px] h-[40px] rounded-md mt-1 ml-5 mr-4">Cek lokasi saya!</button>
                <p id="demo" class="text-white mt-1 ml-5 mr-4 text-justify"></p>

                <!-- Dropdown custom -->
                <div x-data="{ open: false }" class="relative w-[250px] border border-gray-500 rounded-md ml-5 mt-4">
                    <!-- Tombol -->
                    <button @click="open = !open"
                        class="flex justify-between items-center w-full px-4 py-2 bg-[#014D9B] text-white text-lg rounded-md focus:outline-none">
                        Ganti kecamatan
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- List kecamatan -->
                    <div x-show="open" @click.away="open = false"
                        class="absolute mt-1 w-full bg-[#014D9B] rounded-md shadow-lg z-10 max-h-[140px] overflow-y-auto">
                        @foreach ($kecamatans as $kc)
                            <a href="#"
                                x-on:click.prevent="window.location='{{ route('petalanjutan', ['kode_kecamatan' => $kc->Kode_kecamatan]) }}'"
                                class="block px-4 py-2 text-white hover:bg-[#001F3F] border-b border-gray-700">
                                {{ $kc->Nama_kecamatan }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </p>
        </div>
        <div>
            <x-map></x-map>    
        </div>
    </div>

<div id="info" class="text-sm text-gray-600 mt-2"></div>
<div id="popup" class="absolute text-gray-950 bg-white border border-gray-300  rounded-lg shadow p-3 hidden z-10">
    <div id="popup-content"></div>
</div>
</x-main-layout>

<script>
    window.kecamatanCenter = {
        lon: Number({{ $kecamatan->Longitude }}),
        lat: Number({{ $kecamatan->Latitude }})
    };

    window.btsData = @json($btsList);
    window.kecamatanGeoJSON = '{{ $geojsonPath }}';

    var x = document.getElementById("demo");

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((pos) => {
                const lat = pos.coords.latitude;
                const lon = pos.coords.longitude;

                if (window.mapObj && typeof window.mapObj.showUserLocation === 'function') {
                    window.mapObj.showUserLocation(lat, lon);
                }
            });
        } else {
            alert("Geolocation tidak didukung browser ini.");
        }
    }
</script>

@push('scripts')
    @vite(['resources/js/map.js'])
@endpush
